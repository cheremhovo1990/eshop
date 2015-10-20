<?
namespace controller;

class FrontController {

	private function __construct(){}
	private function __clone(){}
	static function run(){
		$instance = new FrontController;
		$instance->init();
		$instance->handleRequest();
	}
	function init(){
		$applicationHelper = \controller\ApplicationHelper::instance();
		$applicationHelper->init();
	}
	function handleRequest(){

	}
}