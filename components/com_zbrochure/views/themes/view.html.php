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
JHTML::stylesheet( 'jquery.dataTables.css', 'components/com_zbrochure/assets/css/' );
JHTML::script( 'jquery.dataTables.min.js', 'components/com_zbrochure/assets/js/' );

/**
 * HTML View class for Theme listing
 */
class ZbrochureViewThemes extends JView {
	
	protected $template;
	protected $state;
	
	function display( $tpl = null ){
		
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$doc			= JFactory::getDocument();
		
		//Get the template to edit
		$this->themes	= $this->get( 'themes' );
				
		$this->left_modules			= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class		= ( $this->left_modules ) ? 'left-middle' : 'middle';
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'themes' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'theme', true );
		
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