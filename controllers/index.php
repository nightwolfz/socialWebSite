<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

class index extends namespace\baseController {
    public $title = 'Rule20 - Social Networking & Dating website';
    public $metaDescription = 'Free dating for singles and social networking for everyone. Find singles in your area.';

    public function __construct(){
		$this->db = \models\MySQL::getInstance();
        session_start();
	}

	/**
     * @http://localhost/index/index/hello/eee
	 * @http://localhost/index.php?controller=index&action=index&params[hello]=eee
     */
	public function index($pageNum = 1)
    {
        $this->content = new View('index');
		$results = \models\Profiles::selectId('',16, $pageNum);

        // Change page title if we are browsing user profiles
        if ($pageNum>1) $this->title = 'Browsing user profiles, page '.\models\String::convertNumber($pageNum);

        foreach ($results as $result){
            $this->content->newUsers[] = array_merge((array)$result, \models\Profiles::getWantsTo($result), \models\Profiles::getAvatar($result->login_id, $result->gender));
        }
        
        //$this->userNews = $this->content->newUsers;

        if (\models\session::get('username')){
        	$this->content->events = \models\Events::get("visited", \models\session::get('login_id'));
        }
        $this->content->pagination = \models\Guestbook::createPagination(null, $pageNum, 16, '/index/page', 'accounts', 'login_id', 'login_id');
        $this->display();
	}

    public function page($pageNum = 1)
    {
        $this->index($pageNum);
    }

    /**
     * Login screen
     */
    public function login($params = null)
    {
        // Load form view
		$this->content = new View('login');
		$this->content->redirect = getenv('HTTP_REFERER');
        // What to render from the view
		if ($params) $this->content->required = true;
        $this->content->action = "default";

        if (Input::post('action-login') && Input::post('username') && Input::post('password')){
            $this->db->query("SELECT login_id, username FROM accounts WHERE username = '".Input::post('username')."' LIMIT 1");
            if ($checklogin = $this->db->fetch()){
                session_regenerate_id(true);
                session::set('login_id', $checklogin->login_id);
                session::set('username', $checklogin->username);
                # Update last login time
                $this->db->query("UPDATE profiles SET last_online='".time()."' WHERE username = '".Input::post('username')."' LIMIT 1");
                $this->content->action = "action-login";
            }
        }
        $this->display();
	}

    /**
     * Logout screen
     */
    public function logout()
    {
        session_regenerate_id();
        session_destroy();
        // Load form view
		$this->content = new View('login');
        $this->content->action = "action-logout";
        # Update last login time
        if (session::get('username')){
            $this->db->query("UPDATE profiles SET last_online='".time()."' WHERE username = '".session::get('username')."' LIMIT 1");
        }
        $this->display();
	}

}
?>
