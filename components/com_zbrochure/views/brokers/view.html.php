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

JHTML::script( 'jquery.tablesorter.min.js', 'components/com_zbrochure/assets/js/' );
JHTML::stylesheet( 'jquery.dataTables.css', 'components/com_zbrochure/assets/css/' );
JHTML::script( 'jquery.dataTables.min.js', 'components/com_zbrochure/assets/js/' );

/**
 * HTML View class for Template Editing
 */
class ZbrochureViewBrokers extends JView {
	
	/**
	 * @var obj component parameters
	 */
	protected $_com_params;
	
	function display( $tpl = null ){
		
		$app				= JFactory::getApplication();
		$user				= JFactory::getUser();
		$doc				= JFactory::getDocument();
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		//Get the brokers
		$this->brokers		= $this->get( 'brokers' );
		$this->file_dir		= $this->_com_params->get( 'broker_file_path' );
		$this->broker_dir	= $this->_com_params->get( 'broker_file_path' );
		
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'packages' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'package', true );
		
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