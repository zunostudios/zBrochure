<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2012 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for Template Editing
 */
class ZbrochureViewLayout extends JView{
	
	function display( $tpl = null ){
		
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser()->get( 'id' );
		$this->id		= JRequest::getInt( 'id', 0 );
		$this->tmpl_id	= JRequest::getInt( 'tmpl_id', 0 );
		
		$this->_com_params		= JComponentHelper::getParams( 'com_zbrochure' );
		
		if( $this->id ){
		
			$this->layout	= $this->get( 'layout' );
		
		}else{
			
			$this->layout->tmpl_id			= $this->tmpl_id;
			$this->layout->tmpl_layout_id	= 0;
			$this->next_layout_key			= $this->get( 'nextLayoutKey' );
			
		}
		
		$tmpl_path				= JPATH_SITE.'/media/zbrochure/tmpl/'.$this->layout->tmpl_id.'/';
		
		if( JFile::exists( $tmpl_path . 'default_styles.css' ) ){
			
			JHTML::stylesheet( 'default_styles.css', 'media/zbrochure/tmpl/'.$this->layout->tmpl_id.'/' );
			
		}
		
		if( JFile::exists( $tmpl_path . 'editor_styles.css' ) ){
			
			JHTML::stylesheet( 'editor_styles.css', 'media/zbrochure/tmpl/'.$this->layout->tmpl_id.'/' );
			
		}
		
		$theme_path				= JPATH_SITE.'/media/zbrochure/themes/'.$this->layout->theme->theme_id.'/';
		
		if( JFile::exists( $theme_path . 'theme_styles.css' ) ){
			
			JHTML::stylesheet( 'theme_styles.css', 'media/zbrochure/themes/'.$this->layout->theme->theme_id.'/' );
			
		}
		
		//Build published select list
		$options = array();
		$options[] = JHTML::_( 'select.option', '0', 'Unpublished' );
		$options[] = JHTML::_( 'select.option', '1', 'Published' );
		$options[] = JHTML::_( 'select.option', '2', 'Archived' );
		
		$this->published	= JHTML::_( 'select.genericlist', $options, 'tmpl_layout_published', 'class="inputbox" style="width:212px"', 'value', 'text', $this->layout->tmpl_layout_published );
		
		$this->left_modules		= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'blocks', true );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'block' );
		
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