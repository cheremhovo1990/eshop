<?
namespace controller;

class AppController{
	private static $base_cmd = null;
	private static $default_cmd = null;
	private $controllerMap;

	function __construct(\controller\ControllerMap $map){
		$this->controllerMap = $map;
		self::$base_cmd = new \ReflectionClass('\command\Command');
		self::$default_cmd = new \command\DefaultCommand();
	}
}