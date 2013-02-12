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
			
			<h1><?php echo JText::_( 'THEMES_TITLE' ); ?></h1>
			
			<div class="btn-container">
				<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=theme&Itemid='.$this->list_menu_itemid ); ?>" title="<?php echo JText::_( 'ADD_BTN_THEME' ); ?>" class="btn add-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'ADD_BTN_THEME' ); ?></span></a>
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
		
			<table id="themes-table" class="sortable list-table" cellpadding="0" cellspacing="0" border="1" width="100%">
			
				<thead>
					<tr bgcolor="#CCC">
						<th width="5%" align="center"><?php echo JText::_('TABLE_LIST_ID'); ?></th>
						<th width="20%"><?php echo JText::_('TABLE_LIST_THEMENAME'); ?></th>
						<th><?php echo JText::_('TABLE_LIST_COLORPALETTE'); ?></th>
						<th width="20%">&nbsp;</th>
					</tr>
				</thead>
			
				<tbody>
					
					<?php foreach( $this->themes as $theme ){
					
						$palette = json_decode( $theme->theme_data );
						
						$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=theme&id='.$theme->theme_id.'&Itemid='.$this->list_menu_itemid );
						$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateTheme&id='.$theme->theme_id.'&Itemid='.$this->item_menu_itemid );
						$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteItem&id='.$theme->theme_id.'&type=theme&Itemid='.$this->list_menu_itemid );
					
					?>
					<tr>
						<td align="center"><?php echo $theme->theme_id; ?></td>
						<td align="left"><a href="<?php echo $edit_link; ?>" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ).' '.$theme->theme_name; ?>"><?php echo $theme->theme_name; ?></td>
					
						<td>
						
							<table cellpadding="0" cellspacing="0" border="0" width="100%" class="theme-color-table">
								<tr>
									<?php $i = 1; foreach( $palette->color as $color ){ ?>
									<td>
										<div id="color<?php echo $theme->id; ?>" class="color-block" style="background-color:rgb(<?php echo $color; ?>)"><!--   --></div>
									</td>
									<?php $i++; } ?>					
								</tr>
							</table>
							
						</td>
						
						<td align="center">
								
								<a href="<?php echo $edit_link; ?>" class="btn edit-icon-btn icon-only-btn" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?></span></a>
								
								<a href="<?php echo $duplicate_link; ?>" class="btn duplicate-icon-btn icon-only-btn" style="margin:0 10px;" title="<?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?></span></a>
								
								<a href="<?php echo $delete_link; ?>" class="btn delete-icon-btn icon-only-btn delete-theme" title="<?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></a>
																
							</td>
					
					</tr>
					<?php } ?>
					
				</tbody>
			
			</table>
		
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_THEME'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_THEME' ); ?></span></a>

</div>
<?php
echo $this->loadTemplate( 'scripts' );