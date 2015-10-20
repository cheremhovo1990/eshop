<?
namespace controller;

class ControllerMap {
	private $viewMap = [];

	function addView( $command = 'default', $status = 0, $view){
		$command = strtolower($command);
		$this->viewMap[$command][$status] = $view;
	}

	function getView($command, $status){
		$command = strtolower($command);
		if (isset($this->viewMap[$command][$status])){
			return $this->viewMap[$command][$status];
		}
		return null;
	}
}