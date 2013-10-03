<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

class register extends namespace\baseController {
    public $title = 'User feeds';
    public $metaDescription = '';

    public function __construct(){
        $this->db = \models\MySQL::getInstance();
        session_start();
    }

    public function index($params = null)
    {
        $this->title   = "Rule20 - Create a new account";
        $this->content = new View('register');
        
        $this->display();
    }
    
    public function done($params = null)
    {
        $error = array();
        $db = \models\MySQL::getInstance();
        $error[] = 'Registration is disabled, the website is still in beta, sorry :)';
        
        if (strlen(Input::post('username')) < 5) $error[] = 'Your username is too short. Minimum 5 letters.';
        if (strlen(Input::post('password')) < 5) $error[] = 'Your password is too short. Minimum 5 letters.';
        if (strlen(Input::post('email')) < 5) $error[] = 'Your email is too short. Minimum 5 letters.';
        if (strlen(Input::post('gender')) < 1) $error[] = 'Please select your gender.';
        
        if (count($error)<1):
            $db->query("INSERT INTO `accounts`
            (`login_id`,`username`,`passwd`,`signed_up`,`birthday`,`email`,`last_ip`,`login_count`,`login_status`,`invite_status`) VALUES 
            (NULL ,  '".Input::post('username')."',  '".Input::post('password')."',  '".time()."',  '',  '".Input::post('email')."',  '',  '',  '0',  '0');");
        
            $login_id = $db->getInsertID();

            $db->query("INSERT INTO `profiles`
            (`login_id`,`username`,`birthday`,`mood`,`city`,`gender`,`status`,`ori`,`wants_to`,`with_who`,`range_from`,`range_to`,`ethnics`,`country`)VALUES 
            ('$login_id', '".Input::post('username')."', NULL, '', '',  '".(Input::post('gender')=='f'?'f':'m')."', NULL, '', 'meet', '0', NULL, NULL,  'Caucasian',  'Belgium');");
        endif;

        
        // If no errors, send OK
        echo (count($error)>0) ? json_encode($error) : json_encode('OK');
    }
    
    
    
}
?>