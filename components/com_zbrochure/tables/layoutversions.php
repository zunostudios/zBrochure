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
class TableLayoutversions extends JTable {

	/* @var int Primary Key */
	var $tmpl_layout_version_id			= null;
	/* @var text */
	var $tmpl_layout_version_content	= null;
	/* @var int */	
	var $tmpl_layout_version_images		= null;
	/* @var string */
	var $tmpl_layout_version_design		= null;
	/* @var string */
	var $tmpl_layout_version_styles		= null;
	/* @var string */
	var $tmpl_layout_version_created	= null;
	/* @var int */
	var $tmpl_layout_version_created_by	= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_template_layout_versions', 'tmpl_layout_version_id', $db );
	
	}
	
}