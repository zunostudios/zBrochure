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
class TableTheme extends JTable {

	/* @var int Primary Key */
	var $theme_id				= null;
	/* @var string */
	var $theme_name				= null;
	/* @var string */
	var $theme_data				= null;
	/* @var string */
	var $theme_is_public		= null;
	/* @var string */
	var $team_id				= null;
	/* @var timestamp */
	var $theme_created			= null;
	/* @var int */
	var $theme_created_by		= null;
	/* @var datetime */
	var $theme_modified			= null;
	/* @var int */
	var $theme_modified_by		= null;
	/* @var int */
	var $theme_published		= null;
	/* @var int */
	var $version				= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_themes', 'theme_id', $db );
	
	}
	
}