<?
namespace eshop\command;

class View_pdf extends \eshop\command\Command{
	function doExecute(\eshop\controller\Request $request){
		$pdo = \eshop\PDO\ConnectPDO::instance();
		//$request->setTitle('default');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);

		$valid = false;
		if (isset($_GET['id']) && (strlen($_GET['id']) === 63) && (substr($_GET['id'], 0, 1) !== '.') ) {

			// Identify the file:
			$file = PDFS_DIR . $_GET['id'];

			// Check that the PDF exists and is a file:
			if (file_exists ($file) && (is_file($file)) ) {

				// Get the info:
				$q = 'SELECT id, title, description, file_name FROM pdfs WHERE tmp_name=' . $pdo->quote($_GET['id']);
				$r = $pdo->query($q);
				if (count($row = $r->fetchAll(\PDO::FETCH_ASSOC)) === 1) { // OK!

					// Fetch the info:
					$row = $row[0];

					// Indicate that the file reference is fine:
					$valid = true;

					// Only display the PDF to a user whose account is active:
					if (isset($_SESSION['user_not_expired'])) {

						// Bonus material! Referenced in Chapter 5.
						// Record this visit to the history table:
						// $q = "INSERT INTO history (user_id, type, pdf_id) VALUES ({$_SESSION['user_id']}, 'pdf', {$row['id']})";
						// $r = mysqli_query($dbc, $q);

						// Send the content information:
						header('Content-type:application/pdf');
						header('Content-Disposition:inline;filename="' . $row['file_name'] . '"');
						$fs = filesize($file);
						header("Content-Length:$fs\n");

						// Send the file:
						readfile ($file);
						exit();

					} else { // Inactive account!

						// Display an HTML page instead:
						//$page_title = $row['title'];
						$request->setArray('pdf', $row);
						//include('./includes/header.html');
						//echo "<h1>$page_title</h1>";


						// Change the message based upon the user's status:
						if (isset($_SESSION['user_id'])) {
							$string = '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content, but your account is no longer current. Please <a href="renew.php">renew your account</a> in order to access this file.</div>';
							//echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content, but your account is no longer current. Please <a href="renew.php">renew your account</a> in order to access this file.</div>';
						} else { // Not logged in.
							$string = '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to access this file.</div>';
							//echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to access this file.</div>';
						}

						$request->setVariable('string', $string);
						// Complete the page:
						//echo '<div>' . htmlspecialchars($row['description']) . '</div>';
						return self::statuses('CMD_DEFAULT');
						//include('./includes/footer.html');

					} // End of user IF-ELSE.

				} // End of mysqli_num_rows() IF.

			} // End of file_exists() IF.

		} // End of $_GET['id'] IF.

		if (!$valid) {
			//$page_title = 'Error!';
			$request->setTitle('Error!');
			//include('./includes/header.html');
			return self::statuses('CMD_ERROR');
			//echo '<div class="alert alert-danger">This page has been accessed in error.</div>';
			//include('./includes/footer.html');
		}

		//$request->setErrors($login_errors);
		//return self::statuses('CMD_DEFAULT');
	}
}