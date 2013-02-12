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
class TablePlan extends JTable {

	/* @var int Primary Key */
	var $plan_id			= null;
	/* @var int */
	var $plan_parent		= null;
	/* @var string */
	var $plan_name			= null;
	/* @var string */
	var $plan_details		= null;
	/* @var string */
	var $plan_subplan		= null;
	/* @var int */
	var $package_id			= null;
	/* @var int */
	var $brochure_id		= null;
	/* @var int */
	var $plan_created		= null;
	/* @var int */
	var $plan_created_by	= null;
	/* @var int */
	var $plan_modified		= null;
	/* @var int */
	var $plan_modified_by	= null;
	/* @var int */
	var $plan_ordering		= null;
	/* @var int */
	var $version			= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_package_plans', 'plan_id', $db );
	
	}
	
}