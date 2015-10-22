<?
namespace controller;

class ControllerMap {
	private $viewMap = [];
	private $classRootMap = [];
	private $forwardMap = [];

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

	function addClassRoot($command, $classAlias){
		$command = strtolower($command);
		$this->classRootMap[$command] = $classAlias;
	}

	function getClassRoot($command){
		$command = strtolower($command);
		if ($this->classRootMap[$command]){
			return $this->classRootMap[$command];
		}
		return $command;
	}

	function addForward($command, $status=0, $newCommand){
		$command = strtolower($command);
		$this->forwardMap[$command][$status] = $newCommand;
	}

	function getForward($command, $status){
		$command = strtolower($command);
		if (isset($this->forwardMap[$command][$status])){
			return $this->forwardMap[$command][$status];
		}
		return null;
	}
}