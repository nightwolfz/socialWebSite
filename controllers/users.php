<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

/**
 * This controller has special routing.
 * @user/nightwolfz
 */

class users extends namespace\baseController {

	public function __construct(){
		$this->db = \models\MySQL::getInstance();
        session_start();
        $this->login_id = session::get('login_id', null);
        $this->username = session::get('username', null);
	}

	public function index()
    {

		$guestbookPage = explode('/',$_GET['params']);//[1] wait for php5.4
        $guestbookPage = isset($guestbookPage[1])?$guestbookPage[1]:1;

        $username = \models\String::substrtruncate( Input::get('params') , '/');
        $result0 = \models\Profiles::findByUsername($username);
        $result = $result0[0];

        if (empty($result)){
            header('HTTP/1.0 404 Not Found');
            $this->content = new View('user_404');
            $this->header  = '404 : Profile not found';
            $maybeUsers = \models\Profiles::selectId(null, 8);

            foreach ($maybeUsers as $maybeUser){
                $this->content->maybeUsers[] = (object)array_merge((array)$maybeUser, \models\Profiles::getWantsTo($maybeUser), \models\Profiles::getAvatar($maybeUser->login_id));
            }
            $this->display();
            return;
        }
        # Some visual candy, should probably be macro in the view or something
        $this->content              = new View('user');
        $this->title                = "Rule20 - $username's Profile";
        $this->header               = $username;
        $this->content->him         = $result->gender=='m' ? 'him' : 'her';
        $this->content->username    = $username;
        $this->content->result      = $result;
        $this->content->pictures    = \models\Photos::getPictures($result->login_id, 30);
        $this->content->posts       = \models\Guestbook::selectId($result->login_id, $guestbookPage);
        $this->content->pagination  = \models\Guestbook::createPagination($result->login_id, $guestbookPage, null, '/user/'.$result->username, 'guestbook', 'id');
        //$this->similarProfiles    = \models\Profiles::findSimilar(array('sex' => $result->gender, 'location' => $result->location)); //Inject into layout.php
        $this->content->wants_to    = \models\Profiles::getWantsTo($result);
        
        # Log events
        if ($this->login_id && $this->login_id == $result->login_id){
            \models\Events::add('visited', $result->login_id, $this->login_id, array('by' => $this->username));
        }
        
        # Do we already like this person?
        if ($this->login_id){
            $matchedProfiles = $this->db->queryAll("SELECT * FROM crushes WHERE login_id=".$this->login_id." AND crush_id={$result->login_id} LIMIT 1");
            $this->content->iLikeOrNot = count($matchedProfiles)>0 ? true : false;
        }
        # Extract some information from main array
        $this->mainInfoBox($result);

        # Do we have to flash a message?
        if (\models\Flash::hasFlash('success')) $this->content->flash = \models\Flash::getFlash('success');

		$this->display();
	}

	public function mainInfoBox($result){
        $infoOptions = \models\Users::$infoOptions;
        //$this->content->infoOptions = $infoOptions;
        
        foreach ($this->content->result as $key => $what){
            // Don't show if value is empty, but DO show if you are viewing your own account!
            if (!array_key_exists($key, $infoOptions) OR (($this->login_id != $result->login_id) && !$what)) continue;
            $this->content->mainInfoBox[$key] = $what;
            $this->content->mainInfoBoxEdit[$key] = $infoOptions[$key];
        }
    }

	// Save user status
	public function updateStatus(){
		if (!Input::post('value') OR !$this->login_id) return false;

		$this->db->query("UPDATE profiles SET mood='".Input::post('value')."' WHERE login_id = ".$this->login_id." LIMIT 1");
		exit;
	}

	// Post comment
	public function postComment(){
        if (!$this->login_id) View::redirect('/index/login/required');

		if (Input::post('username') && Input::post('comment-box') && $this->login_id){
            if (!$this->login_id) exit('Hack attempt. Logged.');
            $this->db->query("INSERT INTO guestbook (owner_id, poster_id,poster_name,content,`timestamp`)VALUES ('".(int)Input::post('userid')."', '".$this->login_id."', '".$this->username."', '".Input::post('comment-box')."', ".\time().")");
            View::redirect('/user/'.Input::post('username'));
        }
		exit;
	}
    
    public function updateInfo(){
        $sql = "";
        foreach(\models\Users::$infoOptions as $key => $value){
            if (Input::post($key)){
                $sql .= "`$key`='".Input::post($key)."',";
            }
        }
        $this->db->query("UPDATE profiles SET ".rtrim($sql, ',')." WHERE login_id = {$this->login_id} LIMIT 1");
        # Redirect back to user page
        View::redirect('/user/'.session::get('username'));
    }

    public function updateField(){

        # Have to be logged in
        if (!$this->login_id) View::redirect('/index/login/required');

        # Match textarea names to db column names
        $fields = array(
            'form_edit' => 'like',
            'notice_edit' => 'notice_about'
        );

        foreach ($fields as $field => $db_column){
            if (Input::post($field)){
                $fieldToUpdate = $db_column;
                $fieldData     = htmlentities(Input::post($field));
                break;
            }
        }

        # Update profiles table with new data
        if ($fieldData && $fieldToUpdate) \models\Profiles::updateField($fieldToUpdate, $fieldData, $this->login_id);

        \models\Flash::setFlash('success','Your profile was successfully saved');

        # Redirect back to user page
        View::redirect('/user/'.session::get('username'));
    }
    
    public function likeAction(){

        # Have to be logged in
        if (!$this->login_id) exit('Not logged in');
        
        $crush_id = (int)Input::post('crush_id') or exit('No crush id');
        
        # Do we already like this person?
        $matchedProfiles = $this->db->queryAll("SELECT * FROM crushes WHERE login_id=".$this->login_id." AND crush_id=$crush_id LIMIT 1");
            
        if (count($matchedProfiles)<1){
            $this->db->query("INSERT INTO crushes VALUES (NULL, ".$this->login_id.", $crush_id)");
            exit('LIKE');
        }else{
            $this->db->query("DELETE FROM crushes WHERE login_id=".$this->login_id." AND crush_id=$crush_id");
            exit('DISLIKE');
        }
    }
    
    
}
?>
