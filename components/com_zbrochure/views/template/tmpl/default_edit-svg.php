<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');
	
$theme = json_decode( $this->theme->theme_data );
	
?>

<div id="edit-svg" title="Edit Element">

	<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=saveContentBlockSvg' ); ?>" method="post">
		
		<div class="form-row text-container">
			
			<div id="tmpl-theme-change" class="form-row add-padding package-container-header">
				
				
				<div class="theme-color-chosen">
					<div style="border:2px solid #FFF">
						<div id="theme-color-chosen"><!-- --></div>
					</div>
				</div>
				
				<div class="theme-color-opacity">
					<div class="slider-label-left"><?php echo JText::_( 'OPACITY' ); ?></div><div id="svg-opacity-scale-slider" class="svg-opacity-slider"></div>
					<div class="clear"><!-- --></div>
				</div>
				
				<div class="theme-container">			
							
				<?php if( $theme ){
					
					$i = 1;
					foreach( $theme->color as $color ){
				
				?>
				
					<div id="color<?php echo $i; ?>" class="theme-element" style="background-color:rgb(<?php echo $color; ?>)" onclick="changeColor( this, <?php echo $i; ?> );"><!--   --></div>
					
					<?php $i++; }
					
				} ?>
	
				</div>
				
				
			</div>
			
		</div>
		
		<div class="form-row btn-container add-padding" style="margin:10px 0">
			<button class="btn save-btn fr" type="submit"><span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			<button class="btn cancel-btn fr" onclick="$( '#edit-svg' ).dialog('close'); return false;"><span><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
		</div>
		
		<input type="hidden" name="svg_content_block" id="svg-content-block" value="" />
		<input type="hidden" name="svg_block" id="svg-block" value="" />
		
		<input type="hidden" name="svg_color_start" id="svg-color-start" value="" />
		<input type="hidden" name="svg_opacity_start" id="svg-opacity-start" value="" />
		
		<input type="hidden" name="svg_color" id="svg-color" value="" />
		<input type="hidden" name="svg_opacity" id="svg-opacity" value="" />
		
		<input type="hidden" name="bro_id" value="<?php echo $this->bro->bro_id; ?>" />
		
	</form>
	
</div>