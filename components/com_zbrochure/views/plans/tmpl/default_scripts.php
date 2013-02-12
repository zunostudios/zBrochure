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
		
	var delete_plan_dialog = $( "#delete-plan-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
		}
	});
	
	edit_plan_dialog = $( '<div style="display:none" class="loading" title="Edit Plan"><iframe id="edit-plan-modal" width="100%" height="100%" frameBorder="0">No iframe support</iframe></div>').appendTo('body');
			
	edit_plan_dialog.dialog({
	
		autoOpen:false,
		width: 600,
		height: 350,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#edit-plan-modal').attr( 'src', '' );
		}
	
	});
	
	$('.edit-plan-link').each(function(){
		
		var edit_url = $(this).attr('href');
		$(this).attr( 'href', 'javascript:void(0);' );
		
		$(this).click(function(){
		
			$('#edit-plan-modal').attr( 'src', edit_url+'&Itemid=<?php echo $this->list_menu_itemid; ?>&layout=assign&tmpl=modal' );
			edit_plan_dialog.dialog( 'open' );
			
		});
		
	});
	
	
	edit_package_dialog = $( '<div style="display:none" class="loading" title="Edit Package"><iframe id="edit-package-modal" width="100%" height="100%" frameBorder="0">No iframe support</iframe></div>').appendTo('body');
			
	edit_package_dialog.dialog({
	
		autoOpen:false,
		width: 800,
		height: 600,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#edit-package-modal').attr( 'src', '' );
		}
	
	});
	
	$('.edit-package-link').each(function(){
		
		var edit_url = $(this).attr('href');
		$(this).attr( 'href', 'javascript:void(0);' );
		
		$(this).click(function(){
		
			$('#edit-package-modal').attr( 'src', edit_url+'&Itemid=<?php echo $this->list_menu_itemid; ?>&layout=modal&tmpl=modal' );
			edit_package_dialog.dialog( 'open' );
			
		});
		
	});
	
	
	
	
	
	$('.delete-plan-link').each(function(){
		
		var delete_url = $(this).attr('href');
		$(this).attr( 'href', 'javascript:void(0);' );
		
		$(this).click(function(){
		
			$('#delete-plan-modal-btn').attr( 'href', delete_url+'&Itemid=<?php echo $this->list_menu_itemid; ?>' );
			delete_plan_dialog.dialog( 'open' );
			
		});
		
	});
	
	$('.sortable').dataTable({
		"aoColumnDefs":[{ "bSortable": false, "aTargets": [ 4 ] }],
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
	});
	
});
</script>