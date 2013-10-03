<?php
namespace models;

class Time extends \DateTime {
    /**
     * Create a new datetime object from a string or timestamp
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        if (is_int($time))   $time = "@$time";
        if (is_array($time)) $time = self::fromArray($time);
        
        if ($timezone) parent::__construct($time, $timezone);
        else parent::__construct($time);
    }

    /**
     * Return the difference between $this and $now
     *
     * @param Datetime $ |String $now
     * @return DateInterval
     */
    public function diff($now = 'NOW', $absolute = false)
    {
        if (!($now instanceOf DateTime)) $now = new Time($now);
        return parent::diff($now, $absolute);
    }

    /**
     * Return a SQL date string
     *
     * @return string
     */
    public function getSQL()
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * Show a human-readable time difference ("10 seconds")
     *
     * @param int $d the difference to caculate
     * @param int $len the max length of values
     * @return string
     */
    public function difference($d = 'NOW', $len = 1)
    {
        $d = $this->diff($d);
        $shortnames = array('y' => 'year', 'm' => 'month', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
        $r = array();
        foreach($shortnames as $k => $name) {
            $val = $d->$k;
            if ($name) $arr[] = "$val $name" . ($val > 1 ? 's' : '');
            if (count($arr) == $len) return implode(', ', $arr);
        }
    }

    /**
     * Returns the closest human friendly format of the date from right now.
     *
     * @param string $format if longer than one day ago
     * @return string
     */
    public function humanFriendly($format = 'M j, Y \a\t g:ia')
    {
        $diff = $this->diff();
        $t = $this->getTimestamp();
		//@TODO: Fix this
        if (!$diff->d) {
            $s = $this->difference();
            return $t < time() ? "$s ago" : "in $s";
        }
        return $this->format($format);
    }
	
	/**
     * Return age based on birthday in unix format
     *
	 * @param int $birthday in unix time format
     * @return string
     */
	static function getAge($birthday){
		return floor((time() - $birthday) / (60*60*24*365));
	}

    /**
     * Return an array of time values
     *
     * @return array
     */
    public function getArray()
    {
        $ts = $this->getTimestamp();
        return array(
            'year' => date('Y', $ts), 
            'month' => date('m', $ts), 
            'day' => date('d', $ts), 
            'hour' => date('H', $ts), 
            'minute' => date('i', $ts), 
            'second' => date('s', $ts));
    }

    /**
     * Turn an array into a timestamp (used by constructor)
     *
     * @param array $data
     * @return int
     */
    public static function fromArray(array $data)
    {
        foreach(array('year', 'month', 'day', 'hour', 'minute', 'second') as $k) $$k = isset($data[$k]) ? $data[$k] : 0;
        return mktime($hour, $minute, $second, $month, $day, $year);
    }
    
    public static function timeAgo($toTime, $showLessThanAMinute = false, $fromTime = 0) {
        $fromTime = time();
        $distanceInSeconds = round(abs($toTime - $fromTime));
        $distanceInMinutes = round($distanceInSeconds / 60);
        
        if ( $distanceInMinutes <= 1 ) {
            if ( !$showLessThanAMinute ) {
                return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
            } else {
                if ( $distanceInSeconds < 5 )  return 'less than 5 seconds';
                if ( $distanceInSeconds < 10 ) return 'less than 10 seconds';
                if ( $distanceInSeconds < 20 ) return 'less than 20 seconds';
                if ( $distanceInSeconds < 40 ) return 'about half a minute';
                if ( $distanceInSeconds < 60 ) return 'less than a minute';
                return '1 minute';
            }
        }
        if ( $distanceInMinutes < 45 ) return $distanceInMinutes . ' minutes';
        if ( $distanceInMinutes < 90 ) return 'about 1 hour';
        if ( $distanceInMinutes < 1440 ) return 'about ' . round(floatval($distanceInMinutes) / 60.0) . ' hours';
        if ( $distanceInMinutes < 2880 ) return '1 day';
        if ( $distanceInMinutes < 43200 ) return 'about ' . round(floatval($distanceInMinutes) / 1440) . ' days';
        if ( $distanceInMinutes < 86400 ) return 'about 1 month';
        if ( $distanceInMinutes < 525600 ) return round(floatval($distanceInMinutes) / 43200) . ' months';
        if ( $distanceInMinutes < 1051199 ) return 'about 1 year';

        return 'over ' . round(floatval($distanceInMinutes) / 525600) . ' years';
    }

    /**
     * Quick way to show a humanfriendly time
     *
     * @param mixed $time
     * @return string
     */
    public static function show($time)
    {
        $time = new Time($time - 1);
        return $time->humanFriendly();
    }
}
?>