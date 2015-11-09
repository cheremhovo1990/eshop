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
$reg_errors = $request->getErrors();
$e = trim($request->getVariable('email'), '\'');
// To test the sidebars:
//$_SESSION['user_id'] = 1;
//$_SESSION['user_admin'] = true;
//$_SESSION['user_not_expired'] = true;
//$_SESSION=array();

// Require the database connection:
//require(MYSQL);

// Next block added in Chapter 4:
// If it's a POST request, handle the login attempt:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//include('./includes/login.inc.php');
}

// Include the header file:
require "includes/header.php";
// Display a thanks message...

// Original message from Chapter 4:
// echo '<div class="alert alert-success"><h3>Thanks!</h3><p>Thank you for registering! You may now log in and access the site\'s content.</p></div>';

// Updated message in Chapter 6:
echo '<div class="alert alert-success"><h3>Thanks!</h3><p>Thank you for registering! To complete the process, please now click the button below so that you may pay for your site access via PayPal. The cost is $10 (US) per year. <strong>Note: When you complete your payment at PayPal, please click the button to return to this site.</strong></p></div>';

// PayPal link added in Chapter 6:
echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="email" value="' . $e . '">
	<input type="hidden" name="hosted_button_id" value="8YW8FZDELF296">
	<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
';

// Send a separate email?
$body = "Thank you for registering at <whatever site>. Blah. Blah. Blah.\n\n";
	mail($_POST['email'], 'Registration Confirmation', $body, 'From: admin@example.com');

	// Finish the page:
include "includes/footer.php";