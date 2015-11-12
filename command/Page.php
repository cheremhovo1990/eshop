<?
namespace eshop\command;

class Page extends \eshop\command\Command{
	function doExecute(\eshop\controller\Request $request){
		$pdo = \eshop\PDO\ConnectPDO::instance();
		//$request->setTitle('default');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		//$request->setArray('cats', $cats);
		$request->setDataTwig('cats', $cats);
		$request->setDataTwig('post', $request->getPost());

		if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {

			$page_id = $_GET['id'];

			// Get the page info:
			$q = 'SELECT title, description, content FROM pages WHERE id=' . $page_id;
			$r = $pdo->query($q);

			if (count($row = $r->fetchAll(\PDO::FETCH_ASSOC)) !== 1) { // Problem!
				$page_title = 'Error!';
				//include('./includes/header.html');
				//$request->setTitle($page_title);
				$request->setDataTwig('title', $page_title);
				$request->setDataTwig('session', $_SESSION);
				return self::statuses('CMD_ERROR');

			}

			// Fetch the page info:
			$row = $row[0];
			$page_title = $row['title'];
			//$request->setTitle($page_title);
			$request->setDataTwig('title', $page_title);
			if (isset($_SESSION['user_not_expired'])) {


				$string = "<div>{$row['content']}</div>";
			} elseif (isset($_SESSION['user_id'])) { // Logged in but not current.
				$string = '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content, but your account is no longer current. Please <a href="renew.php">renew your account</a> in order to view this page in its entirety.</div>';
				$string .= '<div>' . htmlspecialchars($row['description']) . '</div>';
				//$request->setVariable('string', $string);
			} else { // Not logged in.
				$string = '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to view this page in its entirety.</div>';
				$string .= '<div>' . htmlspecialchars($row['description']) . '</div>';
				//$request->setVariable('string', $string);

			}
			$request->setDataTwig('string', $string);
			//$request->setArray('page', $row);
			$request->setDataTwig('session', $_SESSION);
			return self::statuses('CMD_DEFAULT');
		} else { // No valid ID.
			$page_title = 'Error!';
			//include('includes/header.html');
			//$request->setTitle($page_title);
			$request->setDataTwig('title', $page_title);
			$request->setDataTwig('session', $_SESSION);
			return self::statuses('CMD_ERROR');
			//echo '<div class="alert alert-danger">This page has been accessed in error.</div>';
		} // End of primary IF.

		//$request->setErrors($login_errors);
		//return self::statuses('CMD_DEFAULT');
	}
}