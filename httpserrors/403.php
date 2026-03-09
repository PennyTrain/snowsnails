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
        <img src="/assets/images/403.png" alt="Page not found" class="error-image">
    </div>

    <a href="/index.php" class="error-button">Go Home</a>
</div>

<?php include "../footer.php"; ?>


<!-- https://http.cat/status/403 -->

<!-- https://hostscore.net/learn/htaccess-guide/ -->