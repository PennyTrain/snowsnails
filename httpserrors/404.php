<?php
require_once "../helpers/error_page.php";

// creates an instance of the ErrorPage class
$error = new ErrorPage(
    404,
    "Not Found",
    "Oops! The page you are looking for does not exist.",
    "https://res.cloudinary.com/dgz5gpe5z/image/upload/q_auto/f_auto/v1776178502/404_ipnapb.png"
);

$error->render();