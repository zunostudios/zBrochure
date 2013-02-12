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
class TableBlock extends JTable {

	/* @var int Primary Key */
	var $content_block_id				= null;
	/* @var int */
	var $content_bro_id					= null;
	/* @var int */
	var $content_page_id				= null;
	/* @var int */
	var $content_tmpl_id				= null;
	/* @var int */
	var $content_tmpl_block_id			= null;
	/* @var int */
	var $content_block_type				= null;
	/* @var int */
	var $content_block_created			= null;
	/* @var int */
	var $content_block_created_by		= null;
	/* @var int */
	var $content_block_modified			= null;
	/* @var int */
	var $content_block_modified_by		= null;
	/* @var int */
	var $content_block_current_version	= null;
	/* @var int */
	var $content_block_published		= null;
	/* @var int */
	var $content_block_ordering			= null;
	/* @var int */
	var $content_block_desc				= null;
	/* @var int */
	var $content_block_version			= null;
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_content_blocks', 'content_block_id', $db );
	
	}
	
}