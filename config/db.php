<?php

if (getenv('JAWSDB_URL')) {
    // Running on Heroku (JawsDB MySQL)
    $url = parse_url(getenv('JAWSDB_URL'));

    $host = $url['host'];
    $dbname = ltrim($url['path'], '/');
    $username = $url['user'];
    $password = $url['pass'];
} else {
    // Local development (your current setup)
    $host = "localhost";
    $dbname = "snowsnails";
    $username = "root";
    $password = "";
}

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
