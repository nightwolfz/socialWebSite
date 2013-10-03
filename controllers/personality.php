<?php
namespace controllers;
use \models\session as session;
use \models\View as View;
use \models\Input as Input;

class personalityOptions extends namespace\baseController {
    public $title = 'Rule20';
    public $metaDescription = 'Free dating for singles and social networking for everyone. Find singles in your area.';

    public $questions = array(
        array("question" => "You are almost never late for your appointments", "type" => 'n'),
        array("question" => "You like to be engaged in an active and fast-paced job", "type" => 'n'),
        array("question" => "You enjoy having a wide circle of acquaintances", "type" => 'n'),
        array("question" => "You feel involved when watching TV soaps", "type" => 'n'),
        array("question" => "You are usually the first to react to a sudden event: the telephone ringing or unexpected question", "type" => 'n'),
        array("question" => "You are more interested in a general idea than in the details of its realization", "type" => 'n'),
        array("question" => "You tend to be unbiased even if this might endanger your good relations with people", "type" => 'n'),
        array("question" => "Strict observance of the established rules is likely to prevent a good outcome", "type" => 'n'),
        array("question" => "It's difficult to get you excited ", "type" => 'n'),
        array("question" => "It is in your nature to assume responsibility", "type" => 'n'),
        array("question" => "You often think about humankind and its destiny", "type" => 'n'),
        array("question" => "You believe the best decision is one that can be easily changed", "type" => 'n'),
        array("question" => "Objective criticism is always useful in any activity", "type" => 'n'),
        array("question" => "You prefer to act immediately rather than speculate about various options", "type" => 'n'),
        array("question" => "You trust reason rather than feelings", "type" => 'n'),
        array("question" => "You are inclined to rely more on improvisation than on careful planning", "type" => 'n'),
        array("question" => "You spend your leisure time actively socializing with a group of people, attending parties, shopping, etc.", "type" => 'n'),
        array("question" => "You usually plan your actions in advance", "type" => 'n'),
        array("question" => "Your actions are frequently influenced by emotions", "type" => 'n'),
        array("question" => "You are a person somewhat reserved and distant in communication", "type" => 'n'),
        array("question" => "You know how to put every minute of your time to good purpose", "type" => 'n'),
        array("question" => "You readily help people while asking nothing in return", "type" => 'n'),
        array("question" => "You often contemplate about the complexity of life", "type" => 'n'),
        array("question" => "After prolonged socializing you feel you need to get away and be alone", "type" => 'n'),
        array("question" => "You often do jobs in a hurry", "type" => 'n'),
        array("question" => "You easily see the general principle behind specific occurrences", "type" => 'n'),
        array("question" => "You frequently and easily express your feelings and emotions", "type" => 'n'),
        array("question" => "You find it difficult to speak loudly", "type" => 'n'),
        array("question" => "You get bored if you have to read theoretical books", "type" => 'n'),
        array("question" => "You tend to sympathize with other people", "type" => 'n'),
        array("question" => "You value justice higher than mercy", "type" => 'n'),
        array("question" => "You rapidly get involved in social life at a new workplace", "type" => 'n'),
        array("question" => "The more people with whom you speak, the better you feel", "type" => 'n'),
        array("question" => "You tend to rely on your experience rather than on theoretical alternatives", "type" => 'n'),
        array("question" => "You like to keep a check on how things are progressing", "type" => 'n'),
        array("question" => "You easily empathize with the concerns of other people", "type" => 'n'),
        array("question" => "Often you prefer to read a book than go to a party", "type" => 'n'),
        array("question" => "You enjoy being at the center of events in which other people are directly involved", "type" => 'n'),
        array("question" => "You are more inclined to experiment than to follow familiar approaches", "type" => 'n'),
        array("question" => "You avoid being bound by obligations", "type" => 'n'),
        array("question" => "You are strongly touched by the stories about people's troubles ", "type" => 'n'),
        array("question" => "Deadlines seem to you to be of relative, rather than absolute, importance", "type" => 'n'),
        array("question" => "You prefer to isolate yourself from outside noises", "type" => 'n'),
        array("question" => "It's essential for you to try things with your own hands", "type" => 'n'),
        array("question" => "You think that almost everything can be analyzed", "type" => 'n'),
        array("question" => "You do your best to complete a task on time", "type" => 'n'),
        array("question" => "You take pleasure in putting things in order", "type" => 'n'),
        array("question" => "You feel at ease in a crowd", "type" => 'n'),
        array("question" => "You have good control over your desires and temptations", "type" => 'n'),
        array("question" => "You easily understand new theoretical principles", "type" => 'n'),
        array("question" => "The process of searching for a solution is more important to you than the solution itself", "type" => 'n'),
        array("question" => "You usually place yourself nearer to the side than in the center of the room", "type" => 'n'),
        array("question" => "When solving a problem you would rather follow a familiar approach than seek a new one", "type" => 'n'),
        array("question" => "You try to stand firmly by your principles", "type" => 'n'),
        array("question" => "A thirst for adventure is close to your heart", "type" => 'n'),
        array("question" => "You prefer meeting in small groups to interaction with lots of people", "type" => 'n'),
        array("question" => "When considering a situation you pay more attention to the current situation and less to a possible sequence of events", "type" => 'n'),
        array("question" => "You consider the scientific approach to be the best", "type" => 'n'),
        array("question" => "You find it difficult to talk about your feelings", "type" => 'n'),
        array("question" => "You often spend time thinking of how things could be improved", "type" => 'n'),
        array("question" => "Your decisions are based more on the feelings of a moment than on the careful planning", "type" => 'n'),
        array("question" => "You prefer to spend your leisure time alone or relaxing in a tranquil family atmosphere", "type" => 'n'),
        array("question" => "You feel more comfortable sticking to conventional ways", "type" => 'n'),
        array("question" => "You are easily affected by strong emotions", "type" => 'n'),
        array("question" => "You are always looking for opportunities", "type" => 'n'),
        array("question" => "Your desk, workbench etc. is usually neat and orderly", "type" => 'n'),
        array("question" => "As a rule, current preoccupations worry you more than your future plans", "type" => 'n'),
        array("question" => "You get pleasure from solitary walks", "type" => 'n'),
        array("question" => "It is easy for you to communicate in social situations", "type" => 'n'),
        array("question" => "You are consistent in your habits", "type" => 'n'),
        array("question" => "You willingly involve yourself in matters which engage your sympathies", "type" => 'n'),
        array("question" => "You easily perceive various ways in which events could develop", "type" => 'n')
    );
}

