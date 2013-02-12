<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

JHTML::script( 'fileuploader.js', 'components/com_zbrochure/assets/js/' );

?>
<script>
$(document).ready(function(){
	
	createUploader();
	
	$('.qq-upload-button').mouseenter( function(){
	
		$('div.qq-upload-drop-area').css('display','block');
	
	}).mouseleave(function() {
	
		$('div.qq-upload-drop-area').css('display','none');
	
	});
	
	equalHeight( $('.form-column') );
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	$('.delete-client').each(function( index ){
		
		var link = $(this).attr( 'href' );
		$(this).attr( 'href', 'javascript:void(0)' );
		
		$(this).click(function(){
					
			$('#delete-dialog a.delete-btn').attr( 'href', link );
			delete_dialog.dialog( 'open' );
			
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
	
});
       
function createUploader(){
        
    var uploader = new qq.FileUploader({

        element: document.getElementById('file-uploader'),

        allowedExtensions: ['jpg','jpeg','gif','png', 'svg'],

        action: 'index.php?option=com_zbrochure&task=uploadPreview&context=broker',

        debug: true,

        onSubmit: function( id, fileName ){
        	//$('#client_version_logo_full_color').val(fileName);
        },

        onComplete: function( id, fileName, responseJSON ){
			
			var newfilename = responseJSON.newname+'.'+responseJSON.extension;
						
			$('#broker-logos').append('<tr><td style="text-align:center;background-color:#E4E4E4;"><img src="<?php echo JURI::base(); ?>media/zbrochure/brokers/tmp/'+newfilename+'" width="80%" /></td><td><input class="inputbox" placeholder="Enter Logo Name" name="logos['+id+'][name]" style="width:95%" value="" /></td><td><label><input type="checkbox" name="logos['+id+'][default]" value="1" /> Default</label></td><td><input type="hidden" name="logos['+id+'][filename]" value="'+responseJSON.newname+'" /><input type="hidden" name="logos['+id+'][extension]" value="'+responseJSON.extension+'" /></td></tr>');
        
        }
        
    });
              
}

/* Jquery Equal Heights */
function equalHeight(group) {

	var tallest = 0;

	group.each(function() {

		var thisHeight = $(this).height();

		if(thisHeight > tallest) {

			tallest = thisHeight;

		}

	});

	group.height(tallest);

}
</script>