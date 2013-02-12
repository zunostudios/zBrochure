<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="sub-header">
	
	<div class="wrapper">
	
		<div id="top-bar">
			
			<h1><?php echo JText::_( 'CATEGORY_TITLE' ); echo ($this->category->cat_name) ? ': '.$this->category->cat_name : ': New'; ?></h1>
		
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
		
			<form id="categoryForm" name="clientForm" action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" enctype="multipart/form-data">
				
				<fieldset class="form-fieldset">
				
					<div class="form-row">
						<label for="cat_name">Category Name:</label>
						<input class="inputbox" type="text" name="cat_name" id="cat_name" value="<?php echo $this->category->cat_name; ?>" />
					</div>
					
					<div class="form-row">
						<label for="published">Published:</label>
						<?php echo $this->published; ?>
					</div>
					
					<div class="form-row">
						<label for="cat_desc">Category Description:</label>
						<textarea class="inputbox" name="cat_desc" id="cat_desc"><?php echo $this->category->cat_desc; ?></textarea>
					</div>
				
				</fieldset>
				
				<div class="btn-container">
				
					<button id="cancel" class="btn cancel-btn" onclick="window.location='<?php echo JRoute::_('index.php?option=com_zbrochure&Itemid='.$this->Itemid ); ?>';return false;">
						<span><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span>
					</button>
					
					<button id="save" class="btn save-btn" type="submit">
						<span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span>
					</button>
					
				</div>
				
				<input type="hidden" name="option" value="com_zbrochure" />
				<input type="hidden" name="task" value="saveCategory" />
				<input type="hidden" name="cat_id" value="<?php echo ($this->category->cat_id) ? $this->category->cat_id : '0'; ?>" />
				<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
				
			</form>
		
		</div>
		
	</div>
	<div class="clear"><!-- --></div>
</div>