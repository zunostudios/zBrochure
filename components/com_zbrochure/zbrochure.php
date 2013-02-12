<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

//Point all calls for table classes to the site component directory
JTable::addIncludePath(JPATH_COMPONENT . '/tables');

JHTML::stylesheet( 'tmpl-base.css', 'components/com_zbrochure/assets/css/' );
JHTML::stylesheet( 'jquery-ui-1.8.20.custom.css', 'components/com_zbrochure/assets/css/' );

JHTML::script( 'jquery.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery-ui.js', 'components/com_zbrochure/assets/js/' );

JHTML::script( 'jquery.ui.selectmenu.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.fancybox.pack.js', 'components/com_zbrochure/assets/js/' );

JHTML::script( 'zbrochure.stopscroll.js', 'components/com_zbrochure/assets/js/' );

//JHTML::script( 'jquery.tinyscrollbar.min.js', 'components/com_zbrochure/assets/js/' );

$doc = JFactory::getDocument();
$mediamanager_modal_var = '$(document).ready(function(){'.PHP_EOL.'var change_dialog = \'\';'.PHP_EOL.'var change_logo_dialog = \'\';'.PHP_EOL.'});'.PHP_EOL.PHP_EOL;
$doc->addScriptDeclaration( $mediamanager_modal_var );

// Include dependancies
jimport('joomla.application.component.controller');

//require_once JPATH_COMPONENT.'/helpers/ctype.php';
require_once JPATH_COMPONENT.'/helpers/clients.php';
require_once JPATH_COMPONENT.'/helpers/vars.php';
require_once JPATH_COMPONENT.'/helpers/themes.php';
require_once JPATH_COMPONENT.'/helpers/templates.php';
require_once JPATH_COMPONENT.'/helpers/thumbnails.php';
require_once JPATH_COMPONENT.'/helpers/blocks.php';
require_once JPATH_COMPONENT.'/helpers/teams.php';
//require_once JPATH_COMPONENT.'/helpers/pdf.php';

$controller = JController::getInstance('zbrochure');
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();