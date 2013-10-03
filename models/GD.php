<?php
namespace models;

class GD {
    /**
     * Create a JPEG thumbnail for the given png/gif/jpeg image and return the path to the new image.
     *
     * @param string $file the file path to the image
     * @param int $w the width
     * @param int $h the height
     * @param int $q the image quality
     * @return string
     */
    public static function thumbnail($file, $w = 120, $h = 120, $q = 80, $rename = false)
    {
        $d = SP . "uploads/t/" . date('m') . "/" . date('d') . "/";
        $n = basename($file);
        if (is_file($d . $n) OR !is_file($file) OR !($i = self::open($file))) return;
        
        // Make sure the folder is writable
        if ((!is_dir($d) && !mkdir($d, '0776', true)) OR (!is_writable($d) && !chmod($d, '0776'))) return false;

        if (imagejpeg(self::resize($i, $w, $h), $d . $n, $q)) {
            if ($rename && rename($d . $n, $d . $rename)) {
                return $d . $rename;
            } else {
                return $d . $n;
            }
        }
    }

    /**
     * Open a resource handle to a (png/gif/jpeg) image file for processing.
     *
     * @param string $file the file path to the image
     * @return resource
     */
    public static function open($file)
    {
        if (is_file($file) 
		&& ($e = pathinfo($file, PATHINFO_EXTENSION)) 
		&& ($x = 'imagecreatefrom' . ($e == 'jpg'?'jpeg':$e)) 
		&& ($i = $x($file)) && is_resource($i))
		return $i;
    }

    /**
     * Resize and crop the image to fix proportinally in the given dimensions.
     *
     * @param resource $handle the image resource handle
     * @param int $w the width
     * @param int $h the height
     * @return resource
     */
    public static function resize($handle, $w, $h)
    {
        $x = imagesx($handle);
        $y = imagesy($handle);
        $s = min($x / $w, $y / $h);
        $n = imagecreatetruecolor($w, $h);
        self::alpha($n);
        imagecopyresampled($n, $handle, 0, 0, 0, ($y / 4 - ($h / 4)), $w, $h, $x - ($x - ($s * $w)), $y - ($y - ($s * $h)));
        return$n;
    }

    /**
     * Preserve the alpha channel transparency in PNG images
     *
     * @param resource $handle the image resource handle
     */
    public static function alpha($handle)
    {
        imagecolortransparent($handle, imagecolorallocate($handle, 0, 0, 0));
        imagealphablending($handle, false);
        imagesavealpha($handle, true);
    }

    /**
     * Send the correct HTTP header to display the image
     *
     * @param string $extension type of png, gif, or jpeg
     */
    public static function header($extension)
    {
        headers_sent() || header('Content-type: image/' . $extension);
    }
}
// END