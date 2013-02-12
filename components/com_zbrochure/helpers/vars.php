<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure Component Themes Helper
 *
 * @static
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @since		1.0
 */
class ZbrochureHelperVars{
	
	/**
	 * Method to get all the clients
	 * Need to filter this by app owner
	 */
	public function getContentVars(){
	
		try{
			
			$db		= JFactory::getDBO();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_content_vars' );
			$query->where( 'var_published = 1' );
			
			$db->setQuery($query);
			$vars = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $vars ) ){
			
				return JError::raiseError( 404, JText::_( 'COM_ZBROCHURE_VARS_NOT_FOUND' ) );
			
			}
					
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $vars;	
	
	}
	
	public function bindVarData( $vars, $bro=null, $tmpl=null ){
		
		$bound = array();
		
		foreach( $vars as $var_set ){
		
			$var_set->vars = json_decode( $var_set->var_fields, true );
		
		}
		
		foreach( $vars as $var_set ){
			
			foreach( $var_set->vars as $k=>$v ){
				
				if( $bro->$v ){
						
					$var_set->vars[$k] = $bro->$v;
				
				}elseif( $tmpl->$v ){
					
					$var_set->vars[$k] = $tmpl->$v;
					
				}else{
				
					unset( $var_set->vars[$k] );
				
				}
				
			}
			
			$bound = $bound + $var_set->vars;
		
		}
		
		return $bound;
	
	}
	
	public function buildVarList( $vars ){
		
		$output = '<dl>';
		
		foreach( $vars as $var_set ){
		
			$var_set->vars = json_decode( $var_set->var_fields, true );
		
		}
		
		foreach( $vars as $var_set ){
			
			$output .= '<dt>'.$var_set->var_title.'</dt>';
			
			$output .= '<dd><ul>';
			
			foreach( $var_set->vars as $k=>$v ){
											
				$output .= '<li>{'.$k.'}</li>';
		
			}
			
			$output .= '</ul></dd>';
		
		}
		
		$output .= '</dl>';
		
		return $output;
	
	}

}