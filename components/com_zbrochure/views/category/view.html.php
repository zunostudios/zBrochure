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
 * HTML View class for Category Editing
 */
class ZbrochureViewCategory extends JView{
	
	function display( $tpl = null ){
		
		$app				= JFactory::getApplication();
		$user				= JFactory::getUser();
		$id					= JRequest::getInt( 'id', 0 );
		$layout				= JRequest::getVar( 'layout', 'default' );
		
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		//Get the category to edit/display
		if( $id ){
		
			$this->category	= $this->get( 'category' );
		
		}else{
			
			$this->category	= '';
			
		}
		
		$options = array();
		$options[] = JHTML::_( 'select.option', '0', 'Unpublished' );
		$options[] = JHTML::_( 'select.option', '1', 'Published' );
		$options[] = JHTML::_( 'select.option', '2', 'Archived' );
		
		$this->published	= JHTML::_( 'select.genericlist', $options, 'published', 'class="inputbox"', 'value', 'text', $this->category->published );
		
		$this->left_modules		= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'categories', true );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'category' );
		
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