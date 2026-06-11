<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/validation.php";
require_once "../helpers/auth.php";
require_once "../helpers/errors.php";
require __DIR__ . "/../vendor/autoload.php";

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

if (isset($_POST["register"])) {
    handleRegister($conn);
} elseif (isset($_POST["login"])) {
    handleLogin($conn);
} elseif (isset($_POST["update_profile"])) {
    handleProfileUpdate($conn);
} elseif (isset($_POST["subscribe"])) {
    handleSubscribe($conn);
} elseif (isset($_POST["confirm_logout"])) {
    handleLogout();
} elseif (isset($_POST["delete"])) {
    handleDelete($conn);
} elseif (isset($_POST["cancel_logout"])) {
    header("Location: ../index.php");
    exit();
} elseif (isset($_POST["permanent_delete"])) {
    handlePermanentDelete($conn);
} else {
    header("Location: index.php");
    exit();
}

function handlePermanentDelete(PDO $conn): void
{
    protectedPage($conn);
    $user_id = (int) ($_POST["user_id"] ?? 0);
    if ($user_id <= 0) {
        throwErr("delete", "danger", "Invalid user.");
        header("Location: users_page.php");
        exit();
    }
    try {
        $stmt = $conn->prepare("
            DELETE FROM users
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);

        throwErr("delete", "success", "User permanently deleted.");
        header("Location: users_page.php");
        exit();
    } catch (PDOException $e) {
        throwErr("delete", "danger", "Unable to delete user.");
        header("Location: users_page.php");
        exit();
    }
}

