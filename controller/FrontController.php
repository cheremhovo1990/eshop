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
		// экзмпляр класса twig
		$twig = ApplicationHelper::getTwig();
		$this->invoked($app_c->getView($request), $twig, $request->getDatatwig());
	}
	function invoked($target, $twig, $datatwig){
		echo $twig->render($target . '.twig', $datatwig);
		//include( "view/$target.php" );
	}
}