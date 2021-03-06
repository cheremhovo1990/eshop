<?php
namespace eshop\PDO;

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
        $dsn = \eshop\base\ApplicationRegistry::getDSN();
        $user = \eshop\base\ApplicationRegistry::getUser();
        $password = \eshop\base\ApplicationRegistry::getPassword();
        $this->db = new \PDO($dsn, $user, $password,
            [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        );
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function instance( )
    {
        is_null(self::$istance) AND self::$istance = new self();
        return self::$istance;
    }

    // Заключает строку в кавычки для использования в запросе и Удаляет пробелы из начала и конца строки
    function quote($string){
        $string = $this->db->quote(trim($string));
        return $string;
    }

    function query($sql){
        return  $this->db->query($sql);
    }

    function exec($sql){
        return $this->db->exec($sql);
    }
    function lastInsertId(){
        return $this->db->lastInsertId();
    }

    function prepare($sql){
        return $this->db->prepare($sql);
    }
}