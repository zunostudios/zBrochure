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
class TableContacts extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $contact_id 	= null;
    /** @var string */
    var $contact_name 	= null;
    /** @var string */
    var $contact_phone 	= null;
    /** @var string */
    var $contact_email 	= null;
    /** @var int */
    var $provider_id 	= null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_providers_contacts', 'contact_id', $db );
	
	}

}