<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for Package Editing
 */
class ZbrochureViewPackage extends JView {
	
	function display( $tpl = null ){
		
		//Load the js for the editor
		JHTML::script( 'ckeditor.js', 'components/com_zbrochure/assets/js/ckeditor/' );
		
		$app						= JFactory::getApplication();
		$user						= JFactory::getUser();
		$id							= JRequest::getInt( 'id', 0 );
		
		$this->action				= JRequest::getVar( 'action', 'savePackage' );
		$this->brochure_id			= JRequest::getInt( 'brochure_id', 0 );
		$this->page_id				= JRequest::getInt( 'page_id', 0 );
		$this->block_id				= JRequest::getInt( 'block_id', 0 );
		
		$this->_com_params			= JComponentHelper::getParams( 'com_zbrochure' );
		
		//If this is being edited from a brochure, we need to include the template and theme
		//CSS files for the editor
		if( $this->brochure_id ){
			
			$model					= $this->getModel();
			$this->bro				= $model->getBro( $this->brochure_id );
			
			$editor_css				= array();
			
			$tmpl_path				= JPATH_SITE.'/media/zbrochure/tmpl/'.$this->bro->bro_tmpl.'/';
			$theme_path				= JPATH_SITE.'/media/zbrochure/themes/'.$this->bro->bro_theme.'/';
			
			if( JFile::exists( $tmpl_path . 'default_styles.css' ) ){
				
				JHTML::stylesheet( 'default_styles.css', 'media/zbrochure/tmpl/'.$this->bro->bro_tmpl.'/' );
				
			}
			
			if( JFile::exists( $tmpl_path . 'editor_styles.css' ) ){
				
				JHTML::stylesheet( 'editor_styles.css', 'media/zbrochure/tmpl/'.$this->bro->bro_tmpl.'/' );	
				$editor_css[]		= '"'.JURI::base().'media/zbrochure/tmpl/'.$this->bro->bro_tmpl.'/editor_styles.css"';

			}
			
			if( JFile::exists( $theme_path . 'theme_styles.css' ) ){
				
				JHTML::stylesheet( 'theme_styles.css', 'media/zbrochure/themes/'.$this->bro->bro_theme.'/' );
				$editor_css[]		= '"'.JURI::base().'media/zbrochure/themes/'.$this->bro->bro_theme.'/theme_styles.css"';
				
			}
			
			
			
		}else{
			
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
			
		}
		
		$editor_config	= '$(document).ready(function(){';
		$editor_config	.= 'CKEDITOR.config.contentsCss = [ '.implode( ',', $editor_css ).' ]';
		$editor_config	.= '});';
		
		$this->document->addScriptDeclaration( $editor_config );
		
		//Get the package
		//Get the category to edit/display
		if( $id ){
			
			$this->package		= $this->get( 'package' );
			$this->plans		= $this->get( 'plans' );
			
			$plans				= array();
			
			foreach( $this->plans as $plan_id => $plan_data ){
				
				$plans[] = $plan_id;
				
			}
			
			$plans_json = '{"plans":['.implode( ',', $plans ).']}';
			
			$this->package->data = $plans_json;
			
			require_once JPATH_COMPONENT.'/content/package/package.php';
			$render 	= new ZbrochureContentPackage();
		
			$this->plans_output	= $render->renderContent( $this->package, 0, 0, 1 );
			
		}else{
			
			$this->package		= '';
			
		}
		
		$this->categories	= $this->get('categories');
		
		$this->category	= $this->categories[ $selected[0] ];
		
		//Build Category Select list
		$cat_options[] = JHTML::_( 'select.option', '0', JText::_('-- Select a Category --') );
		
		foreach( $this->categories as $key => $value ){
			
			$cat_options[] = JHTML::_( 'select.option', $value->cat_id, JText::_( $value->cat_name ) );
		
		}
		
		$this->categories = JHTML::_('select.genericlist', $cat_options,'package_cat', 'class="inputbox" style="width:212px"','value','text', $this->package->package_cat );
		
		//Build published select list
		$options = array();
		$options[] = JHTML::_( 'select.option', '0', 'Unpublished' );
		$options[] = JHTML::_( 'select.option', '1', 'Published' );
		$options[] = JHTML::_( 'select.option', '2', 'Archived' );
		$options[] = JHTML::_( 'select.option', '3', 'Template Default' );
		
		$this->published	= JHTML::_( 'select.genericlist', $options, 'published', 'class="inputbox" style="width:212px"', 'value', 'text', $this->package->published );
		
		$this->left_modules		= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'packages', true );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'package' );
		
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