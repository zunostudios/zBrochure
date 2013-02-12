<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure' ); ?>" method="post">
	
	<div class="form-row">
		
		<div class="add-padding package-container-header">
			
			<div style="width:66%;float:left">
							
				<div class="form-row">
					
					<div style="float:right">
								
						<label for="tmpl_layout_type"><?php echo JText::_( 'TMPL_LAYOUT_TYPE' ); ?></label>
						<?php
						
						$type[] = JHTML::_( 'select.option', '1', 'Cover' );
						$type[] = JHTML::_( 'select.option', '2', 'Content Page' );
			
						echo JHTML::_('select.genericlist', $type, 'tmpl_layout_type', '', 'value', 'text', $this->layout->tmpl_layout_type );
						
						?>
					
					</div>
					
					<div style="float:right;padding:0 20px">
								
						<label for="tmpl_layout_current_version"><?php echo JText::_( 'TMPL_LAYOUT_CURRENT_VERSION' ); ?></label>
						<input id="tmpl_layout_current_version" name="tmpl_layout_current_version" class="inputbox" style="width:100px" disabled="disabled" value="<?php echo $this->layout->tmpl_layout_current_version; ?>" />
					
					</div>
					
					<label for="tmpl_layout_name"><?php echo JText::_( 'TMPL_LAYOUT_NAME' ); ?> ( Current Version ID: <?php echo $this->layout->tmpl_layout_current_version; ?> )</label>
					<input id="tmpl_layout_name" name="tmpl_layout_name" class="inputbox" style="width:300px" value="<?php echo $this->layout->tmpl_layout_name; ?>" />
					
				</div>
				
				<div class="form-row">
								
					<label for="tmpl_layout_desc"><?php echo JText::_( 'TMPL_LAYOUT_DESC' ); ?></label>
					<textarea id="tmpl_layout_desc" name="tmpl_layout_desc" class="inputbox" style="width:98%;height:40px"><?php echo $this->layout->tmpl_layout_desc; ?></textarea>
					
				</div>
				
				<div class="form-row">
								
					<label for="tmpl_layout_details"><?php echo JText::_( 'TMPL_LAYOUT_DETAILS' ); ?></label>
					<textarea id="tmpl_layout_details" name="tmpl_layout_details" class="inputbox" style="width:98%;height:40px"><?php echo $this->layout->tmpl_layout_details; ?></textarea>
					
				</div>
				
				<div id="layout-accordion">
					
					<label for="tmpl_layout_version_content"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_CONTENT' ); ?></label>
					
					<div style="padding:0 20px">
							
						<textarea id="tmpl_layout_version_content" name="version[tmpl_layout_version_content]" class="inputbox" style="width:95%;height:150px;margin:0 0 20px 0"><?php echo $this->layout->tmpl_layout_version_content; ?></textarea>
						
					</div>
				
					<label for="tmpl_layout_version_images"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_IMAGES' ); ?></label>
						
					<div style="padding:0 20px">
			
						<textarea id="tmpl_layout_version_images" name="version[tmpl_layout_version_images]" class="inputbox" style="width:95%;height:150px;margin:0 0 20px 0"><?php echo $this->layout->tmpl_layout_version_images; ?></textarea>
						
					</div>
				
					<label for="tmpl_layout_version_design"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_DESIGN' ); ?></label>	
						
					<div style="padding:0 20px">
						
						<textarea id="tmpl_layout_version_design" name="version[tmpl_layout_version_design]" class="inputbox" style="width:95%;height:150px;margin:0 0 20px 0"><?php echo $this->layout->tmpl_layout_version_design; ?></textarea>
						
					</div>
			
					<label for="tmpl_layout_version_styles"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_STYLES' ); ?></label>
						
					<div style="padding:0 20px">
						
						<textarea id="tmpl_layout_version_styles" name="version[tmpl_layout_version_styles]" class="inputbox" style="width:95%;height:150px;margin:0 0 20px 0"><?php echo $this->layout->tmpl_layout_version_styles; ?></textarea>
						
					</div>
						
				</div>
			
			</div>
			
			<div style="width:33%;float:right">
				
				<div style="padding:0 0 0 20px">
					
					<div id="accordion">
						
						<?php if( $this->layout->content_blocks ){ ?>
						
						<h3><?php echo JText::_( 'CONTENT_BLOCKS' ); ?></h3>	
						
						<div style="padding:0 20px">					
						<?php foreach( $this->layout->content_blocks as $block ){
							
							echo '<div class="block-listing-item" id="block_'.$block['content']->content_block_id.'">';
							echo '<strong>Content Block ID:</strong> '.$block['content']->content_block_id.'<br />';
							echo '<strong>Content Block Type:</strong> '.$block['content']->content_type_name.' ('.$block['content']->content_type_id.')<br />';
							echo '<strong>Content Version:</strong> '.$block['content']->content_block_current_version.'<br />';
							echo '<strong>Tmpl Block ID:</strong> '.$block['content']->content_tmpl_block_id.'<br />';
							echo '<a href="'.JRoute::_( 'index.php?option=com_zbrochure&view=block&id='.$block['content']->content_block_id ).'" class="edit-block-link">'.JText::_( 'EDIT_BLOCK' ).'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
							echo '<a href="javascript:void(0);" onclick="deleteBlock('.$block['content']->content_block_id.')" class="delete-block-link">'.JText::_( 'DELETE_BLOCK' ).'</a>';
							echo '</div>';
							
						}?>
						</div>
						
						<?php 
						}
						
						if( $this->layout->image_blocks ){ ?>
						
						<h3><?php echo JText::_( 'IMAGE_BLOCKS' ); ?></h3>	
						
						<div style="padding:0 20px">					
						<?php foreach( $this->layout->image_blocks as $block ){
							
							echo '<div class="block-listing-item" id="block_'.$block['content']->content_block_id.'">';
							echo '<strong>Content Block ID:</strong> '.$block['content']->content_block_id.'<br />';
							echo '<strong>Content Block Type:</strong> '.$block['content']->content_type_name.' ('.$block['content']->content_type_id.')<br />';
							echo '<strong>Content Version:</strong> '.$block['content']->content_block_current_version.'<br />';
							echo '<strong>Tmpl Block ID:</strong> '.$block['content']->content_tmpl_block_id.'<br />';
							echo '<a href="'.JRoute::_( 'index.php?option=com_zbrochure&view=block&id='.$block['content']->content_block_id ).'" class="edit-block-link">'.JText::_( 'EDIT_BLOCK' ).'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
							echo '<a href="javascript:void(0);" onclick="deleteBlock('.$block['content']->content_block_id.')" class="delete-block-link">'.JText::_( 'DELETE_BLOCK' ).'</a>';
							echo '</div>';
							
						}?>
						</div>
						
						<?php } 
							
						if( $this->layout->design_blocks ){ ?>
						
						<h3><?php echo JText::_( 'DESIGN_BLOCKS' ); ?></h3>	
						
						<div style="padding:0 20px">					
						<?php foreach( $this->layout->design_blocks as $block ){
							
							echo '<div class="block-listing-item" id="block_'.$block['content']->content_block_id.'">';
							echo '<strong>Content Block ID:</strong> '.$block['content']->content_block_id.'<br />';
							echo '<strong>Content Block Type:</strong> '.$block['content']->content_type_name.' ('.$block['content']->content_type_id.')<br />';
							echo '<strong>Content Version:</strong> '.$block['content']->content_block_current_version.'<br />';
							echo '<strong>Tmpl Block ID:</strong> '.$block['content']->content_tmpl_block_id.'<br />';
							echo '<a href="'.JRoute::_( 'index.php?option=com_zbrochure&view=block&id='.$block['content']->content_block_id ).'" class="edit-block-link">'.JText::_( 'EDIT_BLOCK' ).'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
							echo '<a href="javascript:void(0);" onclick="deleteBlock('.$block['content']->content_block_id.')" class="delete-block-link">'.JText::_( 'DELETE_BLOCK' ).'</a>';
							echo '</div>';
							
						}?>
						</div>
						
						<?php } ?>
						
					</div>
				
				</div>
			
			</div>
			
			<div class="clear"><!-- --></div>
			
		</div>
			
	</div>

	<div class="btn-container modal-bottom-fixed">
		<div class="modal-bottom-fixed-inner">
			<button type="button" class="btn cancel-btn icon-btn" onclick="parent.window.edit_layout_dialog.dialog('close'); return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
			<button type="submit" class="btn save-btn" id="save-btn"><span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
		</div>
	</div>
	
	<input type="hidden" name="tmpl_layout_id" value="<?php echo $this->layout->tmpl_layout_id; ?>" />
	<input type="hidden" name="version[tmpl_layout_version_id]" value="<?php echo $this->layout->tmpl_layout_version_id; ?>" />
	<input type="hidden" name="tmpl_id" value="<?php echo $this->layout->tmpl_id; ?>" />
	<input type="hidden" name="layout" value="modal" />
	<input type="hidden" name="tmpl" value="modal" />
	<input type="hidden" name="task" value="saveLayout" />
	<input type="hidden" name="tmpl_layout_version" value="<?php echo $this->layout->tmpl_layout_version; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
					
</form>
<?php echo $this->loadTemplate( 'scripts' ); ?>