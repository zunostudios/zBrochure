<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 
	
$menu = JSite::getMenu();
$active = $menu->getActive()->id;
	
?>

<script type="text/javascript">
$(document).ready(function(){
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	$('.delete-provider').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
	$('.sortable').dataTable({
		"bFilter": false,
		"aoColumnDefs":[{ "bSortable": false, "aTargets": [ 2 ] }],
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
	});
	
});
</script>


<div id="sub-header">
	
	<div class="wrapper">
	
		<div id="top-bar">
			
			<h1><?php echo JText::_( 'PROVIDERS_TITLE' ); ?></h1>
			
			<div class="btn-container">
				<button class="btn add-btn" onclick="window.location='<?php echo JRoute::_('index.php?option=com_zbrochure&view=provider'); ?>'; return false;">
					<span>+ Add Provider</span>
				</button>
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
			
			<table id="providers-sort" class="sortable list-table dataTable" cellpadding="0" cellspacing="0" border="1" width="100%">
				<thead>
					<tr>
						<th width="50"><?php echo JText::_( 'TABLE_LIST_ID' ); ?></th>
						<th><?php echo JText::_( 'TABLE_LIST_PROVIDER_NAME' ); ?></th>
						<th width="150">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $this->data as $provider ){ 
						
						$edit_link		= JRoute::_('index.php?option=com_zbrochure&view=provider&id='.$provider->provider_id);
						$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateProvider&id='.$provider->provider_id.'&Itemid='.$active );
						$delete_link	= JRoute::_('index.php?option=com_zbrochure&task=deleteProvider&Itemid='.$active.'&id='.$provider->provider_id);
						
					?>
					<tr>
						<td align="center"><?php echo $provider->provider_id; ?></td>
						<td><a href="<?php echo $edit_link; ?>" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ).' '.$provider->provider_name; ?>"><?php echo $provider->provider_name; ?></td>
						<td align="center">
						
							<a href="<?php echo $edit_link; ?>" class="btn edit-icon-btn icon-only-btn" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?></span></a>
							
							<a href="<?php echo $duplicate_link; ?>" class="btn duplicate-icon-btn icon-only-btn" style="margin:0 10px;" title="<?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?></span></a>
							
							<a href="<?php echo $delete_link; ?>" class="btn delete-icon-btn icon-only-btn delete-provider" title="<?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></a>
							
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<div id="delete-dialog" title="<?php echo JText::_( 'DIALOG_TITLE_PROVIDER' ); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_PROVIDER' ); ?></span></a>

</div>