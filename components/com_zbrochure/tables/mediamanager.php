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
class TableMediamanager extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $assetid = null;
    /** @var varchar */
    var $asset_title = null;
    /** @var varchar */
    var $asset_file = null;
    /** @var datetime */
    var $created = null;
    /** @var int */
    var $created_by = null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_assets', 'assetid', $db );
	
	}

}