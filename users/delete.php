<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/errors.php";
require_once "../helpers/auth.php";

protectedUserPage($conn);
if (isset($_POST["cancel_logout"])) {
    header("Location: user_update.php");
    exit();
}
include "../header.php";
?>

<div class="user-container">
    <h1 class="heading">Are you sure you want to delete your account?</h1>
    <form method="post" action="user_control.php">
        <button type="submit" name="delete" class="btn btn-danger">
            Yes, Delete Account
        </button>

        <button type="submit" name="cancel_logout" class="btn btn-secondary">
            No, Take Me Back
        </button>
    </form>
</div>
<?php include "../footer.php"; ?>


<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->