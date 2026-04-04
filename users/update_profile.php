<?php
session_start();
require_once "../db.php";
require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

// Auth check
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Load env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Cloudinary
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
    ],
    'url' => ['secure' => true]
]);

// Get inputs
$first_name = trim($_POST["first_name"]);
$last_name = trim($_POST["last_name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);

$new_password = $_POST["new_password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

// Get current user
$stmt = $conn->prepare("SELECT password, img_url FROM users WHERE email = ?");
$stmt->execute([$_SESSION["email"]]);
$user = $stmt->fetch();

$img_url = $user["img_url"];


// ===== IMAGE UPLOAD =====
if (!empty($_FILES["profile_image"]["tmp_name"])) {
    $upload = $cloudinary->uploadApi()->upload($_FILES["profile_image"]["tmp_name"]);
    $img_url = $upload['secure_url'];
}


// ===== PASSWORD UPDATE =====
if (!empty($new_password)) {
    if ($new_password !== $confirm_password) {
        die("Passwords do not match");
    }

    if (strlen($new_password) < 8) {
        die("Password too short");
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
} else {
    $hashed_password = $user["password"]; // keep old
}


// ===== UPDATE USER =====
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
    $_SESSION["email"]
]);

// update session
$_SESSION["name"] = $first_name;
$_SESSION["email"] = $email;

header("Location: update_form.php");
exit();