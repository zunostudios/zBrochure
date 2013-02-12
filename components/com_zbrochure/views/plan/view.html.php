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
 * HTML View class for Plan Editing
 */
class ZbrochureViewPlan extends JView {
		
	function display( $tpl = null ){
		
		JHTML::script( 'zbrochure.block.plan.js', 'components/com_zbrochure/assets/js/' );
		
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$doc			= JFactory::getDocument();
		$model			= $this->getModel();
		
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$this->page_id	= JRequest::getInt( 'page_id', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		
		$this->_com_params		= JComponentHelper::getParams( 'com_zbrochure' );
		
		//Get the template to edit
		$this->plan				= $this->get( 'plan' );
		$this->package			= $this->get( 'package' );
		
		if( $this->getLayout() == 'assign' ){
		
			$this->packages			= $this->get( 'packages' );
			$this->brochures		= $model->getBrochures( $this->plan->brochure_id );
			
			//Build Plan Select list
			$package[] = JHTML::_('select.option', '0', JText::_( '-- Select a Package --' ) );
			
			foreach( $this->packages as $k => $v ){
				
				$package[] = JHTML::_( 'select.option', $v->package_id, JText::_( $v->package_name ) );
			
			}
			
			$this->packages = JHTML::_('select.genericlist', $package, 'package_id', 'class="inputbox" style="width:250px"', 'value', 'text', $this->plan->package_id );
		
		}else{
		
			$this->table			= $model->getPlanTable( 0, 0 );
		
		}
		
		$this->plan_select	= $model->getPlansByPackage( $this->package->package_id, $this->package->package_parent, $this->plan->plan_id, 0, 0, $block_id, $brochure_id );
		
		$this->left_modules			= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class		= ( $this->left_modules ) ? 'left-middle' : 'middle';
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'plans', true );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'plan' );
		
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