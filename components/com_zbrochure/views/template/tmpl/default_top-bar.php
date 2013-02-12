<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure' ); ?>" name="tmpl-settings" id="tmpl-settings" method="post">

	<div id="brochure-top-bar" class="page-thumbnails sidebar">
		
		<div id="top-bar-nav-wrapper">
			<ul id="top-bar-nav">
				<li>
					<a href="javascript:void(0);" onclick="toggleNav(this, 'bro-details');" class="toggleNav"><?php echo JText::_( 'TMPL_DETAILS_TITLE' ); ?></a>
								
					<div id="bro-details" class="bro-details jq-hide" style="width:600px">
					
						<div class="bro-details-inner">
								
							<div class="layout-details-left">
								
								<div class="form-row">
									
									<label for="tmpl_name"><?php echo JText::_( 'DETAILS_TMPL_TITLE' ); ?></label>
									<input id="tmpl_name" name="tmpl_name" class="inputbox" value="<?php echo $this->tmpl->tmpl_name; ?>" />
									
								</div>
								
								<div class="form-row">
									
									<label for="tmpl_published"><?php echo JText::_( 'DETAILS_TMPL_PUBLISHED' ); ?></label>
									<?php echo $this->published; ?>
									
								</div>
								
								<div class="form-row">
									
									<label for="tmpl_page_width"><?php echo JText::_( 'DETAILS_TMPL_PAGE_WIDTH' ); ?></label>
									<input id="tmpl_page_width" name="tmpl_page_width" class="inputbox" value="<?php echo $this->tmpl->tmpl_page_width; ?>" />
									
								</div>
								
								<div class="form-row">
									
									<label for="tmpl_page_height"><?php echo JText::_( 'DETAILS_TMPL_PAGE_HEIGHT' ); ?></label>
									<input id="tmpl_page_height" name="tmpl_page_height" class="inputbox" value="<?php echo $this->tmpl->tmpl_page_height; ?>" />
									
								</div>
								
								<div class="form-row">
									
									<label for="tmpl_unit_of_measure"><?php echo JText::_( 'DETAILS_TMPL_UNIT_MEASURE' ); ?></label>
									<?php echo $this->units; ?>
									
								</div>
								
								<div class="form-row">
									
									<label for="tmpl_page_number_format"><?php echo JText::_( 'DETAILS_TMPL_PAGE_HEIGHT' ); ?></label>
									<input id="tmpl_page_number_format" name="tmpl_page_number_format" class="inputbox" value="<?php echo $this->tmpl->tmpl_page_number_format; ?>" />
									
								</div>
																
							</div>
							
							<div class="layout-details-right preview-page">
								
								<?php echo JHTML::_( 'image.site', $this->tmpl->tmpl_thumbnail, '/media/zbrochure/images/tmpl/'.$this->tmpl->tmpl_id.'/', '', '', $this->tmpl->tmpl_name . ' thumbnail', 'style="margin:0 0 20px 0"' );?>
							
								<div class="form-row">
									
									<label><?php echo JText::_('DETAILS_TMPL_THEME'); ?></label>							
									<div id="themes" style="width:176px">
										<?php echo $this->themes; ?>
									</div>
									
								</div>
								
								<ul>

									<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED' ).'</strong> '.JHtml::_('date', $this->tmpl->tmpl_created, JText::_('DATE_FORMAT_LC3')); ?></li>
									<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED_BY' ).'</strong> <a href="mailto:'.$this->tmpl->email.'">'.$this->tmpl->username.'</a>'; ?></li>
									
								</ul>
								
							</div>
							
							<div class="clear"><!-- --></div>
															
						</div>
					
					</div>
			
				</li>
				
				<li>
					
					<a href="javascript:void(0);" onclick="toggleNav(this, 'tmpl-styles');" class="toggleNav"><?php echo JText::_( 'TMPL_STYLES_TITLE' ); ?></a>
								
					<div id="tmpl-styles" class="jq-hide">
						
						<div class="inner">
						
							<div class="form-row" style="padding:0 20px">
									
								<label for="tmpl_default_styles"><?php echo JText::_( 'DETAILS_TMPL_DEFAULT_STYLES' ); ?></label>
								<textarea id="tmpl_default_styles" name="tmpl_default_styles" class="inputbox" style="width:98%"><?php echo $this->tmpl->tmpl_default_styles; ?></textarea>
								
							</div>
							
							<div class="form-row" style="padding:0 20px">
								
								<label for="tmpl_editor_styles"><?php echo JText::_( 'DETAILS_TMPL_EDITOR_STYLES' ); ?></label>
								<textarea id="tmpl_editor_styles" name="tmpl_editor_styles" class="inputbox" style="width:98%"><?php echo $this->tmpl->tmpl_editor_styles; ?></textarea>
								
							</div>
						
						</div>
						
						<p>&nbsp;</p>
						
					</div>
					
				</li>
				
				<li>
				
					<a href="javascript:void(0);" onclick="toggleNav(this, 'bro-thumbs');" class="toggleNav"><?php echo JText::_( 'LAYOUT_THUMBNAILS_TITLE' ); ?></a>
	
					<div id="bro-thumbs" class="jq-hide">
					
						<div class="inner">
						
							<ul class="thumbnail-list" id="sortable" style="width:<?php echo (count($this->pages) * 100);?>px">
						
							<?php
							
							$i = 1;
							
							foreach( $this->pages as $page ){
									
								$toc_txt	= ($page->tmpl_layout_name) ? $page->tmpl_layout_name : JText::_( 'NO_PAGE_TITLE' );
								$toc[$i] 	= '<li><a href="javascript:void(0)" onclick="$.scrollTo( $(\'#bro-page-'.$i.'\'), 1000 )">'.$toc_txt.'</a></li>';
								
								$th_img		= ($page->tmpl_layout_thumbnail) ? JURI::base().'media/zbrochure/tmpl/'.$this->tmpl->tmpl_id.'/th/'.$page->tmpl_layout_thumbnail : JURI::base().'media/zbrochure/images/tmpl/no-tmpl-img.png';
								
							?>
								
								<li class="tmpl-page-th" pid="<?php echo $i; ?>" id="tmpl-page-<?php echo $page->tmpl_layout_id; ?>" zid="<?php echo $page->tmpl_layout_id; ?>" style="background-image:url(<?php echo $th_img; ?>)" title="<?php echo $page->tmpl_layout_name; ?>">
								<?php echo $page->tmpl_layout_id; ?>
								
								<input type="hidden" name="ordering[<?php echo $page->tmpl_layout_id; ?>]" value="<?php echo $page->tmpl_layout_id; ?>" />
								
								</li>
													
							<?php 
							
							$i++;
							
							} ?>
							</ul>
							
							<div class="clear"><!--   --></div>
							
						</div>
	
						<div class="btn-container form-row" style="margin:10px 10px 10px 0">				
							<button type="button" class="btn add-btn icon-btn" title="<?php echo JText::_( 'ADD_PAGE' ); ?>" id="add-page-btn" style="margin:0 auto;display:block">
								<span class="icon"><!-- --></span>
								<span class="btn-text"><?php echo JText::_( 'ADD_PAGE' ); ?></span>
							</button>
						</div>
						
					</div>
					
				</li>
										
				<li class="clear"><!--   --></li>
				
			</ul>
			
			<div class="btn-container" style="float:right;padding:6px">
				<button type="button" class="btn build-bro-btn" id="generate-btn"><span>Generate</span></button>
				<button type="submit" class="btn save-btn" id="save-btn"><span><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			</div>
			
		</div>
		
		<div class="clear"><!--   --></div>
		
	</div>

	<input type="hidden" name="tmpl_id" id="tmpl_id" value="<?php echo $this->tmpl->tmpl_id; ?>" />
	<input type="hidden" name="view" id="view" value="template" />
	<input type="hidden" name="task" id="task" value="saveTemplate" />
	<input type="hidden" name="tmpl_default_theme" id="tmpl_default_theme" value="<?php echo $this->tmpl->tmpl_default_theme; ?>" />
	<input type="hidden" name="tmpl_version" value="<?php echo $this->tmpl->tmpl_version; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
	
</form>