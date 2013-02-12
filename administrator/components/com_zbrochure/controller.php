<?php
/**
 * Zuno Studios
 */

// no direct access
defined('_JEXEC') or die;

//jimport('joomla.application.component.controller');

/**
 * zBrochure Component Controller
 *
 * @package		Zuno.Administrator
 * @subpackage	com_zbrochure
 * @version 2.5
 */
class ZbrochureController extends JController{
	
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'dashboard';
	
	/**
	 * Method to display a view.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false){
			
		parent::display();
		
		return $this;

	}

}
