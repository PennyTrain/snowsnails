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
} else {
    header("Location: login.php");
    exit();
}

function handleSubscribe(PDO $conn): void
{
    $first_name = trim($_POST["firstname"] ?? "");
    $last_name  = trim($_POST["lastname"] ?? "");
    $email      = trim($_POST["email"] ?? "");

    if ($first_name === "" || $last_name === "" || $email === "") {
        throwErr("subscribe", "warning", "Please fill in all required fields.");
        header("Location: ../footer.php");
        exit();
    }

    if (!validateEmail($email)) {
        throwErr("subscribe", "danger", "Invalid email.");
        header("Location: ../footer.php");
        exit();
    }

    try {
        $check = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            throwErr("subscribe", "danger", "Email already exists.");
            header("Location: ../footer.php");
            exit();
        }

        // If you want subscribers stored in the same users table, do it here.
        // Otherwise change this to insert into a newsletter/subscribers table.
        $insert = $conn->prepare("
            INSERT INTO users (first_name, last_name, email, role)
            VALUES (?, ?, ?, 'subscriber')
        ");

        $insert->execute([$first_name, $last_name, $email]);

        throwErr("subscribe", "success", "Subscribed successfully!");
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        throwErr("subscribe", "danger", "Database error.");
        header("Location: ../footer.php");
        exit();
    } catch (Exception $e) {
        throwErr("subscribe", "danger", $e->getMessage());
        header("Location: ../footer.php");
        exit();
    }
}

function handleRegister(PDO $conn): void
{
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

    if ($first_name === "" || $last_name === "" || $email === "" || $password === "") {
        throwErr("register", "warning", "Please fill in all required fields.");
        header("Location: register.php");
        exit();
    }

    if (!validateEmail($email)) {
        throwErr("register", "danger", "Invalid email.");
        header("Location: register.php");
        exit();
    }

    try {
        $check = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetchColumn()) {
            throwErr("register", "danger", "Email already exists.");
            header("Location: register.php");
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
            $role
        ]);

        $user_id = (int) $conn->lastInsertId();

        if ($role === "employee") {
            if ($salary === null || $salary === "" || $title === "") {
                throw new Exception("Salary and title are required for employees.");
            }

            $when_hired_db = null;
            if (!empty($when_hired)) {
                $when_hired_db = (new DateTime($when_hired))->format("Y-m-d");
            }

            $insertEmployee = $conn->prepare("
                INSERT INTO employee (
                    user_id,
                    salary,
                    when_hired,
                    title
                ) VALUES (?, ?, ?, ?)
            ");

            $insertEmployee->execute([
                $user_id,
                (float) $salary,
                $when_hired_db,
                $title
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

        throwErr("register", "danger", "Database error." . $e->getMessage());
        header("Location: register.php");
        exit();
    }
}

function handleLogin(PDO $conn): void
{
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        throwErr("login", "warning", "Enter email and password.");
        header("Location: login.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("
            SELECT first_name, email, password, role
            FROM users
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user["password"])) {
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
        throwErr("login", "danger", "Login database error.");
        header("Location: login.php");
        exit();
    }
}

function handleProfileUpdate(PDO $conn): void
{
    $user = getCurrentUser($conn);

    if (!$user) {
        throwErr("update", "danger", "User not found.");
        header("Location: login.php");
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

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    $img_url = $user["img_url"];

    if (!empty($_FILES["profile_image"]["tmp_name"])) {
        $upload = $cloudinary
            ->uploadApi()
            ->upload($_FILES["profile_image"]["tmp_name"]);
        $img_url = $upload["secure_url"];
    }

    try {
        if ($new_password !== "") {
            $hashed_password = hashValidatedPassword(
                $new_password,
                $confirm_password
            );
        } else {
            $hashed_password = $user["password"];
        }
    } catch (Exception $e) {
        throwErr("update", "danger", $e->getMessage());
        header("Location: user_update.php");
        exit();
    }

    try {
        $update = $conn->prepare("
            UPDATE users
            SET first_name=?, last_name=?, email=?, phone=?, password=?, img_url=?
            WHERE email=?
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
