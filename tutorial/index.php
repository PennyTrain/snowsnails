 <?php
require_once '../db.php';
 ?>
 <?php
// Include the header file
include '../header.php';
?>
<!-- LOGIN -->
    <div class="user-container active">
        <div class="form" id="login-form">
            <form action="" method="post" class="form-container">
                <h2>login</h2>
                <input type="email" name="email" placeholder="email" required>
                <input type="password" name="password" placeholder="password" required>
                <button type="submit" name="login">login</button>
                <p>Dont have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>

            </form>
        </div>
    </div>
<!-- REGISTER -->
        <div class="user-container">
        <div class="form" id="register-form">
            <form action="" method="post" class="form-container">
                <h2>Register</h2>
                <input type="firstname" name="firstname" placeholder="firstname" required>
                <input type="lastname" name="lastname" placeholder="lastname" required>
                <input type="email" name="email" placeholder="email" required>
                <input type="tel" id="phone" name="phone" class="form-control" aria-label="Phone Number">
                <input type="password" name="password" placeholder="password" required>
                <button type="submit" name="register">Register</button>
                <p>Already have an account?<a href="#" onclick="showForm('login-form')">Login</a></p>

            </form>
        </div>
    </div>
 <?php
// Include the footer file
include '../footer.php';
?>