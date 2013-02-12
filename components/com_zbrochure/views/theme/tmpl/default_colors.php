<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="form-row">
	
	<div class="form-row-inner">
	
		<label><?php echo JText::_( 'THEME_COLORS' ); ?></label>
		
		<button class="btn minus-btn icon-btn" onclick="deleteColor(); return false;">
			<span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_THEME_COLOR' ); ?></span>
		</button>
		
		<button class="btn add-btn icon-btn" onclick="addColor(); return false;">
			<span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'ADD_THEME_COLOR' ); ?></span>
		</button>
		
	</div>
	
</div>

<div class="theme-color-list-table">
						
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id="previewTable">
			
			<tbody>
			
				<tr id="preview-container">
					
					<?php if( $this->theme_data ){
						
						$i = 1;
						foreach( $this->theme_data->color as $color ){
					
					?>
						
						<td>
							<div id="color<?php echo $i; ?>" class="color-block" style="background-color:rgb(<?php echo $color; ?>)" onclick="colorSelected(<?php echo $i; ?>);"><!--   --></div>
							<?php echo $color; ?>
							<input id="savecolor<?php echo $i; ?>" name="data[color][]" type="hidden" value="<?php echo $color; ?>" />
						</td>
						
						<?php $i++; }
						
					} ?>
					
				</tr>
			
			</tbody>
			
		</table>
	
</div>
		
		<div class="clear"><!--   --></div>
		

	
<div class="form-row">
						
	<div id="radioContainer" style="display:none;overflow:hidden">
	<?php if( !$this->theme_data ){ ?>
	
			<span style="float:left">
			<span id="selector1" class="selector" style="border:2px solid #000;cursor:pointer;display:block;height:15px;margin:0 5px 0 0;width:15px" onclick="colorSelected(1);"><!--   --></span>
			<input type="radio" name="colorEdit" class="color-edit" onchange="colorSelected(this.value);" id="radio1" value="1" style="display:none" checked="checked" />
			</span>
	
	<?php }else{ ?>
		<?php $c = 1; foreach( $this->theme_data->color as $color ){ ?>
			<span style="float:left">
			<span id="selector<?php echo $c; ?>" class="selector" style="background-color:rgb(<?php echo $color; ?>);border:1px solid #CCC;cursor:pointer;display:block;height:15px;margin:0 5px 0 0;width:15px" onclick="colorSelected(<?php echo $c; ?>);"><!--   --></span>
			<input type="radio" name="colorEdit" class="color-edit" onchange="colorSelected(this.value);" id="radio<?php echo $c; ?>" value="<?php echo $c; ?>" style="display:none" />
			</span>
		<?php $c++; } ?>
	<?php } ?>
	</div>

</div>

<div class="form-row">
	<div id="maincp" style="margin:0 auto;width:356px"></div>
</div>