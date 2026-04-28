<?php

function getCurrentUser(PDO $conn): array
{
    if (empty($_SESSION["email"])) {
        header("Location: /login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_SESSION["email"]]);
    $user = $stmt->fetch();

    if (!$user) {
        session_destroy();
        header("Location: /login.php");
        exit();
    }

    return $user;
}

function getCurrentUserData(PDO $conn): array
{
    $stmt = $conn->prepare(
        "SELECT first_name, last_name, email, phone, img_url FROM users WHERE email = ?",
    );
    $stmt->execute([$_SESSION["email"]]);
    $user = $stmt->fetch();

    return $user;
}

function protectedPage(PDO $conn): array
{
    if (!isset($_SESSION["email"])) {
        http_response_code(403);
        include "../httpserrors/403.php";
        exit();
    }
}
