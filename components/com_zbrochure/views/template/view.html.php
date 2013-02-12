<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
JHTML::script( 'ckeditor.js', 'components/com_zbrochure/assets/js/ckeditor/' );

/**
 * HTML View class for Brochure Editing
 */
class ZbrochureViewTemplate extends JView{
		
	public function display( $tpl = null ){
		
		JHTML::script( 'zbrochure.brochure.edit.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.plan.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.svg.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'jquery.carousel.min.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'jquery.scrollTo.min.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'jquery.jscroll.min.js', 'components/com_zbrochure/assets/js/' );
		JHTML::stylesheet( 'tmpl-base.css', 'components/com_zbrochure/assets/css/' );
		
		$app				= JFactory::getApplication();
		$doc				= JFactory::getDocument();
		$dispatcher			= JDispatcher::getInstance();
		$layout				= JRequest::getVar( 'layout' );		
		$model				= $this->getModel( 'template' );
		
		$this->_com_params		= JComponentHelper::getParams( 'com_zbrochure' );
		
		$this->user			= JFactory::getUser();
				
				
		//Template data
		$this->tmpl				= $this->get( 'tmpl' );
		$this->pages			= $model->getPages( 0 );
		
		$tmpl_path				= JPATH_SITE.'/media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/';
		
		if( JFile::exists( $tmpl_path . 'default_styles.css' ) ){
			
			JHTML::stylesheet( 'default_styles.css', 'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/' );
			
		}
		
		if( JFile::exists( $tmpl_path . 'editor_styles.css' ) ){
			
			JHTML::stylesheet( 'editor_styles.css', 'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/' );
			
		}
		
		//Theme data
		$this->theme			= $model->getTheme( $this->tmpl->tmpl_default_theme );
		$this->themes			= ZbrochureHelperThemes::getThemes( 1, null, null, 0, $this->tmpl->tmpl_default_theme );
					
		$theme_path				= JPATH_SITE.'/media/zbrochure/themes/'.$this->theme->theme_id.'/';
		
		if( JFile::exists( $theme_path . 'theme_styles.css' ) ){
			
			JHTML::stylesheet( 'theme_styles.css', 'media/zbrochure/themes/'.$this->theme->theme_id.'/' );
			
		}

					
		//Build Package Select list
		if( isset($packages) ){
			$pack[] = JHTML::_('select.option', '0', JText::_( '-- Select a Package --' ) );
			foreach( $packages as $k => $v ) : $pack[] = JHTML::_( 'select.option', $v->package_id, JText::_( $v->package_name ) ); endforeach;
			$packages = JHTML::_('select.genericlist', $pack, 'packages[]', 'onchange="getPackages(this.value)"', 'value', 'text', '' );
		}
		
		$unit[] = JHTML::_( 'select.option', 'in', 'inches' );
		$unit[] = JHTML::_( 'select.option', 'px', 'pixels' );
		
		$this->units	= JHTML::_('select.genericlist', $unit, 'tmpl_unit_of_measure', '', 'value', 'text', $this->tmpl->tmpl_unit_of_measure );
		
		//Build published select list
		$options = array();
		$options[] = JHTML::_( 'select.option', '0', 'Unpublished' );
		$options[] = JHTML::_( 'select.option', '1', 'Published' );
		$options[] = JHTML::_( 'select.option', '2', 'Archived' );
		
		$this->published	= JHTML::_( 'select.genericlist', $options, 'tmpl_published', 'class="inputbox" style="width:212px"', 'value', 'text', $this->tmpl->tmpl_published );
		
		$mediamanager_modal = '$(document).ready(function(){
		
			change_dialog = $( \'<div style="display:none" class="loading" title="Media Manager"><iframe id="mediamanager-modal" width="100%" height="100%" frameBorder="0" src="#">No iframe support</iframe></div>\').appendTo(\'body\');
		
			change_dialog.dialog({
		
				autoOpen:false,
				width: 960,
				height: 480,
				modal: true,
				close: function( event, ui ){
					$(\'#mediamanager-modal\').attr( \'src\', \'\' );
				}
			
			});
			
		});';
		
		$logomanager_modal = '$(document).ready(function(){
		
			change_logo_dialog = $( \'<div style="display:none" class="loading" title="Logo Manager"><iframe id="logomanager-modal" width="100%" height="100%" frameBorder="0" src="#">No iframe support</iframe></div>\').appendTo(\'body\');
		
			change_logo_dialog.dialog({
		
				autoOpen:false,
				width: 960,
				height: 400,
				modal: true,
				close: function( event, ui ){
					$(\'#logomanager-modal\').attr( \'src\', \'\' );
				}
			
			});
			
		});';
		
		$doc->addScriptDeclaration( $mediamanager_modal );
		$doc->addScriptDeclaration( $logomanager_modal );
		
		/*
		JPluginHelper::importPlugin('zbrochure');
		$results = $dispatcher->trigger( 'onContentPrepare', array( 'com_zbrochure.template', $this->pages, '' ) );
		*/
		
		$this->left_modules			= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class		= ( $this->left_modules ) ? 'left-middle' : 'middle';
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'templates' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'template', true );
		
		parent::display($tpl);

	}
	
	//Method to find a menu item so we can get an ItemID and keep our menu items
	//and component-internal dynamically generated views consistent
	private function _getMenuItemId( $option, $view, $override=false ){
		
		$menus				= JSite::getMenu();
		$active_menu_item	= $menus->getActive();
		$Itemid				= JRequest::getInt( 'Itemid', 0 );
		
		//Let's find a menu item so we can get an ItemID and keep our menu items and dynamically
		//generated views consistent
		if( $override || !isset( $active_menu_item ) ){
		
			$new_Itemid	= $menus->getItems( 'link', 'index.php?option='.$option.'&view='.$view );	
						
			if( isset( $new_Itemid[0] ) ){
				
				$Itemid = $new_Itemid[0]->id;
				
			}
		
		}
						
		return $Itemid;
		
	}
	
	//Method to put our own module position inside the component view
	//this pulls in all modules assigned to the $position position
	private function _getModulesFor( $position, $style ){
		
		$doc		= JFactory::getDocument();
		$renderer	= $doc->loadRenderer( 'modules' );
		$raw		= array( 'style' => $style );
		
		return $renderer->render( $position, $raw, null );
		
	}
		
}