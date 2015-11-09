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
$add_page_errors = $request->getErrors();
$cats2 = $request->getArray('cats2');
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
?>
	<h1>Add a Site Content Page</h1>
	<form action="?cmd=add_page" method="post" accept-charset="utf-8">

		<fieldset><legend>Fill out the form to add a page of content:</legend>
			<div class="form-group">
				<label for="status" class="control-label">Status</label>
				<select name="status" class="form-control"><option value="draft">Draft</option>
					<option value="live">Live</option>
				</select></div>

			<?php
			VH::create_form_input('title', 'text', 'Title', $add_page_errors);

			// Add the category drop down menu:
			echo '<div class="form-group';
			if (array_key_exists('category', $add_page_errors)) echo ' has-error';

			/*
			echo '"><label for="category" class="control-label">Category</label>
			<select name="category" class="form-control">
			<option>Select One</option>';
			*/

			// Bonus material!
			// Added in Chapter 12.
			// Allow for multiple categories:
			echo '"><label for="category" class="control-label">Category</label>
<select name="category" class="form-control" multiple size="5">';

			// Retrieve all the categories and add to the pull-down menu:
			//$q = "SELECT id, category FROM categories ORDER BY category ASC";
			//$r = mysqli_query($dbc, $q);
/*			while ($row = mysqli_fetch_array($r, MYSQLI_NUM)) {
				echo "<option value=\"$row[0]\"";
				// Check for stickyness:
				if (isset($_POST['category']) && ($_POST['category'] == $row[0]) ) echo ' selected="selected"';
				echo ">$row[1]</option>\n";
			}*/

			foreach ($cats2 as $row) {
				echo "<option value=\"$row[0]\"";
				if (isset($_POST['category']) && ($_POST['category'] == $row[0]) ) echo ' selected="selected"';
				echo ">$row[1]</option>\n";
			}


			echo '</select>';
			if (array_key_exists('category', $add_page_errors)) echo '<span class="help-block">' . $add_page_errors['category'] . '</span>';
			echo '</div>';

			VH::create_form_input('description', 'textarea', 'Description', $add_page_errors);
			VH::create_form_input('content', 'textarea', 'Content', $add_page_errors);
			?>

			<input type="submit" name="submit_button" value="Add This Page" id="submit_button" class="btn btn-default" />

		</fieldset>

	</form>

	<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
			// General options
			selector : "#content",
			width : 800,
			height : 400,
			browser_spellcheck : true,

			plugins: "paste,searchreplace,fullscreen,hr,link,anchor,image,charmap,media,autoresize,autosave,contextmenu,wordcount",

			toolbar1: "cut,copy,paste,|,undo,redo,removeformat,|hr,|,link,unlink,anchor,image,|,charmap,media,|,search,replace,|,fullscreen",
			toolbar2:	"bold,italic,underline,strikethrough,|,alignleft,aligncenter,alignright,alignjustify,|,formatselect,|,bullist,numlist,|,outdent,indent,blockquote,",

			// Example content CSS (should be your site CSS)
			content_css : "/ex1/html/css/bootstrap.min.css",

		});
	</script>
	<!-- /TinyMCE -->
<?php /* PAGE CONTENT ENDS HERE! */

// Include the footer file to complete the template:

include "includes/footer.php";