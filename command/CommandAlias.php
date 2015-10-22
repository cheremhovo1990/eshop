<?
namespace command;

class CommandAlias extends \command\Command{
	function doExecute(\controller\Request $request){
		return self::statuses('CMD_ERROR');
	}
}