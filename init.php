<?php
// Transform notices, warnings, errors to exceptions
/*
set_error_handler( function( $num, $msg, $file, $line ) {
  # take into account the '@' operators ( or remove this line and ignore them ):
  if ( error_reporting() === 0 ) return false;
  throw new \ErrorException( $msg, $num, 0, $file, $line );
});*/

// specify parameters for autoloading classes
spl_autoload_register(NULL, FALSE);
spl_autoload_extensions('.php');
spl_autoload_register(array('Autoloader', 'load'));

class ClassNotFoundException extends \Exception{}

class Autoloader
{
	// attempt to autoload a specified class
	public static function load($classname)
	{
		if (class_exists($classname, FALSE)) return;

		//?controller=index&action=show
		$filename = str_replace( '\\', '/', $classname ) . '.php';

        if (!file_exists($filename)){
            header('HTTP/1.0 404 Not Found');
            print '<link rel="stylesheet" media="all" href="/views/css/style.css"/><style>#main {display:block; opacity:1;}</style>';
            print 'File <b>' . $classname . '</b> not found : '. __FILE__ .' at '. __LINE__;
            return;
        }
        require_once $filename;
	}
}

// handle request and dispatch it to the appropriate controller
try {
	\models\Dispatcher::dispatch();
} catch (\ClassNotFoundException $e){
	echo $e->getMessage();
	exit();
} catch (Exception $e){
	echo $e->getMessage();
	exit();
}

?>
