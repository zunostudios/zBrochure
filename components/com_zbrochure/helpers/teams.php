<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure Component Teams Helper
 *
 * @static
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @since		1.0
 */
class ZbrochureHelperTeams{

	/**
	 * Method to get all the teams
	 * Need to filter this by app owner
	 */
	public function getTeams( $active=null, $render='json' ){
	
		$user	= JFactory::getUser();
		//$active = $user->get( 'active_team' );
		
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		
		try{
			
			$db		= JFactory::getDbo();
			$query	= $db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__usergroups' );
			$query->where( 'parent_id = '.$this->_com_params->get('team_parent') );
			
			$db->setQuery( $query );
			$teams = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}
			
			if( empty( $teams ) ){
			
				return JError::raiseError( 404, JText::_( 'COM_ZBROCHURE_TEAMS_NOT_FOUND' ) );
			
			}
			
			switch( $render ){
			
				case 'genericlist':
					$teams = ZbrochureHelperTeams::_buildGenericList( $active, $teams );
				break;
				
				case 'unorderedlist':
					$teams = $teams;
			
			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $teams;	
	
	}
	
	private function _buildGenericList( $active=null, $teams ){
		
		$options[]		= JHTML::_('select.option', '', JText::_( '-- No Team --' ) );
		
		foreach( $teams as $team ){
	
			$options[] = JHTML::_( 'select.option', $team->id, JText::_( $team->title ) );
	
		}

		return JHTML::_('select.genericlist', $options, 'team_id', 'class="inputbox"', 'value', 'text', $active );
	
	}
	
	private function _buildUnorderedList( $active=null, $teams ){
	
	
	
	}

}