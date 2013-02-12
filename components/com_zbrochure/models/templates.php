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
class ZbrochureModelTemplates extends JModelItem{
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.templates';
	
	/**
	 * @var object templates
	 */	
	protected $_templates;
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
					
		parent::__construct();
		
	}
	
	
	/**
	 * Get the templates
	 * @return object of the templates
	 */
	public function getTemplates(){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_templates AS t' );
			$query->where( 't.tmpl_published = 1' );
			
			$db->setQuery($query);
			$this->_templates = $db->loadObjectList();

			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $this->_templates ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_NO_TEMPLATES') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $this->_templates;
	
	}
	
}