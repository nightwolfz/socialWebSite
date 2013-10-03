<?php
date_default_timezone_set('America/Los_Angeles');
ini_set("display_errors","1");
error_reporting(E_ALL);

/**
* Define the language using language code based on BCP 47 + RFC 4644,
* The language files can be found in directory 'lang'
*/
defined('LANGUAGE') or define('LANGUAGE', 'en');
defined('LOCALE_PATH') or define('LOCALE_PATH', realpath(dirname(__FILE__)).'/locale');

// System Start Time + System Start Memory
define('START_TIME', microtime(true));
define('START_MEMORY_USAGE', memory_get_usage());

// Absolute path to the system folder
define('SP', realpath(dirname(__FILE__)). '/');

// Database variables
const MYSQL_ROOT = "127.0.0.1";
const MYSQL_DB = "ccrush";
const MYSQL_USER = "root";
define('LOCAL', (strpos($_SERVER['SERVER_NAME'], 'rule20.com') === false) ? true : false);
define('METRO', (strpos($_SERVER['HTTP_USER_AGENT'], 'MSAppHost') !== false) ? true : false);
define('ROOT', METRO ? 'http://localhost/' : '');

define('MYSQL_PASSWD', LOCAL ? "" : "axiaaxia");

class MyException extends Exception {
	/**
	 * I don't know about others, but I like my exceptions well formatted.
	 */
	public function __construct($message, $code = 0, Exception $previous = null) {
		$message = '<head><link rel="stylesheet" media="all" href="/views/css/style.css"/></head><body>
		<div class="container"><article class="content">'.$message.'</article></div></body>';
        parent::__construct($message, $code, $previous);
    }
}

require_once('init.php');


/**
* Load the proper language file and return the translated phrase
*
* The language file is JSON encoded and returns an associative array
* Language filename is determined by BCP 47 + RFC 4646
* http://www.rfc-editor.org/rfc/bcp/bcp47.txt
*
* @param string $phrase The phrase that needs to be translated
* @return string
*/
function t($phrase) {
    /* Static keyword is used to ensure the file is loaded only once */
    static $translations = NULL;
    /* If no instance of $translations has occured load the language file */
    if (is_null($translations)) {
        $lang_file = LOCALE_PATH . '/' . LANGUAGE . '.json';
        if (!file_exists($lang_file)) {
            $lang_file = LOCALE_PATH . '/' . 'en.json';
            return "[Couldn't find translation file]";
        }else{
            if (!$lang_file_content = file_get_contents($lang_file) OR
                !$translations = json_decode($lang_file_content, true)) {
                return $phrase;
            }
        }
    }
    return $translations[$phrase];
}

?>
