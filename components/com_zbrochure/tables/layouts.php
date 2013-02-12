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
class TableLayouts extends JTable {

	/* @var int Primary Key */
	var $tmpl_layout_id					= null;
	/* @var int */
	var $tmpl_layout_key				= null;
	/* @var int */	
	var $tmpl_id						= null;
	/* @var string */
	var $tmpl_layout_name				= null;
	/* @var string */
	var $tmpl_layout_desc				= null;
	/* @var string */
	var $tmpl_layout_details			= null;
	/* @var int */
	var $tmpl_layout_type				= null;
	/* @var string */
	var $tmpl_layout_thumbnail			= null;
	/* @var int */
	var $tmpl_layout_preview			= null;
	/* @var int */
	var $tmpl_layout_order				= null;
	/* @var int */
	var $tmpl_layout_spread				= null;
	/* @var int */
	var $tmpl_layout_spread_side		= null;
	/* @var int */
	var $tmpl_layout_page_number		= null;
	/* @var int */
	var $tmpl_layout_created_by			= null;
	/* @var datetime */
	var $tmpl_layout_created			= null;
	/* @var int */
	var $tmpl_layout_published			= null;
	/* @var int */
	var $tmpl_layout_version			= null;
	/* @var int */
	var $tmpl_layout_current_version	= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_template_layouts', 'tmpl_layout_id', $db );
	
	}
	
}