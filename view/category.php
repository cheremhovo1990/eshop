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
$content_pages = $request->getArray('content_pages');
$login_errors = $request->getErrors();

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
var_dump($_SESSION['user_not_expired']);
if (isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])) {
	echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content. Unfortunately your account has expired. Please <a href="renew.php">renew your account</a> in order to access site content.</div>';
} elseif (!isset($_SESSION['user_id'])) {
	echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to view site content.</div>';
}

foreach ($content_pages as $content_page) {

	echo '<div><h4><a href="?cmd=page&id=' . $content_page['id'] . '">' . htmlspecialchars($content_page['title']) . '</a></h4><p>' . htmlspecialchars($content_page['description']) . '</p></div>';
}


/*while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

	// Display each record:
	echo '<div><h4><a href="page.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a></h4><p>' . htmlspecialchars($row['description']) . '</p></div>';

}*/ // End of WHILE loop.

/* PAGE CONTENT ENDS HERE! */

// Include the footer file to complete the template:

include "includes/footer.php";