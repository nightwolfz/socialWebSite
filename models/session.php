<?php
namespace models;

class session {
	/**
     * Get session value
     *
     * @param string $k session key
	 * @param string $d default value if session key is not found
     * @return key value
     */
    public static function get($k, $d = null)
    {
        return isset($_SESSION[$k]) ? $_SESSION[$k] : $d;
    }
	/**
     * Set session value
     *
     * @param string $k session key
	 * @param string $v value to set
     * @return key value
     */
    public static function set($k, $v = null)
    {
        $_SESSION[$k] = isset($v) ? $v : null;
    }
    
}
?>
