<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

JHTML::script( 'colorpicker.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'eye.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'layout.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'utils.js', 'components/com_zbrochure/assets/js/' );

?>

<script type="text/javascript">
$(document).ready(function(){
	
	var delete_dialog = $( "#delete-dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true
	});
	
	$('button.delete-theme').each(function( index ){
		
		$(this).click(function(){
					
			delete_dialog.dialog( 'open' );
			
		});
		
	});
	
	count = <?php echo $this->dataCount; ?>;
	
	<?php if( $this->theme->theme_data ){ ?>
/*
	var headerColor = $('#color1').css('backgroundColor');
	$('#headline').css('color',headerColor);
	
	var subColor = $('#color2').css('backgroundColor');
	$('#subheading').css('color',subColor);
	
	var paragraphColor = $('#color3').css('backgroundColor');
	$('#paragraph').css('color',paragraphColor);
*/
	<?php } ?>

	$('#maincp').ColorPicker({
		color: '#FFFFFF',
		flat: true,
		onChange: function( hsb, hex, rgb, el ) {
			
			var colorToEdit = $('.color-edit:checked').attr('value');
			
			$('#savecolor'+colorToEdit).attr('value',rgb.r+','+rgb.g+','+rgb.b);
			$('#color'+colorToEdit).css('background-color','rgb('+rgb.r+','+rgb.g+','+rgb.b+')');
			$('#selector'+colorToEdit).css('background-color','rgb('+rgb.r+','+rgb.g+','+rgb.b+')');
			
			if(colorToEdit == 1){$('#headline').css('color','#'+hex);}
			
			if(colorToEdit == 2){$('#subheading').css('color','#'+hex);}
			
			if(colorToEdit == 3){$('#paragraph').css('color','#'+hex);}
			
			if(colorToEdit == 4){$('#main_header').css('color','#'+hex);}
			
			if(colorToEdit == 5){$('#table_header').css('color','#'+hex);}
			
			if(colorToEdit == 6){$('#alternate_table_row').css('color','#'+hex);}
			
		}
		
	});
	
	$( "#preview-container" ).sortable();
	$( "#preview-container" ).disableSelection();
	
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

function addColor(){

	$('.color-block').css('marginTop','0');
	$('.selector').css('border','1px solid #CCCCCC');
	$('.color-block').css('height','100px');

	count = count + 1;
	var newRadio = '<span style="float:left"><span id="selector'+count+'" class="selector" style="background-color:#FFFFFF;border:2px solid #000;cursor:pointer;display:block;height:15px;margin:0 5px 0 0;width:15px" onclick="colorSelected('+count+');"><!--   --></span><input type="radio" style="margin:0 2px;display:none" name="colorEdit" class="color-edit" id="radio'+count+'" value="'+count+'" checked="checked" /></span>';
	
	var newPreview 	= '<td><div onclick="colorSelected('+count+');" id="color'+count+'" class="color-block" style="background-color:#FFF;height:110px"><!--   --></div><input id="savecolor'+count+'" name="data[color][]" type="hidden" /></td>';
	
	$('#radioContainer').append( newRadio );
	$('#preview-container').append( newPreview );
	

}

function deleteColor(){

	var colorToDel = $('.color-edit:checked').attr('value');
	
	if( colorToDel == null ){
		alert('Please select a color');
	}else{
		$('#color'+colorToDel).parent().remove();
		$('#selector'+colorToDel).parent().remove();
	}

}

function colorSelected(color){
	
	$('.selector').css('border','1px solid #CCCCCC');
	$('#selector'+color).css('border', '2px solid #000000');
	$('#radio'+color).attr('checked', true);

	$('.color-block').css('height','100px');
	$('#color'+color).css('height','110px');
	
	//Let's get the current color and make an RGB object
	//so we can set the color in the picker
	var thisColor = $('#color'+color).css('backgroundColor');
	var numberPattern = /\d+/g;
	var rgbValues = String( thisColor.match(numberPattern) );
	var rgbValuesArray = rgbValues.split(",");
	thisColorHash = new Object();
	thisColorHash.r = rgbValuesArray[0];
	thisColorHash.g = rgbValuesArray[1];
	thisColorHash.b = rgbValuesArray[2];
	
	//Cool, now we have the RGB object we can set the color picker
	$('#maincp').ColorPickerSetColor(thisColorHash);
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

function getColors( picker ){
	i = 0;
	colors = new Array();
	
	$('.'+picker+'-choose').empty();
	
	$('.color-block').each( function(index){
		colors[i] = $(this).css('backgroundColor');
		var bg = $(this).css('backgroundColor');
		$('.'+picker+'-choose').append('<div style="background-color:'+bg+'" class="'+picker+'" onClick="newTextcolor(this);"></div>');
		i++;
	});
	
	$('.'+picker+'-choose').append('<div style="background-color:rgb(255,255,255)" class="'+picker+'" onClick="newTextcolor(this);"></div>');
	$('.'+picker+'-choose').append('<div style="background-color:rgb(0,0,0)" class="'+picker+'" onClick="newTextcolor(this);"></div>');
	
	$('.'+picker+'-choose').slideToggle();
	
}
	
function newTextcolor( elem ){
	
	var color = $(elem).css('backgroundColor');
	var type = $(elem).attr('class');
	
	$('#'+type+'-choose div.fill').css('backgroundColor', color);
	
	if( $('.'+type+'-color').hasClass('bg') ){
		$('.'+type+'-color').css('backgroundColor', color);
	}else{
		$('.'+type+'-color').css('color', color);
	}
	
	
	$('#'+type+'-input').val(color);
	
}
</script>