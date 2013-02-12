<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">

$(document).ready(function(){
	
	var delete_dialog = $('#delete-dialog').dialog({

		autoOpen:false,
		width:260,
		height:360,
		resizable:false,
		modal: true,
		close: function( event, ui ){
		}
	
	});
	
	$('.bro-thumb-rollover .delete-btn').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
			
			$('#delete-dialog-preview').empty();
			
			var preview = $(this).parents('.bro-thumb-container').clone().appendTo('#delete-dialog-preview');
			
			$(preview).find('.bro-thumb-rollover').remove();
			
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
	var preview_dialog = $('#preview-dialog').dialog({
			
		autoOpen:false,
		width: 730,
		height: 650,
		modal: true,
		close: function( event, ui ){
			$('#preview-dialog').empty();
		}
	
	});
	
	$('.bro-thumb-rollover .preview-btn').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
			
			$('<iframe id="preview-modal" width="100%" height="100%" frameBorder="0" scrolling="no" src="'+link+'">No iframe support</iframe>').appendTo('#preview-dialog');
			preview_dialog.dialog( 'open' );
			
		});
		
	});
	
});

function brochureSort( sortby ){

	$('#order').val(sortby);
	$('#sortBrochures').submit();

}
</script>