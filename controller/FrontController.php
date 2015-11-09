<?
namespace eshop\controller;

class FrontController {

	private function __construct(){}
	private function __clone(){}
	static function run(){
		$instance = new FrontController;
		$instance->init();
		$instance->handleRequest();
	}
	function init(){
		$applicationHelper = ApplicationHelper::instance();
		$applicationHelper->init();
	}
	function handleRequest(){
		$request = \eshop\base\ApplicationRegistry::getRequest();
		$app_c = \eshop\base\ApplicationRegistry::appController();
		while( $cmd = $app_c->getCommand( $request ) ){
			$cmd->execute( $request );
		}
		$this->invoked($app_c->getView($request));
	}
	function invoked($target){
		include( "view/$target.php" );
	}
}