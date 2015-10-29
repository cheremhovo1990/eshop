<?
namespace command;

class Forgot_password extends \command\Command{
	function doExecute(\controller\Request $request){
		$pdo = \PDO\ConnectPDO::instance();
		$request->setTitle('Forgot Your Password?');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);
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



							return self::statuses('CMD_OK');

						} else { // If it did not run OK.

							trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.');

						}


/*				// Bonus material!
				// Referenced in Chapter 12:
				$token = openssl_random_pseudo_bytes(32);
				$token = bin2hex($token);

				// Store the token in the database:
				$q = "REPLACE INTO access_tokens (user_id, token, date_expires) VALUES (':uid', ':token', DATE_ADD(NOW(), INTERVAL 15 MINUTE))";
				$stmt = $pdo->prepare($q);
				$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
				$stmt->bindValue(':token', $token, PDO::PARAM_STR);
				//mysqli_stmt_bind_param($stmt, 'is', $uid, $token);
				$stmt->execute($stmt);

				if (mysqli_stmt_affected_rows($stmt) > 0) {
					$url = 'https://' . BASE_URL . 'reset.php?t=' . $token;
					$body = "This email is in response to a forgotten password reset request at 'Knowledge is Power'. If you did make this request, click the following link to be able to access your account:
$url
For security purposes, you have 15 minutes to do this. If you do not click this link within 15 minutes, you'll need to request a password reset again.
If you have _not_ forgotten your password, you can safely ignore this message and you will still be able to login with your existing password. ";
					mail($email, 'Password Reset at Knowledge is Power', $body, 'FROM: ' . CONTACT_EMAIL);

					echo '<h1>Reset Your Password</h1><p>You will receive an access code via email. Click the link in that email to gain access to the site. Once you have done that, you may then change your password.</p>';
					include('./includes/footer.html');
					exit(); // Stop the script.

				} else { // If it did not run OK.

					trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.');

				}*/

			} // End of empty($pass_errors) IF.

		}
		return self::statuses('CMD_DEFAULT');
	}
}