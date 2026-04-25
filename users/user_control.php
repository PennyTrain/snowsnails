<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/validation.php";
require_once "../helpers/auth.php";
require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (isset($_POST["register"])) {
    handleRegister($conn);
} elseif (isset($_POST["login"])) {
    handleLogin($conn);
} elseif (isset($_POST["update_profile"])) {
    handleProfileUpdate($conn);
} else {
    header("Location: login.php");
    exit();
}

function handleRegister(PDO $conn)
{
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($first_name === "" || $last_name === "" || $email === "" || $password === "") {
        $_SESSION["register_error"] = "Please fill in all required fields.";
        header("Location: register.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION["register_error"] = "Invalid email.";
        header("Location: register.php");
        exit();
    }

    try {
        $check = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $_SESSION["register_error"] = "Email already exists.";
            header("Location: register.php");
            exit();
        }

        $hashed_password = hashValidatedPassword($password, $confirm_password);

        $insert = $conn->prepare("
            INSERT INTO users (first_name, last_name, email, phone, password, role)
            VALUES (?, ?, ?, ?, ?, 'customer')
        ");

        $insert->execute([$first_name, $last_name, $email, $phone, $hashed_password]);

        $_SESSION["name"] = $first_name;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = "customer";

        header("Location: user.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION["register_error"] = "Database error.";
        header("Location: register.php");
        exit();
    } catch (Exception $e) {
        $_SESSION["register_error"] = $e->getMessage();
        header("Location: register.php");
        exit();
    }
}

function handleLogin(PDO $conn)
{
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $_SESSION["login_error"] = "Enter email and password.";
        header("Location: login.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT first_name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user["password"])) {
            $_SESSION["login_error"] = "Incorrect login.";
            header("Location: login.php");
            exit();
        }

        $_SESSION["name"] = $user["first_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        header("Location: user.php");
        exit();

    } catch (PDOException $e) {
        die("Login DB error");
    }
}

function handleProfileUpdate(PDO $conn)
{
    $user = getCurrentUser($conn);

    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
            'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
        ],
        'url' => ['secure' => true]
    ]);

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    $img_url = $user["img_url"];

    if (!empty($_FILES["profile_image"]["tmp_name"])) {
        $upload = $cloudinary->uploadApi()->upload($_FILES["profile_image"]["tmp_name"]);
        $img_url = $upload['secure_url'];
    }

    try {
        if ($new_password !== "") {
            $hashed_password = hashValidatedPassword($new_password, $confirm_password);
        } else {
            $hashed_password = $user["password"];
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

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
        $_SESSION["email"]
    ]);

    $_SESSION["name"] = $first_name;
    $_SESSION["email"] = $email;

    header("Location: user.php");
    exit();
}

function handleLogout(PDO $conn) {

}

// <!-- https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
// https://www.geeksforgeeks.org/php/how-to-validate-and-sanitize-user-input-with-php/
// https://www.geeksforgeeks.org/php/how-to-prevent-sql-injection-in-php/ -->

// <!-- https://www.php.net/manual/en/function.filter-var.php -->