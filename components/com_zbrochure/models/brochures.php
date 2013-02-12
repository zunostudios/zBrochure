<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

//JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zbrochure'.DS.'tables' );
jimport( 'joomla.application.component.modelitem' );

/**
 * zBrochure Brochures Model
 */
class ZbrochureModelBrochures extends JModelItem{

	var $_orderby = null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.brochures';
	
	/**
	 * @var object templates
	 */	
	protected $_brochures;
	
	/**
	 * @var object templates
	 */	
	protected $_teams;
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){

		parent::__construct();
		
	}
	
	
	/**
	 * Get the brochures
	 * @return object of the templates
	 */
	public function getBrochures(){
	
		$app = JFactory::getApplication();
		$orderby = $app->getUserState( "com_zbrochure_brochures_filter_order", 'b.bro_id' );
		
		$user = JFactory::getUser();

		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'b.*' );
			$query->from( '#__zbrochure_brochures AS b' );
			$query->where( 'b.bro_published = 1' );
			
			if( $orderby == 'search' ){
			
				$searchterm = $app->getUserState( "com_zbrochure_brochures_filter_searchterm" );
				$query->where( 'b.bro_title LIKE "%'.$searchterm['search'].'%"' );
			
			}
			
			if( array_key_exists( $user->active_team, $user->teams ) ){
				
				$query->where( 'b.team_id = '.$user->active_team );
				
			}
			
			if( $orderby != 'search' ){
				$query->order( $orderby.' ASC' );
			}
			
			$this->_db->setQuery($query);
			$this->_brochures = $this->_db->loadObjectList();

			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
			
			foreach( $this->_brochures as $bro ){
				
				$bro->pages	= $this->_getPages( $bro->bro_id );
				
			}
								
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $this->_brochures;
	
	}
	
	private function _getPages( $bro_id ){
		
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'p.*' );
			$query->from( '#__zbrochure_brochure_pages AS p' );
			$query->where( 'p.bro_id = '.$bro_id );
			$query->where( 'p.bro_page_published = 1' );
			$query->order( 'p.bro_page_order' );
			
			$this->_db->setQuery($query);
			$pages = $this->_db->loadObjectList();

			if( $error = $this->_db->getErrorMsg() ){

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
				
		return $pages;
		
	}
	
	/**
	 * Get the brochures
	 * @return object of the templates
	 */
	public function getClients(){
		
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'c.*, v.*, l.*' );
			$query->from( '#__zbrochure_clients AS c' );
			$query->join( 'LEFT', '#__zbrochure_client_versions AS v ON v.client_id = c.client_id AND v.client_version_id = c.client_current_version' );
			$query->join( 'LEFT', '#__zbrochure_client_logos AS l ON l.client_id = c.client_id' );
			$query->order( 'v.client_version_name ASC' );		
			
			$this->_db->setQuery($query);
			$this->_clients = $this->_db->loadObjectList();

			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $this->_clients ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_NO_BROCHURES') );
			
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
	 * Get the brochures
	 * @return object of the templates
	 */
	public function getTeams(){
		
		$com_params		= JComponentHelper::getParams('com_zbrochure');
		
		try{
			
			$query			= $this->_db->getQuery(true);
			$team_parent	= $com_params->get('team_parent');
			
			$query->select( '*' );
			$query->from( '#__usergroups' );
			$query->where( 'parent_id = '.$team_parent );
			
			$this->_db->setQuery($query);
			$this->_teams = $this->_db->loadObjectList( 'id' );

			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $this->_teams ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_NO_BROCHURES') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $this->_teams;
	
	}
	
}