class personality extends personalityOptions {

    public function __construct(){
        $this->db = \models\MySQL::getInstance();
        session_start();
    }


    public function index($params = null)
    {
        $this->content = new View('personality');
        $this->content->questions = $this->questions;

        # sample results from a questionnaire
        $test = 'i e i e i n s n s n t f t f t p j p j p';

        # calculate type
        $this->content->type = $this->type( $test );
        $this->display();
    }



    private function type($letters, $wantarray = false) {

        //$count = map( { $_ => 0 } qw/e i s n f t p j/ );
        $count0 = explode(' ', 'e i s n f t p j');
        foreach($count0 as $v){
            $count[$v] = 0;
        }

        $letters = explode(' ', $letters);
        foreach ($letters as $letter) {
            $letter = strtolower($letter);
            $weight = 1;
            $count[$letter] += $weight;
        }
        $result  = $this->typePreference(array('e' => $count['e'], 'i' => $count['i']));
        $result .= $this->typePreference(array('s' => $count['s'], 'n' => $count['n']));
        $result .= $this->typePreference(array('f' => $count['f'], 't' => $count['t']));
        $result .= $this->typePreference(array('p' => $count['p'], 'j' => $count['j']));

        return $wantarray ? $count : $result;
    }

    private function typePreference($count) {

        $countOriginal = $count;
        $a = $this->array_kshift($countOriginal);
        $b = $this->array_kshift($countOriginal);
        $aa = key($a);
        $bb = key($b);
        return $count[$aa] > $count[$bb] ? $aa : $count[$bb] > $count[$aa] ? $bb : 'x';
    }


    /*
     * Receives a personality type (e.g. "infp") and returns its dominant
     * function (in this case, "fi" - introverted feeling).
     */
    private function dominant($type) {
        $ei = $type{0};
        $ns = $type{1};
        $ft = $type{2};
        $jp = $type{3};

        $orient = $ei; # always
        $function = '';

        if ( ($jp == 'p' and $ei == 'i') or $jp == 'j' and $ei == 'e' ) {
            $function = $ft;
        } elseif ( ($jp == 'p' and $ei == 'e') or ($jp == 'j' and $ei == 'i')) {
            $function = $ns;
        }
        return $function.$orient;
    }

    /*
     * Receives a personality type (e.g. "infp") and returns its auxiliary
     * function (in this case, "ne" - extroverted intuition).
     */
    private function auxiliary($type) {
        $ei = $type{0};
        $ns = $type{1};
        $ft = $type{2};
        $jp = $type{3};

        $orient = $ei == 'i' ? 'e' : 'i'; # the opposite, always
        $function = '';

        if ( ($jp == 'p' and $ei == 'i') or $jp == 'j' and $ei == 'e' ) {
            $function = $ft;
        } elseif ( ($jp == 'p' and $ei == 'e') or ($jp == 'j' and $ei == 'i')) {
            $function = $ns;
        }
        return $function.$orient;
    }


    private function letterFunction($wantarray = false) {

        $function = array(
            'e' => 'Extraversion',
            'i' => 'Introversion',
            's' => 'Sensing',
            'n' => 'iNtuition',
            'f' => 'Feeling',
            't' => 'Thinking',
            'p' => 'Perceiving',
            'j' => 'Judging',
        );

        return $wantarray ? $function : $function[$wantarray];
    }

    private function typeToName($type, $wantarray = false) {

        $keirsey = array(
            'esfj' => 'provider',
            'enfj' => 'teacher',
            'isfp' => 'composer',
            'infp' => 'healer',
            'enfp' => 'champion',
            'entp' => 'inventor',
            'infj' => 'counselor',
            'intj' => 'mastermind',
            'esfp' => 'performer',
            'estp' => 'promoter',
            'isfj' => 'protector',
            'istj' => 'inspector',
            'estj' => 'supervisor',
            'entj' => 'field marshal',
            'istp' => 'crafter',
            'intp' => 'architect',
        );

        return $wantarray ? $keirsey : $keirsey[$type];
    }

    private function array_kshift(&$arr) {
        list($k) = array_keys($arr);
        $r  = array($k => $arr[$k]);
        unset($arr[$k]);
        return $r;
    }

}
?>