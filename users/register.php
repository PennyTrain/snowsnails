<?php
session_start();

// If already logged in, don’t show register again
if (isset($_SESSION["email"])) {
    header("Location: account.php");
    exit();
}

include "../header.php";
?>

<section class="user-container">
    <h1 class="heading">Create an Account</h1>

    <?php if (!empty($_SESSION["register_error"])): ?>
        <p class="error-message"><?= htmlspecialchars(
            $_SESSION["register_error"],
        ) ?></p>
        <?php unset($_SESSION["register_error"]); ?>
    <?php endif; ?>

    <form action="login_register.php" method="post" class="form-container">

        <label for="first_name" class="form-label">First Name</label>
        <input type="text" id="first_name" name="first_name" class="form-control" required>

        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" id="last_name" name="last_name" class="form-control" required>

        <label for="register_email" class="form-label">Email</label>
        <input type="email" id="register_email" name="email" class="form-control" required>

        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" id="phone" name="phone" class="form-control">

        <label for="register_password" class="form-label">Password</label>
        <input type="password" id="register_password" name="password" class="form-control" required minlength="8">

        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">

        <button type="submit" name="register" class="button link">Register</button>

        <p class="text-center">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </form>
</section>

<?php include "../footer.php"; ?>

<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->