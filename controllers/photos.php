<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;


class photos extends namespace\baseController {

	public function __construct(){
        session_start();
        $this->login_id = session::get('login_id', null);
        $this->username = session::get('username', null);

        // This whole controller requires you to be logged in
        if (!$this->login_id) View::redirect('/index/login/required');

        $this->db = \models\MySQL::getInstance();
	}

	//@http://localhost/photos
	//@http://localhost/index.php?controller=index&action=index&params[hello]=eee
	public function index($userid = 1)
    {
		$this->content = new View('photos');
        $this->header = 'Photos';
		$this->userid = ($userid == 1 && $this->login_id) ? $this->login_id : $userid;

		$this->content->pictures = \models\Photos::getPictures($this->userid, 30);
		$this->display();
	}

	// Set as default
	public function setDefault($pictureId){
        if (!$pictureId) View::redirect('/index/login/required');

        // Check if the picture you are setting is really yours
		$isMyPicture = \models\Photos::getPictures($this->login_id, 1, $pictureId);

        // If it is, reset all and make the picture in question the avatar
		if ($isMyPicture->id){
			\models\Photos::updateAvatar($this->login_id, 0, 100);
			\models\Photos::updateAvatar($this->login_id, 1, 1, $pictureId);
		}

		//$this->db->query("update photos set avatar = case when `id` in (select `id` from photos where login_id = '".$this->login_id."' and id='".(int)$pictureId."' limit 1 union select null) then '1' else '0' where login_id = '".$this->login_id."' ");
		View::redirect('/photos/');
		exit;
	}

	// Upload picture
	public function uploadPicture(){
        if (!$_FILES['realfile']) View::redirect('/index/login/required');

        $oldumask = umask(0);
        
		// Where to store uploads
        $dir = SP.'uploads/'.date('m').'/'.date('d').'/';
        
        if (!file_exists($dir) OR !is_dir($dir)) mkdir($dir, 0744, true);
        
        $dirthumb = SP.'uploads/t/'.date('m').'/'.date('d').'/';
        
        if (!file_exists($dirthumb) OR !is_dir($dirthumb)) mkdir($dirthumb, 0744, true);


		// Check to see if we are uploading a new file
		if ($_FILES AND !empty($_FILES['realfile']))
		{
            $fileRename = $this->username.'_'.time().'_'.rand(1,1000).'.jpg';

			if ($file = \models\Upload::file( $_FILES['realfile'], $dir, false, false, $fileRename))
			{
				$this->event('message', '<a href="/uploads/'. $file. '">'. $file. '</a> Uploaded!');// We should implement a message flash event for this
                \models\GD::thumbnail($dir.'/'.$fileRename, 120, 120, 80, $fileRename);// Create thumbnail 120x120
                $this->db->query("INSERT INTO photos (login_id, thumb) VALUES ('{$this->login_id}', '".date('m').'/'.date('d').'/'.$fileRename."') ");// Add to database
                $uploadedPictureId = $this->db->getInsertID();

                // If no other picture was set as avatar then set this one
                if (!$exists = \models\Photos::getAvatar($this->login_id)){
                    \models\Photos::updateAvatar($this->login_id, 1, 1, $uploadedPictureId);
                }
			}
            umask($oldumask);
		}
		/*
		$_FILES['bestand']['name'] Deze bevat de naam van het bestand dat geupload word
		$_FILES['bestand']['size'] Deze bevat de groote van het bestand dat geupload word in bytes
		$_FILES['bestand']['type'] Deze leest het mime-type van het bestand dat geupload word
		$_FILES['bestand']['tmp_name'] Deze bevat het bestand dat geupload word. Deze word dus tijdelijk op je server opgeslagen tijdens het uploaden.
		*/
		View::redirect('/photos/');
		exit;
	}

    public function delete($pictureId){
		// Check if the picture is really yours
		$exists = \models\Photos::getPictures($this->login_id, 1, $pictureId);

		// File location to delete
        $file = SP.'uploads/'.$exists->thumb;
        $thumb = SP.'uploads/t/'.$exists->thumb;

        // Check if directory exists, else create
        if(file_exists($file)) unlink($file) && unlink($thumb);
        $this->db->query("DELETE FROM photos WHERE login_id={$this->login_id} AND id='{$exists->id}' LIMIT 1");

		View::redirect('/photos/');
		exit;
	}


	/**
	 * We should implement an event handler someday
	 */
	public function event($event, $value){
		echo $value;
	}
}
?>
