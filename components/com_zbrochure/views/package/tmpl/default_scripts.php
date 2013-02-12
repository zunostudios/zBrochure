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

	var table_columns = 5;
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
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
	
	$('button.delete-package').each(function( index ){
		
		$(this).click(function(){
					
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
	i = <?php echo ( (int)$this->dataCount ) ?  (int)$this->dataCount + 1 : 2; ?>;

	var checked = $('.isheader:checked').get();
	
	//makeHeader( checked );
	
	$( "#sortable" ).sortable({
		placeholder: "ui-state-highlight",
		cancel: "nosort",
		helper: fixHelper,
		handle: '.handle',
		'start': function (event, ui) {
        	ui.placeholder.html('<td colspan="'+table_columns+'">&nbsp;</td>');
    	},
	});
	
	$(checked).each(function(index){

		var parent = $(this).parent().parent().parent();
		var parentId = $(parent).attr('id');
		var checkBox = $('#'+parentId+' .isheader').attr('id');
	
		//$(parent).css('background-color','#EAEAEA');
		$(parent).addClass( 'header-row' );
		//$('#'+parentId + ' .inputbox').attr('name','details['+checkBox+'][]');	

	});

	edit_plan_dialog = $( '<div style="display:none" class="loading" title="Edit Plan"><iframe id="edit-plan-modal" width="100%" height="455" frameBorder="0">No iframe support</iframe></div>').appendTo('body');
			
	edit_plan_dialog.dialog({
	
		autoOpen:false,
		width: 600,
		height: 500,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#edit-plan-modal').attr( 'src', '' );
		}
	
	});
	
	$( '#add-plan-btn' ).click(function(){
		
		$('#edit-plan-modal').attr( 'src', '<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=plan&pid='.$this->package->package_id.'&Itemid='.$this->list_menu_itemid ); ?>&layout=modal&tmpl=modal' );
		edit_plan_dialog.dialog( 'open' );
		
	});
	
	$('.edit-plan-link').each(function(){
		
		var edit_url = $(this).attr('href');
		$(this).attr( 'href', 'javascript:void(0);' );
		
		$(this).click(function(){
		
			$('#edit-plan-modal').attr( 'src', edit_url+'&Itemid=<?php echo $this->list_menu_itemid; ?>&layout=modal&tmpl=modal' );
			edit_plan_dialog.dialog( 'open' );
			
		});
		
	});
	
	$('.delete-plan-link').each(function(){
		
		var delete_url = $(this).attr('href');
		$(this).attr( 'href', 'javascript:void(0);' );
		
		$(this).click(function(){
		
			$('#delete-plan-modal-btn').attr( 'href', delete_url+'&view=package&pid=<?php echo $this->package->package_id; ?>&Itemid=<?php echo $this->list_menu_itemid; ?>' );
			delete_plan_dialog.dialog( 'open' );
			
		});
		
	});
	
	CKEDITOR.replace( "package_content", { height:"200" } );
	CKEDITOR.replace( "package_footer", { height:"100" } );
	
});

function addRow(elem){
	
	var myrow = $(elem).parent().parent();
	
	$('.toClone').clone(true).removeClass('toClone').attr('id','row'+i).insertAfter(myrow);
	
	$('#row'+i+' .isheader').attr('id','isheader'+i);
	
	$('#row'+i+' .isheader').attr('name','details['+i+'][is_header]');
	
	$('#row'+i+' .isheader').attr('onchange','makeHeader(this)');
	
	$('#row'+i+' #labelinput').attr('name','details['+i+'][package_label]');
	//$('#row'+i+' #labelid').attr('name','details['+i+'][package_label_id]');
	//$('#row'+i+' #labelid').attr('value', i);

	
	$('#row'+i+' #labelinput').attr('id','')
	//$('#row'+i+' #labelid').attr('id','')
	
	i++;
	
}

function deleteRow(elem){

	var myrow = $(elem).parent().parent();
	$(myrow).remove();
	i--;
	
}

function makeHeader(elem){
	var parent = $(elem).parent().parent().parent();
	var parentId = $(parent).attr('id');
	var checkBox = $('#'+parentId+' .isheader').attr('id');
	
	if( $('#'+checkBox+':checked').length >= 1 ){
		$(parent).addClass( 'header-row' );
		//$('#'+parentId + ' .inputbox').attr('name','details['+checkBox+'][]');	
	}else{
		$(parent).removeClass( 'header-row' );
		//$('#'+parentId + ' .inputbox').attr('name','details[]');
	}
	
	
}

function clearClone(){
	$('.toClone').remove();
}

function addCat(){
	var toAdd = $('#addPackageCat').val();
	var url = 'index.php?option=com_zbrochure&task=saveCat&data='+toAdd;
	$.get(url, function(data){
		$('#package_categories').append('<option selected="selected" value="'+data+'">'+toAdd+'</option>');
		$('#addPackageCat').val('');
	});
}

// Return a helper with preserved width of cells
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};

</script>