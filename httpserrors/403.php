<?php
require_once "../helpers/error_page.php";

// creates an instance of the ErrorPage class
$error = new ErrorPage(
    403,
    "Forbidden",
    "Oops! You are FORBIDDEN from going here!",
    "https://res.cloudinary.com/dgz5gpe5z/image/upload/q_auto/f_auto/v1776178502/403_zcpzfd.png",
);

$error->render();
