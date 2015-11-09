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
?>
	<h1>Register</h1>
<p>Access to the site's content is available to registered users at a cost of $10.00 (US) per year. Use the form below to begin the registration process. <strong>Note: All fields are required.</strong> After completing this form, you'll be presented with the opportunity to securely pay for your yearly subscription via <a href="http://www.paypal.com">PayPal</a>.</p>
<form action="http://<?=BASE_URL?>?cmd=register" method="post" accept-charset="utf-8">
<?php
VH::create_form_input('first_name', 'text', 'First Name', $reg_errors);
VH::create_form_input('last_name', 'text', 'Last Name', $reg_errors);
VH::create_form_input('username', 'text', 'Desired Username', $reg_errors);
echo '<span class="help-block">Only letters and numbers are allowed.</span>';
VH::create_form_input('email', 'email', 'Email Address', $reg_errors);
VH::create_form_input('pass1', 'password', 'Password', $reg_errors);
echo '<span class="help-block">Must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number.</span>';
VH::create_form_input('pass2', 'password', 'Confirm Password', $reg_errors);
?>
	<input type="submit" name="submit_button" value="Next &rarr;" id="submit_button" class="btn btn-default" />
</form>
<br>
<?php /* PAGE CONTENT ENDS HERE! */

// Include the footer file to complete the template:

include "includes/footer.php";