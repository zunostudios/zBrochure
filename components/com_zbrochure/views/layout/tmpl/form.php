<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure' ); ?>" method="post">

	<div id="sub-header">

		<div class="wrapper">
			
			<div id="top-bar">
			
				<h1><?php echo JText::_( 'LAYOUT_DETAILS_TITLE'); ?></h1>
				
				<div class="btn-container">
						
					<?php if( $this->layout->tmpl_layout_id ){ ?>
					<button type="button" class="btn delete-btn icon-btn delete-layout" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
					<?php } ?>
					
					<button type="button" class="btn cancel-btn icon-btn" onclick="window.location='<?php echo JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$this->list_menu_itemid ); ?>'; return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
					
					<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
				
				</div>
				
			</div>
			
		</div>
		
	</div>
	
	<div class="view-wrapper<?php echo ' '.$this->columns_class; ?>">
		
		<?php if( $this->left_modules ){ ?>
		<div id="left">
			<div class="inner">
			<?php echo $this->left_modules; ?>
			</div>
		</div>
		<?php } ?>
		
		<div id="middle">
		
			<div class="inner">
			
														
				<div class="form-row">
					
					<label for="tmpl_layout_name"><?php echo JText::_( 'TMPL_LAYOUT_NAME' ); ?></label>
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
				
				<div class="form-row">
								
					<label for="tmpl_layout_type"><?php echo JText::_( 'TMPL_LAYOUT_TYPE' ); ?></label>
					<?php
					
					$type[] = JHTML::_( 'select.option', '1', 'Cover' );
					$type[] = JHTML::_( 'select.option', '2', 'Content Page' );
		
					echo JHTML::_('select.genericlist', $type, 'tmpl_layout_type', '', 'value', 'text', $this->layout->tmpl_layout_type );
					
					?>
					
				</div>	
				
				<div class="form-row">
				
					<label for="tmpl_layout_version_content"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_CONTENT' ); ?></label>
					<textarea id="tmpl_layout_version_content" name="version[tmpl_layout_version_content]" class="inputbox" style="width:98%;height:40px"><?php echo $this->layout->tmpl_layout_version_content; ?></textarea>
					
				</div>
				
				<div class="form-row">
				
					<label for="tmpl_layout_version_images"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_IMAGES' ); ?></label>
					<textarea id="tmpl_layout_version_images" name="version[tmpl_layout_version_images]" class="inputbox" style="width:98%;height:40px"><?php echo $this->layout->tmpl_layout_version_images; ?></textarea>
					
				</div>
				
				<div class="form-row">
				
					<label for="tmpl_layout_version_design"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_DESIGN' ); ?></label>
					<textarea id="tmpl_layout_version_design" name="version[tmpl_layout_version_design]" class="inputbox" style="width:98%;height:40px"><?php echo $this->layout->tmpl_layout_version_design; ?></textarea>
					
				</div>
				
				<div class="form-row">
				
					<label for="tmpl_layout_version_styles"><?php echo JText::_( 'TMPL_LAYOUT_VERSION_STYLES' ); ?></label>
					<textarea id="tmpl_layout_version_styles" name="version[tmpl_layout_version_styles]" class="inputbox" style="width:98%;height:40px"><?php echo $this->layout->tmpl_layout_version_styles; ?></textarea>
					
				</div>
					
			<div class="clear"><!-- --></div>
		
			<div class="btn-container fr">
				<button type="button" class="btn cancel-btn icon-btn" onclick="parent.window.edit_layout_dialog.dialog('close'); return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
				<button type="submit" class="btn save-btn" id="save-btn"><span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			</div>
			
			<div class="clear"><!-- --></div>
			
			<input type="hidden" name="tmpl_layout_id" value="<?php echo $this->layout->tmpl_layout_id; ?>" />
			<input type="hidden" name="tmpl_layout_key" value="<?php echo $this->next_layout_key; ?>" />
			<input type="hidden" name="version[tmpl_layout_version_id]" value="<?php echo $this->layout->tmpl_layout_version_id; ?>" />
			
			<input type="hidden" name="tmpl_id" value="<?php echo $this->layout->tmpl_id; ?>" />
			<input type="hidden" name="task" value="saveLayout" />
			
			<input type="hidden" name="tmpl_layout_version" value="<?php echo $this->layout->tmpl_layout_version; ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
	
			</div>
		
		</div>
	
	</div>
		
</form>
<?php //echo $this->loadTemplate( 'scripts' ); ?>