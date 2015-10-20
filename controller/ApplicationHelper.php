<?
namespace controller;

class ApplicationHelper {

	private $config = '../options.xml';

	private function __construct(){}
	private function __clone(){}

	static function instance(){
		static $instance;
		is_null($instance) AND $instance = new self;
		return $instance;
	}

	function init(){
		$this->getOptions();
	}

	function getOptions(){

		
		$this->ensure( file_exists($this->config), "Не могу найти файл опции" );
		$options = simplexml_load_file( $this->config );
		$this->ensure( $options instanceof \SimpleXMLElement, "Не могу преобразовать xml файл" );

		// настройки базы PDO($dsn, $user, $password)
		$dsn = (string)$options->dsn;
		$this->ensure( $dsn, 'Не найден dsn' );
		\base\ApplicationRegistry::setDSN($dsn);
		$user = (string)$options->user;
		$this->ensure( $user, 'Не задан пользователь' );
		\base\ApplicationRegistry::setUser($user);
		$password = (string)$options->password;
		\base\ApplicationRegistry::setPassword($password);
		// конец настройки базы

		$map = new \controller\ControllerMap();
		foreach ($options->control->view as $default_view) {
			$stat_str = trim($default_view);
			if (empty($str_str)){
				$stat_str = "CMD_DEFAULT";
			}
			$status = \command\Command::statuses($stat_str);
			$map->addView( 'default', $statuses, (string)$default_view );
		}
	}

	private function ensure($expr, $message){
		if(!$expr){
			throw new Exception($message);
		}
	}
}