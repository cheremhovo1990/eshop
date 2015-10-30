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
$pass_errors = $request->getErrors();

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
?><h1>Change Your Password</h1>
	<p>Use the form below to change your password.</p>
	<form action="?cmd=change_password" method="post" accept-charset="utf-8">
		<?php
		VH::create_form_input('current', 'password', 'Current Password', $pass_errors);
		VH::create_form_input('pass1', 'password', 'Password', $pass_errors);
		echo '<span class="help-block">Must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number.</span>';
		VH::create_form_input('pass2', 'password', 'Confirm Password', $pass_errors);
		?>
		<input type="submit" name="submit_button" value="Change &rarr;" id="submit_button" class="btn btn-default" />
	</form>

<?php // Include the HTML footer:

// Include the footer file to complete the template:

include "includes/footer.php";