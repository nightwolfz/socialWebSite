<?php
namespace models;

class Upload {
    // List of allowed file extensions separated by "|"
    public $allowed_files = 'gif|jpg|jpeg|png';

    /**
     * Try to Upload the given file returning the filename on success
     *
     * @param array $f $_FILES array element
     * @param string $d destination directory
     * @param boolean $o overwrite existing files of the same name?
     * @param integer $s maximum size allowed (can also be set in php.ini or server config)
     * @param boolean $rename rename uploaded file? boolean or string
     */
    static function file($f, $d, $o = false, $s = false, $rename = false)
    {
        if (self::error($f) OR !extract(self::parse_filename($f['name'])) OR !$name || ($s && $f['size'] > $s)) return 0;

        // Make sure the folder is writable
        if ((!is_dir($d) && !mkdir($d, '0744', true)) OR (!is_writable($d) && !chmod($d, '0744'))) return false;

        $n = $o ? "$name.$ext" : self::unique_filename($d, $name, $ext);
        if (self::move($f, $d.$n)) {
            if ($rename && rename($d.$n, $d.$rename)) {
                return $d.$rename;
            } else {
                return $n;
            }
        }
    }

    static function error($f)
    {
        if (!isset($f['tmp_name'], $f['name'], $f['error'], $f['size']) OR $f['error'] != UPLOAD_ERR_OK) return true;
    }

    static function parse_filename($f)
    {
        $p = pathinfo($f);
        return((isset($p['filename'], $p['extension']) && $n = \models\String::sanitize($p['filename'])) ? array('name' => $n, 'ext' => strtolower($p['extension'])) : array('name' => '', 'ext' => ''));
    }

    static function allowed_file($ext)
    {
        return in_array($ext, explode('|', self::$allowed_files));
    }

    static function unique_filename($d, $f, $e)
    {
        $x = null;
        while (file_exists("$d$f$x.$e")) {
            $x++;
        }
        return"$f$x.$e";
    }

    static function move($f, $d)
    {
        return move_uploaded_file($f['tmp_name'], $d);
    }
}
?>