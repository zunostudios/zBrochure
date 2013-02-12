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
class ZbrochureViewAsset extends JView {
	
	protected $template;
	protected $state;
	
	function display( $tpl = null ){
		
		$app				= JFactory::getApplication();
		$user				= JFactory::getUser();
		$doc				= JFactory::getDocument();
		
		//Get the template to edit
		$this->data			= $this->get( 'data' );
		$this->clients		= $this->get( 'clients' );
		$this->selected 	= $this->get( 'selectedclients' );
		$this->keywords 	= $this->get( 'keywordslist' );
		
		foreach( $this->selected as $select ){
			$selectarray[] = $select->cid;
		}
				
		//Build Client Multi Select list
		foreach( $this->clients as $key=>$client) : $options[] = JHTML::_('select.option', $client->client_id, JText::_($client->client_version_name) );endforeach;
		$clientlist = JHTML::_( 'select.genericlist', $options, 'clients[]', 'class="inputbox" style="height:150px;width:400px" multiple="multiple"','value','text', $selectarray );		
		
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'mediamanager' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'asset', true );
		
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