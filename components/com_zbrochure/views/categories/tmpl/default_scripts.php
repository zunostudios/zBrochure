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

	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	
	$('.delete-category').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
	$('.sortable').dataTable({
		"aoColumnDefs":[{ "bSortable": false, "aTargets": [ 3 ] }],
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
	});
	
});
</script>