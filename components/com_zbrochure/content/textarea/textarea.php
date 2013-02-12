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
class ZbrochureContentTextarea{
	
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
		
		//If this block has never been edited, let's get the default value
		//If the block has been edited, let's get the current version of the content -MM
		if( $block->content_block_current_version == 0){
			$query->where( 'id = '.$block->content_content_id );
		}else{
			$query->where( 'id = '.$block->content_block_current_version );
		}
		
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
		
		switch( $output ){
			
			case 'renderAdmin':
				$output = ZbrochureContentTextarea::renderAdmin( $this->_data, $block );
			break;
			
			default:
				$output	= ZbrochureContentTextarea::renderContent( $this->_data, $block, $edit );
		}
				
		return $output;
	
	}
	
	/**
	 * Method to render an image content element
	 */
	public function renderContent( $data, $block, $edit ){
		
		$categories = $block->categories;
		$randomid = rand();
		
		$this->_output = '';
	
		$this->_output .= '<div id="textareablock-'.$randomid.'" class="editable-content">'.$data->data.'</div>'.PHP_EOL;
		
		if( $edit == 1 ){
			
			$categories 		= ZbrochureContentTextarea::getCategories();
			
			//**************** Start Dialog	****************//
			$this->_edit_output = '<div id="edittextarea-'.$randomid.'" title="Edit Text Block">'.PHP_EOL;
			
			$this->_edit_output .= '<form action="index.php?option=com_zbrochure&task=saveBlock" method="post">'.PHP_EOL;
			
			$this->_edit_output .= '<div class="form-row text-container">'.PHP_EOL;
			
			//If we have predefined content
			//this is just in case we have permissions or instances that would not have predefined content
			if( $categories ){
			
				$this->_edit_output .= '<div class="form-row add-padding package-container-header">'.PHP_EOL;
				
				$this->_edit_output .= '<label for="categories-'.$randomid.'">Load Predefined Content</label>'.PHP_EOL;
				
				//Build Category Select list
				$options[] = JHTML::_('select.option', '0', JText::_( '-- Select a Category --' ) );
				
				foreach( $categories as $k => $v ){
					
					$options[] = JHTML::_( 'select.option', $v->cat_id, JText::_( $v->cat_name ) );
					
				}
				
				$categories = JHTML::_('select.genericlist', $options, 'package_categories[]', 'onchange="activateContent(this.value, '.$randomid.')"', 'value', 'text', '', 'categories-'.$randomid.'' );
				
				$this->_edit_output .= $categories.PHP_EOL;
				
				$this->_edit_output .= '&nbsp;&nbsp;<select id="content-'.$randomid.'" onchange="placeContent(this.value, '.$randomid.');"></select>'.PHP_EOL;
				
				$this->_edit_output .= '</div>'.PHP_EOL;
				
			}
			
			$this->_edit_output .= '<div>'.PHP_EOL;
			
			$this->_edit_output .= '<textarea id="editor-'.$randomid.'" type="text" name="content[data]" class="editor" style="height:150px;width:100%">'.$data->data.'</textarea>'.PHP_EOL;
			$this->_edit_output .= '<input name="content[id]" value="'.$data->id.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '</div>'.PHP_EOL;
			
			$this->_edit_output .= '</div>'.PHP_EOL;
			
			$this->_edit_output .= '<button class="var-btn btn build-bro-btn" type="button" style="margin:10px 0 20px 20px"><span>'.JText::_( 'AVAILABLE_VARIABLES' ).'</span></button>'.PHP_EOL;
			
			$this->_edit_output .= '<div class="form-row btn-container add-padding" style="margin:10px 0 20px 0">
									<button class="btn cancel-btn icon-btn" onclick="location.reload(); return false;"><span class="icon"><!-- --></span><span class="btn-text">'.JText::_( 'CANCEL_BTN_GENERAL' ).'</span></button>
									<button class="btn save-btn icon-btn" type="submit"><span class="icon"><!-- --></span><span class="btn-text">'.JText::_( 'SAVE_BTN_GENERAL' ).'</span></button>
									</div>'.PHP_EOL;
			
			
			$this->_edit_output .= '<input name="content_block_id" value="'.$block->content_block_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_block_type" value="'.$block->content_block_type.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_bro_id" value="'.$block->content_bro_id.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '<input name="view" value="'.JRequest::getVar('view').'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_id" value="'.$block->bro_id.'" type="hidden" />'.PHP_EOL;
			
			$this->_edit_output .= '<input name="bro_page_id" value="'.$block->bro_page_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_page_order" value="'.$block->bro_page_order.'" type="hidden" />'.PHP_EOL;	
			
			$this->_edit_output .= '</form>'.PHP_EOL;
			$this->_edit_output .= '</div>'.PHP_EOL;
			//**************** End Dialog	****************//
			
			
			$this->_edit_output .= '<script type="text/javascript">
									
									$(document).ready(function(){
										
										CKEDITOR.replace("editor-'.$randomid.'");
										
										$( "#textareablock-'.$randomid.'" ).click(function(){
											$("#edittextarea-'.$randomid.'").dialog(\'open\');
										});
				
										$( "#edittextarea-'.$randomid.'" ).dialog({
											autoOpen: false,
											width: 600,
											modal: false,
											resizable: false,
											open: function( event, ui ){
												disable_scroll();
											},
											close: function( event, ui ){
												enable_scroll();
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
	
	/**
	 * Method to get content categories
	 */
	public function getCategories(){
				
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
				
		$query->select( 'c.*' );
		$query->from( '#__zbrochure_categories AS c' );
		$query->where( 'c.published = 1' );
		$query->order( 'c.cat_name' );
		
		$db->setQuery($query);
	
		return $db->loadObjectList( 'cat_id' );
	
	}
	
	public function renderAdmin( $data, $block ){
		
		$this->_output = '';
		
		$this->_output .= '<textarea class="inputbox" name="content[data]" style="width:98%;height:150px">'.$data->data.'</textarea>'.PHP_EOL;
		$this->_output .= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
		
		return $this->_output;
		
	}
	
	public function saveContent( $data, $new_version=false ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
		
		if( $new_version ){
			
			unset( $data['id'] );
			
		}
				
		$row	= JTable::getInstance( 'contenttextarea', 'Table' );
		
		if( !$row->bind( $data ) ){
		
			$this->setError( JText::_( 'ERROR_BINDING_CONTENT_TEXTAREA' ) );
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
		
			$this->setError( JText::_( 'ERROR_STORING_CONTENT_TEXTAREA' ) );
			return false;
		
		}
		
		return $row->id;
		
	}

	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contenttextarea', 'Table' );
		
		if( !$row->load( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DUPLICATING_LOAD_CONTENT_TEXTAREA' ) );
			return false;
		
		}
		
		$row->id = '';
		$row->created_by	= $user;
		$row->created		= $date;
		$row->modified_by	= 0;
		$row->modified		= '0000-00-00 00:00:00';			
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_DUPLICATING_STORE_CONTENT_TEXTAREA' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contenttextarea', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_TEXTAREA' ) );
			return false;
		
		}
		
		return true;
		
	}

}