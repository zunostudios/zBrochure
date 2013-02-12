<?php
/**
 * Zuno Studios
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
/*
if (!JFactory::getUser()->authorise('core.manage', 'com_content')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
*/

// Include dependencies
jimport('joomla.application.component.controller');

$controller = JController::getInstance('Zbrochure');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();