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
	
	edit_block_dialog = $( '<div style="display:none" class="loading" title="Edit Block"><iframe id="edit-block-modal" width="100%" height="100%" frameBorder="0">No iframe support</iframe></div>').appendTo('body');
			
	edit_block_dialog.dialog({
	
		autoOpen:false,
		width: 600,
		height: 500,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#edit-block-modal').attr( 'src', '' );
		}
	
	});
	
	$('.edit-block-link').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#edit-block-modal').attr( 'src', link+'&layout=modal&tmpl=modal' );
			edit_block_dialog.dialog( 'open' );
			
		});
		
	});
	
	var icons = {
		header: "ui-icon-circle-arrow-e",
		headerSelected: "ui-icon-circle-arrow-s"
	};
	
	$( "#accordion" ).accordion({
		autoHeight: false,
		navigation: true,
		collapsible: true,
		active: false,
		icons: icons
	});
	
	$( "#layout-accordion" ).accordion({
		autoHeight: false,
		navigation: true,
		collapsible: true,
		active: false,
		header: 'label',
		icons: icons
	});
	
});

function deleteBlock( block_id ){
	
	$.ajax({
		type: 'POST',
		url: 'index.php',
		data: 'option=com_zbrochure&task=deleteBlock&block_id='+block_id,
		success: function(html){
		
			alert( 'Block '+block_id+' has been deleted. Farewell.' );
			$( '#block_'+block_id ).remove();
		
		}
	});
	
}

</script>