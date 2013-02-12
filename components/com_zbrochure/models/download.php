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
class ZbrochureModelDownload extends JModelItem{
	
	var $_id		= null;
	var $_doc		= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.download';
			
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
		
		parent::__construct();
		
	}
	
	/**
	 * Method to get plan data
	 */
	public function getDoc( $id ){
				
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'd.*' );
			$query->from( '#__zbrochure_brochure_docs AS d' );
			$query->where( 'd.id = '.$id );
	
			$this->_db->setQuery($query);
			$this->_doc = $this->_db->loadObject();
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
			
		
		return $this->_doc;
		
	}
	
}