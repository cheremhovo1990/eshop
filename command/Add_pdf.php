<?
namespace command;

class Add_pdf extends \command\Command{
	function doExecute(\controller\Request $request){
		$this->redirect_invalid_user('user_admin');
		$pdo = \PDO\ConnectPDO::instance();
		$request->setTitle('user_admin');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);

		$add_pdf_errors = array();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			// Check for a title:
			if (!empty($_POST['title'])) {
				$t = $pdo->quote(strip_tags($_POST['title']));
			} else {
				$add_pdf_errors['title'] = 'Please enter the title!';
			}

			// Check for a description:
			if (!empty($_POST['description'])) {
				$d = $pdo->quote(strip_tags($_POST['description']));
			} else {
				$add_pdf_errors['description'] = 'Please enter the description!';
			}

			// Check for a PDF:
			if (is_uploaded_file($_FILES['pdf']['tmp_name']) && ($_FILES['pdf']['error'] === UPLOAD_ERR_OK)) {

				// Get a reference:
				$file = $_FILES['pdf'];

				// Find the size:
				$size = ROUND($file['size']/1024);

				// Validate the file size (5MB max):
				if ($size > 5120) {
					$add_pdf_errors['pdf'] = 'The uploaded file was too large.';
				}

				// Validate the file type:
				// Create the resource:
				$fileinfo = finfo_open(FILEINFO_MIME_TYPE);

				// Check the file:
				if (finfo_file($fileinfo, $file['tmp_name']) !== 'application/pdf') {
					$add_pdf_errors['pdf'] = 'The uploaded file was not a PDF.';
				}

				// Close the resource:
				finfo_close($fileinfo);

				// Move the file over, if no problems:
				if (!array_key_exists('pdf', $add_pdf_errors)) {

					// Create a tmp_name for the file:
					$tmp_name = sha1($file['name']) . uniqid('',true);

					// Move the file to its proper folder but add _tmp, just in case:
					$dest =  PDFS_DIR . $tmp_name . '_tmp';

					if (move_uploaded_file($file['tmp_name'], $dest)) {

						// Store the data in the session for later use:
						$_SESSION['pdf']['tmp_name'] = $tmp_name;
						$_SESSION['pdf']['size'] = $size;
						$_SESSION['pdf']['file_name'] = $file['name'];

						// Print a message:

						//echo '<div class="alert alert-success"><h3>The file has been uploaded!</h3></div>';
						$request->setVariable('string', '<div class="alert alert-success"><h3>The file has been uploaded!</h3></div>');
					} else {
						trigger_error('The file could not be moved.');
						unlink ($file['tmp_name']);
					}

				} // End of array_key_exists() IF.

			} elseif (!isset($_SESSION['pdf'])) { // No current or previous uploaded file.
				switch ($_FILES['pdf']['error']) {
					case 1:
					case 2:
						$add_pdf_errors['pdf'] = 'The uploaded file was too large.';
						break;
					case 3:
						$add_pdf_errors['pdf'] = 'The file was only partially uploaded.';
						break;
					case 6:
					case 7:
					case 8:
						$add_pdf_errors['pdf'] = 'The file could not be uploaded due to a system error.';
						break;
					case 4:
					default:
						$add_pdf_errors['pdf'] = 'No file was uploaded.';
						break;
				} // End of SWITCH.

			} // End of $_FILES IF-ELSEIF-ELSE.

			if (empty($add_pdf_errors)) { // If everything's OK.

				// Add the PDF to the database:
				$fn = $pdo->quote($_SESSION['pdf']['file_name']);
				$tmp_name = $pdo->quote($_SESSION['pdf']['tmp_name']);
				$size = (int) $_SESSION['pdf']['size'];
				$q = "INSERT INTO pdfs (title, description, tmp_name, file_name, size) VALUES ($t, $d, $tmp_name, $fn, $size)";
				$r = $pdo->exec($q);
				if ($r === 1) { // If it ran OK.

					// Rename the temporary file:
					$tmp_name = trim($tmp_name, '\'');
					$original =  PDFS_DIR . $tmp_name . '_tmp';
					$dest =  PDFS_DIR . $tmp_name;
	/*				echo $original;
					echo '<br>';
					echo $dest;
					exit;*/
					rename($original, $dest);

					// Print a message:
					//echo '<div class="alert alert-success"><h3>The PDF has been added!</h3></div>';
					//
					// Clear $_POST:
					$_POST = array();

					// Clear $_FILES:
					$_FILES = array();

					// Clear $file and $_SESSION['pdf']:
					unset($file, $_SESSION['pdf']);
					return self::statuses('CMD_OK');

				} else { // If it did not run OK.
					trigger_error('The PDF could not be added due to a system error. We apologize for any inconvenience.');
					unlink ($dest);
				}

			} // End of $errors IF.

		} else { // Clear out the session on a GET request:
			unset($_SESSION['pdf']);
		} // End of the submission IF.

		$request->setErrors($add_pdf_errors);
		return self::statuses('CMD_DEFAULT');
	}
}