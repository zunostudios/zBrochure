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

?>
<div class="page tmpl-page" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">					
	<div class="cnt-container">
	<?php foreach( $this->page->blocks as $block ){
		
		if( $block->content_type_output_format == 'html' ){
						
			if( isset( $content_block_styles[$block->content_tmpl_block_id]['styles'] ) && $content_block_styles[$block->content_tmpl_block_id]['tmpl_block_type'] == $block->content_block_type ){
				
				$styles = '';
				
				foreach( $content_block_styles[$block->content_tmpl_block_id]['styles'] as $k=>$v ){
				
					$styles .= $k.':'.$v.';';
				
				}
				
				echo '<div class="layout-block';
				
				if( isset( $content_block_styles[$block->content_tmpl_block_id]['class'] ) ){
				
					echo ' '.$content_block_styles[$block->content_tmpl_block_id]['class'];
				
				}
				
				echo '" style="'.$styles.'">';
				echo $block->data->render;
				echo '</div>';
						
			}
			
		}
	
	}?>
	</div>
	
	<div id="svg-<?php echo $this->page->tmpl_layout_id; ?>" class="svg-container" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">
		
		<svg style="overflow-x: hidden; overflow-y: hidden; position: relative; " height="<?php echo $this->page->height.''.$this->page->unit_of_measure;; ?>" version="1.1" width="<?php echo $this->page->width.''.$this->page->unit_of_measure; ?>" viewBox="0 0 <?php echo $this->page->width; ?> <?php echo $this->page->height; ?>" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<?php
			
			foreach( $this->page->blocks as $block ){
		
				if( $block->content_type_output_format == 'svg' && ( $block->content_type_folder == 'image' || $block->content_type_folder == 'logo' ) ){
					
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
	
	</div>
			
</div>