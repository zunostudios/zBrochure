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
class ZbrochureHelperThemes{

	/**
	 * Method to get all the themes
	 */ 
	public function getThemes( $public=1, $client=null, $tmpl=null, $form=null, $active=null ){
	
		try{
			
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( 't.*' );
			$query->from( '#__zbrochure_themes AS t' );
			
			if( $client || $tmpl ){
			
				$query->join( 'LEFT', '#__zbrochure_themes_rel AS r ON r.theme_id = t.theme_id' );
				
				if( $client ){
					$query->where( 'r.client_id = '.$client );
				}
				
				if( $tmpl ){
					$query->where( 'r.tmpl_id = '.$tmpl );
				}
				
				//$query->where( 'r.client_id = '.$client );
				
			}else{
				
				if( $public ){
					
					$query->where( 't.theme_is_public = 1' );
				
				}else{
				
					return;
				
				}
				
			}
			
			$query->where( 't.theme_published = 1' );
			
			$db->setQuery($query);
			$themes = $db->loadObjectList();
			
			$active_match = false;
			
			if( $active ){
			
				foreach( $themes as $theme ){
				
					if( $theme->theme_id == $active ){
					
						$active_match = true;
					
					}
				
				}
			
			}
			
			if( $active_match == false ){
			
				$active = null;
			
			}
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $themes ) ){
				
				return '<ul class="carousel"><li class="no-theme">'.JText::_( 'NO_CLIENT_THEMES' ).'</li></ul>';
			
			}
			
			$themes = ZbrochureHelperThemes::_buildThemes( $themes, $form, $active );
			
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $themes;
		
	}
	
	/**
	 * Method to get a specific theme
	 */
	public function getTheme( $id ){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_themes AS t' );
			$query->where( 't.theme_id = '.$id );
			
			$db->setQuery($query);
			$theme = $db->loadObject();
			
			
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $theme;
	
	}
	
	
	private function _buildThemes( $themes, $form, $active ){
		
		$output = '<ul class="carousel">';
		
		foreach( $themes as $theme ){
			
			$colors = json_decode( $theme->theme_data );
			
			$output .= '<li class="theme" zid="'.$theme->theme_id.'">';
			
			if( $theme->theme_id == $active ){
				
				$output .= '<div class="theme-container selected">';
			
			}else{
			
				$output .= '<div class="theme-container">';
			
			}
			
			foreach( $colors->color as $color ){
					
				$output .= '<div class="theme-element" style="background-color:rgb('.$color.')"><!-- --></div>';
			
			}
			
			$output .= '<div class="clear"><!-- --></div>';
			
			$output .= '</div>';
			
			if( $form ){
			
				$output .= '<label><input type="radio" name="bro_theme" value="'.$theme->theme_id.'" />&nbsp;'.$theme->theme_name.'</label>';
				
			}else{
			
				$output .= '<div class="theme-title">'.$theme->theme_name.'</div>';
			
			}
			
			$output .= '</li>';
			
		}
		
		$output .= '</ul>';
		
		return $output;
	
	}

}