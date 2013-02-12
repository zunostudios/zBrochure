<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for Brochure Editing
 */
class ZbrochureViewBrochure extends JView{
		
	public function display( $tpl = null ){
		
		//Load the js for the editor
		JHTML::script( 'ckeditor.js', 'components/com_zbrochure/assets/js/ckeditor/' );
		
		//Load the js for general brochure editing
		JHTML::script( 'zbrochure.brochure.edit.js', 'components/com_zbrochure/assets/js/' );
		
		//Load the edit js for each block type
		JHTML::script( 'zbrochure.block.plan.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.svg.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.image.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.logo.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'zbrochure.block.theme.js', 'components/com_zbrochure/assets/js/' );
		
		//Misc JQuery js, mostly for UI
		JHTML::script( 'jquery.carousel.min.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'jquery.scrollTo.min.js', 'components/com_zbrochure/assets/js/' );
		JHTML::script( 'jquery.jscroll.min.js', 'components/com_zbrochure/assets/js/' );
		
		$app				= JFactory::getApplication();
		$this->user			= JFactory::getUser();
		$dispatcher			= JDispatcher::getInstance();
		$layout				= JRequest::getVar( 'layout' );		
		$model				= $this->getModel( 'brochure' );	
		$this->_com_params	= JComponentHelper::getParams( 'com_zbrochure' );
	
		$user_teams			= $this->user->get( 'teams' );
		
		if( count( $user_teams ) > 1 ){
	
			foreach( $user_teams as $k => $v ){
				$options[] = JHTML::_( 'select.option', $k, $v  );
			}
			
			$this->team_list	= JHTML::_('select.genericlist', $options, 'team_id', 'class="inputbox"', 'value', 'text', $this->user->get('active_team'), 'bro_team_id' );	
			
		}else if( count($user_teams) == 1 ){
			
			$single = current($user_teams);
			$this->team_list	= '<input type="hidden" name="team_id" value="'.$single.'">';
			
		}
		
		
		if( $layout == 'choose' ){
		
			//Clients
			$this->clients		= ZbrochureHelperClients::getClients( null, 'genericlist' );
		
			//Themes
			$this->themes		= ZbrochureHelperThemes::getThemes( 1, null, null, 0 );
			
			//Templates
			$this->templates	= ZbrochureHelperTemplates::getTemplates();
		
		}else{
			
			//Brochure data
			$this->bro				= $this->get( 'bro' );
			$this->pages			= $model->getPages( 1, 0 );
			
			//Team select list
			$this->teams			= ZbrochureHelperTeams::getTeams( $this->bro->team_id, 'genericlist' );
						
			//Template data
			$this->tmpl				= $model->getTemplate();
			$this->tmpl_layouts		= $model->getTemplateLayouts();
			$this->tmpls			= ZbrochureHelperTemplates::getTemplates();
			
			$tmpl_path				= JPATH_SITE.'/media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/';
			
			$editor_css				= array();
			
			if( JFile::exists( $tmpl_path . 'default_styles.css' ) ){
				
				JHTML::stylesheet( 'default_styles.css', 'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/' );
				
			}
			
			if( JFile::exists( $tmpl_path . 'editor_styles.css' ) ){
				
				JHTML::stylesheet( 'editor_styles.css', 'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/' );
				
				$editor_css[]		= '"'.JURI::base().'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/editor_styles.css"';

			}
			
			//Theme data
			$this->theme			= $model->getTheme( $this->bro->bro_theme );
			$this->themes			= ZbrochureHelperThemes::getThemes( 1, null, null, 0, $this->bro->bro_theme );
			$this->client_themes	= ZbrochureHelperThemes::getThemes( 0, $this->bro->bro_client, null, 0, $this->bro->bro_theme );
			
			$theme_path				= JPATH_SITE.'/media/zbrochure/themes/'.$this->theme->theme_id.'/';
		
			if( JFile::exists( $theme_path . 'theme_styles.css' ) ){
				
				JHTML::stylesheet( 'theme_styles.css', 'media/zbrochure/themes/'.$this->theme->theme_id.'/' );
				
				$editor_css[]		= '"'.JURI::base().'media/zbrochure/themes/'.$this->theme->theme_id.'/theme_styles.css"';
				
			}
			
			//Content data
			//$this->categories		= $this->get( 'categories' );		
			//$this->packages		= $this->get( 'packages' );
			
			//Client Data
			$this->clients			= ZbrochureHelperClients::getClients( $this->bro->bro_client, 'genericlist' );
			//$this->team			= $this->get( 'team' );
			
			//Content Variables
			$this->vars				= ZbrochureHelperVars::getContentVars();
			$this->vars_bound		= ZbrochureHelperVars::bindVarData( $this->vars, $this->bro, $this->tmpl );
			$this->vars_list		= ZbrochureHelperVars::buildVarList( $this->vars );
						
			//Build Package Select list
			/*
			if( isset($packages) ){
				$pack[] = JHTML::_('select.option', '0', JText::_( '-- Select a Package --' ) );
				foreach( $packages as $k => $v ) : $pack[] = JHTML::_( 'select.option', $v->package_id, JText::_( $v->package_name ) ); endforeach;
				$packages = JHTML::_('select.genericlist', $pack, 'packages[]', 'onchange="getPackages(this.value)"', 'value', 'text', '' );
			}
			*/
			
			$editor_config	= '$(document).ready(function(){';
			
			$editor_config	.= 'CKEDITOR.config.contentsCss = [ '.implode( ',', $editor_css ).' ]';
			
			$editor_config	.= '});';
			
			$this->document->addScriptDeclaration( $editor_config );
			
			JPluginHelper::importPlugin('zbrochure');
			$results = $dispatcher->trigger( 'onContentPrepare', array( 'com_zbrochure.brochure', $this->pages, $this->vars_bound ) );
		
		}
		
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