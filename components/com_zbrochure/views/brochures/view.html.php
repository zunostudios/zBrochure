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
 * HTML View class for Template Listing
 */
class ZbrochureViewBrochures extends JView {
		
	function display( $tpl = null ){
		
		$app				= JFactory::getApplication();
		
		$doc				= JFactory::getDocument();
		$model				= $this->getModel();
		
		$this->user			= JFactory::getUser();
		$this->user_teams	= $this->user->get( 'teams' );
		
		
		// Access check.
		/*
		if( !$this->user->authorise('core.edit', 'com_zbrochure') ){
		
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		
		}
		*/
		
		
		
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
    	$filter_order		= $app->getUserStateFromRequest( 'com_zbrochure_brochures_filter_order', 'filter_order', 'b.bro_id', 'cmd', true );
    	$filter_order_Dir	= $app->getUserStateFromRequest( 'com_zbrochure_brochures_filter_order_Dir', 'filter_order_Dir', '', 'word', true );
    	$filter_state		= $app->getUserStateFromRequest( 'com_zbrochure_brochures_filter_state', 'filter_state', '', 'word', true );
		
		$this->searchterm	= $app->getUserState( "com_zbrochure_brochures_filter_searchterm" );
		
		//Get the template to edit
		$this->brochures	= $model->getBrochures();
		$this->clients		= $model->getClients();
		$this->teams		= $model->getTeams();
		$this->client_dir	= $this->_com_params->get( 'client_file_path' );
		
		$js = "$(document).ready(function(){
		
			$('.no-thumb').each(function( index, item ){
				
				ids = $(item).attr('rel').split(':');
				
				$.ajax({
					type: 'POST',
					url: 'index.php',
					data: 'option=com_zbrochure&task=generateBroThumb&bid='+ids[0]+'&pid='+ids[1],
					success: function( html ){
									
					}
				}).done(function( html ){
				
					$(item).removeClass( 'no-thumb' );						
					$(item).empty();
					
					$(document.createElement('img'))
						.hide()
					    .attr({ src: html, alt: 'Brochure thumbnail' })
					    .addClass( 'bro-thumb' )
					    .appendTo( $(item) )
					    .fadeIn(500);
				
				});
			
			});
		
		});";
		
		$doc->addScriptDeclaration( $js );
		
		$this->left_modules			= $this->_getModulesFor( 'left', 'raw' );
		$this->columns_class		= ( $this->left_modules ) ? 'left-middle' : 'middle';
		$this->list_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'brochures' );
		$this->item_menu_itemid		= $this->_getMenuItemId( 'com_zbrochure', 'brochure', true );
		
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