<?php
http_response_code(403);
include "../header.php";
?>

<div class="user-container error-page">
    <h1 class="heading error-code">403</h1>

    <p class="text error-text">
        Oops! You are FORBIDDEN from going here!
    </p>

    <div class="error-image-wrapper">
        <img src="https://res.cloudinary.com/dgz5gpe5z/image/upload/q_auto/f_auto/v1776178502/403_zcpzfd.png" alt="Page not found" class="error-image">
    </div>

    <a href="/index.php" class="error-button">Go Home</a>
</div>

<?php include "../footer.php"; ?>


<!-- https://http.cat/status/403 -->

<!-- https://hostscore.net/learn/htaccess-guide/ -->