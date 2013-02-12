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
class ZbrochureContentLogo{
	
	/**
	 * @var string output
	 */	
	protected $_data;
	
	/**
	 * @var string output
	 */	
	protected $_output;
	
	/**
	 * Method to render a logo content element
	 */
	public function getContent( $block, $edit, $output='renderContent' ){
			
		$db		= JFactory::getDbo();
		$query	= $db->getQuery( true );
		
		$query->select( 'i.*, l.*, c.*' );
		$query->from( '#__'.$block->content_type_table.' AS i' );
		$query->join( 'LEFT', '#__zbrochure_client_logos AS l ON l.client_logo_id = i.data' );
		$query->join( 'LEFT', '#__zbrochure_clients AS c ON c.client_id = l.client_id' );	
		$query->where( 'i.'.$block->content_type_table_key.' = '.$block->content_block_current_version );
		
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
			
		//Check to see if the logo client id matches the brochure client id
		//If not grab the default logo from the brochure client
		//This is to automatically load the client logo if the client is changed on a brochure	
		if( !$this->_data->client_id && !$block->bro_client ){
			
			$this->_data->no_logos	= 1;
			
		}else if( $this->_data->client_id != $block->bro_client ){
			
			$query	= $db->getQuery( true );
			
			$query->select( 'l.*' );
			$query->from( '#__zbrochure_client_logos AS l' );
			$query->where( 'l.client_id = '.$block->bro_client );
			
			$db->setQuery( $query );
			$logos	= $db->loadObjectList( 'client_logo_id' );
			
			$match	= false;
			
			if( !empty($logos) ){
				
				foreach( $logos as $logo ){
								
					if( $logo->client_logo_default ){
						
						$this->_data->data					= $logo->client_logo_id;
						$this->_data->client_logo_filename	= $logo->client_logo_filename;
						$this->_data->client_logo_filetype	= $logo->client_logo_filetype;
						$this->_data->client_id				= $logo->client_id;
						$match								= true;
						break;
						
					}
					
				}
				
				if( !$match ){
					
					$default	= array_shift( $logos );
					
					$this->_data->data					= $default->client_logo_id;
					$this->_data->client_logo_filename	= $default->client_logo_filename;
					$this->_data->client_logo_filetype	= $default->client_logo_filetype;
					$this->_data->client_id				= $default->client_id;
					
				}
				
			}else{
				
				$this->_data->no_logos	= 1;
				
			}
			
		}
		
		switch( $output ){
			
			case 'renderAdmin':
				$output	= ZbrochureContentLogo::renderAdmin( $this->_data, $block );
			break;
			
			default:
				$output	= ZbrochureContentLogo::renderContent( $this->_data, $block, $edit );
		}
		
		
		
		return $output;
	
	}
	
