<?
namespace eshop\command;

class Category extends \eshop\command\Command{
	function doExecute(\eshop\controller\Request $request){
		$pdo = \eshop\PDO\ConnectPDO::instance();
		//$request->setTitle('default');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		//$request->setArray('cats', $cats);
		$request->setDataTwig('cats', $cats);
		$request->setDataTwig('post', $request->getPost());

		if (filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
			$cat_id = $_GET['id'];

			// Get the category title:
			$q = 'SELECT category FROM categories WHERE id=' . $cat_id;
			$r = $pdo->query($q);
			if (count($row = $r->fetchAll(\PDO::FETCH_NUM)) !== 1) { // Problem!
				$page_title = 'Error!';
				//include('./includes/header.html');
				//$request->setTitle($page_title);
				$request->setDataTwig('title', $page_title);
				$request->setDataTwig('session', $_SESSION);
				return self::statuses('CMD_ERROR');
			}

			// Fetch the category title and use it as the page title:
			list($page_title) = $row[0];
			//$request->setTitle($page_title);
			$request->setDataTwig('title', $page_title);


			//include('./includes/header.html');
			//echo '<h1>' . htmlspecialchars($page_title) . '</h1>';

			// Print a message if they're not an active user:
			// Change the message based upon the user's status:
/*			if (isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])) {
				echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content. Unfortunately your account has expired. Please <a href="renew.php">renew your account</a> in order to access site content.</div>';
			} elseif (!isset($_SESSION['user_id'])) {
				echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to view site content.</div>';
			}*/

			// Get the pages associated with this category:
			$q = 'SELECT id, title, description FROM pages WHERE categories_id=' . $cat_id . ' ORDER BY date_created DESC';
			$r = $pdo->query($q);

			if (count($row = $r->fetchAll(\PDO::FETCH_ASSOC)) > 0) { // Pages available!
				//$request->setArray('content_pages', $row);
				$request->setDataTwig('content_pages', $row);
				$request->setDataTwig('session', $_SESSION);
				return self::statuses('CMD_DEFAULT');
				// Fetch each record:
				/*while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

					// Display each record:
					echo '<div><h4><a href="page.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a></h4><p>' . htmlspecialchars($row['description']) . '</p></div>';

				} // End of WHILE loop.*/

			} else { // No pages available.
				$request->setDataTwig('session', $_SESSION);
				return self::statuses('CMD_OK');
				//echo '<p>There are currently no pages of content associated with this category. Please check back again!</p>';
			}

		} else { // No valid ID.
			$page_title = 'Error!';
			//include('./includes/header.html');
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