<?
namespace controller;

class Request {
	private $get = [];

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
}