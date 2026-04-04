<?php
session_start();

// If already logged in, don’t show login again

if (isset($_SESSION["email"])) {
    header("Location: update_form.php");
    exit();
}

include "../header.php";
?>

<section class="user-container">
    <h1 class="heading">Welcome Back</h1>

    <?php if (!empty($_SESSION["login_error"])): ?>
        <p class="error-message"><?= htmlspecialchars(
            $_SESSION["login_error"],
        ) ?></p>
        <?php unset($_SESSION["login_error"]); ?>
    <?php endif; ?>

    <form action="login_register.php" method="post" class="form-container">

        <label for="login_email" class="form-label">Email</label>
        <input type="email" id="login_email" name="email" class="form-control" required>

        <label for="login_password" class="form-label">Password</label>
        <input type="password" id="login_password" name="password" class="form-control" required>

        <button type="submit" name="login" class="button link">Login</button>

        <p class="text-center">
            Don’t have an account?
            <a href="register.php">Register</a>
        </p>

    </form>
</section>

<?php include "../footer.php"; ?>

<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->