	/**
	 * Method to render a logo content element
	 */
	public function renderContent( $data, $block, $edit=null ){
		
		$no_logos			= ($data->no_logos) ? 1 : 0;
		
		$media_dir			= JComponentHelper::getParams('com_zbrochure')->get( 'client_file_path' );
		$logomanager_url	= JRoute::_('index.php?option=com_zbrochure&view=client&layout=logomanager&tmpl=modal&id='.$block->bro_client.'&block_id='.$block->content_block_id.'&bro_page_order='.$block->bro_page_order.'&data='.$data->data );
		
		$file_path			= JURI::base().$media_dir.'/'.$data->client_id.'/logos/'.$data->client_logo_filename.'.'.$data->client_logo_filetype;
		$fpo_path			= JURI::base().'/components/com_zbrochure/assets/images/logo-fpo.png';
		
		$image_x		= ($data->image_x) ? $data->image_x : '0';
		$image_y		= ($data->image_y) ? $data->image_y : '0';
		$image_scale	= ($data->image_scale) ? $data->image_scale : '100';
		
		$scale				= $image_scale.'%';
		//$size				= getimagesize( $file_path );		
		//$width				= $size[0] / 72;
		//$height				= $size[1] / 72;
		
		
		$this->_edit_output	= '';
		$this->_output		= '';
		
		$bg 		= '';
		
		$bg_color	= json_decode( $data->image_bg_color )->rgb;
		
		if( !$bg_color ){
			
			$bg_color	= '255,255,255';
			
		}
		
		if( $data->image_bg ){
			
			$bg = 'background-color:rgb('.$bg_color.');';
			
		}
		
		$this->_output .= '<div id="logo_container_'.$block->content_block_id.'" style="'.$bg.'width:100%;height:100%">';
		
		if( $no_logos && !$data->asset ){
		
			$this->_output .= '<img src="'.$fpo_path.'" height="100%" asset="0" id="logo_'.$block->content_block_id.'" />';
		
		}else{
		
		$this->_output .= '<img src="'.$file_path.'" height="'.$scale.'" asset="'.$data->data.'" id="logo_'.$block->content_block_id.'" style="position:absolute;top:'.$image_y.'px;left:'.$image_x.'px;" />';
		
		}
		
		$this->_output .= '</div>';
		
		
		
		
		
		$this->_edit_output = '';
		
		$this->_edit_output .= '<div id="edit-logo-'.$block->content_block_id.'" title="Edit Logo">'.PHP_EOL;
		
		if( $no_logos ){
			
			$this->_edit_output .= '<div class="form-row move-btns text-container" style="text-align:center;font-weight:bold;padding:20px">'.PHP_EOL;
			
			$this->_edit_output .= '<p>'.JText::_('NO_LOGOS_MESSAGE').'</p>';
			
			$this->_edit_output .= '<a href="'.JRoute::_( 'index.php?option=com_zbrochure&view=client&id='.$block->bro_client ).'" class="btn add-btn"><span>'.JText::_( 'ADD_LOGOS' ).'</span></a>';
			
			$this->_edit_output .= '</div>'.PHP_EOL;
		
		}else{
		
			$this->_edit_output .= '<form id="logo-form-'.$block->content_block_id.'" name="logo-form-'.$block->content_block_id.'" action="index.php?option=com_zbrochure&task=saveBlock" method="post">'.PHP_EOL;
			
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
					
						$this->_edit_output .= '<div id="logo-edit-scale-current-'.$block->content_block_id.'" class="image-edit-scale-txt">'.round( $image_scale ).'%</div>'.PHP_EOL;
						$this->_edit_output .= '<div id="logo-edit-scale-slider-'.$block->content_block_id.'" class="image-edit-scale-slider"></div>'.PHP_EOL;
					
					$this->_edit_output	.= '</div>'.PHP_EOL;
					
					$checked = '';
					
					if( $data->image_bg ){
						
						$checked = ' checked="checked"';
						
					}
					
					$this->_edit_output .= '<div class="image-bg-checkbox"><label><input type="checkbox" name="content[image_bg]" id="image_bg-'.$block->content_block_id.'" value="1"'.$checked.'> '.JText::_( 'ON_WHITE_BG' ).'</label></div>'.PHP_EOL;
				
				$this->_edit_output	.= '</div>'.PHP_EOL;
			
			$this->_edit_output	.= '</div>'.PHP_EOL;
			
			
			$this->_edit_output	.= '<div id="action-btns-'.$block->content_block_id.'" class="btns-container action-btns-image">'.PHP_EOL;
			
				$this->_edit_output	.= '<div id="change-btns-'.$block->content_block_id.'" class="change-btns">'.PHP_EOL;
					$this->_edit_output	.= '<button class="change-img-btn btn build-bro-btn" type="button">'.JText::_( 'CHANGE_LOGO' ).'</button>'.PHP_EOL;
				$this->_edit_output	.= '</div>'.PHP_EOL;
				
				$this->_edit_output	.= '<button class="btn cancel-btn" onclick="location.reload(); return false;" type="button"><span>'.JText::_( 'CANCEL_BTN_GENERAL' ).'</span></button>'.PHP_EOL;
				$this->_edit_output	.= '<button class="btn save-btn"  type="submit"><span>'.JText::_( 'SAVE_BTN_GENERAL' ).'</span></button>'.PHP_EOL;
			
			$this->_edit_output	.= '</div>'.PHP_EOL;
			
			$this->_edit_output	.= '<input type="hidden" name="content[image_x]" id="image_x-'.$block->content_block_id.'" value="'.$image_x.'" />'.PHP_EOL;
			$this->_edit_output	.= '<input type="hidden" name="content[image_y]" id="image_y-'.$block->content_block_id.'" value="'.$image_y.'" />'.PHP_EOL;
			$this->_edit_output	.= '<input type="hidden" name="content[image_scale]" id="image_scale-'.$block->content_block_id.'" value="'.$image_scale.'" />'.PHP_EOL;
			$this->_edit_output	.= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
			$this->_edit_output	.= '<input type="hidden" name="content[data]" value="'.$data->data.'" />'.PHP_EOL;
			$this->_edit_output	.= '<input type="hidden" name="content[image_bg_color]" id="image_bg_color-'.$block->content_block_id.'" value="'.$bg_color.'" />'.PHP_EOL;
			
			
			//$this->_edit_output	.= '<input type="hidden" name="content[image_bg]" id="image_bg-'.$block->content_block_id.'" value="'.$data->image_bg.'" />'.PHP_EOL;
			
			$this->_edit_output .= '<input name="content_block_id" value="'.$block->content_block_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_block_type" value="'.$block->content_block_type.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_bro_id" value="'.$block->content_bro_id.'" type="hidden" />'.PHP_EOL;		
			$this->_edit_output .= '<input name="view" value="'.JRequest::getVar('view').'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_id" value="'.$block->bro_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_page_id" value="'.$block->bro_page_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_page_order" value="'.$block->bro_page_order.'" type="hidden" />'.PHP_EOL;			
			
			$this->_edit_output	.= '</form>'.PHP_EOL;
		
		}
		
		$this->_edit_output	.= '</div>'.PHP_EOL;
		
		$this->_edit_output	.= '<script type="text/javascript">$(document).ready(function(){
				
				var edit_logo_'.$block->content_block_id.' = new editBlockLogo().init( '.$block->content_block_id.', "'.$logomanager_url.'", '.$no_logos.' );
			
		});</script>'.PHP_EOL;
		
		
		$output->render = $this->_output;
		$output->edit	= $this->_edit_output;
		
		return $output;
	
	}
	
	public function saveContent( $data, $new_version=false ){
		
		$date	= JFactory::getDate()->toMySQL();
		$user	= JFactory::getUser()->get( 'id' );
		
		if( $new_version ){
			
			unset( $data['id'] );
			
		}
				
		$row	= JTable::getInstance( 'contentlogo', 'Table' );
				
		if( !$data['image_bg'] ){
			
			$data['image_bg'] = 0;
			
		}
		
		if( $data['image_bg_color'] ){
			
			$data['image_bg_color'] = '{"rgb":"'.$data['image_bg_color'].'"}';
			
		}
		
		if( !$row->bind( $data ) ){
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_LOGO' ) );
			return false;
		
		}
		
		if( $new_version ){
			
			$row->id			= '';
			$row->created_by	= $user;
			$row->created		= $date;
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
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_LOGO' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contentlogo', 'Table' );
				
		if( !$row->load( $id ) ){
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_LOGO' ) );
			return false;
		
		}
		
		$row->id = '';
		$row->created_by	= $user;
		$row->created		= $date;
		$row->modified_by	= 0;
		$row->modified		= '0000-00-00 00:00:00';			
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_LOGO' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
		
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contentlogo', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_LOGO' ) );
			return false;
		
		}
		
		return true;
		
	}


}