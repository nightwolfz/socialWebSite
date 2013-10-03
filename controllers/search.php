<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

class search extends namespace\baseController {
    public $title = 'Search results';
    public $results = array();

    public function __construct(){
		$this->db = \models\MySQL::getInstance();
        session_start();
	}

	public function index($params = null)
    {
        $this->content = new View('search');
        $this->header = 'Search';
        $this->content->ranges = \models\Users::$ageRanges;

        if (Input::post('search_now')):
	        $this->results = \models\Users::findWhere(array(
				'range' => Input::post('range'), # age range
				'sex' => Input::post('sex'), # 'f' / 'm'
                'with_picture' => Input::post('with_picture') ? true : false,
                'with_webcam' => Input::post('with_webcam') ? true : false,
                'is_online' => Input::post('is_online') ? true : false,
			));
        endif;

        foreach ($this->results as $result){
            $this->content->newUsers[] = array_merge((array)$result, \models\Profiles::getWantsTo($result), \models\Profiles::getAvatar($result->login_id));
        }

        $this->display();
	}

    public function winjs(){
        echo '<article class="content clearfix">testing string is here oh yeahhhh </article>';
    }

}
?>
