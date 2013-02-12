<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.modelitem' );

/**
 * zBrochure Template Model
 */
class ZbrochureModelBrokers extends JModelItem{

	var $_brokers 		= null;
	var $_allversions	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.brokers';
	
	/**
	 * @var int id
	 */	
	protected $_id;
	
	/**
	 * @var int id
	 */	
	protected $_active_page;
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id = JRequest::getInt( 'id' );
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current broker data
	 */
	public function getbrokers(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
/*
			$query->select( 'c.*, v.*, l.*' );
			$query->from( '#__zbrochure_broker_versions AS v' );
			$query->join( 'LEFT', '#__zbrochure_broker_logos AS l ON l.broker_id = v.broker_id' );
			$query->join( 'INNER', '#__zbrochure_brokers AS c ON c.broker_current_version = v.broker_version_id' );
			$query->group( 'v.broker_id' );
*/
			
			$query->select( '*, c.broker_id AS cid, u.title as team_name' );
			$query->from( '#__zbrochure_brokers AS c' );
			$query->join( 'LEFT', '#__zbrochure_broker_versions AS v ON v.broker_version_id = c.broker_current_version' );
			$query->join( 'LEFT', '#__zbrochure_broker_logos AS l ON l.broker_id = c.broker_id' );
			$query->join( 'LEFT', '#__usergroups AS u ON u.id = c.team_id' );
			
			if( $active != 13 ){
				$query->where( 'team_id = 0 OR team_id = '.(int)$active );
			}

			$query->group( 'c.broker_id' );
			
			$db->setQuery($query);
			$this->_brokers = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $this->_brokers ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_brokerS_NOT_FOUND') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}

		return $this->_brokers;
		
	}
	
	/**
	 * Method to get current broker data
	 */
	public function deletebroker($id){
		
		$query = "DELETE FROM #__zbrochure_brokers "
		."WHERE broker_id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
		
	}
	
	/**
	 * Method to save the broker
	 */
	public function store( $data ){
	
	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$broker	= $data['broker_id'];
		$row	= $this->getTable();
		
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
		$broker_dir = JPath::clean( JPATH_SITE.DS.'images'.DS.'broker'.DS.$broker.DS.($this->getCount($broker) + 1) );
		if( $_FILES['broker_version_logo_full_color']['name'] ){
			
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
		
			if( !JFolder::exists($broker_dir) ){
				JFolder::create($broker_dir);
				//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
			}		
			
			$ext = end(explode('.', $_FILES['broker_version_logo_full_color']['name']));			
			$newfile = JFile::makeSafe( 'broker_version_logo_full_color.'.$ext );
			
			$src = $_FILES['broker_version_logo_full_color']['tmp_name'];
			$dest = $broker_dir.DS.$newfile;
				  		
	  		if( JFile::upload($src, $dest) ){
	  			
	  			$return = array(
					'status' => '1',
					'name' => $newfile
				);
				  			
	  		}else{
	  			
	  			$error = 'Invalid Upload';
	  			
	  			$return = array(
					'status' => '0',
					'error' => $error
				);
	  		
	  		}
	  		
  		}
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( !$row->broker_version_number ){
			$date								= JFactory::getDate( $row->created, $app->getCfg('offset') );
			$row->broker_version_created		= $date->toMySQL();
			$row->broker_version_number			= $this->getCount($broker) + 1;
		}
		
		
		$tmp_file = JPATH_SITE.DS.'images'.DS.'broker'.DS.'tmp'.DS.$row->broker_version_logo_full_color;
		$new_file = $broker_dir.DS.$row->broker_version_logo_full_color;
		
		if( !JFolder::exists($broker_dir) ){
			JFolder::create($broker_dir);
			//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
		}	
		
		if( JFile::exists($tmp_file) ){
			JFile::move( $tmp_file, $new_file );
		}
		
		//$row->broker_version_logo_full_color = $newfile;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row->broker_version_number;
		
	}
	
	/**
	 * Method to save the broker
	 */
	public function getCount( $cvid ){
	
		$db = JFactory::getDBO();
		
		$query = "SELECT a.* "
		."FROM #__zbrochure_broker_versions AS a "
		."WHERE a.broker_id = ".$cvid
		;
		
		$db->setQuery($query);
		$versions = $db->loadObjectList();
		
		return count($versions);
	
	}
	
	/**
	 * Method to save the broker
	 */
	public function getVersions(){
	
		if( empty($this->_allversions) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_broker_versions AS a "
			."WHERE a.broker_id = ".$this->_id
			;
					
			$this->_db->setQuery( $query );
			$this->_allversions = $this->_db->loadObjectlist();
		
		}
		
		return $this->_allversions;
		
	}
	
}