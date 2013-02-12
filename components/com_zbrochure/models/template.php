<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.modelitem' );

/**
 * zBrochure Brochure Model
 */
class ZbrochureModelTemplate extends JModelItem{

	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.template';
	
	/**
	 * @var int id
	 */	
	protected $_id;
	
	/**
	 * @var object clients
	 */	
	protected $_clients;
	
	/**
	 * @var object themes
	 */	
	protected $_themes;
	
	/**
	 * @var object pages
	 */	
	protected $_pages;
	
	/**
	 * @var object pages
	 */	
	protected $_specificpage;
	
	/**
	 * @var object content types
	 */	
	protected $_ctypes;
	
	/**
	 * @var object content types
	 */	
	protected $_packages;
	
	/**
	 * @var object content types
	 */	
	protected $_thesepackages;
	
	/**
	 * @var object content types
	 */	
	protected $_package;
	
	/**
	 * @var object content types
	 */	
	protected $_plan;
	
	/**
	 * @var object content types
	 */	
	protected $_contentblocks;
	
	/**
	 * @var object content types
	 */	
	protected $_layoutlist;
	
	/**
	 * @var object content types
	 */	
	protected $_layoutinfo;
	
	var $_tmpl		= null;
		
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id		= JRequest::getInt( 'id' );		
		$this->_user	= JFactory::getUser()->get('id');
		
