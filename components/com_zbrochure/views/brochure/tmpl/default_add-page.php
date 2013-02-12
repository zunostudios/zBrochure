<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="add-page-modal" title="Add a Page">

	<form action="index.php?option=com_zbrochure&task=addPage" method="post">
	
		<div class="tmpl-layouts-modal">
		
			<div id="tmpl-main-preview-add">
					
			</div>
		
			<div id="tmpl-layouts-add">
	
				<ul class="carousel">
				<?php 
					$c = 1;
						
					foreach( $this->tmpl_layouts as $page ){ ?>
			
					<li class="tmpl-layout-th tmpl-layout" id="tmpl-layout-th-<?php echo $page->tmpl_layout_id; ?>" des="<?php echo $page->tmpl_layout_name.'::'.$page->tmpl_layout_details.'::'.$page->tmpl_layout_desc; ?>" kid="<?php echo $page->tmpl_layout_key; ?>" rel="<?php echo JURI::base().'media/zbrochure/tmpl/'.$page->tmpl_id.'/th/'.$page->tmpl_layout_thumbnail; ?>"><?php echo JHTML::_( 'image.site', $page->tmpl_layout_thumbnail, '/media/zbrochure/images/tmpl/'.$page->tmpl_id.'/th/', '', '', $page->tmpl_layout_name . ' thumbnail', 'height="100"' ); ?>
					</li>
				
				<?php } ?>
				</ul>
				
			</div>

		</div>
		
		<span class="page-desc-title"><?php echo JText::_( 'PAGE_DESCRIPTION' ); ?></span>
		
		<div id="tmpl-layout-add-desc" class="tmpl-layout-desc"><div class="tmpl-layout-desc-inner"></div></div>
		
		<div class="form-row btn-container add-padding" style="margin:10px 0">
			<button class="btn save-btn fr" type="submit"><span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			<button class="btn cancel-btn fr" onclick="$( '#add-page-modal' ).dialog('close'); return false;"><span><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
		</div>
		
		<input type="hidden" name="bro_id" value="<?php echo $this->bro->bro_id; ?>" />
		<input type="hidden" name="bro_pages" value="<?php echo count( $this->pages ); ?>" />
		<input type="hidden" name="bro_page_layout" id="bro_page_new_layout" value="" />
		<input type="hidden" name="tmpl_id" value="<?php echo $this->tmpl->tmpl_id; ?>" />
		
	</form>
	
</div>