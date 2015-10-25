<?
namespace command;

class DefaultCommand extends \command\Command{
	function doExecute(\controller\Request $request){
		$pdo = \PDO\ConnectPDO::instance();
		$sql = "SELECT * FROM test";
		$result = $pdo->query($sql);
		var_dump($result->fetchall());
		return self::statuses('CMD_OK');
	}
}