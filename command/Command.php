<?
namespace command;

abstract class Command{
	private static $STATUS_STRINGS = [
		'CMD_DEFAULT' => 0,
		'CMD_OK' => 1,
		'CMD_ERROR' => 2,
	];

	private $status = 0;

	final function __construct(){}

	static function statuses( $str='CMD_DEFAULT' ){
		if (isset( self::$STATUS_STRINGS[$str] )){
			return self::$STATUS_STRINGS[$str];
		}
		throw new Exception("не знает статус");
	}
}