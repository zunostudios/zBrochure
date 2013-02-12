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
class ZbrochureModelBlock extends JModelItem{

	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.block';
	
	/**
	 * @var int id
	 */	
	protected $_id;
	
	/**
	 * @var obj block
	 */	
	protected $_block;
	
	/**
	 * @var obj content
	 */	
	protected $_content;
	
	/**
	 * @var obj type
	 */	
	protected $_type;
			
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
	 * Method to get list of Packages associated with this brochure
	 */
	public function getBlock(){
	
		if( empty($this->_block) ){
	
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'b.*, t.*, u.*, m.email AS modified_email, m.username AS modified_username, m.name AS modified_name' );
			
			$query->from( '#__zbrochure_content_blocks AS b' );
			
			$query->join( 'LEFT', '#__users AS u ON u.id = b.content_block_created_by' );
			$query->join( 'LEFT', '#__users AS m ON m.id = b.content_block_modified_by' );
			$query->join( 'LEFT', '#__zbrochure_content_types AS t ON t.content_type_id = b.content_block_type' );
			
			$query->where( 'b.content_block_id = '.$this->_id );
			
			$this->_db->setQuery($query);
			$this->_block = $this->_db->loadObject();
			
			$this->_block->render	= $this->_getContent();
			
		}
		
