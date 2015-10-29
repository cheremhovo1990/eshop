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
?>
<h1>Reset Your Password</h1>
<p>Enter your email address below to reset your password.</p>
<form action="?cmd=forgot_password" method="post" accept-charset="utf-8">
	<?php VH::create_form_input('email', 'text', 'Email Address', $pass_errors); ?>
	<input type="submit" name="submit_button" value="Reset &rarr;" id="submit_button" class="btn btn-default" />
</form>

<?php // Include the HTML footer:

include "includes/footer.php";