<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>
<?php // Use a default page title if one wasn't provided...
	if (isset($title)) {
			echo $title;
	} else {
			echo 'Knowledge is Power: And It Pays to Know';
	}
?>

	</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

  </head>

  <body>

    <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-fixed-top">
        <div class="container">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Knowledge is Power</a>
          <div class="nav-collapse collapse">
            <ul class="nav navbar-nav">
<?php // Dynamically create header menus...

// Array of labels and pages (without extensions):
$pages = array (
	'Home' => 'index.php',
	'Register' => '?cmd=register'
);

// The page being viewed:
$this_page = basename($_SERVER['PHP_SELF']);

// Create each menu item:
foreach ($pages as $k => $v) {

	// Start the item:
	echo '<li';

	// Add the class if it's the current page:
	if ($this_page == $v) echo ' class="active"';

	// Complete the item:
	echo '><a href="' . $v . '">' . $k . '</a></li>
	';

} // End of FOREACH loop.

// Show the user options:
if (isset($_SESSION['user_id'])) {

	// Show basic user options:
	// Includes references to some bonus material discussed in Part Four!
	echo '<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="?cmd=logout">Logout</a></li>
			<li><a href="?cmd=change_password">Change Password</a></li>
		</ul>
	</li>';

	// Show admin options, if appropriate:
	if (isset($_SESSION['user_admin'])) {
		echo '<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li><a href="?cmd=add_page">Add Page</a></li>
				<li><a href="?cmd=add_pdf">Add PDF</a></li>
			</ul>
		</li>';		
	}
	
} // user_id not set.

?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/container-->
      </div><!--/navbar-->

      <!-- Begin page content -->
      <div class="container">
	
		<div class="row">
			
			<div class="col-3">
				<h3 class="text-success">Content</h3>
			<div class="list-group">
<?php // Dynamically generate the content links:
foreach( $cats as $cat ){
	echo '<a href="?cmd=category&id=' . $cat['id'] . '" class="list-group-item" title="' . $cat['category'] . '">' . htmlspecialchars($cat['category']) . '</a>';
}

?>
			  <a href="?cmd=pdfs" class="list-group-item" title="PDFs">PDF Guides
			  </a>
			</div><!--/list-group-->

<?php // Should we show the login form?
if (!isset($_SESSION['user_id'])) {
	require('login_form.inc.php');
}
?>
			</div><!--/col-3-->
		  
			
		  <div class="col-9">
			<!-- CONTENT -->
