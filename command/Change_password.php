<?
namespace command;

class Change_password extends \command\Command{
	function doExecute(\controller\Request $request){
		$pdo = \PDO\ConnectPDO::instance();
		$request->setTitle('Change Your Password');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);
		$pass_errors = array();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			// Check for the existing password:
			if (!empty($_POST['current'])) {
				$current = $_POST['current'];
			} else {
				$pass_errors['current'] = 'Please enter your current password!';
			}

			// Check for a password and match against the confirmed password:
			if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']) ) {
				if ($_POST['pass1'] == $_POST['pass2']) {
					$p = $_POST['pass1'];
				} else {
					$pass_errors['pass2'] = 'Your password did not match the confirmed password!';
				}
			} else {
				$pass_errors['pass1'] = 'Please enter a valid password!';
			}

			if (empty($pass_errors)) { // If everything's OK.

				// Check the current password:
				$q = "SELECT pass FROM users WHERE id={$_SESSION['user_id']}";
				$r = $pdo->query($q);
				list($hash) = $r->fetch(\PDO::FETCH_NUM);


				// Validate the password:
				// Include the password_compat library, if necessary:
				// include('./includes/lib/password.php');
				if (password_verify($current, $hash)) { // Correct!

					// Define the query:
					$q = "UPDATE users SET pass='"  .  password_hash($p, PASSWORD_BCRYPT) .  "' WHERE id={$_SESSION['user_id']} LIMIT 1";
					if ($r = $pdo->exec($q)) { // If it ran OK.

						// Send an email, if desired.

						// Let the user know the password has been changed:
						return self::statuses('CMD_OK');

					} else { // If it did not run OK.

						trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.');

					}

				} else { // Invalid password.
					$pass_errors['current'] = 'Your current password is incorrect!';
				}

			} // End of empty($pass_errors) IF.

		} // End of the form submission conditional.


		$request->setErrors($pass_errors);
		return self::statuses('CMD_DEFAULT');
	}
}