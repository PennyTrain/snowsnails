<?php
session_start();
require_once "../db.php";
require_once "./auth_helpers.php";
require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

$user = getCurrentUser($conn);

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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

$stmt = $conn->prepare("SELECT password, img_url FROM users WHERE email = ?");
$stmt->execute([$_SESSION["email"]]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

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

$_SESSION["name"] = $first_name;
$_SESSION["email"] = $email;

header("Location: update_form.php");
exit();