<?php
// Here I get all the user data in the same file
// and import the function whereever I need it
function getCurrentUserData(PDO $conn): array
{
    if (empty($_SESSION["email"])) {
        header("Location: /dab502/assignment/snowsnail/users/login.php");
        exit();
    }

    $stmt = $conn->prepare("
        SELECT 
            first_name,
            last_name,
            user_id,
            email,
            phone,
            img_url,
            role,
            subscribed,
            gender
        FROM users
        WHERE email = ?
        AND deleted_at IS NULL
    ");

    $stmt->execute([$_SESSION["email"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_unset();
        session_destroy();
        header("Location: /dab502/assignment/snowsnail/users/login.php");
        exit();
    }

    return $user;
}

function protectedPage(PDO $conn): array
{
    if (!isset($_SESSION["email"])) {
        http_response_code(403);
        include "/dab502/assignment/snowsnail/httpserrors/403.php";
        exit();
    }

    $user = getCurrentUserData($conn);

    if (!$user || $user["role"] !== "admin") {
        http_response_code(403);
        include "/dab502/assignment/snowsnail/httpserrors/403.php";
        exit();
    }

    return $user;
}

function protectedUserPage(PDO $conn): array
{
    if (!isset($_SESSION["email"])) {
        http_response_code(403);
        include "/dab502/assignment/snowsnail/httpserrors/403.php";
        exit();
    }

    $user = getCurrentUserData($conn);

    return $user;
}

function noAccess(): void
{
    http_response_code(403);
    include __DIR__ . "/dab502/assignment/snowsnail/httpserrors/403.php";
    exit();
}
