<?php
namespace models;

class Forum {
	/**
     * Fetch guestbook table from the database
     *
     * @param int $ownerId owner_id to select
	 * @param int $posterId poster_id to select
	 * @param int $limit limit to how many results?
     * @return Object
     */
	static function getPosts($topic_id){
		$db = \models\MySQL::getInstance();

        $result = $db->queryAll("SELECT
                        f.post_topic,
                        f.post_content,
                        f.post_date,
                        f.post_by,
                        p.login_id,
                        p.username,
                        p.gender,
                        p.birthday
                    FROM
                        forum_posts f LEFT JOIN profiles p
                    ON
                        f.post_by = p.login_id
                    WHERE
                        f.post_topic = " . (int)$topic_id);

		return $result;
	}

    static function count($ownerId){
        $db = \models\MySQL::getInstance();
        $db->query("SELECT COUNT(*) as num FROM guestbook ".($ownerId ? "WHERE owner_id='".(int)$ownerId."'" : ""));
        $count = $db->fetch();
        return $count->num;
    }

    static function addTopic(){
        $db = \models\MySQL::getInstance();
        //start the transaction
        $result = $db->query("BEGIN WORK;");

        //the form has been posted, so save it
        //insert the topic into the topics table first, then we'll save the post into the posts table
        $result = $db->query("INSERT INTO forum_topics (topic_subject, topic_date, topic_cat, topic_by)
        VALUES('" . \models\Input::post('topic_subject') . "', NOW()," . \models\Input::post('topic_cat') . "," . \models\session::get('login_id') . ")");

        if (!$result) {
            $result = $db->query("ROLLBACK;") and print 'An error occured while inserting your data. Please try again later.' . mysql_error();
        } else {
            //the first query worked, now start the second, posts query
            //retrieve the id of the freshly created topic for usage in the posts query
            $topicid = $db->getInsertID();

            $result = $db->query("INSERT INTO forum_posts(post_content, post_date, post_topic, post_by)
            VALUES ('" . \models\Input::post('post_content') . "', NOW(), " . $topicid . ", " . \models\session::get('login_id') . ")");

            if (!$result) {
                $result = $db->query("ROLLBACK;") and print 'An error occured while inserting your post. Please try again later.' . mysql_error();
            } else {
                $result = $db->query("COMMIT;") and print 'You have successfully created <a href="topic.php?id=' . $topicid . '">your new topic</a>.';
            }
        }
    }

}

?>