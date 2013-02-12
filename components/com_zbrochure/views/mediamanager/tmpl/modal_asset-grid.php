<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

foreach( $this->assets as $data ){ $image = $this->assets_dir.'thumbnails/'.$data->asset_file;?>
<div class="asset-item-grid" id="asset-<?php echo $data->assetid; ?>" rel="<?php echo $data->assetid; ?>" title="<?php echo ($data->asset_title) ? $data->asset_title : 'No Title'; ?>::<?php echo $data->asset_file; ?>::<?php echo $data->created; ?>">

	<?php if (file_exists($image)) { ?>
	
		<?php echo JHTML::_('image.site', $data->asset_file, $this->assets_dir.'thumbnails/', null, null, $data->asset_title, 'height="100" title="'.$data->asset_title.'"' );?>
	
		<div id="keywords-<?php echo $data->assetid; ?>" class="keywords" style="display:none">
		<?php foreach( $this->keywords as $keywords ){ ?>
			<?php if( $keywords->aid == $data->assetid ){ ?>
				<span><?php echo $keywords->keyword; ?></span>
			<?php } ?>
		<?php } ?>
		</div>

	
	<?php }else{ ?>
	
		<?php echo JHTML::_('image.site', 'no-thumb.png', $this->assets_dir.'thumbnails/', null, null, $data->asset_title, 'height="100" title="'.$data->asset_title.'"' );?>
		
		<div id="keywords-<?php echo $data->assetid; ?>" class="keywords" style="display:none">
		<?php foreach( $this->keywords as $keywords ){ ?>
			<?php if( $keywords->aid == $data->assetid ){ ?>
				<span><?php echo $keywords->keyword; ?></span>
			<?php } ?>
		<?php } ?>
		</div>
	
	<?php } ?>
		
</div>
<?php } ?>