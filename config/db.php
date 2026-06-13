<?php

if (getenv("JAWSDB_URL")) {
    $url = parse_url(getenv("JAWSDB_URL"));

    $host = $url["host"];
    $dbname = ltrim($url["path"], "/");
    $username = $url["user"];
    $password = $url["pass"];
} else {
    // $host = "localhost";
    // $dbname = "snowsnails";
    // $username = "root";
    // $password = "";
    $host = "localhost";
    $dbname = "u2410737";
    $username = "u2410737";
    $password = "4B82bRQd";
}

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// var_dump($conn)
// for debugging