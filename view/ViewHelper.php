<?php
namespace view;

//require_once( "woo/base/Registry.php" );

class ViewHelper {
    static function getRequest() {
        return \base\ApplicationRegistry::getRequest();
    }
}

?>
