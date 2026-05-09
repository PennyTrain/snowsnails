<?php

function throwErr($name, $type, $text)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION["flash_messages"][] = [
        "name" => $name,
        "type" => $type,
        "text" => $text
    ];
}

function displayErrors()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_SESSION["flash_messages"])) {
        foreach ($_SESSION["flash_messages"] as $message) {
            echo '<div class="alert alert-' . htmlspecialchars($message["type"]) . '" role="alert">';
            echo htmlspecialchars($message["text"]);
            echo '</div>';
        }

        // clear messages after showing (important!)
        unset($_SESSION["flash_messages"]);
    }
}