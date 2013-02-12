<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

$client_dir = JURI::base().'images'.DS.'client'.DS.JRequest::getVar('id');
$client_tmp = JURI::base().'images'.DS.'client'.DS.'tmp'.DS;

?>
<form name="logomanager" id="logomanager" action="index.php?option=com_zbrochure&task=saveBlock" method="post" target="_parent">
	
	<div class="form-row thumbnail-grid">
		
		<?php foreach( $this->logos as $logo ){
			
			$image	= $this->client_dir.DS.$this->client->client_id.DS.'logos'.DS.$logo->client_logo_filename.'.'.$logo->client_logo_filetype;
			$file	= $logo->client_logo_filename.'.'.$logo->client_logo_filetype;
			$path	= $this->client_dir.DS.$this->client->client_id.DS.'logos'.DS;

		?>
			
			<div class="asset-item-grid" id="asset-<?php echo $logo->client_logo_id; ?>" rel="<?php echo $logo->client_logo_id; ?>" >
				
				<?php if( file_exists($image) ){
				
					echo JHTML::_('image.site', $file, $path, null, null, $logo->client_logo_name, 'width="150" title="'.$logo->client_logo_name.'" rel="'.$logo->client_logo_id.'"' );
					
				} ?>
				
			</div>
			
		<?php } ?>
		
		<div class="clear"><!--   --></div>
		
	</div>
	
	<div class="modal-bottom-fixed">
		
		<div class="modal-bottom-fixed-inner">

			<button class="btn cancel-btn" onclick="location.reload(); return false;" type="button"><span><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
			<button class="btn save-btn" type="submit"><span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>

		</div>
	
	</div>
	
	<input type="hidden" name="content[id]" value="<?php echo $this->content_block->content_block_current_version; ?>" />
	<input type="hidden" name="content[data]" value="<?php echo JRequest::getInt( 'data', 0 ); ?>" id="image_asset_id" />
	
	<input type="hidden" name="content_block_id" value="<?php echo $this->content_block->content_block_id; ?>" />
	<input type="hidden" name="content_block_type" value="<?php echo $this->content_block->content_block_type; ?>" />
	<input type="hidden" name="content_bro_id" value="<?php echo $this->content_block->content_bro_id; ?>" />
	
	<input type="hidden" name="view" value="brochure" />
	
	<input type="hidden" name="bro_id" value="<?php echo $this->content_block->content_bro_id; ?>" />
	<input type="hidden" name="bro_page_id" value="<?php echo $this->content_block->content_page_id; ?>" />
	<input type="hidden" name="bro_page_order" value="<?php echo JRequest::getInt( 'bro_page_order', 0 ); ?>" />
		
</form>

<script type="text/javascript">
	$(document).ready(function(){
		logoThumbnailActions();
	});
</script>