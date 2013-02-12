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
class TableTeams extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $id 		= null;
    /** @var string */
    var $parent_id 	= null;
    /** @var string */
    var $lft 		= null;
    /** @var string */
    var $rgt 		= null;
    /** @var int */
    var $title 		= null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__usergroups', 'id', $db );
	
	}

}