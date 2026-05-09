<?php

session_start();

if (session_destroy()) echo "you have been logged off";
else echo "error logging off"


?>