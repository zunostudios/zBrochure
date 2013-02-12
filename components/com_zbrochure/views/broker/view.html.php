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
class ZbrochureViewBroker extends JView{
	
	function display( $tpl = null ){
		
		JHTML::script( 'fileuploader.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.theme.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.logo.js', 'components/com_zbrochure/assets/js/' );
		
		$app						= JFactory::getApplication();
		$user						= JFactory::getUser();
		$doc						= JFactory::getDocument();
		$layout						= JRequest::getVar( 'layout', 'default' );
		
		$this->_com_params			= JComponentHelper::getParams('com_zbrochure');
		$this->broker_dir			= $this->_com_params->get( 'broker_file_path' );
		
		$this->broker				= $this->get( 'broker' );
		$this->logos				= $this->get( 'logos' );
		
		//$this->bro_id			= JRequest::getInt( 'bid', 0 );
		$this->block_id				= JRequest::getInt( 'block_id', 0 );
		//$this->logo_id			= JRequest::getInt( 'lid', 0 );
		//$this->active_logo_id	= JRequest::getInt( 'aid', 0 );
		
		if( $this->block_id ){
			
			$this->content_block	= ZbrochureHelperBlocks::getBlock( $this->block_id );
			
		}
		
		$renderer					= $doc->loadRenderer( 'modules' );
		$raw						= array( 'style' => 'raw' );
		$this->left_modules			= $renderer->render( 'left', $raw, null );
		$this->columns_class		= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'brokers', true );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'broker' );
		
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