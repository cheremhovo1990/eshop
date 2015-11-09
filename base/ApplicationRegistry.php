<?
namespace eshop\base;

class ApplicationRegistry extends \eshop\base\Registry{

	private $value = [];
	private $request = null;
	private $appController = null;

	private function __construct(){}
	private function __clone(){}

	static function instance(){
		static $instance;
		is_null($instance) AND $instance = new self;
		return $instance;
	}

	protected function set($key, $val){
		$this->value[$key] = $val;
	}

	protected function get($key){
		return $this->value[$key];
	}


// Сохранения localhost, имя базы, имя пользователя и пороль
	static function setDSN($dsn){
		self::instance()->set('dsn', $dsn);
	}

	static function getDSN(){
		return self::instance()->get('dsn');
	}

	static function setUser($user){
		self::instance()->set('user', $user);
	}

	static function getUser(){
		return self::instance()->get('user');
	}

	static function setPassword($password){
		self::instance()->set('password', $password);
	}

	static function getPassword(){
		return self::instance()->get('password');
	}
// конец сохранения localhost, имя базы, имя пользователя и пороль

	static function setControllerMap($map){
		self::instance()->set('cmap', $map);
	}

	static function getControllerMap(){
		return self::instance()->get('cmap');
	}

	static function getRequest(){
		$inst = self::instance();
		if (is_null($inst->request)){
			$inst->request = new \eshop\controller\Request;
		}
		return $inst->request;
	}

	static function appController(){
		$inst = self::instance();
		if (is_null($inst->appController)){
			$cmap = self::getControllerMap();
			$inst->appController = new \eshop\controller\AppController($cmap);
		}
		return $inst->appController;
	}
}