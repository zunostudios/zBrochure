<?php
/**
 * Joomla! 2.5 component zBrochure
 *
 * @version $Id: broker.php
 * @author Zuno Studios
 * @package zBrochure
 * @subpackage brokers
 * @license GNU/GPL
 *
 * Manages Brokers
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Table class
*
* @package          zBrochure
* @subpackage		zbrochure
*/
class TableBroker extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */	
    var $broker_version_id = null;
    /** @var int */
    var $broker_id = null;
    /** @var string */
    var $broker_version_name = null;
    /** @var string */
    var $broker_version_name_plural = null;
    /** @var string */
    var $broker_version_website = null;
    /** @var string */
    var $broker_version_email = null;
    /** @var string */
    var $broker_version_email_internal = null;
    /** @var string */
    var $broker_version_phone_1 = null;
    /** @var string */
    var $broker_version_phone_2 = null;
    /** @var string */
    var $broker_version_fax_1 = null;
    /** @var string */
    var $broker_version_phone_internal = null;
    /** @var string */
    var $broker_version_address = null;
    /** @var string */
    var $broker_version_contact_internal = null;
    /** @var int */
    var $broker_version_theme = null;
    /** @var datetime */
    var $broker_version_created = 0;
    /** @var string */
    var $broker_version_created_by = null;

	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct( $db ){
	
		parent::__construct( '#__zbrochure_broker_versions', 'broker_version_id', $db );
	
	}

}