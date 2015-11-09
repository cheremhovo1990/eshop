<?
namespace eshop\command;

abstract class Command{
	private static $STATUS_STRINGS = [
		'CMD_DEFAULT' => 0,
		'CMD_OK' => 1,
		'CMD_ERROR' => 2,
	];

	private $status = 0;

	final function __construct(){}

	function execute(\eshop\controller\Request $request){
		$this->status = $this->doExecute( $request );
		$request->setCommand( $this );
	}

	function getStatus(){
		return $this->status;
	}

	static function statuses( $str='CMD_DEFAULT' ){
		if (isset( self::$STATUS_STRINGS[$str] )){
			return self::$STATUS_STRINGS[$str];
		}
		throw new Exception("не знает статус");
	}

	abstract function doExecute( \eshop\controller\Request $request );

	function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://'){
		if (!isset($_SESSION[$check])) {
			$url = $protocol . BASE_URL . $destination; // Define the URL.
			header("Location: $url");
			exit(); // Quit the script.
		}
	}
}