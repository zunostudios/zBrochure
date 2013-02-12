<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

JHTML::stylesheet( 'jquery.dataTables.css', 'components/com_zbrochure/assets/css/' );
JHTML::stylesheet( 'fcbkstyle.css', 'components/com_zbrochure/assets/css/' );

JHTML::script( 'ckeditor.js', 'components/com_zbrochure/assets/js/ckeditor/' );
JHTML::script( 'jquery.dataTables.min.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.fcbkcomplete.min.js', 'components/com_zbrochure/assets/js/' );

/**
 * HTML View class for Brochure Editing
 */
class ZbrochureViewContent extends JView {
	
	function display($tpl = null) {
		
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$pathway 	= $app->getPathway();
		$model		= $this->getModel();
		
		$menus		= JSite::getMenu();
		$menu		= $menus->getActive();

		$this->data				= $this->get( 'data' );
		$this->categories		= $this->get( 'categories' );
		$this->keywords			= $this->get( 'keywords' );
		
		$this->vars				= ZbrochureHelperVars::getContentVars();
		$this->vars_bound		= ZbrochureHelperVars::bindVarData( $this->vars );
		$this->vars_list		= ZbrochureHelperVars::buildVarList( $this->vars );
		
		
		//Hardcoded in order to get some styles in the editor
		//Not sure what the solution for this is since the styles are dependant upon a specific template and theme
		$tmpl_path				= JPATH_SITE.'/media/zbrochure/tmpl/1/';
		$theme_path				= JPATH_SITE.'/media/zbrochure/themes/1/';	
		$editor_css				= array();
		
		if( JFile::exists( $tmpl_path . 'editor_styles.css' ) ){
			
			JHTML::stylesheet( 'editor_styles.css', 'media/zbrochure/tmpl/1/' );
			$editor_css[]		= '"'.JURI::base().'media/zbrochure/tmpl/1/editor_styles.css"';

		}
		
		if( JFile::exists( $theme_path . 'theme_styles.css' ) ){
			
			JHTML::stylesheet( 'theme_styles.css', 'media/zbrochure/themes/1/' );
			$editor_css[]		= '"'.JURI::base().'media/zbrochure/themes/1/theme_styles.css"';
			
		}		
		
		$editor_config	= '$(document).ready(function(){';
		$editor_config	.= 'CKEDITOR.config.contentsCss = [ '.implode( ',', $editor_css ).' ]';		
		$editor_config	.= '});';
		
		$this->document->addScriptDeclaration( $editor_config );
		
		//Build Category Select list
		$options[] = JHTML::_('select.option','0',JText::_('-- Select a Category --'));
		foreach($this->categories as $key=>$value) : $options[] = JHTML::_('select.option',$value->cat_id,JText::_($value->cat_name));endforeach;
		$this->categories = JHTML::_('select.genericlist', $options,'catid', 'style="width:100%"','value','text', explode(',', $selected) );
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'content' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'content', true );
		
		$renderer				= $this->document->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
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