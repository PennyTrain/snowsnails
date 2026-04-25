<?php
session_start();

$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
include "../header.php";
?>

<section class="user-container">
    <h1 class="heading">
        <?= $isAdmin ? "Create User" : "Create an Account" ?>
    </h1>

    <?php if (!empty($_SESSION["register_error"])): ?>
        <p class="error-message"><?= htmlspecialchars($_SESSION["register_error"]) ?></p>
        <?php unset($_SESSION["register_error"]); ?>
    <?php endif; ?>

    <form action="user_control.php" method="post" class="form-container">

        <label>First Name</label>
        <input type="text" name="first_name" class="form-control" required>

        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control" required>

        <label>Email</label>
        <input type="email" name="email" class="form-control" required>

        <label>Phone</label>
        <input type="tel" name="phone" class="form-control">

        <label>Password</label>
        <input type="password" name="password" class="form-control" required minlength="8">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required minlength="8">

        <?php if ($isAdmin): ?>
            <!-- ADMIN ONLY FIELD -->
            <label>User Role</label>
            <select name="role" class="form-control">
                <option value="customer">Customer</option>
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select>
        <?php endif; ?>

        <button type="submit" name="register" class="btn btn-secondary">
            <?= $isAdmin ? "Create User" : "Register" ?>
        </button>

                <p class="text-center">
            Already Have an Account? 
            <a href="login.php">Login</a>
        </p>

    </form>
</section>

<?php include "../footer.php"; ?>