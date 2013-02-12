<?php
/**
 * @package		Zuno.Plugin
 * @subpackage	System.zbrochure
 * @copyright	Copyright (C) 2012 Zuno Enterprises, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class plgSystemZbrochure extends JPlugin{
	/**
	 * 
	 */
	public function onAfterRoute(){
	
		$app	= JFactory::getApplication();
		
		if( $app->getName() != 'site' ){
			return true;
		}
		
		$com_params		= JComponentHelper::getParams('com_zbrochure');
		$team			= JRequest::getInt( 'acl_team_id', 0 );
		$user			= JFactory::getUser();
		$groups			= $user->get('groups');
		reset($groups);
			
		if( !isset( $user->admin_groups ) || !isset( $user->teams ) ){
			
			$admin_group	= $com_params->get('admin_group');
			$team_parent	= $com_params->get('team_parent');
			
			$db			= JFactory::getDBO();
			$query		= $db->getQuery(true);
			$query->select( '*' );
			$query->from( '#__usergroups' );
			$db->setQuery($query);
			$groups_detail = $db->loadObjectList( 'id' );
			
			$admins = array();
			$teams = array();
			
			foreach( $groups_detail as $k => $v ){
					
				if( ($k == $admin_group && $groups[$k] ) || ( $v->parent_id == $admin_group && $groups[$k]) ){
				
					$admins[$k] = $groups_detail[$k]->title;
				
				}
				
				if( ( $v->parent_id == $team_parent && $groups[$k]) ){
				
					$teams[$k] = $groups_detail[$k]->title;
				
				}
								
			}
			
			$user->set( 'admin_groups', $admins );
			$user->set( 'teams', $teams );
			
		}
		
		if( !$user->admin_groups && !$user->teams ){
			
			print_r( 'no access' );
			
		}
		
		reset($user->admin_groups);
		reset($user->teams);
		
		if( !isset( $user->active_team ) && count($user->admin_groups) >= 1 ){
			
			$user->set( 'active_team', key( $user->admin_groups ) );
			
		}else if( !isset( $user->active_team ) && count($user->teams) >= 1 ){
			
			$user->set( 'active_team', key($user->teams) );
			
		}
		
		
		if( $team && array_key_exists( $team, $groups ) ){
			
			$user->set( 'active_team', $team );
			
		}
				
		return true;
		
	}

}