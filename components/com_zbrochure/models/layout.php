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
class ZbrochureModelLayout extends JModelItem{

	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.layout';
	
	/**
	 * @var int id
	 */	
	protected $_id;
	
	/**
	 * @var int id
	 */	
	protected $_tmpl_id;
	
	/**
	 * @var obj layout
	 */	
	protected $_layout;
	
	/**
	 * @var obj layouts
	 */	
	protected $_layouts;
	
	/**
	 * @var object content types
	 */	
	protected $_blocks;
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id		= JRequest::getInt( 'id', 0 );
		$this->_tmpl_id	= JRequest::getInt( 'tmpl_id', 0 );	
		$this->_user	= JFactory::getUser()->get('id');
		
		parent::__construct();
		
	}
	
	/**
	 * Get the brochure pages
	 * @return object list of all pages
	 */
	public function getLayout(){
	
		if( empty($this->_layout) ){
		
			try{
				
				$query	= $this->_db->getQuery(true);
				
				$query->select( 'l.*, v.*, t.*' );
				$query->from( '#__zbrochure_template_layouts AS l' );
				$query->join( 'LEFT', '#__zbrochure_template_layout_versions AS v ON v.tmpl_layout_version_id = l.tmpl_layout_current_version' );
				$query->join( 'LEFT', '#__zbrochure_templates AS t ON t.tmpl_id = l.tmpl_id' );
				
				$query->where( 'l.tmpl_layout_id = '.$this->_id );
									
				$this->_db->setQuery( $query );
				$this->_layout = $this->_db->loadObject();
				
				$this->_layout->theme	= ZbrochureHelperThemes::getTheme( $this->_layout->tmpl_default_theme );
				
				//$this->_layout->blocks = $this->_getBlocks( $this->_layout->tmpl_id, $this->_layout->tmpl_layout_key );
				
				$content_blocks			= json_decode( $this->_layout->tmpl_layout_version_content, true );
				$image_blocks			= json_decode( $this->_layout->tmpl_layout_version_images, true );
				$design_blocks			= json_decode( $this->_layout->tmpl_layout_version_design, true );
				
				$un_tmpl_blocks 		= $this->_getBlocks( $this->_layout->tmpl_id, $this->_layout->tmpl_layout_key, $this->_layout->theme );
				
				//We need these pre-organization for the modal edit view
				$this->_layout->blocks	= $un_tmpl_blocks;
				
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
				
				$this->_layout->content_blocks 	= $html_matched;
				$this->_layout->image_blocks	= $image_matched;
				$this->_layout->design_blocks	= $design_matched;
				
								
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}
			
		}
		
		return $this->_layout;
	
	}
	
	/**
	 * Get the next layout key
	 * @return int
	 */
	public function getNextLayoutKey( $tmpl_id ){
			
		$query	= $this->_db->getQuery(true);
		
		$query->select( 'l.tmpl_layout_key' );
		$query->from( '#__zbrochure_template_layouts AS l' );
		$query->where( 'l.tmpl_id = ' . $tmpl_id );
		$query->order( 'l.tmpl_layout_key DESC' );
		
							
		$this->_db->setQuery( $query );
		$key = $this->_db->loadObject();
		
		$key = (int)$key->tmpl_layout_key + 1;	
	
		return $key;
	
	}
	
	
	/**
	 * Get the template layout blocks
	 * @return object with all the blocks associated with a specific layout
	 */
	private function _getBlocks( $tmpl_id, $page_id, $theme ){
				
		try{
			
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'c.*, t.*' );
			$query->from( '#__zbrochure_content_blocks AS c' );
			$query->join( 'LEFT', '#__zbrochure_content_types AS t ON t.content_type_id = c.content_block_type' );
			$query->where( 'c.content_page_id = '.$page_id );
			$query->where( 'c.content_tmpl_id = '.$tmpl_id );
			
			$this->_db->setQuery( $query );
			$blocks = $this->_db->loadObjectList();
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
			
			foreach( $blocks as $block ){
				
				$block->bro_theme	= $theme;
				$block->data		= $this->_getBlockContent( $block, 0 );
				
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
	private function _getBlockContent( $block, $edit ){
		
		require_once JPATH_COMPONENT.'/content/'.$block->content_type_folder.'/'.$block->content_type_element;		
		$class =  'ZbrochureContent'.$block->content_type_folder;
		$output	= $class::getContent( $block, $edit );
	
		return $output;
	
	}
		
	/**
	 * Method to save a template
	 */
	public function store( $data ){
		
		$user		= JFactory::getUser()->get('id');
		
		$version	= $this->getTable( 'layoutversions' );
		
		if( !$version->bind( $data['version'] ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//alright, good to go. Store it to the Joomla db
		if( !$version->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( $version->tmpl_layout_version_id ){
		
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
		
			$row->tmpl_layout_current_version = $version->tmpl_layout_version_id;
		
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
		
		}
		
		return $row;
	
	}
	
	/**
	 * Method to duplicate a template layout
	 */
	public function duplicate( $id ){
		
		$layout	= $this->getLayout();
		
		$user		= JFactory::getUser()->get('id');
		$date		= JFactory::getDate();
		
		$version	= $this->getTable( 'layoutversions' );
		
		if( !$version->load( $layout->tmpl_layout_current_version ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		$version->tmpl_layout_version_id 			= '';
		$version->tmpl_layout_version_created_by	= $user;
		$version->tmpl_layout_version_created		= $date->toSql();
		
		//alright, good to go. Store it to the Joomla db
		if( !$version->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( $version->tmpl_layout_version_id ){
			
			$row		= $this->getTable( 'layouts' );
			
		 	if( !$row->load( $id ) ){
		 	
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			$row->tmpl_layout_id				= '';
			$row->tmpl_layout_key				= $this->getNextLayoutKey( $row->tmpl_id );
			$row->tmpl_layout_current_version	= $version->tmpl_layout_version_id;
			
			$row->tmpl_layout_created_by	= $user;
			$row->tmpl_layout_created		= $date->toSql();
			$row->tmpl_layout_modified_by	= 0;
			$row->tmpl_layout_modified		= '0000-00-00 00:00:00';			
			$row->tmpl_layout_version		= 1;
			
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
			
		}
		
		return $row;
		
	}
	
	/**
	 * Method to duplicate a layout row
	 */
	private function _getLayoutRow(){
	
		$query	= $this->_db->getQuery(true);
				
		$query->select( 'l.*' );
		$query->from( '#__zbrochure_template_layouts AS l' );	
		$query->where( 'l.tmpl_layout_id = '.$this->_id );
							
		$this->_db->setQuery( $query );
		
		return $this->_db->loadAssoc();
	
	}
	
	/**
	 * Method to duplicate a layout version row
	 */
	private function _getLayoutVersionRow( $tmpl_layout_current_version ){
	
		$query	= $this->_db->getQuery(true);
				
		$query->select( 'v.*' );
		$query->from( '#__zbrochure_template_layout_versions AS v' );	
		$query->where( 'v.tmpl_layout_version_id = '.$tmpl_layout_current_version );
							
		$this->_db->setQuery( $query );
		
		return $this->_db->loadAssoc();
	
	}
	
	/**
	 * Method to duplicate a layout version row
	 */
	private function _getBlockRows( $tmpl_id, $tmpl_layout_key ){

		$query	= $this->_db->getQuery( true );
			
		$query->select( 'c.*, t.*' );
		$query->from( '#__zbrochure_content_blocks AS c' );
		$query->join( 'LEFT', '#__zbrochure_content_types AS t ON t.content_type_id = c.content_block_type' );
		$query->where( 'c.content_page_id = '.$tmpl_layout_key );
		$query->where( 'c.content_tmpl_id = '.$tmpl_id );
		
		$this->_db->setQuery( $query );
		$blocks = $this->_db->loadAssocList();
		
		if( $error = $this->_db->getErrorMsg() ){

			throw new Exception( $error );

		}
		
		foreach( $blocks as $block ){
			
			
			
			
			//$block['content']	= $this->_getBlockContent( $block, 0 );
			
		}
		
		return $blocks;
		
	}
		
}