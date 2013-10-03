<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;


class messages extends namespace\baseController {

    public function __construct(){
        session_start();
        $this->login_id = session::get('login_id', null);
        $this->username = session::get('username', null);
		if (!$this->login_id) View::redirect("/index/login/required");
		$this->db = \models\MySQL::getInstance();
	}

	/**
	 * Inbox
	 */
	public function index($params = null)
    {
        $this->content = new View('messages');
        $this->header = 'Messages';
        $messages = array();
        $this->db->query("SELECT msg.id, msg.from_id, msg.to_id, msg_convo.text, msg_convo.timestamp FROM msg
		INNER JOIN msg_convo ON msg.id = msg_convo.message_id
		WHERE msg.from_id='".(int)$this->login_id."' OR msg.to_id='".(int)$this->login_id."' LIMIT 20");
		while ($message = $this->db->fetch()){
			$messages[$message->id] = $message;
		}
		// Attach faces to each message, hey we want to know who we are talking to, right !?
		foreach($messages as $message){
			$this->db->query("SELECT photos.thumb, profiles.username FROM photos
			INNER JOIN profiles ON profiles.login_id = photos.login_id
			WHERE photos.login_id='".(int)$message->from_id."' AND avatar='1' LIMIT 1");
			$thumb = $this->db->fetch();
			$messages[$message->id]->username = $thumb->username;
			$messages[$message->id]->thumb = $thumb->thumb;
		}
		$this->content->messages = $messages;
        $this->display();
	}

	public function write(){
        if (!$this->login_id) exit('Not logged in.');

		if (Input::post('to_id') && Input::post('message_id') && Input::post('message-box') && $this->login_id){
            if (!$this->login_id) exit('Hack attempt. Logged.');
            $this->db->query("INSERT INTO msg_convo (message_id, from_id, text,`timestamp`)VALUES ('".(int)Input::post('message_id')."', '".$this->login_id."', '".Input::post('message-box')."', ".\time().")");
            View::redirect('/messages/read/'.Input::post('message_id'));
        }else{
			exit('Empty content.');
		}
		exit;
	}


	/**
	 * Read a message
	 */
	public function read($convoId = null)
    {
		$messages = array();
        $this->content = new View('messages_read');

		// Check if the conversation is really yours
		$this->db->query("SELECT id,from_id,to_id FROM msg WHERE (from_id='".(int)$this->login_id."' OR to_id='".(int)$this->login_id."') AND id='".(int)$convoId."' LIMIT 30");
		if (!$isMine = $this->db->fetch())
			exit('This conversation is not yours');
		else
			$this->content->message_id = $isMine->id;

		// From who is the message ?
		$this->content->from_id = ($isMine->from_id == $this->login_id) ? $isMine->to_id : $isMine->from_id;
		$this->content->from_username = \models\Profiles::getUsername(($isMine->from_id == $this->login_id) ? $isMine->to_id : $isMine->from_id);

		// Get the conversation
        $this->db->query("SELECT * FROM msg_convo WHERE message_id='".(int)$convoId."' ORDER BY timestamp ASC LIMIT 30");
		while ($message = $this->db->fetch()){
			$messages[$message->id] = $message;
		}
		// Attach faces to each message, hey we want to know who we are talking to, right !?
		foreach($messages as $message){
			$this->db->query("SELECT photos.thumb, profiles.username FROM photos
			INNER JOIN profiles ON profiles.login_id = photos.login_id
			WHERE photos.login_id='".(int)$message->from_id."' AND avatar='1' LIMIT 1");
			$thumb = $this->db->fetch();
			$messages[$message->id]->username = $thumb->username;
			$messages[$message->id]->thumb = $thumb->thumb;
		}

		$this->content->messages = $messages;
        $this->display();
	}

}
?>