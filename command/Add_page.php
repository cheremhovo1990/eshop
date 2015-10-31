<?
namespace command;

class Add_page extends \command\Command{
	function doExecute(\controller\Request $request){
		$this->redirect_invalid_user('user_admin');
		$pdo = \PDO\ConnectPDO::instance();
		$request->setTitle('Add a Site Content Page');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);
		$add_page_errors = array();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			// Check for a title:
			if (!empty($_POST['title'])) {
				$t = $pdo->quote(strip_tags($_POST['title']));
			} else {
				$add_page_errors['title'] = 'Please enter the title!';
			}

			// Check for a category:
			if (filter_var($_POST['category'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
				$cat = $_POST['category'];
			} else { // No category selected.
				$add_page_errors['category'] = 'Please select a category!';
			}

			// Check for a description:
			if (!empty($_POST['description'])) {
				$d = $pdo->quote(strip_tags($_POST['description']));
			} else {
				$add_page_errors['description'] = 'Please enter the description!';
			}

			// Check for the content:
			if (!empty($_POST['content'])) {
				$allowed = '<div><p><span><br><a><img><h1><h2><h3><h4><ul><ol><li><blockquote>';
				$c = $pdo->quote(strip_tags($_POST['content'], $allowed));
			} else {
				$add_page_errors['content'] = 'Please enter the content!';
			}

			if (empty($add_page_errors)) { // If everything's OK.

				// Add the page to the database:
				$q = "INSERT INTO pages (categories_id, title, description, content) VALUES ($cat, $t, $d, $c)";
				$r = $pdo->exec($q);

				if ($r === 1) { // If it ran OK.

					// Print a message:
					echo '<div class="alert alert-success"><h3>The page has been added!</h3></div>';

					// Clear $_POST:
					$_POST = array();

					// Send an email to the administrator to let them know new content was added?

				} else { // If it did not run OK.
					trigger_error('The page could not be added due to a system error. We apologize for any inconvenience.');
				}

			} // End of $add_page_errors IF.

		} // End of the main form submission conditional.

		$q = "SELECT id, category FROM categories ORDER BY category ASC";
		$r = $pdo->query($q);
		$cats2 = $r->fetchAll(\PDO::FETCH_NUM);
		$request->setArray('cats2', $cats2);
		$request->setErrors($add_page_errors);
		return self::statuses('CMD_DEFAULT');
	}
}