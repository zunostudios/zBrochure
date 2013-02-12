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
			
			<h1><?php echo JText::_('CATEGORIES_TITLE'); ?></h1>
			
			<div class="btn-container">
				<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=category&Itemid='.$this->item_menu_itemid ); ?>" class="btn add-btn"><span><?php echo JText::_( 'ADD_BTN_CATEGORY' ); ?></span></a>
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
		
			<table id="categories-sort" class="sortable" cellpadding="0" cellspacing="0" border="1" width="100%">
				<thead>
					<tr bgcolor="#CCCCCC">
						<th width="50"><?php echo JText::_('TABLE_LIST_ID'); ?></th>
						<th><?php echo JText::_('TABLE_LIST_CATEGORY'); ?></th>
						<th><?php echo JText::_('TABLE_LIST_STATE'); ?></th>
						<th width="150">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				
					<?php foreach( $this->categories as $category ){
						
						$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=category&id='.$category->cat_id.'&Itemid='.$this->list_menu_itemid );
						$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteItem&id='.$category->cat_id.'&type=category&Itemid='.$this->list_menu_itemid );
						$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateCategory&id='.$category->cat_id.'&Itemid='.$this->list_menu_itemid );
					
					?>
					<tr>
						
						<td align="center">
							<?php echo $category->cat_id; ?>
						</td>
						
						<td>
							<a href="<?php echo $edit_link; ?>" title="Edit <?php echo $category->cat_name; ?>"><?php echo $category->cat_name; ?></a>
						</td>
						
						<td align="center">
							<?php switch( $category->published ){
								
								case 0:
									echo 'Unpublished';
								break;
									
								case 1:
									echo 'Published';
								break;
								
								case 2:
									echo 'Archived';
								break;
								
							}?>
						</td>
						
						<td align="center">
							
							<a href="<?php echo $edit_link; ?>" class="btn edit-icon-btn icon-only-btn" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?></span></a>
							
							<a href="<?php echo $duplicate_link; ?>" class="btn duplicate-icon-btn icon-only-btn" style="margin:0 10px;" title="<?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?></span></a>
							
							<a href="<?php echo $delete_link; ?>" class="btn delete-icon-btn icon-only-btn delete-category" title="<?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></a>
							
						</td>
						
					</tr>
					<?php } ?>
				</tbody>
			</table>
		
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_CATEGORY'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
	
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
		
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_CATEGORY' ); ?></span></a>

</div>
<?php echo $this->loadTemplate( 'scripts' ); ?>