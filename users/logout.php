<?php

session_start();
if (!isset($_SESSION["email"])) {
    http_response_code(403);
    include("../httpserrors/403.php"); // adjust path if needed
    exit();
}

// If user clicks YES
if (isset($_POST["confirm_logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// If user clicks NO
if (isset($_POST["cancel_logout"])) {
    header("Location: account.php");
    exit();
}

include "../header.php";
?>

<div class="user-container">
    <h1 class="heading">Are you sure you want to log out?</h1>

    <form method="post">
        <button type="submit" name="confirm_logout" class="btn btn-danger">
            Yes, Log Me Out
        </button>

        <button type="submit" name="cancel_logout" class="btn btn-secondary">
            No, Take Me Back
        </button>
    </form>
</div>

<?php include "../footer.php"; ?>


<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->