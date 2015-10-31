<?
namespace command;

class DefaultCommand extends \command\Command{
	function doExecute(\controller\Request $request){
		$pdo = \PDO\ConnectPDO::instance();
		//$request->setTitle('default');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//include('./includes/login.inc.php');
			$login_errors = array();
			// Validate the email address:
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$e = $pdo->quote($_POST['email']);
			} else {
				$login_errors['email'] = 'Please enter a valid email address!';
			}

			// Validate the password:
			if (!empty($_POST['pass'])) {
				$p = $_POST['pass'];
			} else {
				$login_errors['pass'] = 'Please enter your password!';
			}
			if (empty($login_errors)) { // OK to proceed!

				// Query the database:
				$q = "SELECT id, username, type, pass, IF(date_expires >= NOW(), true, false) AS expired FROM users WHERE email=$e";
				$r = $pdo->query($q);

				if (count($row = $r->fetchAll(\PDO::FETCH_ASSOC)) === 1) { // A match was made.

					// Get the data:
					$row = $row[0];
					// Validate the password:
					// Include the password_compat library, if necessary:
					// include('./includes/lib/password.php');
					if (password_verify($p, $row['pass'])) { // Correct!

						// If the user is an administrator, create a new session ID to be safe:
						// This code is created at the end of Chapter 4:
						if ($row['type'] === 'admin') {
							session_regenerate_id(true);
							$_SESSION['user_admin'] = true;
						}

						// Store the data in a session:
						$_SESSION['user_id'] = $row['id'];
						$_SESSION['username'] = $row['username'];

						// Only indicate if the user's account is not expired:
						if ($row['expired'] == 1) $_SESSION['user_not_expired'] = true;

					} else { // Right email address, invalid password.
						$login_errors['login'] = 'The email address and password do not match those on file.';
					}

				} else { // No match was made. (technically, only the email address failed)

					$login_errors['login'] = 'The email address and password do not match those on file.';
				}

			} // End of $login_errors IF.
		}

		$request->setErrors($login_errors);
		return self::statuses('CMD_DEFAULT');
	}
}