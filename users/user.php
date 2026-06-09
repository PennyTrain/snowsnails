<?php
session_start();
require_once "../config/db.php";
require_once "../helpers/auth.php";
protectedUserPage($conn);
// Get current user data
$user = getCurrentUserData($conn);

include_once "../header.php";
?>
<section class="user-container">
<div class="profile-header">
    <?php if (!empty($user["img_url"])): ?>
        <img src="<?= $user[
            "img_url"
        ] ?>" class="profile-img" alt="User account">
    <?php endif; ?>
    <h2 class="heading"><?= htmlspecialchars(
        $user["first_name"],
    ) ?>'s Profile</h2>
    </div>
    <div class="profile-content">
        <ul>
            <li>
                <!-- text interpolation to display value from database -->
                <?= htmlspecialchars(
                    $user["first_name"],
                ) ?> <?= htmlspecialchars($user["last_name"]) ?>
            </li>
            <li>
                <?= htmlspecialchars($user["email"]) ?>
            </li>
            <li>
                <?= htmlspecialchars($user["phone"]) ?>
            </li>
        </ul>
            <button onclick="window.location.href='user_update.php'" class="btn btn-secondary">Update Info!</button>
            <button onclick="window.location.href='logout.php'" class="btn btn-secondary">Logout</button>
    </div>
</section>

 <?php // Include the footer file

include_once "../footer.php";
?>
