<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="form-group" id="asset-uploader">
				
	<div class="form-row" id="asset-upload-form">
		
		<div class="broker-logo-preview">
		
			<div id="fullcolor" class="inner"></div>
		
		</div>
	
		<input type="hidden" name="broker_version_logo_full_color" id="broker_version_logo_full_color" value="<?php echo $this->broker->broker_version_logo_full_color; ?>" />
		
		<div id="file-uploader">		
			<noscript>			
				<p>Please enable JavaScript to use file uploader.</p>
				<!-- or put a simple form for upload here -->
			</noscript>         
		</div>					

	</div>

</div>

<div class="form-group" id="broker-logos-container">
	
	<div class="form-row">
		
		<table cellpadding="0" cellspacing="0" id="broker-logos" width="100%">
			
			<tbody>
			<?php if( $this->logos ){ ?>
				
				<?php foreach( $this->logos as $logo ){ $logo_dir = JURI::base().$this->broker_dir.DS.$this->broker->broker_id.DS.'logos'.DS; ?>
				
					<tr>
		
						<td class="logo-list-holder" style="background-color:#E4E4E4;text-align:center;width:150px" id="logo-id-<?php echo $logo->broker_logo_id; ?>">
											
							<a href="<?php echo $logo_dir.$logo->broker_logo_filename.'.'.$logo->broker_logo_filetype; ?>" target="_blank"><img src="<?php echo $logo_dir.$logo->broker_logo_filename.'.'.$logo->broker_logo_filetype; ?>" width="80%" /></a>
						
						</td>
						
						<td>
		
							<input class="inputbox" placeholder="Enter Logo Name" name="logos[logo-<?php echo $logo->broker_logo_id; ?>][name]" style="width:95%" value="<?php echo $logo->broker_logo_name; ?>" />
		
						</td>
						
						<td>
						
							<?php
							
							$checked = '';
							
							if( $logo->broker_logo_default ){
								
								$checked = ' checked="checked"';
								
							}
							
							?>
						
							<label><input type="checkbox" name="logos[logo-<?php echo $logo->broker_logo_id; ?>][default]" value="1"<?php echo $checked; ?> /> <?php echo JText::_( 'DEFAULT_LOGO' ); ?></label>
						
						</td>
						
						<td>
							
							<label><input type="checkbox" name="logos[logo-<?php echo $logo->broker_logo_id; ?>][deletelogo]" value="<?php echo $logo->broker_logo_id; ?>" id="delete-<?php echo $logo->broker_logo_id; ?>" /> <?php echo JText::_( 'DELETE_LOGO' ); ?></label>
						
							<input type="hidden" name="logos[logo-<?php echo $logo->broker_logo_id; ?>][filename]" value="<?php echo $logo->broker_logo_filename; ?>" />
							<input type="hidden" name="logos[logo-<?php echo $logo->broker_logo_id; ?>][extension]" value="<?php echo $logo->broker_logo_filetype; ?>" />
							<input type="hidden" name="logos[logo-<?php echo $logo->broker_logo_id; ?>][id]" value="<?php echo $logo->broker_logo_id; ?>" />
							
						</td>
						
					</tr>
				
				<?php }?>
			
			<?php }?>
			</tbody>
			
		</table>
		
	</div>

</div>
