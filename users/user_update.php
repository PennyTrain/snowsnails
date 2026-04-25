<?php
session_start();
require_once "../config/db.php";
require_once "../helpers/auth.php";

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Get current user data
$user = getCurrentUserData($conn);

include "../header.php";
?>
<section class="user-container">
<form action="user_control.php" method="POST" enctype="multipart/form-data">
    <div class="profile-header">
        <?php if (!empty($user["img_url"])): ?>
        <img src="<?= $user["img_url"] ?>" class="profile-img">
    <?php endif; ?>
                <h2 class="heading">Update Profile</h2>
        </div>
             <label for="login_password" class="form-label">First Name</label>
    <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user["first_name"]) ?>" required>
                 <label for="login_password" class="form-label">Last Name</label>
    <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user["last_name"]) ?>" required>
             <label for="login_password" class="form-label">Email</label>
    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
                 <label for="login_password" class="form-label">Phone</label>
    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user["phone"]) ?>" required>

    <!-- Profile Picture -->
    <label>Profile Picture</label>
    <input type="file" name="profile_image" class="btn btn-secondary" accept="image/*">


    <hr>

    <!-- Change Password -->
             <label for="login_password" class="form-label">New Password</label>
    <input type="password" name="new_password" class="form-control" placeholder="New Password" minlength="8">
        <!-- Change Password -->
             <label for="login_password" class="form-label">Confirm New Password</label>
    <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" minlength="8">

    <button type="submit" name="update_profile" class="btn btn-secondary">Update Profile</button>

</form>
</section>

 <?php // Include the footer file

include "../footer.php";
?>
