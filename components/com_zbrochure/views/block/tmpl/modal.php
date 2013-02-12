<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<form id="block-form" action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" >
	
	<div class="form-row edit-plan">
		
		<ul>
			
			<li><strong>Content Block ID:</strong> <?php echo $this->block->content_block_id; ?></li>
			<li><strong>Content Block Type:</strong> <?php echo $this->block->content_type_name; ?> (<?php echo $this->block->content_type_id; ?>)</li>
			<li><strong>Content Version ID:</strong> <?php echo $this->block->content_block_current_version; ?></li>
			
		</ul>
		
		<!--
		<div class="form-row">
			
			<label for="content_bro_id"><?php echo JText::_( 'FORM_LABEL_BLOCK_BRO_ID' ); ?></label>
			<input type="text" id="content_bro_id" name="content_bro_id" class="inputbox" value="<?php echo $this->block->content_bro_id; ?>" />
			
			<?php //echo JHTML::_( 'select.genericlist', $this->brochures, 'content_bro_id', 'class="inputbox" style="width:212px"', 'value', 'text', $this->block->content_bro_id ); ?>
			
		</div>
		-->
		
		<div class="form-row">
			
			<label for="content_tmpl_id"><?php echo JText::_( 'FORM_LABEL_BLOCK_TMPL' ); ?></label>
			<!-- <input type="text" id="content_tmpl_id" name="content_tmpl_id" class="inputbox" value="<?php echo $this->block->content_tmpl_id; ?>" /> -->
			<?php echo JHTML::_( 'select.genericlist', $this->templates, 'content_tmpl_id', 'class="inputbox"', 'value', 'text', $this->block->content_tmpl_id ); ?>
			
		</div>
		
		<div class="form-row">
			
			<label for="content_page_id"><?php echo JText::_( 'FORM_LABEL_BLOCK_PAGE_ID' ); ?></label>
			<input type="text" id="content_page_id" name="content_page_id" class="inputbox" value="<?php echo $this->block->content_page_id; ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="content_tmpl_block_id"><?php echo JText::_( 'FORM_LABEL_TMPL_BLOCK_ID' ); ?></label>
			<input type="text" id="content_tmpl_block_id" name="content_tmpl_block_id" class="inputbox" value="<?php echo $this->block->content_tmpl_block_id; ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="content_block_type"><?php echo JText::_( 'FORM_LABEL_BLOCK_TYPE' ); ?></label>
			<!-- <input type="text" id="content_block_type" name="content_block_type" class="inputbox" value="<?php echo $this->block->content_block_type; ?>" /> -->
			<?php echo JHTML::_( 'select.genericlist', $this->block_types, 'content_block_type', 'class="inputbox"', 'value', 'text', $this->block->content_block_type ); ?>
			
		</div>
		
		
		
		<?php echo $this->block->render; ?>	
		
	</div>
					
	<div class="btns-container" style="padding:10px 20px 20px 20px;text-align:right">
			
		<button type="button" class="btn cancel-btn icon-btn" onclick="parent.window.edit_block_dialog.dialog('close'); return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
		<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
		
	</div>
	
	<input type="hidden" name="option" value="com_zbrochure" />
	<input type="hidden" name="layout" value="modal" />
	<input type="hidden" name="tmpl" value="modal" />
	<input type="hidden" name="task" value="saveBlock" />
	<input type="hidden" name="view" value="block" />
	<input type="hidden" name="content_block_id" value="<?php echo $this->block->content_block_id; ?>" />
	<input type="hidden" name="content_block_version" value="<?php echo $this->block->content_block_version; ?>" />
	
	<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
		
			
</form>
<?php

//echo $this->loadTemplate( 'scripts' );

?>