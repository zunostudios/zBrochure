<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="bro-options-horiz options-0" style="width:<?php echo $this->layout->tmpl_page_width.''.$this->layout->tmpl_unit_of_measure;?>">
	
	<div class="btn-container">
	
		<div class="bro-option">
	
			<div class="inner" style="width:30px;text-align:center">
				<h4><?php echo $this->layout->tmpl_layout_key; ?> </h4>
			</div>
			
		</div>
		
		<div class="bro-option" style="text-align:left">
		
			<div class="inner">
				
				<dl class="tmpl-details">

					<dt><?php echo JText::_( 'TEMPLATE_LAYOUT_DETAILS' ); ?> (<?php echo $this->layout->tmpl_layout_id; ?>, <?php echo $this->layout->tmpl_layout_current_version; ?>)</dt>
					
					<dd><strong><?php echo $this->layout->tmpl_layout_name; ?></strong></dd>
					
					<dd><?php echo $this->layout->tmpl_layout_desc; ?></dd>
					
					<dd><?php echo $this->layout->tmpl_layout_details; ?></dd>
					
				</dl>
				
			</div>
		
		</div>
			
		<div class="bro-option delete">
	
			<div class="inner">
				
				<a id="edit-<?php echo $this->layout->tmpl_layout_id; ?>" href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=layout&id='.$this->layout->tmpl_layout_id ); ?>" class="btn edit-icon-btn icon-only-btn edit-page edit-layout-link"><span><?php echo JText::_( 'EDIT_LAYOUT' ); ?></span></a>
				
				<a id="duplicate-<?php echo $this->layout->tmpl_layout_id; ?>" href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=duplicateLayout&id='.$this->layout->tmpl_layout_id.'&tmpl_id='.$this->layout->tmpl_id ); ?>" class="btn duplicate-icon-btn icon-only-btn duplicate-page duplicate-layout-link"><span><?php echo JText::_( 'DUPLICATE_LAYOUT' ); ?></span></a>
				
				<a id="delete-<?php echo $this->layout->tmpl_layout_id; ?>" href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=trashPage&bro_id='.$this->layout->tmpl_id.'&tmpl_layout_id='.$this->layout->tmpl_layout_id.'&bro_pages='.$this->bro->bro_pages ); ?>" class="btn delete-icon-btn icon-only-btn delete-page"><span><?php echo JText::_( 'DELETE_LAYOUT' ); ?></span></a>
				
			</div>
			
		</div>
		
	</div>

</div>

<div id="delete-dialog-<?php echo $this->layout->tmpl_layout_id; ?>" title="<?php echo JText::_('DELETE_LAYOUT_TITLE'); ?>">
	
	<div class="form-row add-padding package-container-header">
	
		<div id="delete-dialog-preview-<?php echo $this->layout->tmpl_layout_id; ?>"></div>
	
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_LAYOUT' ); ?></span></a>
	
</div>

<script type="text/javascript">

$(document).ready(function(){
	
	var edit_dialog = $('#edit-dialog-<?php echo $this->layout->tmpl_layout_id; ?>').dialog({

		autoOpen:false,
		width:900,
		height:600,
		resizable:true,
		modal: true,
		close: function( event, ui ){
		}
	
	});
	
	$('#edit-<?php echo $this->layout->tmpl_layout_id; ?>').click(function(){
		
		edit_dialog.dialog( 'open' );
		
	});
	
	var delete_dialog = $('#delete-dialog-<?php echo $this->layout->tmpl_layout_id; ?>').dialog({

		autoOpen:false,
		width:260,
		height:360,
		resizable:false,
		modal: true,
		close: function( event, ui ){
		}
	
	});
	
		
	var link = $('#delete-<?php echo $this->layout->tmpl_layout_id; ?>').attr( 'href' );
	$('#delete-<?php echo $this->layout->tmpl_layout_id; ?>').attr( 'href', 'javascript:void(0)' );
	
	$('#delete-<?php echo $this->layout->tmpl_layout_id; ?>').click(function(){
	
		var thumbnail = $('#tmpl-page-<?php echo $this->layout->tmpl_layout_id; ?>').css('backgroundImage');
		
		$('#delete-dialog-preview-<?php echo $this->layout->tmpl_layout_id; ?>').empty();
		
		$('#delete-dialog-preview-<?php echo $this->layout->tmpl_layout_id; ?>').css({
			'backgroundImage': thumbnail,
			'height': '240px',
			'width': '100%',
			'background-size': '100% auto'
		});
		
		$('#delete-dialog-<?php echo $this->layout->tmpl_layout_id; ?> a.delete-btn').attr( 'href', link );
		
		delete_dialog.dialog( 'open' );
		
	});
	
});

</script>
