<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="bro-options-horiz options-0" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>">
	
	<div class="btn-container">
	
		<div class="bro-option">
	
			<div class="inner" style="width:30px;text-align:center">
				<div style="padding:8px 0">
					<?php echo $this->page->page_number; ?>
				</div>
			</div>
			
		</div>
		
		<div class="bro-option">
	
			<div class="inner">
				<button type="button" zpage="<?php echo $this->page->bro_page_id; ?>" zorder="<?php echo $this->page->bro_page_order; ?>" zlayout="<?php echo $this->page->bro_page_layout; ?>" class="btn build-bro-btn changelayout-btn" title="Click here to change the layout for page <?php echo $this->page->page_number; ?>."><span><?php echo JText::_( 'CHANGE_LAYOUT' ); ?></span></button>
			</div>
			
		</div>
		
		<div class="bro-option">
		
			<div class="inner">
			
				<?php $checked	= ( $this->page->bro_page_show_page_number ) ? ' checked="checked"' : ''; ?>
			
				<div style="padding:8px 0">
					<label> <input type="checkbox" name="bro_page_show_page_number" onchange="showPageNumber( $(this).attr( 'checked' ), <?php echo $this->page->bro_page_id; ?> )"<?php echo $checked?> value="1" /> <?php echo JText::_( 'SHOW_PAGE_NUMBER' ); ?></label>
				</div>
			
			</div>
		
		</div>
		
<!--
		<div class="bro-option">
	
			<div class="inner">
				<a rel="<?php $this->page->bro_page_id; ?>" href="javascript:void(0);" id="pagetitle-btn-<?php echo $this->page->page_number; ?>" class="pagetitle-btn" title="<?php echo $this->page->bro_page_name; ?>"><?php echo JText::_( 'PAGE_TITLE' ); ?></a>
			</div>
			
		</div>
-->
		
		<div class="bro-option delete">
	
			<div class="inner">
				<a id="delete-<?php echo $this->page->bro_page_id; ?>" href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=trashPage&bro_id='.$this->page->bro_id.'&bro_page_id='.$this->page->bro_page_id.'&bro_pages='.$this->bro->bro_pages ); ?>" class="btn delete-icon-btn icon-only-btn delete-page"><span><?php echo JText::_( 'DELETE_PAGE' ); ?></span></a>
			</div>
			
		</div>
		
	</div>

</div>




<div id="delete-dialog-<?php echo $this->page->bro_page_id; ?>" title="<?php echo JText::_('DELETE_PAGE_TITLE'); ?>">
	
	<div class="form-row add-padding package-container-header">
	
		<div id="delete-dialog-preview-<?php echo $this->page->bro_page_id; ?>"></div>
	
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_PAGE' ); ?></span></a>
	
</div>

<script type="text/javascript">

$(document).ready(function(){
	
	var delete_dialog = $('#delete-dialog-<?php echo $this->page->bro_page_id; ?>').dialog({

		autoOpen:false,
		width:260,
		height:360,
		resizable:false,
		modal: true,
		close: function( event, ui ){
		}
	
	});
	
		
	var link = $('#delete-<?php echo $this->page->bro_page_id; ?>').attr( 'href' );
	$('#delete-<?php echo $this->page->bro_page_id; ?>').attr( 'href', 'javascript:void(0)' );
	
	$('#delete-<?php echo $this->page->bro_page_id; ?>').click(function(){
	
		var thumbnail = $('#tmpl-page-<?php echo $this->page->bro_page_id; ?>').css('backgroundImage');
		
		$('#delete-dialog-preview-<?php echo $this->page->bro_page_id; ?>').empty();
		
		$('#delete-dialog-preview-<?php echo $this->page->bro_page_id; ?>').css({
			'backgroundImage': thumbnail,
			'height': '240px',
			'width': '100%',
			'background-size': '100% auto'
		});
		
		$('#delete-dialog-<?php echo $this->page->bro_page_id; ?> a.delete-btn').attr( 'href', link );
		
		delete_dialog.dialog( 'open' );
		
	});
	
});

</script>
