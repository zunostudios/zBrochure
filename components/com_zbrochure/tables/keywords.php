<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Table class
*
* @package          Joomla
* @subpackage		zbrochure
*/
class TableKeywords extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $keyword_id = null;
    /** @var varchar */
    var $keyword = null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_assets_keywords', 'keyword_id', $db );
	
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
/*
	function check() {
	
		if( empty( $this->app_alias ) ){
		
			$this->app_alias = $this->app_name;
		
		}
		
		$this->app_alias = JFilterOutput::stringURLSafe( $this->app_alias );
		
		if( trim(str_replace('_', '', $this->app_alias)) == '' ){
			
			$datenow = JFactory::getDate();
			$this->app_alias = $datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
			
		}
	
		return true;
	}
*/

}