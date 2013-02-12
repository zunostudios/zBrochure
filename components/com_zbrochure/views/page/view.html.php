<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for Brochure Editing
 */
class ZbrochureViewPage extends JView{
		
	public function display( $tpl = null ){
	
		JHTML::stylesheet( 'tmpl-base.css', 'components/com_zbrochure/assets/css/' );
		
		$app		= JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		
		require_once(JPATH_COMPONENT.DS.'models'.DS.'brochure.php');
		$model = new ZbrochureModelBrochure;
		
		$this->user	= JFactory::getUser();
					
		//Brochure data
		$this->bro				= $model->getBro();
		$this->page				= $model->getSpecificPage( JRequest::getInt( 'pid' ) );
					
		//Template data
		$this->tmpl				= $model->getTemplate();
		$this->tmpl_layouts		= $model->getTemplateLayouts();
		$this->tmpls			= ZbrochureHelperTemplates::getTemplates();
		
		//Theme data
		$this->theme			= $model->getTheme( $this->bro->bro_theme );
		
		//Content Variables
		$this->vars				= ZbrochureHelperVars::getContentVars();
		$this->vars_bound		= ZbrochureHelperVars::bindVarData( $this->vars, $this->bro, $this->tmpl );
		$this->vars_list		= ZbrochureHelperVars::buildVarList( $this->vars );
		
		JPluginHelper::importPlugin('zbrochure');
		$results = $dispatcher->trigger( 'onContentPrepare', array( 'com_zbrochure.brochure', $this->page	, $this->vars_bound ) );
		
		parent::display($tpl);
		
	}
		
}