<?php
namespace models;

class Dispatcher
{
    // dispatch request to the appropriate controller/method
    public static function dispatch()
    {
        $controller = '\\controllers\\' . ( ( !empty($_GET['controller']) ) ?  $_GET['controller'] : 'index' );
        $method = !empty($_GET['action']) ? $_GET['action'] : 'index';
        $params = !empty($_GET['params']) ? explode( '/', $_GET['params']) : array();

		$cont = new $controller();

		call_user_func_array( array( $cont, $method ), $params );
    }
}

?>
