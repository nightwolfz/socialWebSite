<?php
namespace models;

class Input
{
	// get $_GET variable
	public static function get($var = NULL)
	{
		if (!isset($_GET[$var])) return null;

		return addslashes(trim($_GET[$var]));
	}

	// get $_POST variable
	public static function post($var = NULL, $filter = NULL)
	{
        if ($var == null && $_SERVER['REQUEST_METHOD'] == 'POST') return true;

		if (!isset($_POST[$var])) return null;
        
        switch ($filter){
            case 'html':
                $_POST[$var] = filter_var($_POST[$var], FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_LOW); //URL-encode string, optionally strip or encode special characters.
                break;
            case 'int':
                $_POST[$var] = filter_var($_POST[$var], FILTER_SANITIZE_NUMBER_INT); //Remove all characters except digits, plus and minus sign.
                break;
            default:
                $_POST[$var] = filter_var($_POST[$var], FILTER_SANITIZE_MAGIC_QUOTES); //Apply addslashes()
                break;
        }

		return trim($_POST[$var]);
	}
}
