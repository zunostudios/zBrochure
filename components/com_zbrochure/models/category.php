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
 * zBrochure Category Model
 */
class ZbrochureModelCategory extends JModelItem{

	var $_category		= null;	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.category';
	
	/**
	 * @var int id
	 */	
	protected $_id;
	
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
	public function getCategory(){
		
		if( empty($this->_category) ){
			
			try{
			
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'c.*' );
				$query->from( '#__zbrochure_categories AS c' );
				$query->where( 'c.cat_id = '.$this->_id );
				
				$this->_db->setQuery( $query );
				$this->_category = $this->_db->loadObject();
				
				if( $error = $this->_db->getErrorMsg() ){
	
					throw new Exception( $error );
	
				}
				
				if( empty( $this->_category ) ){
			
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
		
		}
		
		return $this->_category;
		
	}

	/**
	 * Method to save a category
	 */
	public function store( $post ){
	
	 	$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$row		= $this->getTable();
		$com_params	= JComponentHelper::getParams('com_zbrochure');
				
	 	if( !$row->bind( $post ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( !$row->cat_id ){  	
		
			$row->cat_created_by	= $user->id;
		
		}else{
			
			$date = JFactory::getDate();
			$row->cat_modified_by	= $user->id;
			$row->cat_modified		= $date->toSql();			
			$row->version++;
		
		}
		
		$row->check();
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );			
			return false;
		
		}
		
		return $row->cat_id;
		
	}
	
	/**
	 * Method to delete a category
	 */
	public function delete( $id ){
		
		$cat = $this->getTable();
		
		if( !$cat->delete( $id ) ){
			
			$this->setError( $this->_db->getErrorMsg() );
			return false;
			
		}
		
		return true;
		
	}
	
	/*
	 * Method to initialize the app data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * 
	 */
	 function _initData(){
	 
	 	if( empty($this->_category) ){
	 	
	 		$category = new stdClass();
			$category->cat_id = null;
			$category->cat_name = null;
			$category->cat_alias = null;
			$category->published = null;
			$category->cat_created = null;
			$category->cat_created_by = null;
			$category->ordering = null;
			
	 		$this->_category = $category;
	 		return (boolean) $this->_category;
	 	
	 	}
	 	
	 	return true;
	 
	}
	
	
}