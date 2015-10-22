<?
namespace controller;

class AppController{
	private static $base_cmd = null;
	private static $default_cmd = null;
	private $controllerMap;
	private $invoked = [];

	function __construct(\controller\ControllerMap $map){
		$this->controllerMap = $map;
		self::$base_cmd = new \ReflectionClass('\command\Command');
		self::$default_cmd = new \command\DefaultCommand();
	}

	function getView(\controller\Request $req){
		$view = $this->getResource( $req, 'View' );
		return $view;
	}

	private function getForward( \controller\Request $req ){
		$forward = $this->getResource( $req, 'Forward' );
		if( $forward ){
			$req->setGet('cmd', $forward);
		}
		return $forward;
	}

	private function getResource(\controller\Request $req, $res){
		$cmd_str = $req->getGet('cmd');
		$previous = $req->getLastCommand();
		$status = $previous->getStatus();
		if (!isset( $status ) || !is_int($status)) { $status = 0 ;}
		$acquire = "get$res";
		$resource = $this->controllerMap->$acquire($cmd_str, $status);
		if (is_null($resource)){
			$resource = $this->controllerMap->$acquire($cmd_str, 0);
		}
		if (is_null($resource)){
			$resource = $this->controllerMap->$acquire('default', $status);
		}
		if (is_null($resource)){
			$resource = $this->controllerMap->$acquire('default', 0);
		}
		return $resource;
	}

	function getCommand( \controller\Request $req ){
		$previous = $req->getLastCommand();
		if( is_null($previous) ){
			$cmd = $req->getGet('cmd');
			if ( is_null($cmd) ){
				$req->setGet('cmd', 'default');
				return self::$default_cmd;
			}
		} else {
			$cmd = $this->getForward( $req );
			if (is_null($cmd)) { return null; }
		}
		$cmd_obj = $this->resolveCommand($cmd);
		if (is_null($cmd_obj)){
			throw new \Exception("нету команды '$cmd'");
		}
		$cmd_class = get_class($cmd_obj);
		if ( isset($this->invoked[$cmd_class]) ){
			throw new \Exception('повторное выполнения команды');
		}
		$this->invoked[$cmd_class] = 1;
		return $cmd_obj;
	}

	function resolveCommand($cmd){
		$classroot = $this->controllerMap->getClassRoot($cmd);
		$filepath = "command/$classroot.php";
		$classname = "\\command\\$classroot";
		if ( file_exists($filepath) ){
			require_once($filepath);
			if ( class_exists($classname) ){
				$cmd_class = new \ReflectionClass($classname);
				if ($cmd_class->isSubClassOf(self::$base_cmd)){
					return $cmd_class->newInstance();
				}
			}
		}
		return null;
	}
}