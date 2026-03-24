<?php
//conncect 
$db = new PDO ('mysql:host=localhost; dbname=musicden2;', 'root', '');

var_dump($db);

$stmt = $db->prepare("SELECT * FROM artists");
$stmt->execute();
$artists = $stmt->fetchAll();

// echo'<pre>';
// print_r($artists);
// echo'</pre>'
echo "<ul>";
foreach($artists as $artist){
    echo "<li>" , $artist["first_name"] . "</li>";
}
echo "</ul>"

?>