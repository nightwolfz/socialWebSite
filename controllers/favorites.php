<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

class favorites extends namespace\baseController {
    
    public function __construct(){
        session_start();
	}

	public function index($pageNum = 0)
    {
        $this->content = new View('friends');
        $this->header = 'Favorites';
        
        $results = array();
        $friends = array();
    	$db = \models\MySQL::getInstance();
        
        $friends = \models\Friends::getFriends(session::get('login_id'), 10);

        // Fetch friends profiles
        $this->content->myFriends = array();
        foreach ($friends as $friend){
            $this->content->myFriends[] = array_merge((array)$friend, \models\Profiles::getWantsTo($friend), \models\Profiles::getAvatar($friend->login_id, $friend->gender));
        }
        $this->display();
	}
    
    function getConfirmed(){
        $sql = "SELECT * FROM foo GROUP BY id2,id1,confirmed HAVING id2 = 'friend' AND confirmed = TRUE";
        
        //SELECT * FROM friends WHERE ( id1 = 'user1' OR id2 = 'user1' ) AND ( status1 = 1 AND status2 = 1 );
    }
}
?>
