<?php
if (!isset($_SESSION["email"])) {
    http_response_code(403);
    include("../httpserrors/403.php"); // adjust path if needed
    exit();
}

include "../header.php";
?>

<div class="user-container">
        <form action="login_register.php" method="post" class="form-container">

        <label for="first_name" class="form-label">First Name</label>
        <input type="text" id="first_name" name="first_name" class="form-control" required>

        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" id="last_name" name="last_name" class="form-control" required>

        <label for="register_email" class="form-label">Email</label>
        <input type="email" id="register_email" name="email" class="form-control" required>

        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" id="phone" name="phone" class="form-control">

        <label for="register_password" class="form-label">New Password</label>
        <input type="password" id="register_password" name="password" class="form-control" required minlength="8">

        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">

        <button type="submit" name="update" class="button link">Update</button>

    </form>
</div>

<?php include "../footer.php"; ?>
