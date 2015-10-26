<?
namespace controller;

class ApplicationHelper {

	private $config = './options.xml';

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
		$this->configPDO($options);


		$map = new \controller\ControllerMap();
		foreach ($options->control->view as $default_view) {
			$stat_str = trim($default_view['status']);
			if (empty($stat_str)){
				$stat_str = "CMD_DEFAULT";
			}
			$status = \command\Command::statuses($stat_str);
			$map->addView( 'default', $status, (string)$default_view );
		}
		foreach ( $options->control->command as $command_view ) {
			$command = trim((string)$command_view['name']);
			if ($command_view->classalias){
				$classalias = trim((string)$command_view->classalias['name']);
				$map->addClassRoot($command, $classalias);
			}
			if ($command_view->view){
				$view = trim((string)$command_view->view);
				$forward = trim((string)$command_view->forward);
				$map->addView($command, 0, $view);
				if ($forward){
					$map->addForward($command, 0, $forward);
				}
				foreach($command_view->status as $command_view_status){
					$view = trim((string)$command_view_status->view);
					$forward = trim((string)$command_view_status->forward);
					$stat_str = trim($command_view_status['value']);
					$status = \command\Command::statuses($stat_str);
					if ($view){
						$map->addView($command, $status, $view);
					}
					if ($forward){
						$map->addForward($command, $status, $forward);
					}
				}
			}
		}
		\base\ApplicationRegistry::setControllerMap($map);
	}

	private function ensure($expr, $message){
		if(!$expr){
			throw new \Exception($message);
		}
	}

	/**
	 * @param $options
	 * @throws \Exception
	 */
	private function configPDO($options)
	{
		// настройки базы PDO($dsn, $user, $password)
		$dsn = (string)$options->dsn;
		$this->ensure($dsn, 'Не найден dsn');
		\base\ApplicationRegistry::setDSN($dsn);
		$user = (string)$options->user;
		$this->ensure($user, 'Не задан пользователь');
		\base\ApplicationRegistry::setUser($user);
		$password = (string)$options->password;
		\base\ApplicationRegistry::setPassword($password);
		// конец настройки базы
	}
}