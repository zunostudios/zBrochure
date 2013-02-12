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
 * HTML View class for Template Editing
 */
class ZbrochureViewProvider extends JView {
	
	protected $template;
	protected $state;
	
	function display( $tpl = null ){
		
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$doc		= JFactory::getDocument();
		
		//Get the template to edit
		$this->data		= $this->get( 'data' );
		$this->contacts	= $this->get( 'contacts' );
				
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'providers', true );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'provider' );
		
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
	
}