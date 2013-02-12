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
 * zBrochure Plan Model
 */
class ZbrochureModelPlan extends JModelItem{
	
	var $_plan 		= null;
	var $_package	= null;
	var $_packages	= null;
	var $_brochures	= null;
	var $_id		= null;
	var $_pid		= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.plan';
			
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id = JRequest::getInt( 'id' );
		$this->_pid = JRequest::getInt( 'pid' );
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get plan data
	 */
	public function getPlan(){
	
		if( empty($this->_plan) ){
			
			try{
				
				$query	= $this->_db->getQuery(true);
				
				$query->select( 'p.*, u.name AS author_name, u.username AS author_username, e.name AS editor_name, e.username AS editor_username' );
				$query->from( '#__zbrochure_package_plans AS p' );
				$query->join( 'LEFT', '#__users AS u ON u.id = p.plan_created_by' );
				$query->join( 'LEFT', '#__users AS e ON e.id = p.plan_modified_by' );
				$query->where( 'p.plan_id = '.$this->_id );

				$this->_db->setQuery($query);
				$this->_plan = $this->_db->loadObject();
							
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $this->_plan;
		
	}
	
	/**
	 * Method to get package data
	 */
	public function getPackage(){
	
		if( empty($this->_package) ){
			
			try{
				
				$query	= $this->_db->getQuery(true);
				
				$query->select( 'p.*' );
				$query->from( '#__zbrochure_packages AS p' );
				$query->where( 'p.package_id = '.$this->_pid );
				
				$this->_db->setQuery($query);
				$this->_package = $this->_db->loadObject();
							
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $this->_package;
		
	}
	
	/**
	 * Method to get all published packages
	 */
	public function getPackages(){
		
		if( empty($this->_packages) ){
		
			try{
				
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				
				$query->select( 'p.package_id, p.package_name' );
				$query->from( '#__zbrochure_packages AS p' );
				$query->where( 'p.brochure_id = 0' );
				$query->where( 'p.published = 1' );
				
				$db->setQuery($query);
				$this->_packages = $db->loadObjectList();
				
				
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $this->_packages;

	}
	
	/**
	 * Method to get all published packages
	 */
	public function getBrochures( $active=0 ){
		
		if( empty($this->_brochures) ){
		
			try{
				
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				
				$query->select( 'b.bro_id, b.bro_title, b.team_id, t.title AS team_name' );
				$query->from( '#__zbrochure_brochures AS b' );
				$query->join( 'LEFT', '#__usergroups AS t on t.id = b.team_id' );
				$query->where( 'b.bro_published = 1' );
				$query->order( 'team_name' );
				$query->order( 'b.bro_title' );
				
				$db->setQuery($query);
				$this->_brochures = $db->loadObjectList();
				
				
				//this array will hold each individual category
				$set = array();
				
				//create a multi-deminsional array to hold all the countries for each year
				foreach( $this->_brochures as $bro ){
					
					$set[$bro->team_id][] = $bro;
				
				}
				
				$output = '<select name="brochure_id" id="brochure_id" style="width:250px">';
				
				$output .= '<option value="0">'.JText::_( 'NO_BROCHURE' ).'</option>'.PHP_EOL;
				
				foreach( $set as $optgroup => $bros ){
					
					if( !$bros[0]->team_name ){
						
						$team = JText::_( 'TEAM_NOT_ASSIGNED' );
						
					}else{
						
						$team = $bros[0]->team_name;
						
					}
					
					$output .= '<optgroup label="'.$team.'">'.PHP_EOL;
					
					foreach( $bros as $bro ){
						
						$selected = ( $bro->brochure_id == $optgroup ) ? ' selected="selected"': '';
						
						$output .= '<option value="'.$bro->bro_id.'"'.$selected.'>'.$bro->bro_title.' ('.$bro->bro_id.')</option>'.PHP_EOL;
						
					}
					
					$output .= '</optgroup>'.PHP_EOL;
					
				}
				
				$output .= '</select>';
				
				
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $output;

	}
		
	/**
	 * Method to get chosen package
	 */
	public function getPlanTable( $tab=0, $block_id=0, $brochure_id=0, $hidden=0 ){
	
		$package	= $this->getPackage();
		$plan		= $this->getPlan();
		
		require_once JPATH_COMPONENT.'/content/package/plan.php';
		
		$output = ZbrochureContentPlan::renderContent( $package, $plan, $tab, $block_id, $brochure_id, $hidden );
				
		return $output;
	
	}	

	/**
	 * Method to save a plan
	 */
	public function store( $data, $plan ){
	
		jimport( 'joomla.utilities.date' );
	
	 	$app		= JFactory::getApplication();
	 	$user		= JFactory::getUser();
	 	
		//$id			= $plan['plan_id'];
		//$pid		= $data['package_id'];
		
		$row		= $this->getTable();
		
		$newdata	= Array();
		//$newdata['plan_details'] = $data['plan'];
		
		//$data['plan_id']		= $plan['plan_id'];
		//$data['plan_details']	= $plan['plan'];
		//$data['plan_name']		= $plan['plan_name'];
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( !$row->plan_id ){  	
		
			$is_new = 1;
			$row->plan_created_by	= $user->id;
		
		}else{
			
			$date = JFactory::getDate();
			$row->plan_modified_by	= $user->id;
			$row->plan_modified		= $date->toSql();			
			$row->version++;
		
		}
		
		$i = 1;
		
		foreach( $plan['plan'] as $plid => $newrow ){
			
			foreach( $newrow['plan_cell'] as $cell ){
			
				$newdata['plan_details']['package_label_id_'.$plid]['cell'][] = $cell;	
			
			}
			
			$newdata['plan_details']['package_label_id_'.$plid]['package_label_id'] = $plid;
			
			$i++;
			
		}
				
		//$row->plan_id		= $id;
		$row->plan_details	= json_encode( $newdata['plan_details'] );
		$row->plan_subplan	= json_encode( $plan['network'] );
		//$row->package_id	= $pid;
		
		//print_r( $row );
		//exit();
		
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			
			$this->setError($this->_db->getErrorMsg());
			return false;
			
		}
		
		return $row;
		
	}
	
	/**
	 * Method to check if plan needs to be duplicated
	 */
	public function checkPlans( $post, $package_id, $brochure ){
	
		$plans	= $post['plans'];
		
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		
		foreach( $plans as $plan ){
			
			//$id		= $plan['plan_id'];
			
			if( $plan['brochure_id'] != $brochure ){
				
				$data['plan_id']		= 0;
				$data['plan_parent']	= $plan['plan_id'];
				$data['brochure_id']	= $brochure;
			
			}else{
			
				$data['plan_id'] = $plan['plan_id'];
			
			}
			
			$data['plan_name']		= $plan['plan_name'];
			$data['package_id']		= $package_id;
			
			$row	= $this->store( $data, $plan );
						
			$ids[]	= $row->plan_id;
			
		}
			
		return $ids;
	
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
			
			
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'p.*' );
			$query->from( '#__zbrochure_package_plans AS p' );
			$query->where( 'p.package_id = '.$package_parent );
			$query->order( 'p.plan_name' );
			
			$this->_db->setQuery( $query );
			$predefined_plans = $this->_db->loadAssocList( 'plan_id' );
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
			
			
			$predefined			= '';
			$brochure_specific	= '';
			
			foreach( $plans as $k => $v ){
				
				$selected = ( $plan_id == $k ) ? ' selected="selected"': '';
				$brochure_specific	.= '<option value="'.$k.'"'.$selected.'>'.$v['plan_name'].'</option>'.PHP_EOL;
				
			}
			
			foreach( $predefined_plans as $k => $v ){
			
				$predefined	.= '<option value="'.$k.'">'.$v['plan_name'].'</option>'.PHP_EOL;
				
			}
			
			//$output .= '<label for="plans-'.$block_id.'">'.JText::_( 'PLANS_SELECT' ).'</label>'.PHP_EOL;
			$output .= '<select id="plans-'.$block_id.'" class="select-plans" name="data[package]['.$tab.'][plans][]" onchange="loadPlan( this.value, '.$package_id.', '.$block_id.', '.$brochure_id.' )">'.PHP_EOL;
			
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
	 * Method to duplicate a plan
	 */
	public function duplicate( $id, $pid ){
	
	 	$app						= JFactory::getApplication();
	 	$user						= JFactory::getUser();
		$row						= $this->getTable();
				
	 	if( !$row->load( $id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//Let's get rid of the plan id so now it thinks
		//we have a new record and will store it as such i.e. duplicated bitches
		$row->plan_id				= '';
		$row->plan_name				= $row->plan_name.' Copy';
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
		
		$plan = $this->getTable();
		
		if( !$plan->delete( $id ) ){
			
			$this->setError( $this->_db->getErrorMsg() );
			return false;
			
		}
		
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
	 
	 	if( empty($this->_plan) ){
	 	
	 		$plan								= new stdClass();
			$plan->plan_id 						= null;
			$plan->plan_details					= null;
			
	 		$this->_plan						= $plan;
	 		
	 		return (boolean) $this->_plan;
	 	
	 	}
	 	
	 	return true;
	 
	}
	
}