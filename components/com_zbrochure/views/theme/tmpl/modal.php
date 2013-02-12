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

if($this->data->theme_data){
	$data = json_decode( $this->data->theme_data );
	$dataCount = count($data->color);
}else{
	$dataCount = 4;
}

?>

<link href="<?php echo JURI::base(); ?>components/com_zbrochure/assets/css/colorpicker.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
$(document).ready(function(){

	count = <?php echo $dataCount; ?>;
	
	<?php if( $this->data->theme_data ){ ?>
	var headerColor = $('#color1').css('backgroundColor');
	$('#headline').css('color',headerColor);
	
	var subColor = $('#color2').css('backgroundColor');
	$('#subheading').css('color',subColor);
	
	var paragraphColor = $('#color3').css('backgroundColor');
	$('#paragraph').css('color',paragraphColor);
	<?php } ?>

	$('#maincp-<?php echo JRequest::getVar('id');?>').ColorPicker({
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
			
		}
		
	});

	//equalHeight($('.form-column'));
	
	$( "#preview-container-<?php echo JRequest::getVar('id'); ?>" ).sortable();
	
	$( "#preview-container-<?php echo JRequest::getVar('id'); ?>" ).disableSelection();
	
});

function addColor(){

	$('.color-block').css('marginTop','0');
	$('.selector').css('border','1px solid #CCCCCC');

	count = count + 1;
	var newRadio = '<span style="float:left"><span id="selector'+count+'" class="selector" style="background-color:#FFFFFF;border:2px solid #000;cursor:pointer;display:block;height:15px;margin:0 5px 0 0;width:15px" onclick="colorSelected('+count+');"><!--   --></span><input type="radio" style="margin:0 2px;display:none" name="colorEdit" class="color-edit" id="radio'+count+'" value="'+count+'" checked="checked" /></span>';
	
	var newPreview 	= '<td><div onclick="colorSelected('+count+');" id="color'+count+'" class="color-block" style="background-color:#FFF;margin-top:-10px"><!--   --></div><input id="savecolor'+count+'" name="data[color][]" type="hidden" /></td>';
	
	$('#radioContainer').append(newRadio);
	$('#preview-container-<?php echo JRequest::getVar('id'); ?>').append(newPreview);
	

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

	$('.color-block').css('marginTop','0');
	$('#color'+color).css('marginTop','-10px');
	
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
	$('#maincp-<?php echo JRequest::getVar('id');?>').ColorPickerSetColor(thisColorHash);
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

function submitForm(){

$.ajax({type:'POST', url: 'input.php?option=com_zbrochure&task=saveTheme', data:$('#theme-form-<?php echo JRequest::getVar('id'); ?>').serialize(), success: function(response) {
    location.reload();
}});

}
</script>

<form id="theme-form-<?php echo JRequest::getVar('id'); ?>" style="padding:20px" action="input.php?option=com_zbrochure&task=saveTheme" method="post" enctype="multipart/form-data">

	<div class="two-col-form">
	
		<div class="form-column">
		
			<div class="inner">
			
				<div class="form-group">
				
					<div class="form-row">
						<label for="theme_class">Theme Class:</label>
						<input type="text" class="inputbox" name="data[class]" id="theme_class" style="width:200px" value="<?php echo $data->class; ?>" />
					</div>
					
					<?php if( !$data ){ ?>
					<div class="form-row">
						<label>Preview:</label>
						
						
						<table cellpadding="0" cellspacing="0" border="0" width="100%" id="previewTable">
						<tr id="preview-container-<?php echo JRequest::getVar('id'); ?>">
							<td>
							<div id="color1" class="color-block" style="background-color:#FFF"><!--   --></div>
							<input id="savecolor1" name="data[color][]" type="hidden" />
							</td>							
						</tr>
						</table>
						
						<div class="clear"><!--   --></div>
						
					</div>
					<?php }else{ ?>
					
					<div class="form-row">
					
						<table cellpadding="0" cellspacing="0" border="0" width="100%" id="previewTable">
						<tr id="preview-container-<?php echo JRequest::getVar('id'); ?>">
							
							<?php $i = 1; foreach( $data->color as $color ){ ?>
								<td>
								<div id="color<?php echo $i; ?>" class="color-block" style="background-color:rgb(<?php echo $color; ?>)" onclick="colorSelected(<?php echo $i; ?>);"><!--   --></div>
								<input id="savecolor<?php echo $i; ?>" name="data[color][]" type="hidden" value="<?php echo $color; ?>" />
								</td>
							<?php $i++; } ?>

							
						</tr>
						</table>
						<div class="clear"><!--   --></div>
					</div>
					<?php } ?>
										
					<div class="form-row">
						<h2 id="headline" class="color1">Headline text</h2>
						<h3 id="subheading" class="color2">Subheading</h3>
						<p id="paragraph" class="color3">Example Paragraph text…</p>
					</div>
					
				</div>
			
			</div>
		
		</div>
		
		<div class="form-column">
		
			<div class="inner">
			
				<div class="form-group">
					
					<div class="form-row">
						
						<div id="radioContainer" style="overflow:hidden">
						<?php if( !$data ){ ?>
						
								<span style="float:left">
								<span id="selector1" class="selector" style="background-color:#FFF;border:2px solid #000;cursor:pointer;display:block;height:15px;margin:0 5px 0 0;width:15px" onclick="colorSelected(1);"><!--   --></span>
								<input type="radio" name="colorEdit" class="color-edit" onchange="colorSelected(this.value);" id="radio1" value="1" style="display:none" checked="checked" />
								</span>
						
						<?php }else{ ?>
							<?php $c = 1; foreach( $data->color as $color ){ ?>
								<span style="float:left">
								<span id="selector<?php echo $c; ?>" class="selector" style="background-color:rgb(<?php echo $color; ?>);border:1px solid #CCC;cursor:pointer;display:block;height:15px;margin:0 5px 0 0;width:15px" onclick="colorSelected(<?php echo $c; ?>);"><!--   --></span>
								<input type="radio" name="colorEdit" class="color-edit" onchange="colorSelected(this.value);" id="radio<?php echo $c; ?>" value="<?php echo $c; ?>" style="display:none" />
								</span>
							<?php $c++; } ?>
						<?php } ?>
						</div>
						
						<button class="btn add-btn" onclick="deleteColor(); return false;">
							<span>-</span>
						</button>
						
						<button class="btn add-btn" onclick="addColor(); return false;">
							<span>+</span>
						</button>

					</div>
				
					<div class="form-row">
						<div id="maincp-<?php echo JRequest::getVar('id');?>"></div>
					</div>
				
				</div>
				
			</div>
		
		</div>
		
		<div class="clear"><!--   --></div>
		
	</div>
	
	<div class="btn-container">
	
		<button class="btn cancel-btn" onclick="location.reload(); return false;">
			<span>Cancel</span>
		</button>
		
		<button class="btn save-btn" type="submit" onclick="submitForm(); return false;">
			<span>Save</span>
		</button>
		
	</div>
	
	<input type="hidden" name="theme_id" value="<?php echo JRequest::getVar('id');?>" />

</form>