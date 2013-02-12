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
* @package          Zuno
* @subpackage		Brochure Page
*/
class TablePage extends JTable {

	/* @var int Primary Key */
	var $bro_page_id				= null;
	/* @var int */
	var $bro_id						= null;
	/* @var string */
	var $bro_page_name				= null;
	/* @var int */
	var $bro_page_layout			= null;
	/* @var string */
	var $bro_page_thumb				= null;
	/* @var int */
	var $bro_page_order				= null;
	/* @var int */
	var $bro_page_spread			= null;
	/* @var int */
	var $bro_page_spread_side		= null;
	/* @var int */
	var $bro_page_show_page_number	= null;
	/* @var int */
	var $bro_page_created_by		= null;
	/* @var datetime */
	var $bro_page_created			= null;
	/* @var int */
	var $bro_page_modified_by		= null;
	/* @var datetime */
	var $bro_page_modified			= null;
	/* @var int */
	var $bro_page_published			= null;
	/* @var int */
	var $bro_page_version			= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_brochure_pages', 'bro_page_id', $db );
	
	}
	
}