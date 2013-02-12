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
class TableTemplate extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $tmpl_id 					= null;
    /** @var string */
    var $tmpl_name	 				= null;
    /** @var string */
    var $tmpl_thumbnail 			= null;
    /** @var string */
    var $tmpl_preview 				= null;
    /** @var string */
    var $tmpl_default_theme 		= null;
    /** @var string */
    var $tmpl_default_styles 		= null;
    /** @var string */
    var $tmpl_editor_styles	 		= null;
    /** @var string */
    var $tmpl_page_width 			= null;
    /** @var string */
    var $tmpl_page_height 			= null;
    /** @var string */
    var $tmpl_unit_of_measure		= null;
    /** @var string */
    var $tmpl_page_number_format	= null;
    /** @var string */
    var $tmpl_owner_id	 			= null;
    /** @var string */
    var $tmpl_created_by 			= null;
    /** @var string */
    var $tmpl_created 				= null;
    /** @var string */
    var $tmpl_modified_by 			= null;
    /** @var string */
    var $tmpl_modified 				= null;
    /** @var string */
    var $tmpl_is_global 			= null;
    /** @var string */
    var $tmpl_version 				= null;
    /** @var string */
    var $tmpl_published				= null;
    /** @var string */
    var $tmpl_is_public 			= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_templates', 'tmpl_id', $db );
	
	}

}