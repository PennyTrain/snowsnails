<!-- https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
https://www.geeksforgeeks.org/php/how-to-validate-and-sanitize-user-input-with-php/
https://www.geeksforgeeks.org/php/how-to-prevent-sql-injection-in-php/ -->

<!-- https://www.php.net/manual/en/function.filter-var.php -->
<?php
session_start();
require_once "../db.php";

// ===== REGISTER =====
if (isset($_POST["register"])) {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // basic validation
    if (
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $password === "" ||
        $confirm_password === ""
    ) {
        $_SESSION["register_error"] = "Please fill in all required fields.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["register_error"] = "Please enter a valid email address.";
        header("Location: register.php");
        exit();
    }

    // passwords must match
    if ($password !== $confirm_password) {
        $_SESSION["register_error"] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }

    // enforce minimum length server-side too
    if (strlen($password) < 8) {
        $_SESSION["register_error"] = "Password must be at least 8 characters.";
        header("Location: register.php");
        exit();
    }

    try {
        // check if email already exists
        $check = $conn->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {
            $_SESSION["register_error"] = "Email is already registered!";
            header("Location: register.php");
            exit();
        }

        // Hash AFTER validation
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Default role
$role = 'customer';

// If admin is creating user, allow custom role
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    $role = $_POST["role"] ?? 'customer';
}

// insert user into db
$insert = $conn->prepare("
    INSERT INTO users (first_name, last_name, email, phone, password, role)
    VALUES (?, ?, ?, ?, ?, ?)
");

$insert->execute([
    $first_name,
    $last_name,
    $email,
    $phone,
    $hashed_password,
    $role
]);
        // automatic login after successful registration
        $_SESSION["name"] = $first_name;
        $_SESSION["email"] = $email;

        header("Location: account.php");
        exit();
    } catch (PDOException $e) {
        // duplicate email / unique constraint
        if ($e->getCode() == 23000) {
            $_SESSION["register_error"] = "Email is already registered!";
            header("Location: register.php");
            exit();
        }

        // Other DB errors
        die("Database error (register): " . $e->getMessage());
    }
}

// ===== LOGIN =====
if (isset($_POST["login"])) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $_SESSION["login_error"] = "Please enter your email and password.";
        header("Location: login.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["login_error"] = "Please enter a valid email address.";
        header("Location: login.php");
        exit();
    }

    try {
        // get the user by email
        $stmt = $conn->prepare("
            SELECT first_name, email, password, role
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION["login_error"] = "Incorrect email or password";
            header("Location: login.php");
            exit();
        }

        if (!password_verify($password, $user["password"])) {
            $_SESSION["login_error"] = "Incorrect email or password";
            header("Location: login.php");
            exit();
        }

        // Success
        $_SESSION["name"] = $user["first_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        header("Location: account.php");
        exit();
    } catch (PDOException $e) {
        die("Database error (login): " . $e->getMessage());
    }
}

// If someone hits this file directly without POST:
header("Location: login.php");
exit();