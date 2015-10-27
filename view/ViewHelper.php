<?php
namespace view;

//require_once( "woo/base/Registry.php" );

class ViewHelper {
    static function getRequest() {
        return \base\ApplicationRegistry::getRequest();
    }

    public static function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://')
    {
        // Check for the session item:
        if (!isset($_SESSION[$check])) {
            $url = $protocol . BASE_URL . $destination; // Define the URL.
            header("Location: $url");
            exit(); // Quit the script.
        }
    }
}

?>
