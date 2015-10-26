<?
namespace controller;

class Request {
	private $get = [];
	private $lastCommand;
	private $title = null;

	function __construct(){
		$this->init();
	}
	function init(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET'){
			$this->get = $_GET;
		}
	}

	function getGet($val){
		return $this->get[$val];
	}

	function setGet($key, $val){
		return $this->get[$key] = $val;
	}

	function setCommand( \command\Command $command ){
		$this->lastCommand = $command;
	}

	function getLastCommand(){
		return $this->lastCommand;
	}

	function getTitle(){
		return $this->title;
	}
}