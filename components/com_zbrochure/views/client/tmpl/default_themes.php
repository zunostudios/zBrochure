<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="form-row">

<!--<div class="form-row">
	
			<?php 
			
				$theme_url = JRoute::_('index.php?option=com_zbrochure&view=theme&layout=modal&tmpl=modal');
				$doc = JFactory::getDocument();
				
				$js = '$(document).ready(function(){
					
					var add_theme = new editBlockTheme();
					
					$(\'#add-theme\').click(function(){
					
						add_theme.edit( 0, (this), \''.$theme_url.'\' );
					
					});
				
				});';
				
				$doc->addScriptDeclaration($js);
			
			?>
	
		<a href="javascript:void(0);" class="btn add-btn" id="add-theme">+ Add Theme</a>
	</div>-->
	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	
		<tbody>
	
		<?php foreach( $this->themes as $theme ){ $theme_data = json_decode( $theme->theme_data ); ?>
		
			<?php 
			
				$theme_url = JRoute::_('index.php?option=com_zbrochure&view=theme&layout=modal&tmpl=modal&id='.$theme->theme_id);
				$doc = JFactory::getDocument();
				
				$js = '$(document).ready(function(){
					
					var edit_theme_'.$theme->theme_id.' = new editBlockTheme();
					
					$(\'#theme_edit_'.$theme->theme_id.'\').click(function(){
					
						edit_theme_'.$theme->theme_id.'.edit( '.$theme->theme_id.', (this), \''.$theme_url.'\' );
					
					});
				
				});';
				
				//$doc->addScriptDeclaration($js);
			
			?>
			
			<tr>	
				<td>
				
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="theme-table">
						
						<tr id="preview-container-<?php echo $theme->theme_id; ?>" class="theme-preview">
							
							<?php if( $theme->theme_id == $this->client->client_version_theme ){ ?>
								<td class="first" width="10"><input type="radio" id="themeid_<?php echo $theme->theme_id; ?>" name="client_version_theme" value="<?php echo $theme->theme_id; ?>" checked="checked" /></td>
							<?php }else{ ?>
								<td class="first" width="10"><input type="radio" id="themeid_<?php echo $theme->theme_id; ?>" name="client_version_theme" value="<?php echo $theme->theme_id; ?>" /></td>
							<?php } ?>
						
							<?php $i = 1; foreach( $theme_data->color as $color ){ ?>
							<td>
								<div class="color-block" style="background-color:rgb(<?php echo $color; ?>)"><!--   --></div>
							</td>
							<?php $i++; } ?>
							<td class="last" width="20">&nbsp;</td>
						</tr>
						
					</table>
					
				</td>
						
			</tr>
			
		<?php } ?>
	
		</tbody>
	
	</table>

</div>