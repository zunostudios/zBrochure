<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

$content_block_styles	= json_decode($this->page->tmpl_layout_version_content, true);
$content_block_images	= json_decode($this->page->tmpl_layout_version_images, true);
$content_block_svg		= json_decode($this->page->tmpl_layout_version_design, true);

echo '<?xml version="1.0" standalone="no"?>';
echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
?>
<svg style="overflow-x: hidden; overflow-y: hidden; position: relative; " height="<?php echo $this->page->height.''.$this->page->unit_of_measure;; ?>" version="1.1" width="<?php echo $this->page->width.''.$this->page->unit_of_measure; ?>" viewBox="0 0 <?php echo $this->page->width; ?> <?php echo $this->page->height; ?>" xmlns="http://www.w3.org/2000/svg">
<?php		
foreach( $this->page->blocks as $block ){

	if( $block->content_type_output_format == 'svg' && $block->content_type_folder == 'image' ){
		
		if( $content_block_images[$block->content_tmpl_block_id] ){
			
			echo '<g clip-path="url(#imageClipPath_'.$block->content_block_id.')">'.PHP_EOL;
			echo '<clipPath id="imageClipPath_'.$block->content_block_id.'">'.PHP_EOL;
			echo $content_block_images[$block->content_tmpl_block_id].PHP_EOL;
			echo '</clipPath>'.PHP_EOL;
			echo $block->data;
			echo '</g>'.PHP_EOL;
		
		}
		
	}

}

if( $content_block_svg ){

	foreach( $content_block_svg as $svg ){
	
		echo $svg;
	
	}
	
}
?>
</svg>