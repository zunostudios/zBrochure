<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure Component Templates Helper
 *
 * @static
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @since		1.0
 */
class ZbrochureHelperTemplates{

	/**
	 * Method to get all the templates
	 */ 
	public function getTemplates( $public=1, $client=null, $pages=0 ){
	
		try{
			
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_templates AS t' );
			
			if( $client ){
			
				$query->join( 'LEFT', '#__zbrochure_templates_rel AS r ON r.tmpl_id = t.tmpl_id' );
				
				if( $client ){
					$query->where( 'r.client_id = '.$client );
				}
				
			}else{
				
				if( $public ){
					
					$query->where( 't.tmpl_is_public = 1' );
				
				}else{
				
					return;
				
				}
				
			}
			
			$query->where( 't.tmpl_published = 1' );
			
			$db->setQuery($query);
			$templates = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $templates ) ){
			
				return;
			
			}
				
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $templates;
		
	}
	
	public function getTemplatePages( $id, $type=0 ){
	
		try{
			
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_template_layouts AS p' );
			$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = p.tmpl_layout_current_version' );
			$query->where( 'p.tmpl_id = '.$id );
			
			if( $type != 0 ){
			
				$query->where( 'p.tmpl_layout_layout = '.$type );
			
			}
			
			$query->order( 'p.tmpl_layout_order ASC' );
			
			$db->setQuery($query);
			$pages = $db->loadObjectList();

			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $pages ) ){
			
				return JText::_('COM_ZBROCHURE_template_layoutS_NOT_FOUND');
			
			}
			
			/*
			foreach( $pages as $page ){
			
				$page->formatted = $this->_buildContentBlocks( json_decode( $page->tmpl_layout_version_content ) );
			
			}
			*/
			
		
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

}