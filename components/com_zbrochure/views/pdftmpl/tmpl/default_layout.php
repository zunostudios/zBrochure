<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<div class="page tmpl-page" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">					
	<div class="cnt-container">
	<?php
	
	foreach( $this->page->content_blocks as $block ){
		
		$styles = '';
				
		foreach( $block['layout']['styles'] as $k=>$v ){
		
			$styles .= $k.':'.$v.';';
		
		}
		
		echo '<div class="layout-block';
		
		if( isset( $block['layout']['class'] ) ){
		
			echo ' '.$block['layout']['class'];
		
		}
		
		echo '" style="'.$styles.'">';
		echo JFilterOutput::ampReplace( $block['content']->data->render );		
		echo '</div>';
		
	}
	?>
	</div>
	
	<div id="svg-<?php echo $this->page->tmpl_layout_id; ?>" class="svg-container" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">
		
		<svg style="overflow-x: hidden; overflow-y: hidden; position: relative; " height="<?php echo $this->page->height.''.$this->page->unit_of_measure;; ?>" version="1.1" width="<?php echo $this->page->width.''.$this->page->unit_of_measure; ?>" viewBox="0 0 <?php echo $this->page->width; ?> <?php echo $this->page->height; ?>" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<?php
		
		foreach( $this->page->image_blocks as $block ){
			
			echo '<g clip-path="url(#imageClipPath_'.$block['content']->content_block_id.')">'.PHP_EOL;
			echo '<clipPath id="imageClipPath_'.$block['content']->content_block_id.'">'.PHP_EOL;
			echo $block['layout'].PHP_EOL;
			echo '</clipPath>'.PHP_EOL;
			echo $block['content']->data->render.PHP_EOL;
			echo '</g>'.PHP_EOL;
			
		}
		
		foreach( $this->page->design_blocks as $block ){
					
			echo preg_replace( '/\s+/', $block['content']->data->render, $block['layout'], 1 ).PHP_EOL;
			
			
		}?>
		</svg>
	
	</div>
			
</div>