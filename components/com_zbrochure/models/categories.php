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
 * zBrochure Categories Model
 */
class ZbrochureModelCategories extends JModelItem{

	var $_categories	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.categories';
		
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
		
		parent::__construct();
		
	}
	
	/**
	 * Method to get a published categories
	 */
	public function getCategories(){
	
		$user			= JFactory::getUser();
		$active_team	= $user->get( 'active_team', 0 );
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
					
			$query->select( 'c.*' );
			$query->from( '#__zbrochure_categories AS c' );
			$query->order( 'c.cat_name' );
			
			if( $active_team ){
				$query->where( 'c.team_id = 0 OR c.team_id = '.(int)$active_team );
			}
			
			$db->setQuery($query);
			$this->_categories = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $this->_categories ) ){
			
				return JError::raiseError( 404, JText::_('ZBROCHURE_DB_NOT_FOUND_CATEGORIES') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}

		return $this->_categories;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function deleteCategory($id){
		
		$query = "DELETE FROM #__zbrochure_clients "
		."WHERE client_id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
		
	}
	
	/**
	 * Method to save a category
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
	 * Method to duplicate a category
	 */
	public function duplicate( $id ){
	
	 	$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$row		= $this->getTable('category');
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'c.*' );
			$query->from( '#__zbrochure_categories AS c' );
			$query->where( 'c.cat_id = '.$id );
			
			$this->_db->setQuery( $query );
			$data = $this->_db->loadObject();
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
			
			if( empty( $data ) ){
		
				return JError::raiseError( 404, JText::_('ZBROCHURE_DB_NOT_FOUND_CATEGORY') );
			
			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError( $e );
			
			}

		}		
		
				
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->cat_id = '';
		$row->cat_created_by = '';
		$row->cat_modified_by = '';
		$row->cat_modified = '';
		$row->version = 1;
		
		$row->check();
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );			
			return false;
		
		}
		
		return $row->cat_id;
		
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