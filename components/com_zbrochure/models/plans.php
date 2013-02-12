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
 * zBrochure Plans Model
 */
class ZbrochureModelPlans extends JModelItem{
	
	var $_plans		= null;
	var $_package	= null;
	var $_pid		= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.plans';
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_pid = JRequest::getInt( 'pid' );
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getPlans(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		try{
	
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'a.*, p.*' );
			$query->from( '#__zbrochure_package_plans AS a' );
			$query->join( 'LEFT', '#__zbrochure_packages AS p ON p.package_id = a.package_id' );
			//$query->where( 'p.brochure_id = 0' );
			$query->order( 'a.plan_id' );
			
			$this->_db->setQuery( $query );
			$this->_plans = $this->_db->loadObjectList();
			
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
		
		return $this->_plans;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getPackage(){
	
		if( empty($this->_package) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_packages AS a "
			."WHERE a.package_id = ".$this->_pid
			;
					
			$this->_db->setQuery( $query );
			$this->_package = $this->_db->loadObject();
		
		}
		
		return $this->_package;
		
	}
	
	/**
	 * Method to get list of Plans
	 */
	public function getPlansByPackage( $package_id=0, $package_parent=0, $plan_id=0, $plan_parent=0, $tab=0, $block_id=0, $brochure_id=0 ){
		
		try{
	
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'p.*' );
			$query->from( '#__zbrochure_package_plans AS p' );
			$query->where( 'p.package_id = '.$package_id );
			$query->order( 'p.plan_name' );
			
			$this->_db->setQuery( $query );
			$plans = $this->_db->loadAssocList( 'plan_id' );
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
					
			$predefined			= '';
			$brochure_specific	= '';
			
			foreach( $plans as $k => $v ){
				
				$selected = ( $plan_id == $k ) ? ' selected="selected"': '';
				
				if( $v['brochure_id'] == 0 ){
				
					$predefined			.= '<option value="'.$k.'"'.$selected.'>'.$v['plan_name'].' ('.$v['plan_id'].')</option>'.PHP_EOL;
				
				}else if( $v['brochure_id'] == $brochure_id ){
					
					$brochure_specific	.= '<option value="'.$k.'"'.$selected.'>'.$v['plan_name'].'</option>'.PHP_EOL;
					
				}
				
			}
			
			$output .= '<label for="plans-'.$block_id.'-'.$tab.'">'.JText::_( 'PLANS_SELECT' ).'</label>'.PHP_EOL;
			$output .= '<select id="plans-'.$block_id.'-'.$tab.'" name="data[package]['.$tab.'][plans][]" onchange="getData( this.value, '.$package_id.', '.$tab.', '.$block_id.', '.$brochure_id.' )">'.PHP_EOL;
			
			$output .= '<option value="0">-- Select a Plan --</option>'.PHP_EOL;
			
			$output .= '<optgroup label="'.JText::_( 'PREDEFINED_PLANS' ).'">'.PHP_EOL;
			$output .= $predefined;
			$output .= '</optgroup>'.PHP_EOL;
			
			$output .= '<optgroup label="'.JText::_( 'BROCHURE_SPECIFIC_PLANS' ).'">'.PHP_EOL;
			$output .= $brochure_specific;
			$output .= '</optgroup>'.PHP_EOL;
			
			$output .= '</select>'.PHP_EOL;
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}

		return $output;
	
	}
	
	/**
	 * Method to get list of Plans
	 */
	public function getParentPlan( $packageid ){
		
		try{
	
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'p.package_parent' );
			$query->from( '#__zbrochure_packages AS p' );
			$query->where( 'p.package_id = '.$packageid );
			
			$this->_db->setQuery( $query );
			$planid = $this->_db->loadObject();
			
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
		
		return $planid;
	
	}
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
	 	$app	= JFactory::getApplication();
	 	$user	= JFactory::getUser();
		$id		= $data['plan_id'];
		$pid	= $data['package_id'];
		$row	= $this->getTable();

	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->plan_id = $id;
		$row->plan_details = json_encode($data['details']);
		$row->pid = $pid;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row->plan_id;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function deletePlan($id){
		
		$query = "DELETE FROM #__zbrochure_package_plans "
		."WHERE plan_id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
		
	}
	
	/*
	 * Method to initialize the Referral Form data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * 
	 */
	 function _initData(){
	 
	 	if( empty($this->_plans) ){
	 	
	 		$plan								= new stdClass();
			$plan->plan_id 						= null;
			$plan->plan_details					= null;
			
	 		$this->_plans						= $plan;
	 		
	 		return (boolean) $this->_plans;
	 	
	 	}
	 	
	 	return true;
	 
	}
	
}