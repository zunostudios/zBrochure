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
class ZbrochureModelClients extends JModelItem{

	var $_clients 		= null;
	var $_allversions	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.clients';
	
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
	 * Method to get current client data
	 */
	public function getClients(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
/*
			$query->select( 'c.*, v.*, l.*' );
			$query->from( '#__zbrochure_client_versions AS v' );
			$query->join( 'LEFT', '#__zbrochure_client_logos AS l ON l.client_id = v.client_id' );
			$query->join( 'INNER', '#__zbrochure_clients AS c ON c.client_current_version = v.client_version_id' );
			$query->group( 'v.client_id' );
*/
			
			$query->select( '*, c.client_id AS cid, u.title as team_name' );
			$query->from( '#__zbrochure_clients AS c' );
			$query->join( 'LEFT', '#__zbrochure_client_versions AS v ON v.client_version_id = c.client_current_version' );
			$query->join( 'LEFT', '#__zbrochure_client_logos AS l ON l.client_id = c.client_id' );
			$query->join( 'LEFT', '#__usergroups AS u ON u.id = c.team_id' );
			
			if( $active != 13 ){
				$query->where( 'team_id = 0 OR team_id = '.(int)$active );
			}

			$query->group( 'c.client_id' );
			
			$db->setQuery($query);
			$this->_clients = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $this->_clients ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_CLIENTS_NOT_FOUND') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}

		return $this->_clients;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function deleteClient($id){
		
		$query = "DELETE FROM #__zbrochure_clients "
		."WHERE client_id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$client	= $data['client_id'];
		$row	= $this->getTable();
		
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
		$client_dir = JPath::clean( JPATH_SITE.DS.'images'.DS.'client'.DS.$client.DS.($this->getCount($client) + 1) );
		if( $_FILES['client_version_logo_full_color']['name'] ){
			
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
		
			if( !JFolder::exists($client_dir) ){
				JFolder::create($client_dir);
				//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
			}		
			
			$ext = end(explode('.', $_FILES['client_version_logo_full_color']['name']));			
			$newfile = JFile::makeSafe( 'client_version_logo_full_color.'.$ext );
			
			$src = $_FILES['client_version_logo_full_color']['tmp_name'];
			$dest = $client_dir.DS.$newfile;
				  		
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
		
		if( !$row->client_version_number ){
			$date								= JFactory::getDate( $row->created, $app->getCfg('offset') );
			$row->client_version_created		= $date->toMySQL();
			$row->client_version_number			= $this->getCount($client) + 1;
		}
		
		
		$tmp_file = JPATH_SITE.DS.'images'.DS.'client'.DS.'tmp'.DS.$row->client_version_logo_full_color;
		$new_file = $client_dir.DS.$row->client_version_logo_full_color;
		
		if( !JFolder::exists($client_dir) ){
			JFolder::create($client_dir);
			//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
		}	
		
		if( JFile::exists($tmp_file) ){
			JFile::move( $tmp_file, $new_file );
		}
		
		//$row->client_version_logo_full_color = $newfile;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row->client_version_number;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function getCount( $cvid ){
	
		$db = JFactory::getDBO();
		
		$query = "SELECT a.* "
		."FROM #__zbrochure_client_versions AS a "
		."WHERE a.client_id = ".$cvid
		;
		
		$db->setQuery($query);
		$versions = $db->loadObjectList();
		
		return count($versions);
	
	}
	
	/**
	 * Method to save the client
	 */
	public function getVersions(){
	
		if( empty($this->_allversions) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_client_versions AS a "
			."WHERE a.client_id = ".$this->_id
			;
					
			$this->_db->setQuery( $query );
			$this->_allversions = $this->_db->loadObjectlist();
		
		}
		
		return $this->_allversions;
		
	}
	
}