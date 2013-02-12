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
class ZbrochureViewMediamanager extends JView {
	
	/**
	 * @var int client ID
	 */	
	protected $_client;
	
	/**
	 * @var int template ID
	 */	
	protected $_tmpl;
	
	/**
	 * @var int user ID
	 */	
	protected $_user;
	
	/**
	 * @var obj component parameters
	 */
	protected $_com_params;
	
	public function display( $tpl = null ){
		
		JHTML::stylesheet( 'fileuploader.css', 'components/com_zbrochure/assets/css/' );
		
		JHTML::script( 'jquery.tablesorter.min.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'fileuploader.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.mediamanager.js', 'components/com_zbrochure/assets/js/' );
		
		$app				= JFactory::getApplication();
		$user				= JFactory::getUser();
		$doc				= JFactory::getDocument();
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		$this->_block_id	= JRequest::getInt( 'block', 0 );
		$this->_bro_id		= JRequest::getInt( 'bid', 0 );
		$this->_img_id		= JRequest::getInt( 'iid', 0 );
		$this->_client		= JRequest::getInt( 'cid', 0 );
		$this->_tmpl		= JRequest::getInt( 'tid', 0 );
		$this->_user		= JRequest::getInt( 'uid', 0 );
		
		
		$this->block_id				= JRequest::getInt( 'block_id', 0 );
		
		if( $this->block_id ){
			
			$this->content_block	= ZbrochureHelperBlocks::getBlock( $this->block_id );
			
		}
		
		
		$layout				= JRequest::getVar( 'layout' );
					
		$this->assets		= $this->get( 'assets' );
		$this->keywords		= $this->get( 'keywordslist' );
		$this->tabs			= $this->_separateAssetItems( $this->assets );
		$this->assets_dir	= $this->_com_params->get('asset_file_path').DS;
		
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		parent::display($tpl);

	}
	
	private function _separateAssetItems( $items ){
		
		$tabs		= new stdClass();
		$general	= array();
		$client		= array();
		$tmpl		= array();
		$user		= array();
		
		//Loop through each item to see if it is assigned to a specific
		//client, template or user. If not, add it to the general tab
		foreach( $items as $item ){
			
			if( $item->cid && $this->_client ){
			
				$client[] = $item;
			
			}elseif( $item->asset_tmpl_id && $this->_tmpl ){
			
				$tmpl[] = $item;
			
			}elseif( $item->asset_user_id && $this->_user ){
			
				$user[] = $item;
			
			}elseif( $item->asset_client_id == 0 && $item->asset_tmpl_id == 0 && $item->asset_user_id == 0 ){
			
				$general[] = $item;
			
			}
			
		}
		
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'themes' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'theme', true );
		
		$tabs->general	= $general;
		$tabs->client	= $client;
		$tabs->tmpl		= $tmpl;
		$tabs->user		= $user;
		
		return $tabs;
			
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