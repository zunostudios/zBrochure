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
 * zBrochure Package Model
 */
class ZbrochureModelPackage extends JModelItem{
	
	var $_id			= null;
	var $_package		= null;
	var $_plans			= null;
	var $_categories	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.package';
		
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id = JRequest::getInt( 'id', 0 );
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getPackage(){
		
		if( empty($this->_package) ){
			
			try{
			
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'p.*' );
				$query->from( '#__zbrochure_packages AS p' );
				$query->where( 'p.package_id = '.$this->_id );
					
				$this->_db->setQuery( $query );
				$this->_package = $this->_db->loadObject();
				
				if( $error = $this->_db->getErrorMsg() ){
	
					throw new Exception( $error );
	
				}
				
				if( empty( $this->_package ) ){
			
					return JError::raiseError( 404, JText::_('ZBROCHURE_DB_NOT_FOUND_PACKAGE') );
				
				}
							
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError( $e );
				
				}
	
			}
		
		}
		
		return $this->_package;
		
	}
	
	/**
	 * Method to get the plans associated with this package
	 */
	public function getPlans(){
			
		if( empty($this->_plans) ){
			
			try{
			
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'p.*' );
				$query->from( '#__zbrochure_package_plans AS p' );
				$query->where( 'p.package_id = '.$this->_id );
				$query->order( 'p.plan_ordering' );
				
				$this->_db->setQuery( $query );
				$this->_plans = $this->_db->loadObjectList( 'plan_id' );
				
				if( $error = $this->_db->getErrorMsg() ){
	
					throw new Exception( $error );
	
				}
							
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError( $e );
				
				}
	
			}
		
		}
		
		return $this->_plans;
		
	}
		
	/**
	 * Method to get current client data
	 */
	public function getCategories(){
	
		if( empty($this->_categories) ){
		
			$query = "SELECT a.*, p.* "
			."FROM #__zbrochure_categories AS a "
			."LEFT JOIN #__zbrochure_packages AS p on p.package_id = ".$this->_id." "
			."ORDER BY a.cat_name "
			;
					
			$this->_db->setQuery( $query );
			$this->_categories = $this->_db->loadObjectList( 'cat_id' );
		
		}
		
		return $this->_categories;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function saveCategory( $cat ){

	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	 	
		$date = JFactory::getDate();
		$date = $date->toMySQL();
		
		$query = "INSERT INTO #__zbrochure_categories VALUES ( '0', '".$cat."', '1', '".$date."', '.$user->id.' )";	
    	$this->_db->setQuery($query);
    	$this->_db->query();
    	
    	$newid = $this->_db->insertid();
		
		return $newid;
		
	}
	
	/**
	 * Method to save the package
	 */
	public function store( $data ){
	
		jimport( 'joomla.utilities.date' );
	
	 	$app	= JFactory::getApplication();
	 	$user	= JFactory::getUser();
	 
		$id		= $data['package_id'];
		
		$row	= $this->getTable();
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( !$row->package_id ){  	
		
			$is_new = 1;
			$row->package_created_by	= $user->id;
		
		}else{
			
			$date = JFactory::getDate();
			$row->package_modified_by	= $user->id;
			$row->package_modified		= $date->toSql();			
			$row->version++;
		
		}
		
		//$row->package_id = $id;
		$row->package_details = json_encode( $data['details'] );
		//$row->package_cat = json_encode( $data['package_categories'] );
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row;
		
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
	
	/**
	 * Method to delete a package
	 */
	public function delete( $id ){
		
		$package = $this->getTable();
		
		if( !$package->delete( $id ) ){
			
			$this->setError( $this->_db->getErrorMsg() );
			return false;
			
		}
		
		return true;
		
	}
	
	/**
	 * Method to duplicate package
	 */
	public function duplicate( $id, $brochure=0 ){
		
		$app						= JFactory::getApplication();
	 	$user						= JFactory::getUser();
		$row						= $this->getTable();
				
	 	if( !$row->load( $id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		$date = JFactory::getDate();
		
		//Let's get rid of the plan id so now it thinks
		//we have a new record and will store it as such i.e. duplicated bitches
		$row->package_id				= '';
		$row->package_name				= $row->package_name.' ('.JHTML::_('date', $date, 'm-d-Y' ).')';
		$row->package_created_by		= $user->id;
		$row->brochure_id				= $brochure;
		$row->package_parent			= $id;
		$row->package_modified			= '0000-00-00 00:00:00';
		$row->package_modified_by		= 0;
		$row->version					= 1;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row->package_id;
	
	}
	
	/**
	 * Method to duplicate a plan
	 */
	public function duplicatePlan( $id, $pid ){
	
	 	$user						= JFactory::getUser();
		$row						= $this->getTable( 'plan' );
				
	 	if( !$row->load( $id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//Let's get rid of the plan id so now it thinks
		//we have a new record and will store it as such i.e. duplicated bitches
		$row->plan_id				= '';
		$row->plan_name				= $row->plan_name.' Copy';
		$row->package_id			= $package_id;
		$row->plan_created_by		= $user->id;
		$row->plan_created			= '';
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		return $row->plan_id;
		
	}
	
	
	/**
	 * Method to check packages
	 */
	public function checkPackage( $packageid, $brochure ){
	
		
		$query	= $this->_db->getQuery(true);
		
		$query->select( '*' );
		$query->from( '#__zbrochure_packages' );
		$query->where( 'package_id = '.(int)$packageid );
		
		$this->_db->setQuery($query);
		$package = $this->_db->loadObject();
		
		//if the package doesn't have a brochure_id
		//let's duplicate it and assign it a brochure_id
		if( $package->brochure_id != $brochure ){
		
			$pid = $this->duplicate( $packageid, $brochure );
		
		}else{
		
			$pid = $packageid;
		
		}
		
		return $pid;
	
	}
	
	
	
	/**
	 * Method to get list of Packages associated with this brochure
	 */
	public function getBro( $brochure_id ){
	
	
		$query	= $this->_db->getQuery(true);
		$query->select( 'b.*' );
		$query->from( '#__zbrochure_brochures AS b' );
		$query->where( 'b.bro_id = '.$brochure_id );
		
		$this->_db->setQuery($query);
		$bro = $this->_db->loadObject();
		
		return $bro;
	
	}
	
	
	
	/*
	 * Method to initialize package data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * 
	 */
	 function _initData(){
	 
	 	if( empty($this->_package) ){
	 	
	 		$package									= new stdClass();
			$package->package_id 						= null;
			$package->package_details					= null;
			
	 		$this->_package								= $package;
	 		
	 		return (boolean) $this->_package;
	 	
	 	}
	 	
	 	return true;
	 
	}
	
}