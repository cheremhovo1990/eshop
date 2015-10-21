<?
namespace command;

class DefaultCommand extends \command\Command{
	function doExecute(\controller\Request $request){
		echo $request->getGet('cmd');
	}
}