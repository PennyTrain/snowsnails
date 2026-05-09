<?php
session_start();

echo "hello " . $_SESSION['username'];
?>
<a href="logoff4.php">logoff</a>