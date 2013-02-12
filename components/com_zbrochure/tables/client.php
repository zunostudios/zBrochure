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
class TableClient extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $client_version_id = null;
    /** @var int */
    var $client_id = null;
    /** @var string */
    var $client_version_name = null;
    /** @var string */
    var $client_version_name_plural = null;
    /** @var string */
    var $client_version_website = null;
    /** @var string */
    var $client_version_email = null;
    /** @var string */
    var $client_version_email_internal = null;
    /** @var string */
    var $client_version_phone_1 = null;
    /** @var string */
    var $client_version_phone_2 = null;
    /** @var string */
    var $client_version_fax_1 = null;
    /** @var string */
    var $client_version_phone_internal = null;
    /** @var string */
    var $client_version_address = null;
    /** @var string */
    var $client_version_contact_internal = null;
    /** @var int */
    var $client_version_theme = null;
    /** @var datetime */
    var $client_version_created = 0;
    /** @var string */
    var $client_version_created_by = null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_client_versions', 'client_version_id', $db );
	
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