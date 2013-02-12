<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

echo $this->loadTemplate('options'); ?>

<div class="page page-with-content tmpl-page" zid="<?php echo $this->page->bro_page_id; ?>" lid="<?php echo $this->page->tmpl_layout_id; ?>" lkey="<?php echo $this->page->tmpl_layout_key; ?>" zpage="<?php echo $this->page->page_number; ?>" id="page-<?php echo $this->page->bro_page_order; ?>" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">
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
		
		echo '" style="'.$styles.'" tbid="'.$block['content']->content_tmpl_block_id.'" bid="'.$block['content']->content_block_id.'" tbd="'.$block['tmpl_block'].'">';
		echo $block['content']->data->render;
		echo $block['content']->data->edit;
		echo '</div>';
		
	}
	
	$show_page_number = ( $this->page->bro_page_show_page_number ) ? 'block' : 'none';
	
?>
	
	<div id="page-number-<?php echo $this->page->bro_page_id; ?>" class="page-number" style="display:<?php echo $show_page_number; ?>;"><?php echo sprintf( $this->tmpl->tmpl_page_number_format, $this->page->page_number ); ?></div>

	<div id="svg-<?php echo $this->page->page_number; ?>" class="svg-container" style="width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">
	
		<svg style="overflow-x: hidden; overflow-y: hidden; position: relative; " height="<?php echo $this->page->height.''.$this->page->unit_of_measure;; ?>" version="1.1" width="<?php echo $this->page->width.''.$this->page->unit_of_measure; ?>" viewBox="0 0 <?php echo $this->page->width; ?> <?php echo $this->page->height; ?>" xmlns="http://www.w3.org/2000/svg">
		<?php
		
		foreach( $this->page->image_blocks as $block ){
			
			echo '<g clip-path="url(#imageClipPath_'.$block['content']->content_block_id.')">'.PHP_EOL;
			echo '<clipPath id="imageClipPath_'.$block['content']->content_block_id.'">'.PHP_EOL;
			echo $block['layout'].PHP_EOL;
			echo '</clipPath>'.PHP_EOL;
			
			$click_area = preg_replace( '/\s+/', ' id="imageClick_'.$block['content']->content_block_id.'" fill="#EFEFEF" ', $block['layout'], 1 );
			
			echo $click_area.PHP_EOL;
			echo $block['content']->data->render.PHP_EOL;
			echo '</g>'.PHP_EOL;
			
		}
		
		foreach( $this->page->design_blocks as $block ){
					
			echo preg_replace( '/\s+/', $block['content']->data->render, $block['layout'], 1 ).PHP_EOL;
			
			
		}
		?>
		</svg>
		
		<?php
		foreach( $this->page->image_blocks as $block ){
					
			echo $block['content']->data->edit;
			
		}
		
		foreach( $this->page->design_blocks as $block ){
					
			echo $block['content']->data->edit;
			
		}
		?>
		
	</div>

</div>