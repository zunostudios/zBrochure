<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="template edit-template">

	<?php
	echo $this->loadTemplate('styles');
	echo $this->loadTemplate('details');
	?>
	
	<div class="page page-with-content tmpl-page<?php //echo ( $this->spread ) ? ' spread-'.$this->spread_side : '' ; ?>" zid="<?php echo $this->layout->tmpl_layout_key; ?>" lid="<?php echo $this->layout->tmpl_layout_id; ?>" lkey="<?php echo $this->layout->tmpl_layout_key; ?>" zpage="<?php echo $this->layout->tmpl_layout_order; ?>" id="page-<?php echo $this->layout->tmpl_layout_order; ?>" style="width:<?php echo $this->layout->tmpl_page_width.''.$this->layout->tmpl_unit_of_measure;?>;height:<?php echo $this->layout->tmpl_page_height.''.$this->layout->tmpl_unit_of_measure;?>">
	<?php
		
		foreach( $this->layout->content_blocks as $block ){
			
			$styles = '';
					
			foreach( $block['layout']['styles'] as $k=>$v ){
			
				$styles .= $k.':'.$v.';';
			
			}
			
			echo '<div class="layout-block';
			
			if( isset( $block['layout']['class'] ) ){
			
				echo ' '.$block['layout']['class'];
			
			}
			
			echo '" style="'.$styles.'">';
			echo $block['content']->data->render;		
			echo '</div>';
			
		}
	?>
	
		<div id="svg-<?php echo $this->layout->tmpl_layout_order; ?>" class="svg-container" style="width:<?php echo $this->layout->tmpl_page_width.''.$this->layout->tmpl_unit_of_measure;?>;height:<?php echo $this->layout->tmpl_page_height.''.$this->layout->tmpl_unit_of_measure;?>">
		
			<svg style="overflow-x: hidden; overflow-y: hidden; position: relative; " height="<?php echo $this->layout->tmpl_page_height.''.$this->layout->tmpl_unit_of_measure;; ?>" version="1.1" width="<?php echo $this->layout->tmpl_page_width.''.$this->layout->tmpl_unit_of_measure; ?>" viewBox="0 0 <?php echo $this->layout->tmpl_page_width; ?> <?php echo $this->layout->tmpl_page_height; ?>" xmlns="http://www.w3.org/2000/svg">
				<?php
			
			foreach( $this->layout->image_blocks as $block ){
				
				echo '<g clip-path="url(#imageClipPath_'.$block['content']->content_block_id.')">'.PHP_EOL;
				echo '<clipPath id="imageClipPath_'.$block['content']->content_block_id.'">'.PHP_EOL;
				echo $block['layout'].PHP_EOL;
				echo '</clipPath>'.PHP_EOL;
				
				$click_area = preg_replace( '/\s+/', ' id="imageClick_'.$block['content']->content_block_id.'" fill="#EFEFEF" ', $block['layout'], 1 );
				
				echo $click_area.PHP_EOL;
				echo $block['content']->data->render.PHP_EOL;
		
				echo '</g>'.PHP_EOL;
				
			}
			
			foreach( $this->layout->design_blocks as $block ){
						
				echo preg_replace( '/\s+/', $block['content']->data->render, $block['layout'], 1 ).PHP_EOL;
				
				
			}
			?>
			</svg>
		
		</div>
	
	</div>
	<?php echo $this->loadTemplate('scripts'); ?>

</div>