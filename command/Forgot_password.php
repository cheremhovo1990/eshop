<?
namespace eshop\command;

class Forgot_password extends \eshop\command\Command{
	function doExecute(\eshop\controller\Request $request){
		$pdo = \eshop\PDO\ConnectPDO::instance();
		//$request->setTitle('Forgot Your Password?');
		$request->setDataTwig('title', 'Forgot Your Password?');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		//$request->setArray('cats', $cats);
		$request->setDataTwig('cats', $cats);
		$request->setDataTwig('post', $request->getPost());
		$pass_errors = array();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			// Validate the email address:
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

				$email = $pdo->quote($_POST['email']);

				// Check for the existence of that email address...
				$q = "SELECT id FROM users WHERE email=  $email";
				$r = $pdo->query($q);

				if (count($row = $r->fetchAll(\PDO::FETCH_NUM)) === 1) { // Retrieve the user ID:
					list($uid) = $row[0];
				} else { // No database match made.
					$pass_errors['email'] = 'The submitted email address does not match those on file!';
				}

			} else { // No valid address submitted.
				$pass_errors['email'] = 'Please enter a valid email address!';
			} // End of $_POST['email'] IF.

			if (empty($pass_errors)) { // If everything's OK.

				// Original code below...

				// Create a new, random password:
				$p = substr(md5(uniqid(rand(), true)), 10, 15);

				// Include the password_compat library, if necessary:
				// include('./includes/lib/password.php');

				// Update the database:
				$q = "UPDATE users SET pass='" .  password_hash($p, PASSWORD_BCRYPT) . "' WHERE id=$uid LIMIT 1";
				$r = $pdo->exec($q);


				if ($r === 1) { // If it ran OK.

					// Send an email:
					$body = "Your password to log into <whatever site> has been temporarily changed to '$p'. Please log in using that password and this email address. Then you may change your password to something more familiar.";
					mail($_POST['email'], 'Your temporary password.', $body, 'From: admin@example.com');


					$request->setDataTwig('session', $_SESSION);
					return self::statuses('CMD_OK');

				} else { // If it did not run OK.

					trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.');

				}

			} // End of empty($pass_errors) IF.

		}

		$request->setDataTwig('pass_errors', $pass_errors);
		$request->setDataTwig('session', $_SESSION);
		//$request->setErrors($pass_errors);
		return self::statuses('CMD_DEFAULT');
	}
}