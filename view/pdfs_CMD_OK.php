<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.10.2015
 * Time: 9:02
 */
use \eshop\view\ViewHelper as VH;

$request = VH::getRequest();

$title = $request->getTitle();
$cats = $request->getArray('cats');
//$login_errors = $request->getErrors();
$string = $request->getVariable('string');
$pdfs = $request->getArray('pdfs');
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
echo '<h1>PDF Guides</h1>';
echo $string;

foreach ($pdfs as $row) {
 echo '<div><h4><a href="?cmd=view_pdf&id=' . htmlspecialchars($row['tmp_name']) . '">' . htmlspecialchars($row['title']) . ' </a> (' . $row['size'] . 'kb)</h4><p>' . htmlspecialchars($row['description']) . '</p></div>';
}
 /* PAGE CONTENT ENDS HERE! */

// Include the footer file to complete the template:

include "includes/footer.php";

