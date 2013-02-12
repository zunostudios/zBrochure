<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */
 
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
$(document).ready(function(){
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	$('.delete-package').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			delete_dialog.dialog( 'open' );
			
		});
		
	});
		
	$('.sortable').dataTable({
		"aoColumnDefs":[{ "bSortable": false, "aTargets": [ 3,4 ] }],
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
	});
	
});
</script>