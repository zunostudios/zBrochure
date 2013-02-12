<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure text element
 */
class ZbrochureContentText{
	
	/**
	 * @var string output
	 */	
	protected $_data;
	
	/**
	 * @var string output
	 */	
	protected $_output;
	
	/**
	 * Method to render an image content element
	 */
	public function getContent( $block, $edit, $output='renderContent' ){
		
		$db		= $this->getDbo();
		$query	= $db->getQuery( true );
		
		$query->select( '*' );
		$query->from( '#__'.$block->content_type_table );
		$query->where( 'id = '.$block->content_block_current_version );
				
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
		
		switch( $output ){
			
			case 'renderAdmin':
				$output = ZbrochureContentText::renderAdmin( $this->_data, $block );
			break;
			
			default:
				$output	= ZbrochureContentText::renderContent( $this->_data, $block, $edit );
		}
				
		return $output;
	
	}
	
	/**
	 * Method to render an image content element
	 */
	public function renderContent( $data, $block, $edit ){
	
		$randomid = rand();
	
		$this->_output = '';
	
		$this->_output .= '<div id="textblock-'.$randomid.'" class="editable-content">'.$data->data.'</div>';
		
		if( $edit == 1 ){
		
			//**************** Start Dialog	****************//
			$this->_edit_output = '<div id="edittext-'.$randomid.'" title="Edit Text Block">';
			$this->_edit_output .= '<form action="index.php?option=com_zbrochure&task=saveBlock" method="post">';
			
			$this->_edit_output .= '<div class="form-row text-container">';
			
			$this->_edit_output .= '<div class="form-row add-padding package-container-header">';
			
			$this->_edit_output .= '<input type="text" name="content[data]" class="inputbox" value="'.$data->data.'" style="width:97%" />';
			$this->_edit_output .= '<input name="content[id]" value="'.$data->id.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '</div>';
			
			$this->_edit_output .= '</div>';
			
			$this->_edit_output .= '<button class="var-btn btn build-bro-btn" type="button">Available Variables</button>';
			
			$this->_edit_output .= '<div class="form-row btn-container add-padding">
									<button class="btn save-btn fr" type="submit"><span>Save</span></button>
									<button class="btn cancel-btn fr" onclick="location.reload(); return false;"><span>Cancel</span></button>
								</div>';
			
			$this->_edit_output .= '<input name="content_block_id" value="'.$block->content_block_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_block_type" value="'.$block->content_block_type.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_bro_id" value="'.$block->content_bro_id.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '<input name="view" value="'.JRequest::getVar('view').'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_id" value="'.$block->bro_id.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '<input name="bro_page_id" value="'.$block->bro_page_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_page_order" value="'.$block->bro_page_order.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '</form>';
			$this->_edit_output .= '</div>';
			//**************** End Dialog ****************//
			
			
			$this->_edit_output .= '		<script type="text/javascript">
			
									$(document).ready(function(){
									
										$( "#textblock-'.$randomid.'" ).click(function(){
											$("#edittext-'.$randomid.'").dialog(\'open\');
										});
				
										$( "#edittext-'.$randomid.'" ).dialog({
											autoOpen: false,
											resizable: true,
											height:150,
											width:400,
											modal: true,
											close: function(){
												$( \'#vars-dialog\' ).dialog(\'close\');
											}
										});
					
									});
							
									</script>';
									
		}
				
		$output->render = $this->_output;
		$output->edit	= $this->_edit_output;
				
		return $output;
	
	}

	public function renderAdmin( $data, $block ){
		
		$this->_output = '';
		
		$this->_output .= '<input type="text" class="inputbox" name="content[data]" style="width:98%" value="'.$data->data.'" />'.PHP_EOL;
		$this->_output .= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
		
		return $this->_output;
		
	}
	
	public function saveContent( $data, $new_version=false ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
		
		if( $new_version ){
			
			unset( $data['id'] );
			
		}
				
		$row	= JTable::getInstance( 'contenttext', 'Table' );
				
		if( !$row->bind( $data ) ){
		
			$this->setError( JText::_( 'ERROR_BINDING_CONTENT_TEXT' ) );
			return false;
		
		}
		
		if( $new_version ){
			
			$row->created_by	= $user;
			$row->created		= '';
			$row->modified_by	= 0;
			$row->modified		= '0000-00-00 00:00:00';	
			$row->version		= 1;
			
		}else{
		
			$row->modified_by	= $user;
			$row->modified		= $date;
			$row->version++;
			
		}
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_STORING_CONTENT_TEXT' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contenttext', 'Table' );
		
		if( !$row->load( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DUPLICATING_LOAD_CONTENT_TEXT' ) );
			return false;
		
		}
		
		$row->id = '';
		$row->created_by	= $user;
		$row->created		= $date;
		$row->modified_by	= 0;
		$row->modified		= '0000-00-00 00:00:00';			
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_DUPLICATING_STORE_CONTENT_TEXT' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contenttext', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_TEXT' ) );
			return false;
		
		}
		
		return true;
		
	}
	
}