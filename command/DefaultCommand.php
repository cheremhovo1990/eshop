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
		return self::statuses('CMD_DEFAULT');
	}
}