		return $this->_block;
	
	}
	
	private function _getContent(){
		
		if( empty( $this->_content ) ){
		
			require_once JPATH_COMPONENT.'/content/'.$this->_block->content_type_folder.'/'.$this->_block->content_type_element;		
			$class =  'ZbrochureContent'.$this->_block->content_type_folder;
			
			$this->_content	= $class::getContent( $this->_block, 0, 'renderAdmin' );
		
		}
		
		return $this->_content;
		
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
		
		$row	= $this->getTable( 'content' . $type );
	
		$date	= JFactory::getDate()->toMySQL();
		
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
	public function updateBlock( $block_id, $current_version, $bro, $bro_page ){
		
		$row		= $this->getTable( 'block' );
			
	 	if( !$row->load( $block_id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//If this is set to anything other than 0 it means this block is a default template block
		//and we need to duplicate it instead of updating and overwriting.
		if( $row->content_tmpl_id != 0 ){
			
			$row->content_block_id = '';
			
		}
		
		$row->content_block_current_version = $current_version;
		$row->content_bro_id	= $bro->getId();
		$row->content_tmpl_id	= '';
		$row->content_page_id	= $bro_page;
				
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
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
	 * Method to save a brochure
	 */
	public function store( $data ){
		
		$date	= JFactory::getDate()->toMySQL();
		
		if( $data['content_bro_id'] != $data['bro_id'] ){
			
			$new 	= true;
			
		}else{
			
			$new  	= false;
			
		}
		
		$user		= JFactory::getUser()->get('id');
		$type		= $this->_getContentType( $data['content_block_type'] );
		
		require_once JPATH_COMPONENT.'/content/'.$type->content_type_folder.'/'.$type->content_type_element;		
		$class 		=  'ZbrochureContent'.$type->content_type_folder;
		
		$content	= $class::saveContent( $data['content'], $new );
		
		if( $content ){
		
			$row		= $this->getTable( 'block' );
			
		 	if( !$row->load( $data['content_block_id'] ) ){
		 	
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
					
			$row->content_block_current_version = $content;
			
			if( !$row->content_block_id ){  	
			
				$is_new = 1;
				$row->content_block_created_by	= $user;
			
			}else{
				
				
				$row->content_block_modified_by	= $user;
				$row->content_block_modified	= $date;			
				$row->content_block_version++;
			
			}
			
			if( $new ){
				
				$row->content_block_id 	= '';
				$row->content_bro_id	= $data['bro_id'];
				$row->content_tmpl_id	= '';
				$row->content_page_id	= $data['bro_page_id'];
				
			}
			
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
		
		}
			
		return $row->content_block_id;
	
	}
	
	/**
	 * Method to duplicate a block
	 */
	public function duplicate( $block, $tmpl_id=0, $bro_id=0, $page_id ){
		
		$user		= JFactory::getUser()->get('id');
		$date 		= JFactory::getDate()->toSql();
		
		$type		= $this->_getContentType( $block->content_block_type );
		
		require_once JPATH_COMPONENT.'/content/'.$type->content_type_folder.'/'.$type->content_type_element;		
		$class 		=  'ZbrochureContent'.$type->content_type_folder;
		
		$content	= $class::duplicateContent( $block->content_block_current_version );
		
		if( $content ){
		
			$row		= $this->getTable( 'block' );
			
		 	if( !$row->load( $block->content_block_id ) ){
		 	
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			
			}
			
			$row->content_block_id 				= '';
			$row->content_bro_id				= $bro_id;
			$row->content_tmpl_id				= $tmpl_id;
			$row->content_page_id				= $page_id;
			$row->content_block_current_version = $content;
			$row->content_block_created_by		= $user;
			$row->content_block_created			= $date;	
			$row->content_block_modified_by		= 0;
			$row->content_block_modified		= '0000-00-00 00:00:00';			
			$row->content_block_version			= 1;
		
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
		
		}
			
		return $row->content_block_id;
	
	}
	
	
	
	/**
	 * Method to delete a block
	 */
	public function delete( $block_id ){
		
		$row		= $this->getTable( 'block' );
			
	 	if( !$row->load( $block_id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		$type		= $this->_getContentType( $row->content_block_type );
		
		require_once JPATH_COMPONENT.'/content/'.$type->content_type_folder.'/'.$type->content_type_element;		
		$class 		=  'ZbrochureContent'.$type->content_type_folder;
		
		$success	= $class::deleteContent( $row->content_block_current_version );
		
		//if( $sucess ){
			
			$block		= $this->getTable( 'block' );	
			
			if( !$block->delete( $block_id ) ){
			
				$this->setError( JText::_( 'ERROR_DELETING_CONTENT_BLOCK' ) );
				return false;
			
			}
			
			return true;
			
		//}
		
		//return false;
		
	}
	
	
	/**
	 * Method to get the details for a specific content block type
	 */
	private function _getContentType( $id ){
		
		if( empty( $this->_type ) ){
			
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'c.*' );
			
			$query->from( '#__zbrochure_content_types AS c' );
			$query->where( 'c.content_type_id = '.$id );
			
			$this->_db->setQuery($query);
			$this->_type = $this->_db->loadObject();
			
		}
		
		return $this->_type;
		
	}
	
	/**
	 * Method to get list of published brochures
	 */
	public function getBrochures(){
	
		if( empty($this->_brochures) ){
	
			$query	= $this->_db->getQuery(true);
			
			$query->select( 'b.bro_id AS value, b.bro_title AS text' );
			$query->from( '#__zbrochure_brochures AS b' );
			$query->where( 'b.bro_published = 1' );
			
			$this->_db->setQuery($query);
			$this->_brochures = $this->_db->loadObjectList();

		}
		
		return $this->_brochures;
	
	}
	
	/**
	 * Method to get list of published templates
	 */
	public function getTemplates(){
	
		if( empty($this->_templates) ){
	
			$query	= $this->_db->getQuery(true);
			
			$query->select( 't.tmpl_id AS value, t.tmpl_name AS text' );
			$query->from( '#__zbrochure_templates AS t' );
			$query->where( 't.tmpl_published = 1' );
			
			$this->_db->setQuery($query);
			$this->_templates = $this->_db->loadObjectList();

		}
		
		return $this->_templates;
	
	}
	
	/**
	 * Method to get list of published templates
	 */
	public function getBlockTypes(){
	
		if( empty($this->_block_types) ){
	
			$query	= $this->_db->getQuery(true);
			
			$query->select( 't.content_type_id AS value, t.content_type_name AS text' );
			$query->from( '#__zbrochure_content_types AS t' );
			$query->where( 't.content_type_published = 1' );
			
			$this->_db->setQuery($query);
			$this->_block_types = $this->_db->loadObjectList();

		}
		
		return $this->_block_types;
	
	}	
}