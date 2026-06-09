<?php
require_once "../helpers/error_page.php";

// creates an instance of the ErrorPage class
$error = new ErrorPage(
    500,
    "Server Error",
    "Something went wrong on our side. Please try again later.",
    "https://res.cloudinary.com/dgz5gpe5z/image/upload/q_auto/f_auto/v1776178502/500_image.png"
);

$error->render();