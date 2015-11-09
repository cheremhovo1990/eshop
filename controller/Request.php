<?
namespace eshop\controller;

class Request {
	private $get = [];
	private $lastCommand;
	private $title = null;
	private $array = [];
	private $errors = [];
	private $variable = [];
	function __construct(){
		$this->init();
	}
	function init(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->get = $_GET;
		}
	}

	function getGet($val){
		return $this->get[$val];
	}

	function setGet($key, $val){
		return $this->get[$key] = $val;
	}

	function setCommand( \eshop\command\Command $command ){
		$this->lastCommand = $command;
	}

	function getLastCommand(){
		return $this->lastCommand;
	}

	function getTitle(){
		return $this->title;
	}

	function setTitle($title){
		$this->title = $title;
	}

	function setArray($id,array $array){
		$this->array[$id] = $array;
	}

	function getArray($id){
		return $this->array[$id];
	}

	function setErrors($error){
		$this->errors = $error;
	}

	function getErrors(){
		return $this->errors;
	}

	function setVariable($name, $val){
		$this->variable[$name] = $val;
	}

	function getVariable($name){
		return $this->variable[$name];
	}
}