// this function inserts the current timestamp to the
// deleted_at feild in the database so that users
// can be soft deleted
function handleDelete(PDO $conn): void
{
    try {
        $user = getCurrentUserData($conn);

        if (!$user) {
            throwErr("delete", "danger", "User not found.");
            header("Location: ../index.php");
            exit();
        }

        $stmt = $conn->prepare("
            UPDATE users
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE user_id = ?
        ");
        $stmt->execute([(int) $user["user_id"]]);

        session_unset();
        session_destroy();

        throwErr("delete", "success", "Account deleted successfully!");
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        throwErr("delete", "danger", "Database error.");
        header("Location: ../index.php");
        exit();
    }
}

// this function logs out the user by destroying the session
function handleLogout(): void
{
    session_unset();
    session_destroy();
    throwErr("logout", "success", "You have been logged out successfully.");
    header("Location: login.php");
    exit();
}

function handleSubscribe(PDO $conn): void
{
    // gets the page the user was already on so that they can be
    // rediracted back
    $returnTo = $_POST["return_to"] ?? "/index.php";
    // I check that the user is logged in using the the email
    // stored in the session
    if (empty($_SESSION["email"])) {
        // display error msg if the user is not logged in
        throwErr("subscribe", "danger", "Please log in first.");
        // and I redirect the user to log in
        header("Location: /users/login.php");
        exit();
    }

    try {
        // get the current logged in users data
        $user = getCurrentUserData($conn);
        // check if the user exists
        if (!$user) {
            throwErr("subscribe", "danger", "User not found.");
            header("Location: " . $returnTo);
            exit();
        }
        // prepared statement to safely update the users subscription status
        $stmt = $conn->prepare("
            UPDATE users
            SET subscribed = 1, updated_at = NOW()
            WHERE user_id = ?
        ");
        // NOW() updates the updated_at column with the exact time
        // that the user has subscribed

        // executes the query using the users id
        $stmt->execute([(int) $user["user_id"]]);
        // if successful let the user know via throwErr function
        throwErr("subscribe", "success", "You are now subscribed!");
        header("Location: " . $returnTo);
        exit();
    } catch (PDOException $e) {
        throwErr("subscribe", "danger", "Database error.");
        header("Location: " . $returnTo);
        exit();
    }
}

function handleRegister(PDO $conn): void
{
    // collects all the form values and trims extra spaces
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $role = $_POST["role"] ?? "customer";

    $salary = $_POST["salary"] ?? null;
    $when_hired = $_POST["when_hired"] ?? null;
    $title = trim($_POST["title"] ?? "");

    // checks all important fields are there
    if (
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $password === ""
    ) {
        $_SESSION["old_register"] = $_POST;
        throwErr("register", "warning", "Please fill in all required fields.");
        header("Location: user_create.php");
        exit();
    }

    // ensures names contain only letters
    if (!validateName($first_name) || !validateName($last_name)) {
        $_SESSION["old_register"] = $_POST;
        throwErr(
            "register",
            "danger",
            "First name and last name must contain letters only.",
        );
        header("Location: user_create.php");
        exit();
    }

    // checks correct email format
    if (!validateEmail($email)) {
        $_SESSION["old_register"] = $_POST;
        throwErr("register", "danger", "Invalid email.");
        header("Location: user_create.php");
        exit();
    }

    try {
        $check = $conn->prepare("
            SELECT deleted_at
            FROM users
            WHERE email = ?
        ");
        $check->execute([$email]);

        $existingUser = $check->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION["old_register"] = $_POST;

            if (!empty($existingUser["deleted_at"])) {
                throwErr(
                    "register",
                    "danger",
                    "This account has been removed. Please contact the salon for assistance.",
                );
            } else {
                throwErr("register", "danger", "Email already exists.");
            }

            header("Location: user_create.php");
            exit();
        }

        $hashed_password = hashValidatedPassword($password, $confirm_password);

        $conn->beginTransaction();

        $insertUser = $conn->prepare("
            INSERT INTO users (
                first_name,
                last_name,
                email,
                phone,
                password,
                role
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

        $insertUser->execute([
            $first_name,
            $last_name,
            $email,
            $phone,
            $hashed_password,
            $role,
        ]);

        $user_id = (int) $conn->lastInsertId();

        if ($role === "employee") {
            if ($salary === null || $salary === "" || $title === "") {
                throw new Exception(
                    "Salary and title are required for employees.",
                );
            }

            $when_hired_db = null;
            if (!empty($when_hired)) {
                $dt = new DateTime($when_hired);
                $when_hired_db = $dt->format("Y-m-d");
            }

            $insertEmployees = $conn->prepare("
                INSERT INTO employees (
                    user_id,
                    salary,
                    when_hired,
                    title
                ) VALUES (?, ?, ?, ?)
            ");

            $insertEmployees->execute([
                $user_id,
                (float) $salary,
                $when_hired_db,
                $title,
            ]);
        }

        $conn->commit();

        $_SESSION["name"] = $first_name;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;

        throwErr("register", "success", "Account created successfully!");
        header("Location: user.php");
        exit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

        error_log("Register error: " . $e->getMessage());

        throwErr("register", "danger", "Database error.");
        header("Location: user_create.php");
        exit();
    }
}

function handleLogin(PDO $conn): void
{
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $_SESSION["old_login_email"] = $email;

        throwErr("login", "warning", "Enter email and password.");

        header("Location: login.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("
            SELECT
                first_name,
                email,
                password,
                role,
                deleted_at
            FROM users
            WHERE email = ?
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // account exists but has been deleted
        if ($user && !empty($user["deleted_at"])) {
            $_SESSION["old_login_email"] = $email;

            throwErr(
                "login",
                "danger",
                "This account has been removed. Please contact the salon for assistance.",
            );

            header("Location: login.php");
            exit();
        }

        // incorrect login details
        if (!$user || !password_verify($password, $user["password"])) {
            $_SESSION["old_login_email"] = $email;

            throwErr("login", "danger", "Incorrect login.");

            header("Location: login.php");
            exit();
        }

        $_SESSION["name"] = $user["first_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        throwErr("login", "success", "Logged in successfully!");

        header("Location: user.php");
        exit();
    } catch (PDOException $e) {
        throwErr("login", "danger", "Login unsuccessful, database error.");

        header("Location: login.php");
        exit();
    }
}
function handleProfileUpdate(PDO $conn): void
{
    $user = getCurrentUserData($conn);

    if (!$user) {
        throwErr("update", "danger", "User not found.");
        header("Location: login.php");
        exit();
    }

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    $name_changed =
        $first_name !== $user["first_name"] ||
        $last_name !== $user["last_name"];

    $email_changed = $email !== $user["email"];
    $phone_changed = $phone !== ($user["phone"] ?? "");
    $password_changed = $new_password !== "";
    $image_changed = !empty($_FILES["profile_image"]["tmp_name"]);

    if (
        !$name_changed &&
        !$email_changed &&
        !$phone_changed &&
        !$password_changed &&
        !$image_changed
    ) {
        throwErr("update", "warning", "No changes were made.");
        header("Location: user_update.php");
        exit();
    }

    $cloudinary = new Cloudinary([
        "cloud" => [
            "cloud_name" => $_ENV["CLOUDINARY_CLOUD_NAME"],
            "api_key" => $_ENV["CLOUDINARY_API_KEY"],
            "api_secret" => $_ENV["CLOUDINARY_API_SECRET"],
        ],
        "url" => ["secure" => true],
    ]);

    $img_url = $user["img_url"];

    if ($image_changed) {
        $upload = $cloudinary
            ->uploadApi()
            ->upload($_FILES["profile_image"]["tmp_name"]);
        $img_url = $upload["secure_url"];
    }

    try {
        if ($password_changed) {
            $hashed_password = hashValidatedPassword(
                $new_password,
                $confirm_password,
            );
        } else {
            $hashed_password = $user["password"];
        }
    } catch (Exception $e) {
        throwErr("update", "danger", $e->getMessage());
        header("Location: user_update.php");
        exit();
    }
    if (!validateName($first_name) || !validateName($last_name)) {
        throwErr(
            "update",
            "danger",
            "First name and last name must contain letters only.",
        );
        header("Location: user_update.php");
        exit();
    }

    try {
        $update = $conn->prepare("
            UPDATE users
            SET first_name = ?, last_name = ?, email = ?, phone = ?, password = ?, img_url = ?
            WHERE email = ?
        ");

        $update->execute([
            $first_name,
            $last_name,
            $email,
            $phone,
            $hashed_password,
            $img_url,
            $_SESSION["email"],
        ]);

        $_SESSION["name"] = $first_name;
        $_SESSION["email"] = $email;

        throwErr("update", "success", "Profile updated successfully!");
        header("Location: user.php");
        exit();
    } catch (PDOException $e) {
        throwErr("update", "danger", "Database error.");
        header("Location: user_update.php");
        exit();
    }
}

// <!-- https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
// https://www.geeksforgeeks.org/php/how-to-validate-and-sanitize-user-input-with-php/
// https://www.geeksforgeeks.org/php/how-to-prevent-sql-injection-in-php/ -->

// <!-- https://www.php.net/manual/en/function.filter-var.php -->
