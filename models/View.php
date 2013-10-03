<?php
namespace models;

class View {

    public $wildcards = array('clear' => '<!-- end --><!--[if lte IE 7]><br clear="both"><br clear="both"><![endif]-->');

    /**
     * Returns a new view object for the given view.
     *
     * @param string $fileView the view file to load
     */
    public function __construct($fileView)
    {
        $this->fileView = SP . "/views/" . $fileView . ".php";
    }

    /**
     * Set an array of values
     *
     * @param array $array array of values
     */
    public function set($array)
    {
        foreach($array as $k => $v) $this->$k = $v;
    }
	/**
	 * Partials
	 * Usage : {include="test"} in a view template will look for /views/test.php
	 * Put the results into a buffer then replace the tag inside the template
	 *
	 * @param string $fileBody layout file html
	 * @param array $includes array of matched {includes=""}'s
	 * @return string
	 */
	public function includePartials($fileBody, $includes){
			$limit = count($includes[1]);
			$i = 0;
			foreach ($includes[1] as $include){
				$filename = SP."views/". $include . ".php";

				if (!file_exists($filename)) {
				    echo "<hr>File : $filename doesn't exist.<hr>";
					continue;
				}
				// Get partials contents
				ob_start();
				include_once($filename);
				$partial = ob_get_clean();

				// Replace the {include} tag with partials contents
				$fileBody = str_replace('{include="'.$include.'"}', $partial, $fileBody);
				$i++; if ($i > $limit/2) break;
			}
			return $fileBody;
	}

    public function includeWildcards($fileBody, array $wildcards = array()){
    	foreach ($wildcards as $key => $val){
        	$fileBody = str_replace('{'.$key.'}', $val, $fileBody);
        }
    	return $fileBody;
    }

    /**
     * Return the view's HTML
     *
     * @return string
     */
    public function returnString()
    {
        ob_start();
        extract((array) $this);

        // cannot throw exceptions in toString
        try {
            require $fileView; //comes from the extract, same as $this->fileView
        } catch (Exception $e) {
            exit("Exception in the included template [$fileView] : ".$e->getMessage());
        }

        $fileBody = ob_get_clean();

        // Partials
        if (preg_match_all('#\{include="([\w]+)"\}#', $fileBody, $includes)):
            $fileBody = $this->includePartials($fileBody, $includes);
        endif;

        $fileBody = $this->includeWildcards($fileBody, $this->wildcards);
        return $fileBody;
    }

	/**
     * Send a HTTP header redirect using "location" or "refresh".
     *
     * @param string $uri the URI string
     * @param int $status the HTTP status code
     * @param string $method either location or redirect
     */
    public static function redirect($uri = '', $status = 302, $method = 'location')
    {
		$_SESSION['Referer'] = $_SERVER['REQUEST_URI'];
        header($method == 'refresh' ? "Refresh:0;url=$uri" : "Location: $uri", true, $status);
        exit;
    }
}

?>
