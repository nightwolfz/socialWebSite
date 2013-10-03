<?php
namespace models;

/**
 * This model abstracts users and provides user lookup and factory
 * methods.
 */
class Users {
    
    static public $infoOptions = array(
            "hair" => array("Black", "Brown", "Blond", "Red", "Colored"),
            "eyes" => array("Brown", "Blue", "Green", "Hazel", "Gray"),
            "bodytype" => array("Brown"),
            "face_hair" => array("Brown"),
            "body_hair" => array("Brown"),
            "height" => array("Less than 1m50", "1m50-1m60", "1m60-1m70", "1m70-1m80", "1m80-1m90", "More than 1m90"),
            "smoke" => array("Yes", "No", "Occasionally", "Rarely"),
            "drinks" => array("Yes", "No", "Occasionally", "Rarely"),
            "religion" => array("Very religious", "Religious", "Not religious", "Don't care"),
            "zodiac" => array("Aries", "Scorpion"),
            "education" => array("University", "Highschool", "Other"),
            "work" => array("Employed", "Unemployed", "Student"),
            "kids" => array("Wants kids in the future", "Not sure", "Already has kids"),
            "animals" => array("Likes dogs", "Likes cats", "Like other/everything", "Pets are OK", "Doesn't like pets"),
            "msn" => array(),
            "icq" => array(),
            "webcam" => array("Yes, I have one", "No, I don't have one")
        );
    static public $ageRanges = array('16-18', '18-20', '20-25', '25-30','30-35','35-40','40-50','50-60');


    public static function load() {
	}

	public static function findWhere( array $conditions = array() ) {
		# Create dummy array whose values will be replaced later
        $options = array(
			'range' => '18-20', # age range
			'sex' => null, # 'f' / 'm'
            'with_picture' => false,
		);

        # Split age ex: 18-20 into 18 and 20
        $conditions['range'] = explode('-', $conditions['range']);

		$options = array_merge( $options, $conditions );

		# Saves a timestamp version of the dates into a new field:
        $time = time();
		$options['range_from_ts'] = strtotime("-".(int)$options['range'][0]." years", $time);
		$options['range_to_ts'] = strtotime("-".(int)$options['range'][1]." years", $time);
        
        # Put everything into one
		$q = array();
		$q[] = 'select * from profiles p';
		$q[] = 'join accounts a on a.login_id = p.login_id';
		$q[] = 'where ((1=1)';
		$q[] = $options['sex'] ? 'and ( p.gender = ' . $options['sex'] . ' )' : '';
		$q[] = 'and ( a.birthday <= ' . $options['range_from_ts'] . ' )';
		$q[] = 'and ( a.birthday >= ' . $options['range_to_ts'] . ' )';
        $q[] = $options['is_online'] ? 'and ( p.last_online >= ' . strtotime("-1 day") . ' )' : '';
        $q[] = $options['with_picture'] ? 'and p.photo_id != 8' : '';
		$q[] = ') LIMIT 10;--';

		$query = implode(PHP_EOL, $q);

        # Populate the $results array
        $db = \models\MySQL::getInstance();
        return $db->queryAll($query);
	}
}
