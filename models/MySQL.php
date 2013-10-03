<?php
namespace models;

class MySQL
{
	private $result = NULL;
	private $link = NULL;
	private static $instance = NULL;

	// return Singleton instance of MySQL class
	public static function getInstance(array $config = array())
	{
		if (self::$instance === NULL) self::$instance = new self($config);

		return self::$instance;
	}

	// constructor
	public function __construct(array $config = array())
	{
        // use default configuration
		if (empty($config)){
			$config = array(MYSQL_ROOT, MYSQL_USER, MYSQL_PASSWD, MYSQL_DB);
		}
        // grab connection parameters
		list($host, $user, $password, $database) = $config;
		if ((!$this->link = mysqli_connect($host, $user, $password, $database)))
		{
			throw new \Exception('Error connecting to MySQL : ' . mysqli_connect_error());
		}
	}

    /**
     * Perform a query
     *
     * @param string $query query string
	 * @return void
     */
    public function query($query)
    {
		# @TODO: Caching
        if (is_string($query) and !empty($query))
        {
            if ((!$this->result = mysqli_query($this->link, $query)))
            {
                throw new \MyException(self::backtrace().
				'Error performing query :<br><b>' . $query . '</b><br>Message : ' . mysqli_error($this->link));
            }else{
                return true;
            }
        }
    }
    /**
     * Perform a select query chainable with where()
     * @param type $query
     */
    public function select($table, $selectWhat = null)
    {
		# @TODO: Caching
        if (is_string($table) and !empty($table))
        {
            $this->queryString = "SELECT ".($selectWhat ?: "*")." FROM $table";
            return $this;
        }
    }
    /**
     * Chain element of select()
     * @param type $query
     */
    public function where($query) {
        if ((!$this->result = mysqli_query($this->link, $this->queryString.(!empty($query) ? " WHERE $query" : ""))))
        {
            throw new \MyException(self::backtrace().
            'Error performing query :<br><b>' . $this->queryString . '</b><br>Message : ' . mysqli_error($this->link));
        }
    }


    private static function backtrace(){
		$backtrace = debug_backtrace();
		echo (isset($backtrace[2]['class']) ? 'Class : '.$backtrace[2]['class'] . ' at line '. (isset($backtrace[2]['line']) ?: 'unknown') : '').
		(isset($backtrace[3]['class']) ? '<br>Class : '.$backtrace[3]['class'] : '');
	}

	/**
     * Fetch a row from result set
     *
     * @return Object
     */
	public function fetch()
	{
        if (!$row = mysqli_fetch_object($this->result))
        {
            mysqli_free_result($this->result);
            return FALSE;
        }
        return $row;
	}
    /**
     * Perform a query and return all results immediately
     *
     * @param string $query query string
     * @return array 
     */
    public function queryAll($query){
        $results = array();
        $this->query($query);

        while($r = $this->fetch()){
            $results[] = $r;
        }
        return $results;
    }

    /**
     * Get last inserted id
     *
     * @return int
     */
    public function getInsertID()
    {
        if ($this->result !== NULL) return mysqli_insert_id($this->link);

        return FALSE;
    }

    /**
     * Perform a count on a query result
     *
     * @return int
     */
    public function countRows()
    {
        if ($this->result !== NULL) return mysqli_num_rows($this->result);

        return 0;
    }

	/**
     * Close the connection
     */
	function __destruct()
	{
		is_resource($this->link) and mysqli_close($this->link);
	}
}