		parent::__construct();
		
	}
	
	/**
	 * Method to get a specific template
	 */
	public function getTmpl(){
	
		if( empty($this->_tmpl) ){
	
			$query	= $this->_db->getQuery(true);
			$query->select( 't.*, u.*, m.email AS modified_email, m.username AS modified_username, m.name AS modified_name' );
			$query->from( '#__zbrochure_templates AS t' );
			$query->join( 'LEFT', '#__users AS u ON u.id = t.tmpl_created_by' );
			$query->join( 'LEFT', '#__users AS m ON m.id = t.tmpl_modified_by' );
			$query->where( 't.tmpl_id = '.$this->_id );
			
			$this->_db->setQuery( $query );
			$this->_tmpl 	= $this->_db->loadObject();
			
			$this->_theme	= $this->getTheme( $this->_tmpl->tmpl_default_theme );
		
		}
		
		return $this->_tmpl;
	
	}
	
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getSpecificPage( $pid=0 ){
		
		if( $pid ){
		
			if( empty($this->_specificpage) ){
			
				try{
					
					$db		= $this->getDbo();
					$query	= $db->getQuery(true);
					
					$query->select( 'p.*, l.*, v.*' );
					$query->from( '#__zbrochure_brochure_pages AS p' );
					
					$query->join( 'LEFT', '#__zbrochure_template_layouts AS l ON l.tmpl_layout_key = p.bro_page_layout' );
					$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = l.tmpl_layout_current_version' );
					
					$query->where( 'l.tmpl_id = '.$this->_bro->bro_tmpl );
					$query->where( 'p.bro_id = '.$this->_id );
					$query->where( 'p.bro_page_order = '.$pid );
					
					$db->setQuery($query);
					$this->_specificpage = $db->loadObjectList();
					
					if( empty( $this->_specificpage ) ){
				
						return JError::raiseError( 404, JText::_('COM_ZBROCHURE_BROCHURE_PAGES_NOT_FOUND') );
					
					}
					
					foreach( $this->_specificpage as $page ){
									
						$page->blocks = $this->_getBrochureBlocks( $page->tmpl_layout_id, $page->bro_page_id, $this->_theme, $edit );
						
					}
					
					//$this->_specificpage->blocks = $this->_getBrochureBlocks( $this->_specificpage->tmpl_layout_id, $this->_specificpage->bro_page_id, 0 );
					
				}catch( JException $e ){
				
					if( $e->getCode() == 404 ){
					
						// Need to go thru the error handler to allow Redirect to work.
						JError::raiseError( 404, $e->getMessage() );
					
					}else{
					
						$this->setError($e);
					
					}
		
				}
				
			}
			
			return $this->_specificpage;
		
		}else{
			
			return false;
			
		}
	
	}	
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getPages( $edit=1, $pid=0 ){
	
		if( empty($this->_pages) ){
		
			try{
				
				$query	= $this->_db->getQuery(true);
				
				$query->select( 'l.*, v.*' );
				$query->from( '#__zbrochure_template_layouts AS l' );
				$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = l.tmpl_layout_current_version' );
				
				$query->where( 'l.tmpl_id = '.$this->_id );
				$query->where( 'l.tmpl_layout_published = 1' );
				
				if( $pid ){
					
					$query->where( 'l.tmpl_layout_id = '.$pid );
					
				}
					
				$query->order( 'l.tmpl_layout_order' );
			
				$this->_db->setQuery( $query );
				$this->_pages = $this->_db->loadObjectList();
				
				if( empty( $this->_pages ) ){
			
					//return JError::raiseError( 404, JText::_('COM_ZBROCHURE_BROCHURE_PAGES_NOT_FOUND') );
				
				}else{
									
					foreach( $this->_pages as $page ){
						
						$content_blocks			= json_decode( $page->tmpl_layout_version_content, true );
						$image_blocks			= json_decode( $page->tmpl_layout_version_images, true );
						$design_blocks			= json_decode( $page->tmpl_layout_version_design, true );
						
						$un_tmpl_blocks = $this->_getTemplateBlocks( $page, $this->_theme, 0 );
						
						$tmpl_blocks	= array();
						
						foreach( $un_tmpl_blocks as $block ){
	
							$tmpl_blocks[$block->content_type_output_format][$block->content_tmpl_block_id] = $block;
							
						}
						
						$html_matched	= array();
						$image_matched	= array();
						$design_matched	= array();
						
						if( $content_blocks ){
						
							foreach( $content_blocks as $k => $v ){
								
								$html_matched[$k]['layout']		= $v;
								$html_matched[$k]['content']	= $tmpl_blocks['html'][$k];
								
							}
							
						}
						
						if( $image_blocks ){
						
							foreach( $image_blocks as $k => $v ){
								
								$image_matched[$k]['layout']	= $v;
								$image_matched[$k]['content']	= $tmpl_blocks['image'][$k];
								
							}
						
						}
						
						if( $design_blocks ){

							foreach( $design_blocks as $k => $v ){
								
								$design_matched[$k]['layout']	= $v;
								$design_matched[$k]['content']	= $tmpl_blocks['svg'][$k];
								
							}
						
						}
						
						$page->content_blocks 	= $html_matched;
						$page->image_blocks		= $image_matched;
						$page->design_blocks	= $design_matched;
													
					}
				
				}
								
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
				
		return $this->_pages;
	
	}
	
	/**
	 * Get the template layout blocks
	 * @return object with all the blocks associated with a specific layout
	 */
	private function _getTemplateBlocks( $page, $theme, $edit=0 ){
				
		try{
			
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'c.*, t.*' );
			$query->from( '#__zbrochure_content_blocks AS c' );
			$query->join( 'LEFT', '#__zbrochure_content_types AS t ON t.content_type_id = c.content_block_type' );
			$query->where( 'c.content_page_id = ' . $page->tmpl_layout_key );
			$query->where( 'c.content_tmpl_id = ' . $page->tmpl_id );
			
			$this->_db->setQuery( $query );
			$blocks = $this->_db->loadObjectList();
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
			
			foreach( $blocks as $block ){
				
				$block->bro_client	= 0;
				$block->bro_theme	= $theme;
				$block->data		= $this->_getBlockContent( $block, $edit );
				
			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $blocks;
	
	}
	
	/**
	 * Method to build content block markup
	 */
	private function _getBlockContent( $block, $edit=0 ){
		
		require_once JPATH_COMPONENT.'/content/'.$block->content_type_folder.'/'.$block->content_type_element;		
		$class =  'ZbrochureContent'.$block->content_type_folder;
		$output	= $class::getContent( $block, $edit );
	
		return $output;
	
	}
	
	/**
	 * Method to get content blocks
	 */
	public function getContentblocks( $broid, $blockid, $pageid ){

		$db = JFactory::getDBO();
		
		$query = "SELECT a.*, c.* "
		."FROM #__zbrochure_content_blocks AS a "
		."LEFT JOIN #__zbrochure_content_types AS c ON c.content_type_id = a.content_block_type "
		."WHERE a.content_bro_id = ".(int)$broid." AND a.content_tmpl_block_id = ".(int)$blockid." AND a.content_page_id = ".(int)$pageid." "
		;
		
		$db->setQuery($query);
		$this->_block = $db->loadObject();
		
		$build =  $this->_getBlockContent( $this->_block );
		
		return $build;
	
	}
	
	/**
	 * Method to save the client
	 */
	public function getContent($catid){
	
		if( empty($this->_content) ){
	
			$db = JFactory::getDBO();
			
			$query = "SELECT a.* "
			."FROM #__zbrochure_content AS a "
			."WHERE a.catid = ".$catid
			;
			
			$db->setQuery($query);
			$this->_content = $db->loadObjectList();
		
		}
		
		return $this->_content;
	
	}
	
	/**
	 * Method to save the client
	 */
	public function placeContent( $id ){
	
		if( empty($this->_place) ){
	
			$db = JFactory::getDBO();
			
			$query = "SELECT a.* "
			."FROM #__zbrochure_content AS a "
			."WHERE a.id = ".$id
			;
			
			$db->setQuery($query);
			$this->_place = $db->loadObject();
		
		}
		
		return $this->_place;
	
	}
	
	/**
	 * 
	 */
	public function getCtypes(){
	
		if( empty($this->_ctypes) ){
			
			$db = JFactory::getDBO();
			
			$query = "SELECT a.* "
			."FROM #__zbrochure_content_types AS a "
			."WHERE a.content_type_brochure = 1 "
			;
			
			$db->setQuery($query);
			$this->_ctypes = $db->loadObjectList();
		
		}
		
		return $this->_ctypes;
	
	}
		
	/**
	 * Method to get list of Packages associated with this brochure
	 */
	public function getThesepackages(){
	
		if( empty($this->_thesepackages) ){
	
			$db = JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->select( 'package_id, package_name' );
			$query->from( '#__zbrochure_packages' );
			$query->where( 'brochure_id = 1' );
			$query->order( 'package_name' );
			
			$db->setQuery($query);
			$this->_thesepackages = $db->loadObjectList();
		
		}
		
		return $this->_thesepackages;
	
	}
	
	/**
	 * Method to save content blocks
	 */
	public function saveBlocks($data, $brochure, $page){
	
		$db = JFactory::getDBO();
	
		foreach( $data as $key => $block ){
		
			$block_id = explode('-',$key);
			
			$query = "DELETE FROM #__zbrochure_block_content "
			."WHERE block_id = ".(int)$block_id[1]." AND brochure_id = ".(int)$brochure." AND page = ".(int)$page
			;
			
			$this->_db->setQuery( $query );
			$this->_db->Query();
		
			$row = $this->getTable('blockcontent');
	
		 	if( !$row->bind( $block ) ){
		 	
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
			
			$row->brochure_id 	= $brochure;
			$row->block_bro	= json_encode($row->block_bro);
			$row->page 			= $page;
			$row->block_id		= $block_id[1];
			
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		
		}
	
	}
	
	/**
	 * Method to save single content block from modal
	 */
	public function saveBlock( $data, $blockid ){
	
		$type = key($data['data']);
		$contentid = key($data['data'][$type]);
		$content = $data['data'][$type][$contentid];
		
		if( !empty($data['package_footer']) ){
			$content['package_footer'] = $data['package_footer'];	
		}
		
		$row = $this->getTable('content'.$type);
		
		$date = JFactory::getDate();
		$date = $date->toMySQL();
		
		if( !$row->bind( $data ) ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->id	= '';
		
		if( is_array($content) ){
		
			$row->data	= json_encode($content);
		
		}else{
		
			$row->data	= $content;
		
		}
		
		$row->block_id = $blockid;
		$row->created = $date;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$newid = $row->id;
		
		$query	= $this->_db->getQuery(true);
		$query->update( '#__zbrochure_content_blocks' );
		$query->set( 'content_block_current_version = '.(int)$newid.'' );
		$query->where( 'content_block_id = '.(int)$blockid.'' );
		
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return $newid;
	
	}

	/**
	 * Method to save single content block from modal
	 */
	public function updateBlock( $block_id, $current_version ){
	
		$query	= $this->_db->getQuery(true);
		$query->update( '#__zbrochure_content_blocks' );
		$query->set( 'content_block_current_version = ' . $current_version.'' );
		$query->where( 'content_block_id = '.$block_id.'' );
		
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	
	}

	
	/**
	 * Method to save single content block from modal
	 */
	public function saveSvgBlock( $data, $blockid ){
		
		$row = $this->getTable( 'contentsvg' );
				
		if( !$row->bind( $data ) ){
		
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		return $row;
	
	}

	
	/**
	 * Method to duplicate package
	 */
	public function duplicatePlan( $id, $brochure ){
	
		$db 	= $this->getDBO();
		$row	= $this->getTable('plan');
	
		$query = "SELECT * "
		."FROM #__zbrochure_package_plans "
		."WHERE plan_id = ".$id
		;
		
		$db->setQuery($query);
		$this->_duplicatePlan = $db->loadObject();

	 	if( !$row->bind( $this->_duplicatePlan ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->plan_id = '';
		$row->brochure_id = $brochure;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row;
	
	}
		
	public function getTheme( $id ){
		
		if( empty($this->_theme) ){
		
			try{
				
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				
				$query->select( '*' );
				$query->from( '#__zbrochure_themes AS t' );
				$query->where( 't.theme_id = '.$id );
				
				$db->setQuery($query);
				$this->_theme = $db->loadObject();
				
				
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
		}
		
		return $this->_theme;
	
	}

	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getSinglePage( $pid ){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_brochure_pages' );
			$query->where( 'bro_page_id = '.(int)$pid );
			
			$db->setQuery($query);
			$page = $db->loadObject();
			
			
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $page;
	
	}
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getPage( $bropageid ){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_brochure_pages' );
			$query->where( 'bro_page_id = '.(int)$bropageid );
			$query->order( 'bro_page_order' );
			
			$db->setQuery($query);
			$this->_page = $db->loadObject();
			
			
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $this->_page;
	
	}
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getLayoutList(){
	
		if( empty($this->_layoutlist) ){
		
			try{
				
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				
				$query->select( '*' );
				$query->from( '#__zbrochure_template_layouts' );
				$query->where( 'tmpl_id = '.$this->_template_id );
				$query->order( 'tmpl_layout_name' );
				
				$db->setQuery($query);
				$this->_layoutlist = $db->loadObjectlist();
				
				
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $this->_layoutlist;
	
	}
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getLayoutInfo( $id ){
		
		if( empty($this->_layoutinfo) ){
		
			try{
				
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				
				$query->select( 'l.*, v.*' );
				$query->from( '#__zbrochure_template_layouts AS l' );
				$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = l.tmpl_layout_current_version' );
				$query->where( 'l.tmpl_layout_id = '.$id );
				$query->order( 'l.tmpl_layout_name' );
				
				$db->setQuery($query);
				$this->_layoutinfo = $db->loadObject();
				
				
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $this->_layoutinfo;
	
	}
	
	/**
	 * Update a brochure page
	 * @return object list of all pages
	 */
	public function updateLayout( $data ){
				
		$row	= $this->getTable( 'layouts' );
	
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row;
	
	}
	
	/**
	 * Save a page layout
	 * @return object list of all pages
	 */
	public function savePageLayout( $data ){
		
		$user		= JFactory::getUser()->get('id');	
		$layout		= $this->getLayoutInfo( $data['bro_page_layout'] );
		$row		= $this->getTable('pages');
	
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
				
		//$row->bro_page_id			= '';
		$row->bro_page_name			= $layout->tmpl_layout_name;
		$row->bro_page_spread		= $layout->tmpl_layout_spread;
		$row->bro_page_spread_side	= $layout->tmpl_layout_spread_side;
		$row->bro_page_created_by	= $user;
		$row->bro_page_published	= 1;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
			
		}
		
		//Now we need to duplicate the content_blocks row for this layout
		$this->duplicateContentBlocks( $row, $data['tmpl_id'] );
		
		$row->bro_page_thumb	= ZbrochureHelperThumbnails::generateThumbs( $row->bro_id, $row->bro_page_id );
		$row->store();
		
		return $row;
	
	}
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function pageTitle( $data ){
		
		$page = $this->getPage( $data['pagetitle_pageid'] );
		$row	= $this->getTable('pages');
	
	 	if( !$row->bind( $page ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}

		$row->bro_page_name = $data['pagetitle_pagetitle'];
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $row->bro_id;
	
	}
	
	/**
	 * Add a new page to a brochure
	 * @return object
	 */
	public function addPage( $data ){
		
		$page	= $this->getTable( 'pages' );
		
	 	if( !$page->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$page->bro_page_order	= (int)$data['bro_pages'] + 1;
		
		//alright, good to go. Store it to the Joomla db
		if( !$page->store() ){
			
			$this->setError($this->_db->getErrorMsg());
			return false;
			
		}
					
		//Now we need to duplicate the content_blocks row for this layout
		$this->duplicateContentBlocks( $page, $data['tmpl_id'] );
		
		$page->bro_page_thumb	= ZbrochureHelperThumbnails::generateThumbs( $page->bro_id, $page->bro_page_id );
		$page->store();
		
		return $page;
	
	}
	
	/**
	 * Trash a page from a brochure
	 * This does not delete it from the db
	 * @return object
	 */
	public function trashPage( $bro_page_id, $bro_id, $bro_pages ){
		
		$page	= $this->getTable( 'pages' );
		
	 	if( !$page->load( $bro_page_id ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
	
		$page->bro_page_published = 2;
		
		//alright, good to go. Store it to the Joomla db
		if( !$page->store() ){
			
			$this->setError($this->_db->getErrorMsg());
			return false;
			
		}
		
		//now we need to update the brochure row with the new number of total pages
		/*
		$bro['bro_id']		= $bro_id;
		$bro['bro_pages']	= (int)$bro_pages - 1;
		
		$this->store( $bro );
		*/
		
		return $page;
	
	}
	
	/**
	 * Change the layout of a previously saved layout
	 * @return object list of all pages
	 */
	public function changePageLayout( $data ){
		
		//if this was a blank page there is no page in the db for it
		if( $data['bro_page_id'] == 0 ){
		
			$new = true;
		
		}else{
		
			$new = false;
		
		}
		
		$page	= $this->getTable( 'pages' );
		
	 	if( !$page->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
				
		//alright, good to go. Store it to the Joomla db
		if( !$page->store() ){
			
			$this->setError($this->_db->getErrorMsg());
			return false;
			
		}
		
		if( $new ){
		
			//Now we need to duplicate the content_blocks row for this layout
			$this->duplicateContentBlocks( $page, $data['tmpl_id'] );
		
		}
		
		$page->bro_page_thumb	= ZbrochureHelperThumbnails::generateThumbs( $page->bro_id, $page->bro_page_id );
		$page->store();
		
		return $page;
	
	}
	
	/**
	 * Duplicate the row in Content Blocks
	 * @return object the template to be edited
	 */
	public function duplicateContentBlocks( $page, $tmpl_id ){

		try{
		
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'b.*' );
			$query->from( '#__zbrochure_content_blocks AS b' );
			
			if( $tmpl_id != 0 ){
			
				$query->where( 'b.content_page_id = '.(int)$page->bro_page_layout );
				$query->where( 'b.content_tmpl_id = '.(int)$tmpl_id );
			
			}else{
			
				$query->where( 'b.content_bro_id = '.(int)$page->bro_id );
				$query->where( 'b.content_block_published = 1' );
				$query->where( 'b.content_page_id = '.(int)$page->bro_page_layout );
			
			}
				
			$this->_db->setQuery($query);
			$cblocks = $this->_db->loadObjectList();
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $cblocks ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_CONTENT_BLOCK_DUPLICATION_FAILED') );
			
			}
			
			$date = JFactory::getDate();
			
			foreach( $cblocks as $data ){
			
				$row	= $this->getTable('contentblock');
			
			 	if( !$row->bind( $data ) ){
			 	
					$this->setError($this->_db->getErrorMsg());
					return false;
				
				}
				
				$row->content_block_id = '';
				$row->content_page_id = $page->bro_page_id;
				$row->content_bro_id = $page->bro_id;
				$row->content_block_created = $date->toSql();
				$row->content_block_created_by = $this->_user;
				$row->content_tmpl_id = 0;
				
				//Now we need to duplicate the content associated with the blocks
				$row->content_block_current_version = $this->duplicateContent( $row );
				
				//alright, good to go. Store it to the Joomla db
				if( !$row->store() ){
				
					$this->setError($this->_db->getErrorMsg());
					return false;
				
				}
				
				unset( $row );
			
			}
			
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError( $e );
			
			}

		}
		
		return true;
	
	}
	
	/**
	 * Duplicate the row in Content Blocks
	 * @return object the template to be edited
	 */
	public function duplicateContent( $data ){
	
		$contenttype = $this->getContentType( $data->content_block_type );
		
		if( $data->content_block_current_version == 0 ){
		
			$data->content_block_current_version = $data->content_content_id;
		
		}
		
		$where_id = 'id';

		try{
		
			$query	= $this->_db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__'.$contenttype->content_type_table );
			$query->where( $where_id.' = '.(int)$data->content_block_current_version );
			
			$this->_db->setQuery($query);
			$content = $this->_db->loadObject();

			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}

			/*
			if( empty( $content ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_CONTENT_CONTENT_DUPLICATION_FAILED') );
			
			}
			*/
			
			$row = $this->getTable( 'content'.$contenttype->content_type_folder );
		
		 	if( !$row->bind( $content ) ){
		 	
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
			
			$row->id = '';
			
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $row->id;
	
	}
	
	/**
	 * Get the content type that needs to be duplicated
	 * @return object the template to be edited
	 */
	public function getContentType( $content_type_id ){
	
		try{
		
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_content_types' );
			$query->where( 'content_type_id = '.(int)$content_type_id );
			
			$db->setQuery($query);
			$contenttype = $db->loadObject();

			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $contenttype ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_CONTENT_TYPE_GET_FAILED') );
			
			}			
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $contenttype;
	
	}
	
	/**
	 * Get the template
	 * @return object the template to be edited
	 */
	public function getTemplate(){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( 't.*' );
			$query->from( '#__zbrochure_templates AS t' );
			$query->where( 't.tmpl_id = '.$this->_template_id );
			
			$db->setQuery($query);
			$template = $db->loadObject();

			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $template ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_TEMPLATE_NOT_FOUND') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $template;
	
	}
	
	/**
	 * Get the layouts for all the brochure pages
	 * @return object with all the layouts associated with this brochure
	 */
	public function getTemplateLayouts(){
	
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( 'l.*, v.*' );
			$query->from( '#__zbrochure_template_layouts AS l' );
			$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = l.tmpl_layout_current_version' );
			$query->where( 'l.tmpl_id = '.$this->_template_id );
			
			$db->setQuery($query);
			$layouts = $db->loadObjectList();

			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $layouts ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_TEMPLATE_LAYOUTS_NOT_FOUND') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $layouts;
	
	}
	
	/**
	 * Get the template pages
	 * @return object with all the pages associated with this template
	 */
	public function getTemplateLayout( $id, $page ){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_template_layouts AS p' );
			$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = p.tmpl_layout_current_version' );
			$query->where( 'p.tmpl_layout_id = '.$id );
			
			$db->setQuery($query);
			$layout = $db->loadObject();
			
			$db = JFactory::getDBO();

			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $layout ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_TEMPLATE_LAYOUTS_NOT_FOUND') );
			
			}

			$layout->blocks = $this->_getTemplateBlock( $id, $page );			
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $layout;
	
	}

	public function updateOrdering( $ordering ){
	
		$update = $this->getTable( 'pages' );
		
		foreach( $ordering['order'] as $k => $v ){
			
			$page->bro_page_id = $v;
			$page->bro_page_order = $k + 1;
						
			if( !$update->bind( $page ) ){
			
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			//alright, good to go. Store it to the Joomla db
			if( !$update->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
			
		}
		
		return;
	
	}
	
	/**
	 * Method to save a brochure
	 */
	public function storeDoc( $data ){
		
		$row	= $this->getTable( 'doc' );
	
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
	
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
			
		return $row;
	
	}
	
	
	
	
	
	
	/**
	 * Method wrapper to duplicate a template
	 *
	 * @param   integer  $tmpl_id  Template ID to duplicate
	 *
	 * @return  integer  new template ID
	 *
	 * @since	1.0
	 */
	public function duplicate( $tmpl_id ){
	
		//****First we need to duplicate the template row
		$this->_duplicate_id = $this->duplicateTemplate( $tmpl_id );
		
		//****Then we need to duplicate the template layouts
		$this->duplicateLayouts( $tmpl_id, $this->_duplicate_id );
				
		return $this->_duplicate_id;
		
	}
	
	
	
	
	/**
	 * Method to duplicate template
	 *
	 * @param   integer  $tmpl_id  Template ID to duplicate
	 *
	 * @return  integer  new template ID
	 *
	 * @since	1.0
	 */
	public function duplicateTemplate( $tmpl_id ){
		
		$date	= JFactory::getDate()->toMySQL();
		$row	= $this->getTable( 'template' );
	
	 	if( !$row->load( $tmpl_id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//Clear the bro_id so store() will duplicate the row
		$row->tmpl_id 		= '';
		$row->tmpl_name 	= $row->tmpl_name . ' Copy';
		$row->tmpl_created	= '';
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row->tmpl_id;
		
	}
	
	
	
	
	/**
	 * Method to duplicate template layouts
	 *
	 * @param   integer  $tmpl_id  Template ID layouts belong to
	 * @param   integer  $duplicate_id  New Template ID layouts will belong to
	 *
	 * @return  boolean  True/False
	 *
	 * @since	1.0
	 */
	public function duplicateLayouts( $tmpl_id, $duplicate_id ){
		
		$date	= JFactory::getDate()->toMySQL();
		
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'l.*' );
			$query->from( '#__zbrochure_template_layouts AS l' );
			$query->where( 'tmpl_id = '.$tmpl_id );
			$query->where( 'tmpl_layout_published = 1' );
			$query->order( 'tmpl_layout_order' );
			
			$this->_db->setQuery($query);
			$layouts	= $this->_db->loadObjectList();
			
			if( empty( $layouts ) ){
		
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_TEMPLATE_LAYOUTS_NOT_FOUND') );
			
			}
			
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		foreach( $layouts as $layout ){
			
			$row			= $this->getTable( 'layouts' );
			$version_row	= $this->getTable( 'layoutversions' );
			
			
			//Duplicate the active version first so we can update the layout row
			if( !$version_row->load( $layout->tmpl_layout_current_version ) ){
		 	
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			$version_row->tmpl_layout_version_id		= '';
			$version_row->tmpl_layout_version_created	= $date;
		
			//alright, good to go. Store it to the Joomla db
			if( !$version_row->store() ){
			
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			//Duplicate the layout row
		 	if( !$row->load( $layout->tmpl_layout_id ) ){
		 	
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			$row->tmpl_layout_id		= '';
			$row->tmpl_id				= $duplicate_id;
			$row->tmpl_layout_version	= $version_row->tmpl_layout_version_id;
			$row->tmpl_layout_created	= $date;
		
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
		
			$this->duplicateBlocks( $layout->tmpl_layout_key, $row, $tmpl_id );
			
		}

		return true;
		
	}
	
	
	
	
	
	/**
	 * Method to duplicate content blocks
	 *
	 * @param   integer  $brochure_id  Brochure ID pages belong to
	 * @param   integer  $duplicate_id  New Brochure ID pages will belong to
	 *
	 * @return  boolean  True/False
	 *
	 * @since	1.0
	 */
	public function duplicateBlocks( $duplicate_page_id, $layout, $tmpl_id=0 ){
				
		$date = JFactory::getDate()->toSql();
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'b.content_block_id' );
			$query->from( '#__zbrochure_content_blocks AS b' );
			$query->where( 'b.content_page_id = '. $duplicate_page_id );
			
			if( $tmpl_id ){
				
				$query->where( 'b.content_tmpl_id = '. $tmpl_id );
				
			}
			
			$this->_db->setQuery( $query );
			$blocks = $this->_db->loadObjectList();
				
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $blocks ) ){
			
				return JError::raiseError( 404, JText::_( 'COM_ZBROCHURE_CONTENT_BLOCK_DUPLICATION_FAILED' ) );
			
			}
			
			foreach( $blocks as $data ){
			
				$row	= $this->getTable('contentblock');
			
			 	if( !$row->load( $data->content_block_id ) ){
			 	
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				
				}
								
				$row->content_block_id			= '';
				$row->content_page_id			= $layout->tmpl_layout_key;
				$row->content_tmpl_id			= $layout->tmpl_id;
				$row->content_bro_id			= 0;
				$row->content_block_created		= '';
				$row->content_block_created_by	= $this->_user;
				$row->content_block_modified	= '';
				$row->content_block_modified_by	= 0;
				$row->content_block_version		= 1;
				
				//Now we need to duplicate the content associated with the blocks
				$row->content_block_current_version = $this->_duplicateContent( $row );
								
				//alright, good to go. Store it to the Joomla db
				if( !$row->store() ){
				
					$this->setError($this->_db->getErrorMsg());
					return false;
				
				}
				
				unset( $row );
			
			}
			
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError( $e );
			
			}

		}
				
		return true;
	
	}
	
	
	
	
	
	/**
	 * Method to duplicate block content
	 *
	 * @param   object  $data  Block which to duplicate content for
	 *
	 * @return  boolean  True/False
	 *
	 * @since	1.0
	 */
	protected function _duplicateContent( $data ){
		
		$type		= $this->_getContentType( $data->content_block_type );
		
		require_once JPATH_COMPONENT.'/content/'.$type->content_type_folder.'/'.$type->content_type_element;		
		$class 		=  'ZbrochureContent'.$type->content_type_folder;
		
		$content	= $class::duplicateContent( $data->content_block_current_version );
		
		return $content;
	
	}
	
	
	
	/**
	 * Method to get a specific content type's data
	 *
	 * @param   integer  $id  Content type ID to get
	 *
	 * @return  object  zbrochure_content_types
	 *
	 * @since	1.0
	 */
	protected function _getContentType( $id ){
		
		try{
					
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'c.*' );
			
			$query->from( '#__zbrochure_content_types AS c' );
			$query->where( 'c.content_type_id = '.$id );
			
			$this->_db->setQuery($query);
			$type	= $this->_db->loadObject();
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $type ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_CONTENT_TYPE_GET_FAILED') );
			
			}			
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
			
		return $type;
		
	}
	
	
	
	/**
	 * Duplicate brochure row in db
	 *
	 * @param   integer  $brochure_id     Brochure ID to be duplicated
	 *
	 * @return  integer  New bro_id of the duplicated brochure
	 *
	 * @since	11.1
	 */
	public function duplicateBroRow( $brochure_id ){
	
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select( '*' );
		$query->from( '#__zbrochure_brochures' );
		$query->where( 'bro_id = '.(int)$brochure_id );
		
		$db->setQuery($query);
		$this->_original = $db->loadObject();
			
		$row	= $this->getTable( 'brochure' );
	
	 	if( !$row->bind( $this->_original ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//Clear the bro_id so store() will duplicate the row
		$row->bro_id = '';
		$row->bro_title = $row->bro_title . ' Copy';
	
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
	
		return $row->bro_id;
		
	}
	
	/**
	 * Duplicate brochure pages
	 *
	 * @param   integer  $brochure_id     Brochure ID to be duplicated
	 *
	 * @return  boolean  True/False
	 *
	 * @since	11.1
	 */
	public function duplicatePages( $brochure_id, $duplicate_id ){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_brochure_pages' );
			$query->where( 'bro_id = '.$brochure_id );
			$query->order( 'bro_page_order' );
			
			$db->setQuery($query);
			$pages = $db->loadObjectList();
			
			if( empty( $pages ) ){
		
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_BROCHURE_PAGES_NOT_FOUND') );
			
			}
			
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		foreach( $pages as $page ){
			
			$row	= $this->getTable( 'pages' );
		
		 	if( !$row->bind( $page ) ){
		 	
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			//Clear the bro_page_id so store() will duplicate the row
			$row->bro_page_id = '';
			
			//Set the bro_id to the newly duplicated brochure
			$row->bro_id = $duplicate_id;
			
			//Clear created date
			$row->bro_page_created = '';
		
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
			
			$this->duplicateContentBlocks( $page->bro_page_id, 0, $row );
			
			//***
			//****Now let's duplicate the thumbnail for the cover
			jimport( 'joomla.filesystem.file' );
			$com_params		= JComponentHelper::getParams('com_zbrochure');
			$original_th	= JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.$brochure_id.DS.$row->bro_page_thumb;
			$destination	= JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.$duplicate_id;
			
			if( !JFolder::exists( $destination ) ){JFolder::create( $destination );}
			
			JFile::copy( $original_th, $destination.DS.$row->bro_page_thumb );
			
		}
		
	}
	
	public function trashBrochure( $id ){
		
		$bro = $this->getTable();
		
		if( !$bro->load( $id ) ){
			
			$this->setError( $this->_db->getErrorMsg() );
			return false;
			
		}
		
		$date					= JFactory::getDate();
		$bro->bro_published		= 2;
		$bro->bro_modified_by	= $this->_user;
		$bro->bro_modified		= $date->toSql();
		
		if( !$bro->store( $bro ) ){
			
			$this->setError( $this->_db->getErrorMsg() );
			return false;
			
		}
		
		return true;
		
	}
	
	public function deleteBrochure( $id ){
		
		$bro = $this->getTable();
		
		if( !$bro->delete( $id ) ){
			
			$this->setError( $this->_db->getErrorMsg() );
			return false;
			
		}
		
		$query	= $this->_db->getQuery(true);
		$query->delete();
		$query->from( '#__zbrochure_brochure_pages' );
		$query->where( 'bro_id = '.$id );
		$this->_db->setQuery($query);
		$this->_db->query();
		
		//get all of the content blocks associated with the deleted brochure and delete them as well
		$query	= $this->_db->getQuery(true);
		
		$query->select( 'c.*, t.*' );
		$query->from( '#__zbrochure_content_blocks AS c' );
		$query->join( 'LEFT', '#__zbrochure_content_types AS t ON t.content_type_id = c.content_block_type' );
		$query->where( 'c.content_bro_id = '.$id );
		
		//$query->where( 'c.content_block_type = 13' );
		
		$this->_db->setQuery($query);
		$blocks	= $this->_db->loadObjectList();
		
		//go through each block and delete the content associated with it
		//and if successful, delete the content block
		foreach( $blocks as $block ){
			
			require_once JPATH_COMPONENT.'/content/'.$block->content_type_folder.'/'.$block->content_type_element;
			$class 		=  'ZbrochureContent'.$block->content_type_folder;
			$success	= $class::deleteContent( $block );
			
			if( $success ){
				
				$query = $this->_db->getQuery(true);
				$query->delete();
				$query->from( '#__zbrochure_content_blocks' );
				$query->where( 'content_block_id = '.$block->content_block_id );
				$this->_db->setQuery( $query );
				$this->_db->query();
				
			}
			
		}
		
		//move any associated files that have been generated to a deleted directory on the server
		//for now we'll keep them just in case we ever need them from some silly reason
		//jimport( 'joomla.filesystem.file' );
		//jimport( 'joomla.filesystem.folder' );
		
		$delete_dir	= JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.'deleted';
		$bro_dir	= JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.$id;
		
		if( !JFolder::exists( $delete_dir ) ){

			JFolder::create( $delete_dir );

		}
		
		if( JFolder::exists( $bro_dir ) ){
			
			print_r( $bro_dir );
			print_r('<p>-----</p>');
			print_r( $delete_dir );
			
			JFolder::move( $bro_dir, $delete_dir.DS.$id.'_'.date('m-d-Y') );

		}
				
		return true;
		
	}
	
	/**
	 * Get the page number (order) of the saved text block
	 *
	 * @param   integer  $brochure_id     Brochure ID to be duplicated
	 *
	 * @return  integer  $page	Page Number (order)
	 *
	 * @since	11.1
	 */
	public function getPageNumber( $block_id ){
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( 'b.*, p.*' );
			$query->from( '#__zbrochure_content_blocks AS b' );
			$query->join( 'LEFT', '#__zbrochure_brochure_pages AS p ON p.bro_page_id = b.content_page_id' );
			$query->where( 'b.content_block_id = '.$block_id );
			
			$db->setQuery($query);
			$page = $db->loadObject();
			
			return $page->bro_page_order;
		
	}
	
	
	
	/**
	 * Method to generate CSS file
	*/
	private function _generateCss( $styles, $filename, $tmpl_id ){
		
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );
		
		$root		= JPATH_SITE.'/media/zbrochure/tmpl';		
		$tmpl_path	= JPath::clean( $root.'/'.$tmpl_id.'/' );
		
		if( !JFolder::exists( $tmpl_path ) ){
		
			JFolder::create( $tmpl_path );
			
			$index	= '<html>'.PHP_EOL.'<body bgcolor="#FFFFFF">'.PHP_EOL.'</body>'.PHP_EOL.'</html>';
			JFile::write( $tmpl_path . "/index.html", $index );
		
		}
		
		JFile::write( $tmpl_path . $filename, $styles );
		
		if( !JFile::exists( $tmpl_path . $filename ) ){
			
			return false;
			
		}
		
		return;
		
	}
	
	
	
	
	
	
	/**
	 * Method to save a template
	 */
	public function store( $data ){
		
		$default_styles	= $this->_generateCss( $data['tmpl_default_styles'], 'default_styles.css', $data['tmpl_id'] );
		$editor_styles	= $this->_generateCss( $data['tmpl_editor_styles'], 'editor_styles.css', $data['tmpl_id'] );
			
		if( !$editor_styles ){
			
			$this->setError( 'editor styles was not generated and saved.' );
			
		}
		
		if( !$default_styles ){
			
			$this->setError( 'default styles was not generated and saved.' );
			
		}
				
		$data['tmpl_default_styles'] = JRequest::getVar( 'tmpl_default_styles', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		//print_r( $data['tmpl_default_styles'] );
		//print_r( '<p>----------------------------</p>' );
		
		$user		= JFactory::getUser()->get('id');
		$row		= $this->getTable( 'template' );
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		if( !$row->tmpl_id ){  	
		
			$is_new = 1;
			$row->tmpl_created_by	= $user;
		
		}else{
			
			$date = JFactory::getDate();
			$row->tmpl_modified_by	= $user;
			$row->tmpl_modified		= $date->toSql();			
			$row->tmpl_version++;
		
		}
	
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		//print_r( $row->tmpl_default_styles );
		//exit();
		
		return $row;
	
	}
	
	
	/**
	 * Method to save a template
	 */
	public function storeLayout( $data ){
		
		//$data['tmpl_default_styles'] = JRequest::getVar( 'tmpl_default_styles', '', 'post', 'string', JREQUEST_ALLOWRAW );
			
		$user		= JFactory::getUser()->get('id');
		$row		= $this->getTable( 'layouts' );
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		if( !$row->tmpl_layout_id ){  	
		
			$is_new = 1;
			$row->tmpl_layout_created_by	= $user;
		
		}else{
			
			$date = JFactory::getDate();
			$row->tmpl_layout_modified_by	= $user;
			$row->tmpl_layout_modified		= $date->toSql();			
			$row->tmpl_layout_version++;
		
		}
	
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row;
	
	}
		
}