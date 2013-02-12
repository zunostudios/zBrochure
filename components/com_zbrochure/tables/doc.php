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
class TableDoc extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $id = null;
    /** @var int */
    var $doc_bro_id = null;
    /** @var int */
    var $doc_client_id = null;
    /** @var int */
    var $doc_tmpl_id = null;
    /** @var int */
    var $doc_theme_id = null;
    /** @var string */
    var $doc_filename_id = null;
    /** @var datetime */
    var $doc_created = null;
    /** @var string */
    var $doc_created_by = null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_brochure_docs', 'id', $db );
	
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