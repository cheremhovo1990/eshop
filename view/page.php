<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.10.2015
 * Time: 9:02
 */
use \view\ViewHelper as VH;

$request = VH::getRequest();

$title = $request->getTitle();
$cats = $request->getArray('cats');
$login_errors = $request->getErrors();
$row = $request->getArray('page');
$string = $request->getVariable('string');
// To test the sidebars:
//$_SESSION['user_id'] = 1;
//$_SESSION['user_admin'] = true;
//$_SESSION['user_not_expired'] = true;
//$_SESSION=array();

// Require the database connection:
//require(MYSQL);

// Next block added in Chapter 4:
// If it's a POST request, handle the login attempt:


// Include the header file:
require "includes/header.php";

/* PAGE CONTENT STARTS HERE! */

echo '<h1>' . htmlspecialchars($title) . '</h1>';

//echo $string;
echo $string;

/* PAGE CONTENT ENDS HERE! */

// Include the footer file to complete the template:

include "includes/footer.php";