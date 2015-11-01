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
$add_pdf_errors = $request->getErrors();
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

echo $string;
?>
<h1>Add a PDF</h1>
<form enctype="multipart/form-data" action="?cmd=add_pdf" method="post" accept-charset="utf-8">

	<input type="hidden" name="MAX_FILE_SIZE" value="5242880">

	<fieldset><legend>Fill out the form to add a PDF to the site:</legend>

		<?php
		VH::create_form_input('title', 'text', 'Title', $add_pdf_errors);
		VH::create_form_input('description', 'textarea', 'Description', $add_pdf_errors);

		// Add the file input:
		echo '<div class="form-group';

		// Add classes, if applicable:
		if (array_key_exists('pdf', $add_pdf_errors)) {
			echo ' has-error';
		} else if (isset($_SESSION['pdf'])) {
			echo ' has-success';
		}
		echo '"><label for="pdf" class="control-label">PDF</label><input type="file" name="pdf" id="pdf">';

		// Check for an error:
		if (array_key_exists('pdf', $add_pdf_errors)) {

			echo '<span class="help-block">' . $add_pdf_errors['pdf'] . '</span>';

		} else { // No error.

			// If the file exists (from a previous form submission but there were other errors),
			// store the file info in a session and note its existence:
			if (isset($_SESSION['pdf'])) {
				echo '<p class="lead">Currently: "' . $_SESSION['pdf']['file_name'] . '"</p>';
			}

		} // end of errors IF-ELSE.
		echo '<span class="help-block">PDF only, 5MB Limit</span>
</div>';

		?>
		<input type="submit" name="submit_button" value="Add This PDF" id="submit_button" class="btn btn-default" />

	</fieldset>

</form>

<?
// Include the footer file to complete the template:

include "includes/footer.php";