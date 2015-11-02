<?
define ('BASE_URI', __DIR__ . DIRECTORY_SEPARATOR);
define ('BASE_URL', $_SERVER['HTTP_HOST'] . '/');
define ('PDFS_DIR', BASE_URI . 'pdfs' . DIRECTORY_SEPARATOR);
session_start();

spl_autoload_register(function($class){
	require_once __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
});

controller\FrontController::run();