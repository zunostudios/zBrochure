<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure image element
 */
class ZbrochureContentImage{
	
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
		
		$query->select( 'i.*, a.*, r.cid AS bro_client' );
		$query->from( '#__'.$block->content_type_table.' AS i' );
		$query->join( 'LEFT', '#__zbrochure_assets AS a ON a.assetid = i.data' );
		$query->join( 'LEFT', '#__zbrochure_asset_rel AS r ON r.aid = a.assetid' );
			
		$query->where( 'i.'.$block->content_type_table_key.' = '.$block->content_block_current_version );
		
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
		
		switch( $output ){
			
			case 'renderAdmin':
				$output = ZbrochureContentImage::renderAdmin( $this->_data, $block );
			break;
			
			default:
				$output	= ZbrochureContentImage::renderContent( $this->_data, $block, $edit );
		}
		
		return $output;
	
	}
	
	/**
	 * Method to render an image content element
	 */
	public function renderContent( $data, $block, $edit ){
		
		$media_dir			= JComponentHelper::getParams('com_zbrochure')->get( 'asset_file_path' );
		$mediamanager_url	= JRoute::_('index.php?option=com_zbrochure&view=mediamanager&layout=modal&tmpl=modal&block_id='.$block->content_block_id.'&bro_page_order='.$block->bro_page_order.'&data='.$data->data );
				
		$scale				= $data->image_scale / 100;
	
		$size				= getimagesize( JURI::base().$media_dir.DS.'full'.DS.$data->asset_file );		
		
		$width				= $size[0] / 96;
		$height				= $size[1] / 96;
		
		$scaled_width		= $width * $scale;
		$scaled_height		= $height * $scale;
		
		$doc				= JFactory::getDocument();
		
		$block_select->content_block_id = $block->content_block_id;
		$block_select->content_bro_id = $block->content_bro_id;
		$block_select->content_page_id = $block->content_page_id;
		$block_select->content_tmpl_block_id = $block->content_tmpl_block_id;
		$block_select->content_block_current_version = $block->content_block_current_version;
		$block_select->content_block_type = $block->content_block_type;
		$block_select->content_type_element = $block->content_type_element;
		$block_select->content_type_folder = $block->content_type_folder;
		
		
		$this->_output = '<image asset="'.$data->data.'" x="'.$data->image_x.'" y="'.$data->image_y.'" width="'.round($scaled_width,3).'" height="'.round($scaled_height,3).'" nwidth="'.round($width,3).'" nheight="'.round($height,3).'" zscale="'.$data->image_scale.'" preserveAspectRatio="none" xlink:href="'.JURI::base().$media_dir.DS.'full'.DS.$data->asset_file.'" id="image_'.$block->content_block_id.'"   />'.PHP_EOL;
		
		//transform="scale('.$scale.')";
		
		$this->_edit_output = '';
		
		$this->_edit_output .= '<div id="edit-image-'.$block->content_block_id.'" title="'.JText::_( 'EDIT_PHOTO' ).'">'.PHP_EOL;
		
		$this->_edit_output .= '<form id="image-form-'.$block->content_block_id.'" name="image-form-'.$block->content_block_id.'" action="index.php?option=com_zbrochure&task=saveBlock" method="post">'.PHP_EOL;
		
		$this->_edit_output .= '<div id="move-btns-'.$block->content_block_id.'" class="form-row move-btns text-container">'.PHP_EOL;
		
			$this->_edit_output .= '<div class="form-row add-padding package-container-header">'.PHP_EOL;
			
				$this->_edit_output .= '<div id="move-btns-inner-'.$block->content_block_id.'" class="move-btns-inner">'.PHP_EOL;
				
					$this->_edit_output .= '<button class="up-btn" type="button">Up</button>'.PHP_EOL;
					
					$this->_edit_output .= '<div id="move-btns-hor-'.$block->content_block_id.'" class="move-btns-hor">'.PHP_EOL;
					
						$this->_edit_output .= '<button class="left-btn" type="button">Left</button>'.PHP_EOL;
						$this->_edit_output .= '<button class="center-btn" type="button">Reset image position</button>'.PHP_EOL;
						$this->_edit_output .= '<button class="right-btn" type="button">Right</button>'.PHP_EOL;
					
					$this->_edit_output	.= '</div>'.PHP_EOL;
					
					$this->_edit_output .= '<button class="down-btn" type="button">Down</button>'.PHP_EOL;
							
				$this->_edit_output	.= '</div>'.PHP_EOL;
				
				$this->_edit_output .= '<div id="scale-container-'.$block->content_block_id.'" class="scale-container block">'.PHP_EOL;
					
					$this->_edit_output .= '<label style="text-align:center;text-indent:0;margin:0 0 15px 0"><input type="text" class="inputbox" name="content[image_scale]" id="image_scale-'.$block->content_block_id.'" value="'.$data->image_scale.'" style="width:30px;text-align:center" /> %</label>';
					
					//$this->_edit_output .= '<div id="image-edit-scale-current-'.$block->content_block_id.'" class="image-edit-scale-txt">'.round( $data->image_scale ).'%</div>'.PHP_EOL;
					$this->_edit_output .= '<div id="image-edit-scale-slider-'.$block->content_block_id.'" class="image-edit-scale-slider"></div>'.PHP_EOL;
				
				$this->_edit_output	.= '</div>'.PHP_EOL;
	
			
			$this->_edit_output	.= '</div>'.PHP_EOL;
		
		$this->_edit_output	.= '</div>'.PHP_EOL;
		
		
		$this->_edit_output	.= '<div id="action-btns-'.$block->content_block_id.'" class="btns-container action-btns-image">'.PHP_EOL;
		
			$this->_edit_output	.= '<div id="change-btns-'.$block->content_block_id.'" class="change-btns">'.PHP_EOL;
				$this->_edit_output	.= '<button class="change-img-btn btn build-bro-btn" type="button">Change image</button>'.PHP_EOL;
			$this->_edit_output	.= '</div>'.PHP_EOL;
			
			$this->_edit_output	.= '<button class="btn cancel-btn" onclick="location.reload(); return false;" type="button"><span>Cancel</span></button>'.PHP_EOL;
			$this->_edit_output	.= '<button class="btn save-btn"  type="submit"><span>Save</span></button>'.PHP_EOL;
		
		$this->_edit_output	.= '</div>'.PHP_EOL;
		
		$this->_edit_output	.= '<input type="hidden" name="content[image_x]" id="image_x-'.$block->content_block_id.'" value="'.$data->image_x.'" />'.PHP_EOL;
		$this->_edit_output	.= '<input type="hidden" name="content[image_y]" id="image_y-'.$block->content_block_id.'" value="'.$data->image_y.'" />'.PHP_EOL;
		//$this->_edit_output	.= '<input type="hidden" name="content[image_scale]" id="image_scale-'.$block->content_block_id.'" value="'.$data->image_scale.'" />'.PHP_EOL;
		$this->_edit_output	.= '<input type="hidden" name="content[image_rotate]" id="image_rotate-'.$block->content_block_id.'" value="'.$data->image_rotate.'" />'.PHP_EOL;
		$this->_edit_output	.= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
		$this->_edit_output	.= '<input type="hidden" name="content[data]" value="'.$data->data.'" />'.PHP_EOL;
		
		$this->_edit_output .= '<input name="content_block_id" value="'.$block->content_block_id.'" type="hidden" />'.PHP_EOL;
		$this->_edit_output .= '<input name="content_block_type" value="'.$block->content_block_type.'" type="hidden" />'.PHP_EOL;
		$this->_edit_output .= '<input name="content_bro_id" value="'.$block->content_bro_id.'" type="hidden" />'.PHP_EOL;		
		$this->_edit_output .= '<input name="view" value="'.JRequest::getVar('view').'" type="hidden" />'.PHP_EOL;
		$this->_edit_output .= '<input name="bro_id" value="'.$block->bro_id.'" type="hidden" />'.PHP_EOL;
		$this->_edit_output .= '<input name="bro_page_id" value="'.$block->bro_page_id.'" type="hidden" />'.PHP_EOL;
		$this->_edit_output .= '<input name="bro_page_order" value="'.$block->bro_page_order.'" type="hidden" />'.PHP_EOL;			
		
		$this->_edit_output	.= '</form>'.PHP_EOL;
		
		$this->_edit_output	.= '</div>'.PHP_EOL;
		
		
		$this->_edit_output	.= '<script type="text/javascript">$(document).ready(function(){
				
				var edit_image_'.$block->content_block_id.' = new editBlockImage().init( '.$block->content_block_id.', "'.$mediamanager_url.'" );
			
		});</script>'.PHP_EOL;
		
		$output->render = $this->_output;
		$output->edit	= $this->_edit_output;
				
		return $output;
	
	}
	
	public function renderAdmin( $data, $block ){
		
		$this->_output = '';
	
		$this->_output .= '<label for="data_'.$block->content_block_id.'">Data:</label>';
		$this->_output .= '<input type="text" class="inputbox" name="content[data]" id="data_'.$block->content_block_id.'" value="'.$data->data.'" />'.PHP_EOL;
		
		$this->_output .= '<label for="image_x_'.$block->content_block_id.'">Image X Position:</label>';
		$this->_output .= '<input type="text" class="inputbox" name="content[image_x]" id="image_x_'.$block->content_block_id.'" value="'.$data->image_x.'" />'.PHP_EOL;
		
		$this->_output .= '<label for="image_y_'.$block->content_block_id.'">Image Y Position:</label>';
		$this->_output .= '<input type="text" class="inputbox" name="content[image_y]" id="image_y_'.$block->content_block_id.'" value="'.$data->image_y.'" />'.PHP_EOL;
		
		$this->_output .= '<label for="image_scale_'.$block->content_block_id.'">Image Scale:</label>';
		$this->_output .= '<input type="text" class="inputbox" name="content[image_scale]" id="image_scale_'.$block->content_block_id.'" value="'.$data->image_scale.'" />'.PHP_EOL;
		
		$this->_output .= '<label for="image_rotate_'.$block->content_block_id.'">Image Rotation:</label>';
		$this->_output .= '<input type="text" class="inputbox" name="content[image_rotate]" id="image_rotate_'.$block->content_block_id.'" value="'.$data->image_rotate.'" />'.PHP_EOL;
		
		$this->_output .= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
		
		return $this->_output;
		
	}
	
	public function saveContent( $data, $new_version=false ){
		
		$date	= JFactory::getDate()->toMySQL();
		$user	= JFactory::getUser()->get( 'id' );
				
		if( $new_version ){
			
			unset( $data['id'] );
				
		}
		
		$row	= JTable::getInstance( 'contentimage', 'Table' );
		
		if( !$row->bind( $data ) ){
		
			$this->setError( JText::_( 'ERROR_SAVING_LOAD_CONTENT_IMAGE' ) );
			return false;
		
		}
		
		if( $data['reset'] == 1 ){
			
			$row->image_x		= 0;
			$row->image_y		= 0;
			$row->image_scale	= 100;
			$row->image_rotate	= 0;
			
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
		
			$this->setError( JText::_( 'ERROR_SAVING_STORE_CONTENT_IMAGE' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contentimage', 'Table' );
				
		if( !$row->load( $id ) ){
		
			$this->setError( JText::_( 'ERROR_SAVING_LOAD_CONTENT_IMAGE' ) );
			return false;
		
		}
		
		$row->id = '';
		$row->created_by	= $user;
		$row->created		= $date;
		$row->modified_by	= 0;
		$row->modified		= '0000-00-00 00:00:00';			
	
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_SAVING_STORE_CONTENT_IMAGE' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contentimage', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_IMAGE' ) );
			return false;
		
		}
		
		return true;
		
	}

}