<?php
session_start();

if (!isset($_SESSION["email"])) {
    http_response_code(403);
    include "../httpserrors/403.php"; // adjust path if needed
    exit();
}

include "../header.php";
?>

<div class="user-container">
    <h1>Welcome, <span><?= htmlspecialchars($_SESSION["name"]) ?></span></h1>
    <button onclick="window.location.href='logout.php'">Logout</button>
</div>

<?php include "../footer.php"; ?>

<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->