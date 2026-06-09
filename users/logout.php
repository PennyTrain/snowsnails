<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/errors.php";
require_once "../helpers/auth.php";

protectedUserPage($conn);

include "../header.php";
?>

<div class="user-container">
    <h1 class="heading">Are you sure you want to log out?</h1>

    <form method="post" action="user_control.php">
        <button type="submit" name="confirm_logout" class="btn btn-danger">
            Yes, Log Me Out
        </button>

        <button type="submit" name="cancel_logout" class="btn btn-secondary">
            No, Take Me To Home
        </button>
    </form>
</div>
<?php include "../footer.php"; ?>


<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->