<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

class feed extends namespace\baseController {
    public $title = 'User feeds';
    public $metaDescription = '';

    public function __construct(){
        $this->db = \models\MySQL::getInstance();
        session_start();
    }

    //@http://localhost/index/index/hello/eee
    //@http://localhost/index.php?controller=index&action=index&params[hello]=eee
    public function index($params = null)
    {
        $this->title   = "Rule20 - Feed";
        $this->header = 'What\'s new';
        $this->content = new View('feed');
        $results = \models\Profiles::selectId('',8);

        foreach ($results as $result){
            $this->content->newUsers[] = array_merge((array)$result, \models\Profiles::getWantsTo($result), \models\Profiles::getAvatar($result->login_id));
        }
        $this->display();
    }

    public function test(){
        /*$book = htmlentities(file_get_contents(__DIR__.'/text.txt'));
        $book = str_replace('&mdash;', "-", $book);
        $book = str_replace('&mdash;', "-", $book);
        $bookText = preg_split("/\r\n\r\n/", $book);

        foreach($bookText as $k => $bText){
            if (strlen($bText)<10) unset($bookText[$k]);
        }

        shuffle($bookText);

        for ($i = 0; $i < 379; $i++) {
            \models\Profiles::updateField('notice_about', str_replace(array("\r\n"), "<br>", $bookText[$i]), $i);
            echo "$i...";
        }*/

        /*$list = "New York
                Los Angeles
                Chicago
                Houston
                Philadelphia
                Phoenix
                San Antonio
                San Diego
                Dallas
                San Jose
                Jacksonville
                Indianapolis
                San Francisco
                Austin
                Columbus
                Fort Worth
                Charlotte
                Detroit
                El Paso
                Memphis
                Baltimore
                Boston
                Seattle
                Washington
                Nashville
                Denver
                Louisville
                Milwaukee";
        $list = explode("\n", $list);
        for ($i = 0; $i < 379; $i++) {
            shuffle($list);
            \models\Profiles::updateField('city', str_replace(array("\r\n"), "", $list[0]), $i);
            echo "$list[0]...";
        }*/

        echo '<p>All done...</p>';
    }
}
?>
