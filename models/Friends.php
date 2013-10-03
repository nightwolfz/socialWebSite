<?php
namespace models;

class Friends {
    
    /**
     * Fetch friends list
     * @return Array
     */
    static function getFriends($login_id, $limit = 10) {
        $db = \models\MySQL::getInstance();
        $friends = $db->queryAll("
                SELECT
                    p.login_id,
                    f.friend_id,
                    p.username,
                    p.gender,
                    p.location,
                    p.wants_to,
                    p.with_who,
                    p.range_from,
                    p.range_to
                FROM friends f 
                LEFT JOIN profiles p ON f.friend_id = p.login_id 
                WHERE f.login_id = '$login_id' LIMIT $limit");
        return $friends;
    }
    
    
}
