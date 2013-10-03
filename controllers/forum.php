<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

/**
 * Forum controller
 * @forum/topic/5
 */

class forum extends namespace\baseController {

	public function __construct(){
		$this->db = \models\MySQL::getInstance();
        session_start();
        $this->login_id = session::get('login_id', null);
        $this->username = session::get('username', null);
	}

	public function index()
    {
        $this->listCategories();
	}

    function listCategories(){
        $this->db->query("SELECT cat_id, cat_name, cat_description FROM forum_categories");

        $categories = array();

        while($row = $this->db->fetch()){
            $categories[] = $row;
        }

        $this->content = new View('forum');
        $this->header = 'Forum';
        $this->content->action = 'categories';
        $this->content->categories = $categories;
		$this->display();
    }

    function category($cat_id = 1){

        $this->content = new View('forum');
        $this->header = 'Forum';

        # Get category title
        $this->db->query("SELECT cat_id,cat_name,cat_description FROM forum_categories WHERE cat_id = " . (int)$cat_id);
        $categoryHeader = $this->db->fetch();
        $this->content->categoryTitle = $categoryHeader->cat_name;

        # List all the topics in this category
        $topics = $this->db->queryAll("SELECT *, (SELECT username FROM profiles WHERE login_id=topic_by LIMIT 1) AS username FROM forum_topics WHERE topic_cat = " . (int)$cat_id);

        $this->content->action = 'topics';
        $this->content->topics = $topics;
        $this->display();
    }
    
    function topic($topic_id = 1){
        if (!session::get('login_id')) exit('Must be logged in.');

        if (Input::post()) {
            //a real user posted a real reply
            $this->db->query("INSERT INTO forum_posts(post_content,post_date,post_topic,post_by) VALUES ('" . Input::post('post-content') . "', NOW(), $topic_id," . session::get('login_id') . ")")
                and \models\Flash::setFlash('success','Your reply has been successfully posted')
                 or \models\Flash::setFlash('warning','Your reply has not been saved, please try again later.');

            View::redirect("/forum/topic/$topic_id");

        } else {

            $topicData = $this->db->queryAll("SELECT * FROM forum_topics WHERE topic_id = " . (int)$topic_id." LIMIT 1");

            $this->content = new View('forum');
            $this->header = 'Forum';
            $this->content->action = 'topic';
            $this->content->topicTitle = $topicData[0]->topic_subject;
            $this->content->posts = array();
            
            foreach(\models\Forum::getPosts($topic_id) as $post){
                $this->content->posts[] = (object)array_merge((array)$post, (array)\models\Profiles::getAvatar($post->login_id));
            }
            
            
            $this->display();
        }
    }

    function createTopic(){
        if (!session::get('login_id')) View::redirect('/index/login/required');

        echo '<h2>Create a topic</h2>';

        # The user is creating a topic
        if (Input::post()) {
            \models\Forum::addTopic();

        }else{
            # The form hasn't been posted yet, display it
            # retrieve the categories from the database for use in the dropdown
            $this->db->query("SELECT cat_id, cat_name, cat_description FROM forum_categories LIMIT 10");
            $result = array();

            while ($row = $this->db->fetch()){
                $result[] = $row;
            }
            if (empty($result)) {
                //there are no categories, so a topic can't be posted
                if (session::get('user_level') == 1) {
                    echo 'You have not created categories yet.';
                } else {
                    echo 'Before you can post a topic, you must wait for an admin to create some categories.';
                }
            } else {

                echo '<form method="post" action="">
                Subject: <input type="text" name="topic_subject" />
                Category:';

                echo '<select name="topic_cat">';
                foreach ($result as $row) {
                    echo '<option value="' . $row->cat_id . '">' . $row->cat_name . '</option>';
                }
                echo '</select>';

                echo 'Message: <textarea name="post_content" /></textarea>
                <input type="submit" value="Create topic" />
                </form>';
            }
        }
    }

}

/*
CREATE TABLE forum_categories (
cat_id 		 	INT(8) NOT NULL AUTO_INCREMENT,
cat_name	 	VARCHAR(255) NOT NULL,
cat_description 	VARCHAR(255) NOT NULL,
UNIQUE INDEX cat_name_unique (cat_name),
PRIMARY KEY (cat_id)
) ENGINE=INNODB;


CREATE TABLE forum_topics (
topic_id		INT(8) NOT NULL AUTO_INCREMENT,
topic_subject  		VARCHAR(255) NOT NULL,
topic_date		DATETIME NOT NULL,
topic_cat		INT(8) NOT NULL,
topic_by		INT(8) NOT NULL,
PRIMARY KEY (topic_id)
) ENGINE=INNODB;

CREATE TABLE forum_posts (
post_id 		INT(8) NOT NULL AUTO_INCREMENT,
post_content		TEXT NOT NULL,
post_date 		DATETIME NOT NULL,
post_topic		INT(8) NOT NULL,
post_by		INT(8) NOT NULL,
PRIMARY KEY (post_id)
) ENGINE=INNODB;

ALTER TABLE forum_topics ADD FOREIGN KEY(topic_cat) REFERENCES forum_categories(cat_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_topics ADD FOREIGN KEY(topic_by) REFERENCES profiles(login_id) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE forum_posts ADD FOREIGN KEY(post_topic) REFERENCES forum_topics(topic_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_posts ADD FOREIGN KEY(post_by) REFERENCES profiles(login_id) ON DELETE RESTRICT ON UPDATE CASCADE;

 */
?>
