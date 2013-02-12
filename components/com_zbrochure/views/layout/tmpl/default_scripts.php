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
	
	edit_layout_dialog = $( '<div style="display:none" class="loading" title="Edit Layout"><iframe id="edit-layout-modal" width="100%" height="100%" frameBorder="0">No iframe support</iframe></div>').appendTo('body');
			
	edit_layout_dialog.dialog({
	
		autoOpen:false,
		width: 1000,
		height: 600,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#edit-layout-modal').attr( 'src', '' );
		}
	
	});
	
	$('.edit-layout-link').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#edit-layout-modal').attr( 'src', link+'&layout=modal&tmpl=modal' );
			edit_layout_dialog.dialog( 'open' );
			
		});
		
	});
	
});
</script>