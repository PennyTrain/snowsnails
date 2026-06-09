<?php
require_once "auth.php";
// noAccess();

function throwErr($name, $type, $text)
{
    // ensure there is a session before usin g $_session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // add message to the session
    $_SESSION["flash_messages"][] = [
        "name" => $name, // identifier, what form? login/booking_create/register
        "type" => $type, // boostrap alert type, success/danger/warning
        "text" => $text, // message to display
    ];
}

//funct to dispay all flash msgs
function displayErrors()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_SESSION["flash_messages"])) {
        foreach ($_SESSION["flash_messages"] as $message) {
            echo '<div class="alert alert-' .
                htmlspecialchars($message["type"]) .
                '" role="alert">';
            echo htmlspecialchars($message["text"]);
            echo "</div>";
        }

        // clear messages after showing on refresh
        unset($_SESSION["flash_messages"]);
    }
}
