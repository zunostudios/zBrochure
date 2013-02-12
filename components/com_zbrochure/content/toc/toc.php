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
class ZbrochureContentToc{
	
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
		//If we've edited before let's get the current version of the content -MM
		if( $block->content_block_current_version == 0){
			$query->where( 'id = '.$block->content_content_id );
		}else{
			$query->where( 'id = '.$block->content_block_current_version );
		}
		
		
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
		
		$output	= ZbrochureContentToc::renderContent( $this->_data, $block, $edit );
				
		return $output;
	
	}
	
	/**
	 * Method to render an image content element
	 */
	public function renderContent( $data, $block, $edit ){
	
		$randomid = rand();
		
		$toc_object = json_decode( $data->data );
		$toc_title 	= $toc_object->toc_title;
		unset($toc_object->toc_title);
		
		$this->_output = '';
	
		$this->_output .= '<div id="tocblock-'.$randomid.'" class="editable-content">';
		
		if( !empty($toc_title) ){
			$this->_output .= '<div class="toc-title">'.$toc_title.'</div>';
		}
		
		$this->_output .= '<table class="toc-table" cellspacing="0" cellpadding="0" border="0">';
		foreach( $toc_object as $entry){
			
			$this->_output .= '<tr><td align="middle" class="toc-page" nowrap="nowrap">'.$entry->toc_page.'</td><td>'.$entry->toc_text.'</td></tr>';
		
		}
		$this->_output .= '</table>';
		
		$this->_output .= '</div>';
		
		if( $edit == 1 ){
		
			//**************** Start Dialog	****************//
			$this->_edit_output = '<div id="edittoc-'.$randomid.'" title="Edit Table of Contents Block">';
			$this->_edit_output .= '<form action="index.php?option=com_zbrochure&task=saveBlock" method="post">';
			
			$this->_edit_output .= '';
			
			$this->_edit_output .= '<div class="form-row text-container">';
			
			$this->_edit_output .= '<div class="form-row add-padding package-container-header">';
			
			$this->_edit_output .= '<label>Table of Contents Title:</label>';
			$this->_edit_output .= '<input type="text" name="content[data][toc_title]" class="inputbox" value="'.$toc_title.'" style="width:350px" />';
			
			$this->_edit_output .= '</div>';
			
			$this->_edit_output .= '<div class="form-row add-padding" style="max-height:200px">';
			
			$this->_edit_output .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
			
			$this->_edit_output .= '<thead><th>&nbsp;</th><th>Page</th><th>Title</th><th>&nbsp;</th><th>&nbsp;</th></thead>';
			
			$this->_edit_output .= '<tbody id="sortable-'.$randomid.'" class="ui-sortable">';
			
			$i = 0;
			foreach( $toc_object as $entry){
				
				$this->_edit_output .= '<tr id="row-'.$i.'" class="package-row toc-row"><td style="padding:3px 0" class="handle-td"><span class="handle">drag</span></td>';
				$this->_edit_output .= '<td style="padding:3px 0"><input type="text" name="content[data]['.$i.'][toc_page]" class="inputbox toc_page" value="'.$entry->toc_page.'" style="width:50px" /></td>';
				$this->_edit_output .= '<td style="padding:3px 0"><input type="text" name="content[data]['.$i.'][toc_text]" class="inputbox toc_text" value="'.$entry->toc_text.'" style="width:280px" /></td>';
				$this->_edit_output .= '<td style="padding:3px 0" align="middle"><button class="btn plus-icon-btn icon-only-btn" onclick="duplicateRow(this); return false;"><span>+</span></button></td>';
				$this->_edit_output .= '<td style="padding:3px 0" align="middle"><button class="btn minus-icon-btn icon-only-btn remove-toc-row" onclick="deleteRow(this, \''.$randomid.'\'); return false;"><span>-</span></button></td>';
				$this->_edit_output .= '</tr>';
				$i++;
			
			}
			
			$this->_edit_output .= '</tbody></table>';
			
			$this->_edit_output .= '</div>';
			
			$this->_edit_output .= '</div>';
			
			$this->_edit_output .= '<div class="form-row btn-container add-padding">
									<button class="btn save-btn fr" type="submit"><span>Save</span></button>
									<button class="btn cancel-btn fr" onclick="location.reload(); return false;"><span>Cancel</span></button>
								</div>';
			
			
			$this->_edit_output .= '<input name="content[id]" value="'.$data->id.'" type="hidden" />'.PHP_EOL;
			
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
									
										$( "#tocblock-'.$randomid.'" ).click(function(){
											$("#edittoc-'.$randomid.'").dialog(\'open\');
										});
				
										$( "#edittoc-'.$randomid.'" ).dialog({
											autoOpen: false,
											resizable: true,
											height:400,
											width:570,
											modal: true,
											close: function(){
												$( \'#vars-dialog\' ).dialog(\'close\');
											}
										});
										
										$( "#sortable-'.$randomid.'" ).sortable({
											placeholder: "ui-state-highlight",
											cancel: "nosort",
											helper: fixHelper,
											handle: \'.handle\',
											\'start\': function (event, ui) {
									        	ui.placeholder.html(\'<td colspan="4">&nbsp;</td>\');
									    	},
										});
					
									});
									
									// Return a helper with preserved width of cells
									var fixHelper = function(e, ui) {
									    ui.children().each(function() {
									        $(this).width($(this).width());
									    });
									    return ui;
									};
									
									function duplicateRow( elem ){
										var myrow = $(elem).parent().parent();
										var count = $(".toc-row").length;
										$(".remove-toc-row").css("opacity","1");
										
										$(elem).parent().parent().clone(true).addClass("added").insertAfter(myrow);
										$(".added .inputbox").val("");
										$(".added").attr("id", "row-"+(count+1) );
										$(".added .toc_page").attr("name", "content[data]["+(count+1)+"][toc_page]");
										$(".added .toc_text").attr("name", "content[data]["+(count+1)+"][toc_text]");
										$(".added").removeClass("added");
									}
									
									function deleteRow( elem, id ){
									
										var myrow = $(elem).parent().parent();
										var count = $( "#sortable-"+id+" .toc-row" ).length;
										
										if( count > 1 ){
										
											$(elem).parent().parent().remove();
											
											var newcount = $( "#sortable-"+id+" .toc-row").length;
											if( newcount == 1 ){
												$( "#sortable-"+id+" .remove-toc-row").css("opacity",".3");
											}
										
										}else{
										
											alert("You must have at least one row.");
										}
										
									}
							
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
		
		$date	= JFactory::getDate()->toMySQL();
		$user	= JFactory::getUser()->get( 'id' );
		
		if( $new_version ){
			
			unset( $data['id'] );
			
		}
				
		$row	= JTable::getInstance( 'contenttoc', 'Table' );
				
		if( !$row->bind( $data ) ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->data	= json_encode( $data['data'] );
				
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
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contenttoc', 'Table' );
		
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
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contenttoc', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_TOC' ) );
			return false;
		
		}
		
		return true;
		
	}


}