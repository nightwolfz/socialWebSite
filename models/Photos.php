<?php
namespace models;

class Photos {

	/**
     * Fetch photos table from the database
     *
     * @param int $login_id login_id to select
	 * @param int $limit limit to how many results?
     * @return Object
     */
	static function getPictures($login_id = null, $limit = 1, $pictureId = null){
		$db = \models\MySQL::getInstance();
        $result = $db->queryAll("SELECT * FROM photos ".
                  ($login_id ? "WHERE login_id='".(int)$login_id."'" : "").
                  ($pictureId ? " AND `id`='".(int)$pictureId."'" : '').
                  " LIMIT $limit");
		return ($limit == 1) ? (isset($result[0]) ? $result[0] : array()) : (isset($result) ? $result : array());
	}

	static function updateAvatar($login_id, $set, $limit = 1, $pictureId = null){
		$db = \models\MySQL::getInstance();
        // Update the photos table
        $db->query("UPDATE photos SET avatar='".(int)$set."' WHERE login_id = ".$login_id."
        ".($pictureId ? "AND `id`='".(int)$pictureId."'" : '')." LIMIT $limit");
        
        // Update the profiles table
        if ($pictureId){
            $photos = $db->queryAll("SELECT * FROM photos WHERE `id`='".(int)$pictureId."' LIMIT 1");
            if ($photos) $db->query("UPDATE profiles SET photo_id='".$photos[0]->id."', photo='".$photos[0]->thumb."' WHERE login_id = ".$login_id." LIMIT $limit");
        }
        return true;
	}

    static function getAvatar($login_id){
		$db = \models\MySQL::getInstance();
        $db->query("SELECT * FROM photos WHERE login_id='".(int)$login_id."' AND avatar=1 LIMIT 1");
        $result = $db->fetch();
		return !empty($result) ? $result : null;
	}

}

?>