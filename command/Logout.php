<?
namespace eshop\command;

class Logout extends \eshop\command\Command{
	function doExecute(\eshop\controller\Request $request){
		$pdo = \eshop\PDO\ConnectPDO::instance();
		$sql = 'SELECT * FROM categories ORDER BY category';
		$result = $pdo->query($sql);
		$cats = $result->fetchAll(\PDO::FETCH_ASSOC);
		//$request->setArray('cats', $cats);
		$request->setDataTwig('cats', $cats);
		$this->redirect_invalid_user();

		// Destroy the session:
		$_SESSION = array(); // Destroy the variables.
		session_destroy(); // Destroy the session itself.
		setcookie (session_name(), '', time()-300); // Destroy the cookie.

		//$request->setTitle('Logout');
		$request->setDataTwig('post', $request->getPost());
		$request->setDataTwig('title', 'Logout');
		$request->setDataTwig('session', $_SESSION);
		return self::statuses('CMD_DEFAULT');
	}
}