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
<form action="update_profile.php" method="POST" enctype="multipart/form-data">

    <h2 class="heading">Edit Profile</h2>

    <input type="text" name="first_name" value="<?= htmlspecialchars($user["first_name"]) ?>" required>
    <input type="text" name="last_name" value="<?= htmlspecialchars($user["last_name"]) ?>" required>

    <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
    <input type="text" name="phone" value="<?= htmlspecialchars($user["phone"]) ?>" required>

    <!-- Profile Picture -->
    <label>Profile Picture</label>
    <input type="file" name="profile_image" accept="image/*">

    <?php if (!empty($user["img_url"])): ?>
        <img src="<?= $user["img_url"] ?>" width="100">
    <?php endif; ?>

    <hr>

    <!-- Change Password -->
    <input type="password" name="new_password" placeholder="New Password" minlength="8">
    <input type="password" name="confirm_password" placeholder="Confirm Password" minlength="8">

    <button type="submit" name="update_profile" class="button link">Update Profile</button>

</form>
</section>

 <?php // Include the footer file

include "../footer.php";
?>
