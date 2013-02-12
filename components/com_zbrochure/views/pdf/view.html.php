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
class ZbrochureViewPdf extends JView{
		
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
		
		$tmpl_path				= JPATH_SITE.'/media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/';			
		$theme_path				= JPATH_SITE.'/media/zbrochure/themes/'.$this->theme->theme_id.'/';
		$editor_css				= array();
		
		if( JFile::exists( $tmpl_path . 'default_styles.css' ) ){
			
			JHTML::stylesheet( 'default_styles.css', 'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/' );
			
		}
		
		if( JFile::exists( $tmpl_path . 'editor_styles.css' ) ){
			
			JHTML::stylesheet( 'editor_styles.css', 'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/' );
			$editor_css[]		= '"'.JURI::base().'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/editor_styles.css"';

		}
		
		
		
		if( JFile::exists( $theme_path . 'theme_styles.css' ) ){
			
			JHTML::stylesheet( 'theme_styles.css', 'media/zbrochure/themes/'.$this->theme->theme_id.'/' );
			$editor_css[]		= '"'.JURI::base().'media/zbrochure/themes/'.$this->theme->theme_id.'/theme_styles.css"';
			
		}
	
		
		$editor_config	= '$(document).ready(function(){';			
		$editor_config	.= 'CKEDITOR.config.contentsCss = [ '.implode( ',', $editor_css ).' ]';
		$editor_config	.= '});';
		
		$this->document->addScriptDeclaration( $editor_config );
	
		
		JPluginHelper::importPlugin('zbrochure');
		$results = $dispatcher->trigger( 'onContentPrepare', array( 'com_zbrochure.brochure', $this->page	, $this->vars_bound ) );
		
		parent::display($tpl);
		
	}
		
}