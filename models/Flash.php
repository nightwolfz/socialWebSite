<?php
namespace models;

class Flash
{
    /**
     * Creates the flash message.
     */
    static function setFlash($type, $message) {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = array();
            $_SESSION['flash']['uri'] = $_SERVER['REQUEST_URI'];
        }
        $_SESSION['flash'][$type] .= $message;
    }

    /**
    * Removes all due flash messages.
    */
    static function unsetFlash() {
        if (isset($_SESSION['flash'])) {
            if($_SERVER['REQUEST_URI'] != $_SESSION['flash']['uri']) {
                $_SESSION['flash'] = null;
            }
        }
    }

    /**
    * Gets a flash message, that has the given $type
    * $type, the type to get the flash message for.
    */
    static function getFlash($type) {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'][$type];
            self::unsetFlash();
            return $flash;
        }
        return null;
    }

    /**
     * Checks if a flash message is set.
     */
    static function hasFlash($type) {
        if (isset($_SESSION['flash'])) return true; else return null;
    }
}
