<?php
namespace models;

class Events {

    static function add($event, $to_id = null, $from_id = null, array $content = array())
    {
        $to_id = $to_id ?: \models\session::get('login_id');
        
        $db = \models\MySQL::getInstance();
        
        //For "visited" events, we will check if already exists for today then update or insert as necessary
        $db->select("events")
           ->where("event_name='$event' AND login_id='$to_id' AND from_id='$from_id' LIMIT 1");
        
        if ($db->fetch()):
            $db->query("UPDATE events SET `timestamp` = NOW() WHERE event_name='$event' AND login_id='$to_id' AND from_id='$from_id' LIMIT 1");
        else:// Insert the event
            $db->query("INSERT INTO events (login_id, from_id, event_name, event_content, `timestamp`) 
                VALUES ('$to_id', '$from_id', '$event', '".json_encode($content)."', NOW() )"); 
        endif;
        
    }
    
    static function get($event, $login_id = null, $limit = null)
    {
        $login_id = $login_id ?: \models\session::get('login_id');
        
        $db = \models\MySQL::getInstance();

        $db->select("events")
           ->where("event_name='$event' AND login_id='$login_id' ".($limit ? "LIMIT $limit" : ""));
        
        while($r = $db->fetch()){
            $r->event_content = self::$event($r->event_content, $r);
            $events[] = $r;
        }
		return isset($events) ? ( ($limit == 1) ? $events[0] : $events ) : array();
    }

    static function visited($src, $obj)
    {
        $src = json_decode($src);
        return "<a href=\"/user/{$src->by}\">{$src->by}</a> visited your profile  ".\models\Time::timeAgo(strtotime($obj->timestamp))." ago. ";
    }
    
    static function replaceTags($src, $obj) {
        $src = preg_replace('#\{@([\w]+)\}#', '<a href="/user/$1">$1</a>', $src);
        return preg_replace_callback('#\{timeAgo}#', 
                function($v) use($obj) {
                    return \models\Time::timeAgo(strtotime($obj->timestamp));
                }, $src);
    }

}

?>