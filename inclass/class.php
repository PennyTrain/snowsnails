<?php
require_once "config/db.php";
echo "<h2>prepared sql</h2>";
$lastname = "Train";
$sql = "SELECT * FROM users WHERE last_name = :lastname";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt as $row) {
    echo $row['first_name'] . "" . $row['last_name'] . "<br>";
}

echo "<h2>stand sql</h2>";
$sql = "SELECT * FROM users WHERE last_name = '$lastname'";
$result = $conn->query($sql);
foreach ($result as $row) {
    echo $row['first_name'] . "" . $row['last_name'] . "<br>";
}

echo "<h2>prepared statements with anonymous parameters</h2>";
$sql = "SELECT * FROM users WHERE last_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bindValue(1, $lastname, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt as $row) {
    echo $row['first_name'] . "" . $row['last_name'] . "<br>";
}
