<?php
/**
 * @package		Zuno.Module
 * @subpackage	mod_zbrochure
 * @copyright	Copyright (C) 2012 Zuno Enterprises, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHTML::stylesheet( 'jquery-ui-1.8.20.custom.css', 'components/com_zbrochure/assets/c/' );
JHTML::stylesheet( 'jquery.ui.selectmenu.css', 'components/com_zbrochure/assets/c/' );

JHTML::script( 'jquery.js', 'components/com_zbrochure/assets/j/' );
JHTML::script( 'jquery-ui.js', 'components/com_zbrochure/assets/j/' );
JHTML::script( 'jquery.ui.selectmenu.js', 'components/com_zbrochure/assets/j/' );

$doc = JFactory::getDocument();

$js = "$(document).ready(function(){
	$('select').selectmenu();
});";

//$doc->addScriptDeclaration( $js );

$user		= JFactory::getUser();
$teams		= $user->get('teams');
$admins		= $user->get('admin_groups');

if( (count($teams) > 1 || count($admins) > 1) || (count($teams) == 1 && count($admins) == 1) ){
	
	foreach( $teams as $k => $v ){
		$options[] = JHTML::_( 'select.option', $k, $v  );
	}
	
	foreach( $admins as $k => $v ){
		$options[] = JHTML::_( 'select.option', $k, $v  );
	}
	
	$list	= JHTML::_('select.genericlist', $options, 'acl_team_id', 'class="inputbox" onchange="document.forms[\'teamswitch\'].submit();"', 'value', 'text', $user->get('active_team') );	
	
}else if( count($teams) == 1 ){
	
	$single = current($teams);
	
}else if( count($admins) == 1 ){
	
	$single = current($admins);
	
}

require JModuleHelper::getLayoutPath( 'mod_zbrochure_acl', $params->get( 'layout', 'default' ) );