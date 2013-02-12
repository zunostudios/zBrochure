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
class ZbrochureModelPackages extends JModelItem{
	
	var $_packages 		= null;
	var $_categories	= null;
	var $_state			= null;
	var $_brochure		= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.packages';
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
		
		$this->_state		= JRequest::getInt( 'state', 1 );
		$this->_brochure	= JRequest::getInt( 'brochure', 0 );
					
		parent::__construct();
		
	}
	
	/**
	 * Method to get all packages
	 */
	public function getPackages( $cat_id=0, $brochure=0 ){
		
		$user	= JFactory::getUser();
		$active = (int)$user->get( 'active_team' );
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_packages' );
			
			$query->where( 'brochure_id = ' . $this->_brochure );
			$query->where( 'published = ' . $this->_state );
						
			$this->_db->setQuery( $query );
			$this->_packages = $this->_db->loadObjectList();
						
			if( $cat_id ){
				
				foreach( $this->_packages as $package ){
										
					if( $package->package_cat == $cat_id ){
						
						$packages[$package->package_id] = $package;
						
					}
					
				}	
				
			}else{
				
				$packages = $this->_packages;
				
			}
			
			
			
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
		
		return $packages;
		
	}
	
	/**
	 * Method to get all packages
	 */
	public function getPackagesForSelect( $cat_id=0, $brochure=0 ){
		
		$user	= JFactory::getUser();
		$active = (int)$user->get( 'active_team' );
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_packages' );
			$query->where( 'published = 1' );
						
			$this->_db->setQuery( $query );
			$this->_packages = $this->_db->loadObjectList();
						
			if( $cat_id ){
				
				foreach( $this->_packages as $package ){
										
					if( $package->package_cat == $cat_id ){
						
						$packages[$package->package_id] = $package;
						
					}
					
				}	
				
			}else{
				
				$packages = $this->_packages;
				
			}
			
			
			
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
		
		return $packages;
		
	}
	
	
	public function packagesSelects( $cat_id, $block=0, $brochure=0, $active=0, $bro_page_id=0 ){
				
		$packages	= $this->getPackagesForSelect( $cat_id, $brochure );
				
		$output		= '';
		
		if( $packages ){
			
			$predefined			= '';
			$brochure_specific	= '';
			
			foreach( $packages as $k => $v ){
				
				$selected = ( $active == $k ) ? ' selected="selected"': '';
				
				if( $v->brochure_id == 0 ){
				
					$predefined			.= '<option value="'.$k.'"'.$selected.'>'.$v->package_name.' ('.$k.')</option>'.PHP_EOL;
				
				}else if( $v->brochure_id == $brochure ){
					
					$brochure_specific	.= '<option value="'.$k.'"'.$selected.'>'.$v->package_name.' ('.$k.')</option>'.PHP_EOL;
					
				}
				
			}
			
			$output .= '<div class="package-select-vertical">'.PHP_EOL;
			
			$output .= '<label for="predefined-packages-'.$block.'">'.JText::_( 'PACKAGES_SELECT' ).'</label>'.PHP_EOL;
			$output .= '<select id="predefined-packages-'.$block.'" name="package_id" onchange="setPackage( this.value, '.$brochure.', '.$block.', '.$bro_page_id.' )">'.PHP_EOL;
			
			$output .= '<option value="0">-- Select a Package --</option>'.PHP_EOL;
			
			$output .= '<optgroup label="'.JText::_( 'PREDEFINED_PACKAGES' ).'">'.PHP_EOL;
			$output .= $predefined;
			$output .= '</optgroup>'.PHP_EOL;
			
			$output .= '<optgroup label="'.JText::_( 'BROCHURE_SPECIFIC_PACKAGES' ).'">'.PHP_EOL;
			$output .= $brochure_specific;
			$output .= '</optgroup>'.PHP_EOL;
		
			$output .= '</select>'.PHP_EOL;
			$output .= '<div class="clear"><!-- --></div>'.PHP_EOL;
			
			$output .= '</div>'.PHP_EOL;
			
		}else{
			
			$output = 0;
			
		}
		
		return $output;
		
	}
	
	public function packagesSelectOptions( $cat_id ){
		
		$packages	= $this->getPackages( $cat_id );
		$output		= '';
		
		if( $packages ){
			
			$output .= '<option value="0">-- Select a Package --</option>'.PHP_EOL;
			
			foreach( $packages as $k => $v ){
				
				$output .= '<option value="'.$k.'">'.$v->package_name.'</option>'.PHP_EOL;
				
			}
		
		}else{
			
			$output = 0;
			
		}
		
		return $output;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getCategories(){
	
		if( empty($this->_categories) ){
		
			$query = "SELECT c.* "
			."FROM #__zbrochure_categories AS c "
			;
					
			$this->_db->setQuery( $query );
			$this->_categories = $this->_db->loadAssocList( 'cat_id' );
		
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
		
		$db = JFactory::getDBO();
		$query = "INSERT INTO #__zbrochure_categories VALUES ( '0', '".$cat."', '1', '".$date."', '0' )";	
    	$db->setQuery($query);
    	$db->query();
    	
    	$newid = $db->insertid();
		
		return $newid;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
	 	$app	= JFactory::getApplication();
	 	$user	= JFactory::getUser();
		$id		= $data['package_id'];
		$row	= $this->getTable();

	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->package_id = $id;
		$row->package_details = json_encode($data['details']);
		$row->package_cat = json_encode($data['package_categories']);
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row->package_id;
		
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
	
	/*
	 * Method to initialize the Referral Form data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * 
	 */
	 function _initData(){
	 
	 	if( empty($this->_packages) ){
	 	
	 		$package									= new stdClass();
			$package->package_id 						= null;
			$package->package_details					= null;
			
	 		$this->_packages								= $package;
	 		
	 		return (boolean) $this->_packages;
	 	
	 	}
	 	
	 	return true;
	 
	}
	
}