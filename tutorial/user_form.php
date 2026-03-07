<?php
include '../header.php';

session_start(); //starts or continues an active session
$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
]; // stores error msgs
$activeForm = $_SESSION['active_form'] ?? 'login'; // checks which form is active
session_unset(); // remove all existing session variables however the session itself remains active?
function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
} // shows error

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<!-- LOGIN -->
<section class="user-container <?= isActiveForm('login', $activeForm); ?>" id="login-form">
    <h1 class="heading">Welcome Back</h1>

    <form action="login_register.php" method="post" class="form-container">
        <?= showError($errors['login']); ?>

        <label for="login_email" class="form-label">Email</label>
        <input 
            type="email" 
            id="login_email"
            name="email" 
            class="form-control"
            required
        >

        <label for="login_password" class="form-label">Password</label>
        <input 
            type="password" 
            id="login_password"
            name="password" 
            class="form-control"
            required
        >

        <button type="submit" name="login" class="button link">
            Login
        </button>

        <p class="text-center">
            Don’t have an account?
            <a href="#" onclick="showForm('register-form')">Register</a>
        </p>

    </form>
</section>


<!-- REGISTER -->
<section class="user-container <?= isActiveForm('register', $activeForm); ?>" id="register-form">
    <h1 class="heading">Create an Account</h1>

    <form action="login_register.php" method="post" class="form-container">
        <?= showError($errors['register']); ?>

        <label for="first_name" class="form-label">First Name</label>
        <input 
            type="text" 
            id="first_name"
            name="first_name" 
            class="form-control"
            required
        >

        <label for="last_name" class="form-label">Last Name</label>
        <input 
            type="text" 
            id="last_name"
            name="last_name" 
            class="form-control"
            required
        >

        <label for="register_email" class="form-label">Email</label>
        <input 
            type="email" 
            id="register_email"
            name="email" 
            class="form-control"
            required
        >

        <label for="phone" class="form-label">Phone Number</label>
        <input 
            type="tel" 
            id="phone"
            name="phone" 
            class="form-control"
        >

        <label for="register_password" class="form-label">Password</label>
        <input 
            type="password" 
            id="register_password"
            name="password" 
            class="form-control"
            required
            minlength="8"
        >

        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input 
            type="password" 
            id="confirm_password"
            name="confirm_password" 
            class="form-control"
            required
            minlength="8"
        >

        <button type="submit" name="register" class="button link">
            Register
        </button>

        <p class="text-center">
            Already have an account?
            <a href="#" onclick="showForm('login-form')">Login</a>
        </p>

    </form>
</section>

<?php
include '../footer.php';
?>