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
* @subpackage		Plans
*/
class TableContenttext extends JTable {

	/* @var int Primary Key */
	var $id						= null;
	/* @var text */
	var $data					= null;
	/* @var datetime */
	var $created				= null;
	/* @var int */
	var $created_by				= null;
	/* @var datetime */
	var $modified				= null;
	/* @var int */
	var $modified_by			= null;
	/* @var int */
	var $version				= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_content_text', 'id', $db );
	
	}
	
}