<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */
 
defined('_JEXEC') or die('Restricted access'); ?>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			
			<h1><?php echo JText::_( 'PACKAGES_TITLE' ); ?></h1>
			
			<div class="btn-container">
				<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=package&Itemid='.$this->list_menu_itemid ); ?>" title="Add a Package" class="btn add-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'ADD_BTN_PACKAGE' ); ?></span></a>
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
		
			<table id="package-sort" class="sortable" cellpadding="0" cellspacing="0" width="100%" border="1">
				<thead>
					<tr>
						<th><?php echo JText::_( 'TABLE_HEADER_PACKAGES_ID' ); ?></th>
						<th><?php echo JText::_( 'TABLE_HEADER_PACKAGES_NAME' ); ?></th>
						<th><?php echo JText::_( 'TABLE_HEADER_PACKAGES_CATEGORY' ); ?></th>
						<th><?php echo JText::_( 'TABLE_HEADER_PACKAGES_DESC' ); ?></th>
						<th width="20%">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $this->packages as $package ){
					
						$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$package->package_id.'&Itemid='.$this->list_menu_itemid );
						$add_plan		= JRoute::_( 'index.php?option=com_zbrochure&view=plan&pid='.$package->package_id.'&Itemid='.$this->list_menu_itemid );
						$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicatePackageRow&id='.$package->package_id.'&Itemid='.$this->item_menu_itemid );
						$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteItem&id='.$package->package_id.'&type=package&Itemid='.$this->list_menu_itemid );
					
					?>
						<tr>
							<td align="center"><?php echo $package->package_id; ?></td>
							<td><a href="<?php echo $edit_link; ?>"><?php echo $package->package_name; ?></a></td>
							<td>
								<?php if($package->package_cat){
									
									echo $this->categories[ $package->package_cat ]['cat_name'];

								} ?>
							</td>
							
							<td>
								<?php echo $package->package_desc; ?>
							</td>
							
							<td align="center">
								
								<a href="<?php echo $edit_link; ?>" class="btn edit-icon-btn icon-only-btn" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?></span></a>
								
								<a href="<?php echo $duplicate_link; ?>" class="btn duplicate-icon-btn icon-only-btn" style="margin:0 10px;" title="<?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?></span></a>
								
								<a href="<?php echo $delete_link; ?>" class="btn delete-icon-btn icon-only-btn delete-package" title="<?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></a>
																
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		
		</div>
		
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_PACKAGE'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_PACKAGE' ); ?></span></a>

</div>

<?php echo $this->loadTemplate( 'scripts' ); ?>