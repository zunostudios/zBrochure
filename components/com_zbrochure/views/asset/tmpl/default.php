<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

JHTML::stylesheet( 'fileuploader.css', 'components/com_zbrochure/assets/css/' );
JHTML::stylesheet( 'jquery.lightbox-0.5.css', 'components/com_zbrochure/assets/css/' );
JHTML::stylesheet( 'fcbkstyle.css', 'components/com_zbrochure/assets/css/' );

JHTML::script( 'fileuploader.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.lightbox-0.5.min.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.fcbkcomplete.min.js', 'components/com_zbrochure/assets/js/' );

$assets_dir = JURI::base().'media'.DS.'zbrochure'.DS.'images'.DS.'library'.DS;
$asset_id = JRequest::getVar('id');
$delete_link = 'index.php?option=com_zbrochure&task=deleteAsset&id='.$asset_id.'&Itemid='.$this->list_menu_itemid;

?>

<script type="text/javascript">
$(document).ready(function(){
	
	$('a.modal').lightBox({fixedNavigation:true});
	
    $('#keywords').fcbkcomplete({
	    json_url: "index.php?option=com_zbrochure&task=getKeywords",
	    addontab: true,                   
	    maxitems: 10,
	    input_min_size: 0,
	    height: 3,
	    cache: true,
	    newel: true,
	    select_all_text: "select all"
	});
	
	$('.holder').css('background-color','#FFFFFF');
	$('.holder').css('width','350px');
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	$('.delete-asset').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
});
</script>

<form id="clientForm" action="input.php?option=com_zbrochure&task=saveAsset" method="post" enctype="multipart/form-data">
	
	<div id="sub-header">
		<div class="wrapper">
			<div id="top-bar">
				<h1>Asset View</h1>
				
				<div class="btn-container">
						
					<?php if( $asset_id ){ ?>
					<button type="button" class="btn delete-btn icon-btn delete-asset" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
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
						<div style="float:left;width:50%">
							<div style="padding:20px">
								<img src="<?php echo $assets_dir.'full/'.$this->data->asset_file; ?>" style="width:80%" />
							</div>
						</div>
						<div style="margin:0 0 0 50%">
						
							<div class="form-row">
								<label for="asset_title"><?php echo JText::_('ASSET_TITLE'); ?>:</label>
								<input type="text" class="inputbox" maxlength="255" id="asset_title" name="asset_title" value="<?php echo $this->data->asset_title; ?>" />
							</div>
							
							<div class="form-row">
								<label for="asset_file"><?php echo JText::_('ASSET_FILE_NAME'); ?>:</label>
								<input type="text" class="inputbox" maxlength="255" disabled="disabled" id="asset_file" value="<?php echo $this->data->asset_file; ?>" />
							</div>
							
							<div class="form-row">
								<label for="keywords"><?php echo JText::_('KEYWORDS_LABEL'); ?>:</label>
												
								<select id="keywords" name="keywords">
					
									<?php foreach( $this->keywords as $keyword ){ ?>
									<option value="<?php echo $keyword->keyword; ?>" class="selected"><?php echo $keyword->keyword; ?></option>
									<?php } ?>
									
								</select>
								
							</div>
							
							<div class="form-row">
								<label><?php echo JText::_('CLIENTS_LABEL'); ?>:</label>
								<?php echo $this->clients; ?>
							</div>

							
						</div>
					
						<div class="clear"><!--   --></div>
					
					</div>
					
					<div class="btn-container fr">
							
						<?php if( $asset_id ){ ?>
						<button type="button" class="btn delete-btn icon-btn delete-asset" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
						<?php } ?>
						
						<button type="button" class="btn cancel-btn icon-btn" onclick="window.location='<?php echo JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$this->list_menu_itemid ); ?>'; return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
						
						<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
					</div>
					
					<input type="hidden" id="assetid" name="assetid" value="<?php echo $asset_id; ?>" />
			
			</div>
		
		</div>
		
		<div class="clear"><!-- --></div>
		
	</div>
</form>


<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_CONTENT'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="<?php echo $delete_link; ?>" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_ASSET' ); ?></span></a>

</div>