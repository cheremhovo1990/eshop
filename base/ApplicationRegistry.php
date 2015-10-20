<?
namespace base;

class ApplicationRegistry extends \base\Registry{

	private $value = [];

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
}