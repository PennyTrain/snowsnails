<!-- https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
https://www.geeksforgeeks.org/php/how-to-validate-and-sanitize-user-input-with-php/
https://www.geeksforgeeks.org/php/how-to-prevent-sql-injection-in-php/ -->

<!-- https://www.php.net/manual/en/function.filter-var.php -->
<?php
session_start();
require_once "../db.php";
require_once "./auth_helpers.php";

// ===== REGISTER =====
if (isset($_POST["register"])) {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

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

    if (!validateEmail($email)) {
        $_SESSION["register_error"] = "Please enter a valid email address.";
        header("Location: register.php");
        exit();
    }

    try {
        $check = $conn->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {
            $_SESSION["register_error"] = "Email is already registered!";
            header("Location: register.php");
            exit();
        }

        $hashed_password = hashValidatedPassword($password, $confirm_password);

        $role = 'customer';

        if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
            $role = $_POST["role"] ?? 'customer';
        }

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

        $_SESSION["name"] = $first_name;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;

        header("Location: update_form.php");
        exit();
    } catch (Exception $e) {
        $_SESSION["register_error"] = $e->getMessage();
        header("Location: register.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["register_error"] = "Email is already registered!";
            header("Location: register.php");
            exit();
        }

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

    if (!validateEmail($email)) {
        $_SESSION["login_error"] = "Please enter a valid email address.";
        header("Location: login.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("
            SELECT first_name, email, password, role
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user["password"])) {
            $_SESSION["login_error"] = "Incorrect email or password";
            header("Location: login.php");
            exit();
        }

        $_SESSION["name"] = $user["first_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        header("Location: update_form.php");
        exit();
    } catch (PDOException $e) {
        die("Database error (login): " . $e->getMessage());
    }
}

header("Location: login.php");
exit();