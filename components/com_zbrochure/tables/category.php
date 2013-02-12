<?php
/**
 * Joomla! 1.7 component zBrochure
 *
 * @version $Id: client.php 2012-01-25 17:42:01 svn $
 * @author Zuno Studios
 * @package Joomla
 * @subpackage rpackages
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Table class
*
* @package          Joomla
* @subpackage		zbrochure
*/
class TableCategory extends JTable {

	/** @var int */	
    var $cat_id = null;
    /** @var string */
    var $cat_name = null;
    /** @var string */
    var $cat_alias = null;
    /** @var string */
    var $cat_desc = null;
    /** @var int */	
    var $team_id = null;
    /** @var int */	
    var $client_id = null;
    /** @var int */	
    var $ordering = null;
    /** @var int */	
    var $published = null;
    /** @var timestamp */
    var $cat_created = 0;
    /** @var string */
    var $cat_created_by = null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_categories', 'cat_id', $db );
	
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */

	function check() {
	
		if( empty( $this->cat_alias ) ){
		
			$this->cat_alias = $this->cat_name;
		
		}
		
		$this->cat_alias = JFilterOutput::stringURLSafe( $this->cat_alias );
		
		if( trim(str_replace('_', '', $this->cat_alias)) == '' ){
			
			$datenow = JFactory::getDate();
			$this->cat_alias = $datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
			
		}
	
		return true;
	}

}