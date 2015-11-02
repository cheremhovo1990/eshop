<?
namespace command;

class Pdfs extends \command\Command{
	function doExecute(\controller\Request $request){
		$pdo = \PDO\ConnectPDO::instance();
		$request->setTitle('PDFs');
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		$request->setArray('cats', $cats);

		if (isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])) {
			$string = '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content, but your account is no longer current. Please <a href="renew.php">renew your account</a> in order to view any of the PDFs listed below.</div>';
			//echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content, but your account is no longer current. Please <a href="renew.php">renew your account</a> in order to view any of the PDFs listed below.</div>';
		} elseif (!isset($_SESSION['user_id'])) {
			$string = '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to view any of the PDFs listed below.</div>';
			//echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user to view any of the PDFs listed below.</div>';
		}
		$request->setVariable('string', $string);
		// Get the PDFs:
		$q = 'SELECT tmp_name, title, description, size FROM pdfs ORDER BY date_created DESC';
		$r = $pdo->query($q);
		if (count($rows = $r->fetchAll(\PDO::FETCH_ASSOC) ) > 0) { // If there are some...

			// Fetch every one:
			$request->setArray('pdfs', $rows);
			return self::statuses('CMD_OK');


			/*while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

				// Display each record:
				echo '<div><h4><a href="view_pdf.php?id=' . htmlspecialchars($row['tmp_name']) . '">' . htmlspecialchars($row['title']) . ' </a> (' . $row['size'] . 'kb)</h4><p>' . htmlspecialchars($row['description']) . '</p></div>';

			} // End of WHILE loop.*/

		} else { // No PDFs!
			return self::statuses('CMD_DEFAULT');
			//echo '<div class="alert alert-danger">There are currently no PDFs available to view. Please check back again!</div>';
		}


		//$request->setErrors($login_errors);
		//return self::statuses('CMD_DEFAULT');
	}
}