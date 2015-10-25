<?php
namespace PDO;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 25.10.2015
 * Time: 9:56
 */
class ConnectPDO {
    public static $istance = null;
    public $db;
    /**
     * ConnectPDO constructor.
     */
    public function __construct()
    {
        $dsn = \base\ApplicationRegistry::getDSN();
        $user = \base\ApplicationRegistry::getUser();
        $password = \base\ApplicationRegistry::getPassword();
        $this->db = new \PDO($dsn, $user, $password);
    }


    public static function instance( )
    {
        is_null(self::$istance) AND self::$istance = new self();
        return self::$istance->db;
    }
}