<?php
namespace controllers;

class baseController {
# 	protected $view = null;

	/**
	 * this constructor should be used to define any attributes that'll be used
	 * in the extending/child controllers.
	 */
#	public function __construct() {
#		$parent_name = get_called_class();
#		try {
#			$this->view = new \models\view( $parent_name );
#		} catch { \Exception $e ) {
#			$this->view = null;
#		}
#	}


    public $template = 'layout';

    /**
     * Render the final layout template
     */
    public function display($template = null)
    {
        require './models/lessc.inc.php';
        try {
            \lessc::ccompile('./views/css/style.less', './views/css/style.css');
        } catch (exception $ex) {
            exit('lessc fatal error:<br />'.$ex->getMessage());
        }

        if (METRO) $template = 'metro';

        // Render layout
        headers_sent() OR header('Content-Type: text/html; charset=utf-8');
        $view = new \models\View($template ? $template : $this->template);

        // Set default title
		$whereThisIsCalledFrom = debug_backtrace();
		if (!isset($this->title)) $this->title = 'Rule20 :: Social Networking and Dating website - '. str_replace('controllers\\', '', $whereThisIsCalledFrom[1]['class']);

        // Assign variables
        $view->set((array) $this);
        //print $view; unset($view);
        print $view->returnString();
    }

	public function index(){

    }
}
