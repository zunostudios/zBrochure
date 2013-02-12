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
	
	//Jquery UI dialog/modal creation
	edit_dialog = $( "#dialog-new" ).dialog({
		autoOpen: false,
		resizable: true,
		width:800,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function(){
			enable_scroll();
			document.getElementById('keywords').options.length = 0;
			$('.holder').remove();
			$('.facebook-auto').remove();
			$("#package_categories").val($("#package_categories option:first").val());	
		}
	});
	
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	$('.delete-content').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
	vars = $( '#vars-dialog' ).dialog({
		autoOpen: false,
		resizable: true,
		height:400,
		width:300,
		close: function(){}
	});
	
	$( '.var-btn' ).each(function(index){
	
		$(this).click(function(){
		
			vars.dialog('open');
		
		});
	
	});

});

function deleteContent(){
	window.location = '<?php echo JRoute::_('index.php?option=com_zbrochure&task=deleteContent&id='); ?>'+contentid;
}

function saveContent(){
	$('#contentEditor').submit();
}

function editContent( id, elem ){
	var getTitle 	= $('#contentTitle'+id).text();
	var getData 	= $('#contentData'+id).text();
	var getCat 		= $('#contentCat'+id).val();
	
	if( $('#contentKeywords'+id).length > 0 ){
		var getKeywords = $('#contentKeywords'+id).html();
		var removetags = getKeywords.replace(/<li>/gi,'');
		var keywords = removetags.split('</li>');
		
		keywords.pop();
		
		var keywordsArray = new Array();
		
		var i=0;
		for ( i=0; i<= ( keywords.length - 1 ); i++ ){
			$('#keywords').append( '<option value="'+keywords[i]+'" class="selected" selected="selected">'+keywords[i]+'</option>' );
		}
	}
	
	$('#id').val(id);
	$('#title').val(getTitle);
	$('#editor').val(getData);
	$("#catid").val(getCat);
	
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
	
	CKEDITOR.instances['editor'].setData(getData);
	
	$('#dialog-new').dialog('open');
}

function newContent(){
	$('#id').val('');
	$('#title').val('');
	$('#editor').val('');
	$("#catid").val('');
	
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
	
	$('#dialog-new').dialog('open');
}

$(document).ready(function(){

	CKEDITOR.replace('editor');
				
	$('.sortable').dataTable({
		"aoColumnDefs":[{ "bSortable": false, "aTargets": [ 2, 4, 5 ] }],
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
	});
});
</script>