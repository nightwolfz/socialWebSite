<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;


class settings extends namespace\baseController {

	public function __construct(){
		$this->db = \models\MySQL::getInstance();
        session_start();
        $this->login_id = session::get('login_id', null);
        $this->username = session::get('username', null);
	}

	public function index()
    {

        $result = \models\Profiles::findByUsername($this->username)[0];

        $this->content              = new View('settings');
        $this->header               = 'Settings';
        $this->content->him         = $result->gender=='m' ? 'him' : 'her';
        $this->content->result      = $result;
        $this->content->pictures    = \models\Photos::getPictures($result->login_id, 30);

        // Do we have to flash a message?
        if (\models\Flash::hasFlash('success')) $this->content->flash = \models\Flash::getFlash('success');

		$this->display();
	}
    
	public function saveSearchInfo(){
        if (!$this->login_id) View::redirect('/index/login/required');

        if (!$this->login_id) exit('Hack attempt. Logged.');

        $range = explode('-', Input::post('age_range'));

        $this->db->query("UPDATE profiles SET 
                wants_to='".Input::post('wants_to')."', 
                with_who='".Input::post('with_who')."', 
                range_from='".(int)$range[0]."', 
                range_to='".(int)$range[1]."' WHERE login_id='{$this->login_id}' LIMIT 1");
        View::redirect('/settings');
	}
    
    public function visitors(){

        $this->content           = new View('visitors');
        $this->header            = 'Visitors';
        $this->content->visitors = array();
        
        $visits = $this->db->queryAll("SELECT * FROM events WHERE login_id={$this->login_id} AND event_name='visited' LIMIT 30");
        
        foreach($visits as $visit){
            $user = \models\Profiles::selectId($visit->from_id, 1);
            $image = \models\Profiles::getAvatar($visit->from_id, $user[0]->gender);
            # Such an ugly hack
            $this->content->visitors[] = (object)array_merge((array)$user[0], $image);
        }
        
		$this->display();
    }
}
?>
