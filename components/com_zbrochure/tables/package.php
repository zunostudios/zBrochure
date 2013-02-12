<?php
/**
 * Joomla! 1.7 component zBrochure
 *
 * @version $Id: item.php 2012-02-02 14:47:50 svn $
 * @author Zuno Studios
 * @package Zuno
 * @subpackage Zuno Brochure Generator
 * @license Copyright © 2012 - All Rights Reserved
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Table class
*
* @package          Zuno
* @subpackage		zBrochure
*/
class TablePackage extends JTable {

	/* @var int Primary Key */
	var $package_id				= null;
	/* @var string */
	var $package_name			= null;
	/* @var string */
	var $package_details		= null;
	/* @var string */
	var $package_desc			= null;
	/* @var string */
	var $package_content		= null;
	/* @var string */
	var $package_footer			= null;
	/* @var string */
	var $package_cat			= null;
	/* @var string */
	var $brochure_id			= null;
	/* @var int */
	var $package_parent			= null;
	/* @var int */
	var $team_id				= null;
	/* @var string */
	var $published				= null;
	/* @var datetime */
	var $package_created		= null;
	/* @var int */
	var $package_created_by		= null;
	/* @var datetime */
	var $package_modified		= null;
	/* @var int */
	var $package_modified_by	= null;
	/* @var int */
	var $version				= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_packages', 'package_id', $db );
	
	}
	
}