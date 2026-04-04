<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Get current user data
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, img_url FROM users WHERE email = ?");
$stmt->execute([$_SESSION["email"]]);
$user = $stmt->fetch();

include "../header.php";
?>
<section class="user-container">
<div class="profile-header">
    <?php if (!empty($user["img_url"])): ?>
        <img src="<?= $user["img_url"] ?>" class="profile-img">
    <?php endif; ?>
    <h2 class="heading"><?= htmlspecialchars($user["first_name"]) ?>'s Profile</h2>
    </div>
        <ul>
            <li>
                <?= htmlspecialchars($user["first_name"]) ?> <?= htmlspecialchars($user["last_name"]) ?>
            </li>
            <li>
                <?= htmlspecialchars($user["email"]) ?>
            </li>
            <li>
                <?= htmlspecialchars($user["phone"]) ?>
            </li>
        </ul>
            <button onclick="window.location.href='logout.php'">Logout</button>
                        <button onclick="window.location.href='update_form.php'">Update Info!</button>
</section>

 <?php // Include the footer file
include "../footer.php";
?>
