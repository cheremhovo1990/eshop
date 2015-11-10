<?
namespace eshop\command;

class Register extends \eshop\command\Command{
	function doExecute(\eshop\controller\Request $request){
		$pdo = \eshop\PDO\ConnectPDO::instance();
		//$request->setTitle('Register');
		$request->setDataTwig('title', 'Register');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		//$request->setArray('cats', $cats);
		$request->setDataTwig('cats', $cats);
		$request->setDataTwig('session', $_SESSION);
		$reg_errors = array();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Check for a first name:
			if (preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['first_name'])) {
				$fn = $pdo->quote($_POST['first_name']);
			} else {
				$reg_errors['first_name'] = 'Please enter your first name!';
			}

			// Check for a last name:
			if (preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name'])) {
				$ln = $pdo->quote($_POST['last_name']);
			} else {
				$reg_errors['last_name'] = 'Please enter your last name!';
			}

			// Check for a username:
			if (preg_match('/^[A-Z0-9]{2,45}$/i', $_POST['username'])) {
				$u = $pdo->quote($_POST['username']);
			} else {
				$reg_errors['username'] = 'Please enter a desired name using only letters and numbers!';
			}

			// Check for an email address:
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === $_POST['email']) {
				$e = $pdo->quote($_POST['email']);
			} else {
				$reg_errors['email'] = 'Please enter a valid email address!';
			}

			// Check for a password and match against the confirmed password:
			if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']) ) {
				if ($_POST['pass1'] === $_POST['pass2']) {
					$p = $_POST['pass1'];
				} else {
					$reg_errors['pass2'] = 'Your password did not match the confirmed password!';
				}
			} else {
				$reg_errors['pass1'] = 'Please enter a valid password!';
			}

			if (empty($reg_errors)) { // If everything's OK...

				// Make sure the email address and username are available:
				$q = "SELECT email, username FROM users WHERE email=$e OR username=$u";
				$r = $pdo->query($q)->fetchAll(\PDO::FETCH_NUM);
				// Get the number of rows returned:
				$rows = count($r);
				if ($rows === 0) { // No problems!

					// Add the user to the database...

					// Include the password_compat library, if necessary:
					// include('./includes/lib/password.php');

					// Temporary: set expiration to a month!
					// Change after adding PayPal!
					// $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires) VALUES ('$u', '$e', '"  .  password_hash($p, PASSWORD_BCRYPT) .  "', '$fn', '$ln', ADDDATE(NOW(), INTERVAL 1 MONTH) )";

					// New query, updated in Chapter 6 for PayPal integration:
					// Sets expiration to yesterday:
					$q = "INSERT INTO users (username, email, pass, first_name, last_name, date_created, date_expires) VALUES ($u, $e, '"  .  password_hash($p, PASSWORD_BCRYPT) .  "', $fn, $ln, NOW(), SUBDATE(NOW(), INTERVAL 1 DAY) )";
					//exit($q);
					$r = $pdo->exec($q);
					if ($r === 1) { // If it ran OK.

						// Get the user ID:
						// Store the new user ID in the session:
						// Added in Chapter 6:
						$uid = $pdo->lastInsertId();
//				$_SESSION['reg_user_id']  = $uid;

						//$request->setVariable('email', $e);

						$request->setDataTwig('email', trim($e, '\''));
						return self::statuses('CMD_OK');

					} else { // If it did not run OK.
						trigger_error('You could not be registered due to a system error. We apologize for any inconvenience. We will correct the error ASAP.');
					}

				} else { // The email address or username is not available.

					if ($rows === 2) { // Both are taken.

						$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at left to have your password sent to you.';
						$reg_errors['username'] = 'This username has already been registered. Please try another.';
					} else { // One or both may be taken.

						// Get row:
						$row = $r[0];

						if( ($row[0] === $_POST['email']) && ($row[1] === $_POST['username'])) { // Both match.
							$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at left to have your password sent to you.';
							$reg_errors['username'] = 'This username has already been registered with this email address. If you have forgotten your password, use the link at left to have your password sent to you.';
						} elseif ($row[0] === $_POST['email']) { // Email match.
							$reg_errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at left to have your password sent to you.';
						} elseif ($row[1] === $_POST['username']) { // Username match.
							$reg_errors['username'] = 'This username has already been registered. Please try another.';
						}

					} // End of $rows === 2 ELSE.

				} // End of $rows === 0 IF.

			} // End of empty($reg_errors) IF.
		}
		$request->setDataTwig('post', $request->getPost());
		$request->setDataTwig('reg_errors', $reg_errors);
		//$request->setErrors($reg_errors);
		return self::statuses('CMD_DEFAULT');
	}
}