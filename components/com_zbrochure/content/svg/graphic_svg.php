<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure graphic element
 */
class ZbrochureContentSvg{
	
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
		$query->from( '#__'.$block->content_type_table.' AS c' );
		$query->where( 'c.'.$block->content_type_table_key.' = '.$block->content_block_current_version );
		
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
		
		switch( $output ){
			
			case 'renderAdmin':
				$output = ZbrochureContentSvg::renderAdmin( $this->_data, $block );
			break;
			
			default:
				$output	= ZbrochureContentSvg::renderContent( $this->_data, $block, $edit );
		}
			
		return $output;
	
	}
	
	/**
	 * Method to render a graphic element
	 */
	public function renderContent( $data, $block, $edit ){
		
		//$edit = 0;
		
		$attributes 	= '';
		
		if( $data->style ){
		
			$styles	= json_decode( $data->style );
		
			foreach( $styles as $k => $v ){
				
				$attributes .= $k .'="'.$v.'" ';
				
			}
			
		}
		
		$theme_colors	= $block->bro_theme->theme_data;
		$theme_colors	= json_decode( $theme_colors );
		
		if( $data->theme_position ){
			
			$attributes .= 'fill="rgb('. $theme_colors->color[ ( $data->theme_position -1 ) ] .')" ';
			
		}
		
		
		$attributes_to_add = '';
		
		if( $edit ){
			
			$attributes_to_add	.= ' onclick="';
			//$attributes_to_add	.= '$(\'#edit-svg-'.$block->content_block_id.'\').val('.$data->id.');';
			//$attributes_to_add	.= '$(\'#svg-content-block\').val('.$block->content_block_id.');';
			$attributes_to_add	.= '$(\'#edit-svg-'.$block->content_block_id.'\').dialog(\'open\');"';
			
			$attributes_to_add	.= ' id="svg-'.$block->content_block_id.'" data-theme-position="'.$data->theme_position.'" data-block="'.$block->content_block_id.'" '.$attributes;
			$attributes_to_add	.= ' class="svg-editable" ';
			
		}else{
			
			$attributes_to_add	.= ' '.$attributes;
			
		}
		
		
		
		$this->_edit_output = '';
		
		$this->_edit_output .= '<div id="edit-svg-'.$block->content_block_id.'" title="'.JText::_( 'EDIT_ELEMENT' ).'">'.PHP_EOL;

			$this->_edit_output .= '<form action="'.JRoute::_( 'index.php?option=com_zbrochure&task=saveBlock' ).'" method="post">'.PHP_EOL;
				
				$this->_edit_output .= '<div class="form-row text-container">'.PHP_EOL;
					
					$this->_edit_output .= '<div id="tmpl-theme-change" class="form-row add-padding package-container-header">'.PHP_EOL;
						
						
						$this->_edit_output .= '<div class="theme-color-chosen">'.PHP_EOL;
							$this->_edit_output .= '<div style="border:2px solid #FFF">'.PHP_EOL;
								$this->_edit_output .= '<div id="theme-color-chosen-'.$block->content_block_id.'"><!-- --></div>'.PHP_EOL;
							$this->_edit_output .= '</div>'.PHP_EOL;
						$this->_edit_output .= '</div>'.PHP_EOL;
						
						
						$this->_edit_output .= '<div class="theme-color-opacity">'.PHP_EOL;
							$this->_edit_output .= '<div class="slider-label-left">'.JText::_( 'OPACITY' ).'</div><div id="svg-opacity-scale-slider-'.$block->content_block_id.'" class="svg-opacity-slider"></div>'.PHP_EOL;
							$this->_edit_output .= '<div class="clear"><!-- --></div>'.PHP_EOL;
						$this->_edit_output .= '</div>'.PHP_EOL;
						
						$this->_edit_output .= '<div class="theme-container">'.PHP_EOL;	
							
						$theme = json_decode( $block->bro_theme->theme_data );
									
						if( $theme ){
							
							$i = 1;
							foreach( $theme->color as $color ){
						
								$this->_edit_output .= '<div id="color'.$i.'" class="theme-element" style="background-color:rgb('.$color.')" onclick="changeColor( this, '.$i.', '.$block->content_block_id.' );"><!--   --></div>'.PHP_EOL;
								$i++;
							
							}
							
						}
			
						$this->_edit_output .= '</div>'.PHP_EOL;
						
					$this->_edit_output .= '</div>'.PHP_EOL;
					
				$this->_edit_output .= '</div>'.PHP_EOL;
				
				
				$this->_edit_output .= '<div class="form-row btn-container add-padding" style="margin:10px 0">'.PHP_EOL;
					$this->_edit_output .= '<button class="btn save-btn fr" type="submit"><span>'.JText::_( 'SAVE_BTN_GENERAL' ).'</span></button>'.PHP_EOL;
					$this->_edit_output .= '<button class="btn cancel-btn fr" onclick="$( \'#edit-svg-'.$block->content_block_id.'\' ).dialog(\'close\'); return false;"><span>'.JText::_( 'CANCEL_BTN_GENERAL' ).'</span></button>'.PHP_EOL;
				$this->_edit_output .= '</div>'.PHP_EOL;
				
				/*
				$this->_edit_output .= '<input type="hidden" name="svg_content_block" id="svg-content-block" value="" />'.PHP_EOL;
				$this->_edit_output .= '<input type="hidden" name="svg_block" id="svg-block" value="" />'.PHP_EOL;
				*/
				
				$this->_edit_output .= '<input name="content[id]" value="'.$data->id.'" type="hidden" />'.PHP_EOL;
				
				$this->_edit_output .= '<input type="hidden" name="svg_color_start" id="svg-color-start-'.$block->content_block_id.'" value="" />'.PHP_EOL;
				$this->_edit_output .= '<input type="hidden" name="svg_opacity_start" id="svg-opacity-start-'.$block->content_block_id.'" value="" />'.PHP_EOL;
				
				$this->_edit_output .= '<input type="hidden" name="content[theme_position]" id="svg-color-'.$block->content_block_id.'" value="" />'.PHP_EOL;
				$this->_edit_output .= '<input type="hidden" name="content[svg_opacity]" id="svg-opacity-'.$block->content_block_id.'" value="" />'.PHP_EOL;
				
				$this->_edit_output .= '<input name="content_block_id" value="'.$block->content_block_id.'" type="hidden" />'.PHP_EOL;
				$this->_edit_output .= '<input name="content_block_type" value="'.$block->content_block_type.'" type="hidden" />'.PHP_EOL;
				$this->_edit_output .= '<input name="content_bro_id" value="'.$block->content_bro_id.'" type="hidden" />'.PHP_EOL;
				
				$this->_edit_output .= '<input name="view" value="'.JRequest::getVar('view').'" type="hidden" />'.PHP_EOL;
				$this->_edit_output .= '<input name="bro_id" value="'.$block->bro_id.'" type="hidden" />'.PHP_EOL;
				
				$this->_edit_output .= '<input name="bro_page_id" value="'.$block->bro_page_id.'" type="hidden" />'.PHP_EOL;
				$this->_edit_output .= '<input name="bro_page_order" value="'.$block->bro_page_order.'" type="hidden" />'.PHP_EOL;
				
			$this->_edit_output .= '</form>'.PHP_EOL;
			
		$this->_edit_output .= '</div>'.PHP_EOL;
		
		
		
		$this->_edit_output .= '<script type="text/javascript">
									
									$(document).ready(function(){
														
										$( "#edit-svg-'.$block->content_block_id.'" ).dialog({
											autoOpen: false,
											width: 400,
											modal: false,
											resizable: false,
											open: function(){
		
												disable_scroll();
												
												setCurrentColor( '.$block->content_block_id.' );
												
												$( "#svg-color-start-'.$block->content_block_id.'" ).val( $( "#svg-'.$block->content_block_id.'" ).attr( "data-theme-position" ) );
												$( "#svg-opacity-start-'.$block->content_block_id.'" ).val( $( "#svg-'.$block->content_block_id.'" ).attr( "fill-opacity" ) );
												
											},
											close: function(){
												
												enable_scroll();
			
												start_color	= $( "#svg-color-start-'.$block->content_block_id.'" ).val();
												
												$( "#svg-'.$block->content_block_id.'" ).attr( "fill", $( "#color"+start_color ).css( "backgroundColor" ) );
												$( "#svg-'.$block->content_block_id.'" ).attr( "fill-opacity", $( "#svg-opacity-start-'.$block->content_block_id.'" ).val() );
												
												$( ".theme-element" ).removeClass( "active" );
																				
											}
										});
										
										$( "#svg-opacity-scale-slider-'.$block->content_block_id.'" ).slider({
											range:"max",
											min:10,
											max:100,
											slide:function( event, ui ){
												
												$( "#theme-color-chosen-'.$block->content_block_id.'" ).css( "opacity", (ui.value / 100) );
												$( "#svg-opacity-'.$block->content_block_id.'" ).val( (ui.value / 100) );
												$( "#svg-'.$block->content_block_id.'" ).attr( "fill-opacity", (ui.value / 100) );
												
											}
										
										});
										
					
									});
							
							</script>';


		$output->render = $attributes_to_add;
		
		if( $edit ){
		
			$output->edit	= $this->_edit_output;
		
		}else{
			
			$output->edit	= '';
			
		}
					
		return $output;
	
	}
	
	public function renderAdmin( $data, $block ){
		
		$this->_output = '';
		
		$this->_output .= '<textarea class="inputbox" name="content[data]" style="width:98%;height:75px">'.$data->data.'</textarea>'.PHP_EOL;
		$this->_output .= '<textarea class="inputbox" name="content[style]" style="width:98%;height:75px">'.$data->style.'</textarea>'.PHP_EOL;
		$this->_output .= '<input type="text" class="inputbox" name="content[theme_position]" style="width:98%" value="'.$data->theme_position.'" />'.PHP_EOL;
		$this->_output .= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
		
		return $this->_output;
		
	}
	
	public function saveContent( $data, $new_version=false ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
		
		if( $new_version ){
			
			unset( $data['id'] );
			
		}
		
		$style['fill-opacity']	= $data['svg_opacity'];
		$styles					= json_encode( $style );
		$data['style']			= $styles;
				
		$row	= JTable::getInstance( 'contentsvg', 'Table' );
			
		if( !$row->bind( $data ) ){
		
			$this->setError($this->_db->getErrorMsg());
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
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_SVG' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contentsvg', 'Table' );
				
		if( !$row->load( $id ) ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->id = '';
		$row->created_by	= $user;
		$row->created		= $date;
		$row->modified_by	= 0;
		$row->modified		= '0000-00-00 00:00:00';			
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_SVG' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contentsvg', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_SVG' ) );
			return false;
		
		}
		
		return true;
		
	}

}