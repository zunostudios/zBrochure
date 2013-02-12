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
class TableBrochure extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $bro_id 				= null;
    /** @var string */
    var $bro_title	 			= null;
    /** @var string */
    var $bro_owner_id 			= null;
    /** @var string */
    var $bro_client 			= null;
    /** @var string */
    var $bro_tmpl 				= null;
    /** @var string */
    var $bro_pages 				= null;
    /** @var string */
    var $bro_theme 				= null;
    /** @var string */
    var $bro_variables 			= null;
    /** @var string */
    var $bro_created_by 		= null;
    /** @var string */
    var $bro_created 			= null;
    /** @var string */
    var $bro_modified_by 		= null;
    /** @var string */
    var $bro_modified 			= null;
    /** @var string */
    var $bro_current_version 	= null;
    /** @var string */
    var $bro_published			= null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_brochures', 'bro_id', $db );
	
	}

}