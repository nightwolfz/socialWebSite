<?php
namespace models;

class Profiles {

	/**
     * Fetch profiles table from the database
     *
     * @param int $id login_id to select
	 * @param int $limit limit to how many results?
     * @return Object
     */
	public static function selectId($id = null, $limit = 1, $page = 0)
	{
		$db = \models\MySQL::getInstance();

        //first item to display on this page
        $page = (!$page ? 1 : $page);
        $start = $page ? (($page - 1) * $limit) : 0;
        $result = array();

        return $db->queryAll("SELECT * FROM profiles ".($id ? "WHERE login_id='".(int)$id."'" : "")." ORDER BY login_id DESC LIMIT ".($start?"$start,":'')." $limit");
	}
	/**
     * Fetch one username from profiles table based on login_id
     *
     * @param int $id login_id to select
     * @return Object
     */
	public static function getUsername($id = 1)
	{
		$db = \models\MySQL::getInstance();
        $db->query("SELECT username FROM profiles WHERE login_id='".(int)$id."' LIMIT 1");
        return $db->fetch()->username;
	}

    public static function findByUsername($username = null, $limit = 1)
    {
        $results = array();
    	$db = \models\MySQL::getInstance();

        $query = "SELECT * FROM profiles p RIGHT JOIN accounts a ON p.login_id = a.login_id WHERE p.username='".($username ? $username : "")."' LIMIT $limit";

        if (!$results = \models\cache::get($query)){
            $results = $db->queryAll($query);
        }
		return $results;
    }

    public static function getWantsTo($result){
        $range_from = $result->range_from;
        $range_to = $result->range_to;

        $wants_to = empty($result->wants_to) ? 'nothing' : $result->wants_to;
        $with_who = function($with){
            if ($with == 0) return "man";
            elseif ($with == 0) return "woman";
            else return "anyone";
        };
        $output = "Wants to {$wants_to} with a ".$with_who($result->with_who)." between {$range_from} & {$range_to} years old";
        $output.= (!empty($result->location) ? "living in <strong>{$result->location}</strong>" : "") .".";
        return array("wants" => $output);
    }

    public static function getAvatar($login_id, $gender = '', $returnString = false){
        $db = \models\MySQL::getInstance();
        $db->select("photos","thumb,avatar")->where("login_id='".(int)$login_id."' AND avatar = '1' LIMIT 1");
        $avatar = $db->fetch();

        if ($returnString){
            return isset($avatar->thumb) ? $avatar->thumb : array("avatar" => "default$gender.jpg");
        }else{
            return $avatar ? array("avatar" => $avatar->thumb) : array("avatar" => "default$gender.jpg");
        }
    }

    public static function updateField($field, $field_data, $login_id){
        $db = \models\MySQL::getInstance();
        $db->query("UPDATE profiles SET `$field`='$field_data' WHERE login_id='".(int)$login_id."' LIMIT 1");
        return true;
    }

    public static function findSimilar(array $conditions = array(), $limit = 10)
    {
        $defaults = array(
			'sex' => 'f',
            'location' => null
		);
        $conditions = array_merge( $defaults, $conditions );


        $results = array();
    	$db = \models\MySQL::getInstance();

        # Put everything into one
		$q = array();
		$q[] = "SELECT username, gender, birthday, location FROM profiles WHERE ((1=1)";
		$q[] = "AND gender='{$conditions['sex']}'";
        $q[] = !empty($conditions['location']) ? "AND location='{$conditions['location']}'" : '';
		$q[] = ") LIMIT $limit;--";
		$query = implode(PHP_EOL, $q);

        if (!$results = \models\cache::get($query)){
            $results = $db->queryAll($query);
        }
		return ($limit == 1) ? $results[0] : $results;
    }

}

?>