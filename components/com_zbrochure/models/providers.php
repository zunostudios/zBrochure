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
class ZbrochureModelProviders extends JModelItem{
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.template';
	
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
		
		if( !$this->_active_page = JRequest::getInt( 'p' ) ){
		
			$this->_active_page = 0;
		
		}
		
		/*
		$user = JFactory::getUser();
		if( (!$user->authorise( 'core.edit.state', 'com_zbrochure' )) && (!$user->authorize( 'core.edit', 'com_zbrochure' )) ){
		
			$this->setState( 'filter.published', 1 );
			$this->setState( 'filter.archived', 2 );
		
		}
		*/
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current provider data
	 */
	public function getData(){
		
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
	
		try{
		
			$db		= $this->getDbo();
			$query	= $db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_providers' );
			$query->where( 'team_id = 0 OR team_id = '.(int)$active );
			$query->order( 'provider_id' );
			
			$db->setQuery( $query );
			$this->_data = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $this->_data;
		
	}
	
}