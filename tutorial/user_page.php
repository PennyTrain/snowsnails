 <?php
// Include the header file
include '../header.php';

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: user_form.php");
    exit();
}

?>
    <!-- CONTENT  -->
<div class="div">
    <h1>Welcome, <span><?= $_SESSION['name']; ?></span></h1>
    <button onclick="window.location.href='logout.php'">logout</button>
</div>
 <?php
// Include the footer file
include '../footer.php';
?>