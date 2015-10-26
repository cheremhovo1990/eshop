<?
define ('BASE_URI', 'D:\openserver\domains\eshop.loc/');
define ('BASE_URL', 'eshop.loc/');
define ('PDFS_DIR', BASE_URI . 'pdfs/');
session_start();

spl_autoload_register(function($class){
	require_once __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
});

controller\FrontController::run();