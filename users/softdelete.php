<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/errors.php";
require_once "../helpers/auth.php";

protectedUserPage($conn);
if (isset($_POST["cancel_logout"])) {
    header("Location: /dab502/assignment/snowsnail/users/user_update.php");
    exit();
}
include "../header.php";
?>
<main class="container">
<div class="row service-container">
    <h1 class="heading">WAIT!</h1>
        <p class=heading>Are you sure you want to delete your account?</p>
    <form method="post" action="user_control.php">
        <button type="submit" name="delete" class="btn btn-danger">
            Yes, Delete Account
        </button>

    <a href="/dab502/assignment/snowsnail/users/user_update.php" class="btn btn-secondary">No take me back!</a></p>
    </form>
</div>
</main>
<?php include "../footer.php"; ?>


<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->