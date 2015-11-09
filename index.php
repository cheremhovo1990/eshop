<?

define ('BASE_URI', __DIR__ . DIRECTORY_SEPARATOR);
define ('BASE_URL', $_SERVER['HTTP_HOST'] . '/');
define ('PDFS_DIR', BASE_URI . 'pdfs' . DIRECTORY_SEPARATOR);
session_start();

require_once 'psr4autoloader.php';

$loader = new \Psr4AutoloaderClass;
$loader->register();

$loader->addNamespace('eshop', __DIR__);

/*spl_autoload_register(function($class){
	$class = str_replace('\\', '/', $class);
	require_once __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
});*/

eshop\controller\FrontController::run();