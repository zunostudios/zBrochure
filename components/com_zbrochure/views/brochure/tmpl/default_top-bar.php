<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure' ); ?>" name="bro-settings" id="bro-settings" method="post">

	<div id="brochure-top-bar" class="page-thumbnails sidebar">
		
		<div id="top-bar-nav-wrapper">
			
			<ul id="top-bar-nav">
				
				<li>
					<a href="javascript:void(0);" onclick="toggleNav(this, 'bro-details');" class="toggleNav">Brochure Details</a>
								
					<div id="bro-details" class="bro-details jq-hide">
					
						<div class="bro-details-inner">
							<ul>

								<li>
									<label for="bro_title"><?php echo JText::_( 'DETAILS_BROCHURE_TITLE' ); ?></label>
									<input type="text" class="inputbox" name="bro_title" id="bro_title" value="<?php echo $this->bro->bro_title; ?>" style="width:94%" />
								</li>
								
								<li>
									<label for="team_id"><?php echo JText::_( 'DETAILS_BROCHURE_TEAM' ); ?></label>
									<?php echo $this->teams; ?>
								</li>
								
								<li>
									<label for="bro_client"><?php echo JText::_( 'DETAILS_BROCHURE_CLIENT' ); ?></label>
									<?php echo $this->clients; ?>
								</li>
						
								<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED' ).'</strong> '.JHtml::_('date', $this->bro->bro_created, JText::_('DATE_FORMAT_LC3')); ?></li>
								<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED_BY' ).'</strong> <a href="mailto:'.$this->bro->email.'">'.$this->bro->username.'</a>'; ?></li>
								<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_TMPL' ).'</strong> '.$this->tmpl->tmpl_name; ?></li>

							</ul>

						</div>
					
					</div>
					
				</li>
				
				<li>
				
					<a href="javascript:void(0);" onclick="toggleNav(this, 'bro-thumbs');" class="toggleNav"><?php echo JText::_( 'THUMBNAILS_TITLE' ); ?></a>
	
					<div id="bro-thumbs" class="jq-hide">
					
						<div class="inner">
						
							<ul class="thumbnail-list" id="sortable" style="width:<?php echo (count($this->pages) * 100);?>px">
						
							<?php
							
							$i = 1;
							
							foreach( $this->pages as $page ){
									
								$toc_txt	= ($page->bro_page_name) ? $page->bro_page_name : JText::_( 'NO_PAGE_TITLE' );
								$toc[$i] 	= '<li><a href="javascript:void(0)" onclick="$.scrollTo( $(\'#bro-page-'.$i.'\'), 1000 )">'.$toc_txt.'</a></li>';
								
								$th_img		= ($page->bro_page_thumb) ? JURI::base().'media/zbrochure/docs/'.$this->bro->bro_id.'/th/'.$page->bro_page_thumb.'?v='.uniqid(rand()) : JURI::base().'media/zbrochure/images/tmpl/no-tmpl-img.png';
								
							?>
								
								<li class="tmpl-page-th" pid="<?php echo $i; ?>" id="tmpl-page-<?php echo $page->bro_page_id; ?>" zid="<?php echo $page->bro_page_id; ?>" style="background-image:url(<?php echo $th_img; ?>)" title="<?php echo $page->bro_page_id; ?>">
								<?php echo $page->bro_page_id; ?>
								
								<input type="hidden" name="ordering[<?php echo $page->bro_page_id; ?>]" value="<?php echo $page->bro_page_id; ?>" />
								
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
					
				<li>
				
					<a href="javascript:void(0);" onclick="toggleNav(this, 'bro-templates');" class="toggleNav"><?php echo JText::_( 'BROCHURE_TEMPLATE' ); ?></a>
	
					<div id="bro-templates" class="jq-hide">
						
						<!-- Start Templates -->
						<div id="templates" class="bro-details-inner">
						
							<ul class="carousel">
								
							<?php foreach( $this->tmpls as $tmpl ){ 
							
								if( $tmpl->tmpl_id == $this->bro->bro_tmpl ){
								
									$class = 'selected';
								
								}else{
								
									$class = '';
								
								}
							
							?>
								
								<li class="tmpl-layout-th tmpl-layout" id="tmpl-layout-th-<?php echo $tmpl->tmpl_id; ?>" zid="<?php echo $tmpl->tmpl_id; ?>" rel="<?php echo JURI::base().'media/zbrochure/images/tmpl/'.$tmpl->tmpl_id.'/pv/'.$tmpl->tmpl_preview; ?>"><?php echo JHTML::_( 'image.site', $tmpl->tmpl_thumbnail, '/media/zbrochure/images/tmpl/'.$tmpl->tmpl_id.'/', '', '', $tmpl->tmpl_name . ' thumbnail', 'class="'.$class.'"' ); ?></li>
							
							<?php } ?>
							
							</ul>
						
						</div>
						<!-- End Templates -->
					
					</div>
				
				</li>
				
				<li>
				
					<a href="javascript:void(0);" onclick="toggleNav(this, 'bro-themes');" class="toggleNav"><?php echo JText::_( 'BROCHURE_THEME' ); ?></a>
	
					<div id="bro-themes" class="jq-hide">
					
						<div class="bro-details-inner">
						
							<h4><?php echo JText::_('THEMES_CLIENT'); ?></h4>
							
							<div id="client-themes">
								
								<?php echo $this->client_themes; ?>
								
							</div>
							
							<h4><?php echo JText::_('THEMES_GENERAL'); ?></h4>
							
							<div id="themes">
							
								<?php echo $this->themes; ?>
								
							</div>
							
						</div>
						
					</div>
				
				</li>			
				
				<li class="clear"><!--   --></li>
			
			</ul>
			
			<div class="btn-container" style="float:right;padding:6px">
			
				<button type="button" class="btn build-bro-btn" id="generate-btn"><span class="btn-text"><?php echo JText::_( 'GENERATE_PDF_BTN_GENERAL' ); ?></span></button>
				<button type="submit" class="btn save-btn icon-btn" id="save-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			
			</div>
			
		</div>
		
		<div class="clear"><!--   --></div>
	
	</div>
	
	<input type="hidden" name="bro_id" id="bro_id" value="<?php echo $this->bro->bro_id; ?>" />
	<!-- <input type="hidden" name="bro_client" id="bro_client" value="<?php echo $this->bro->bro_client; ?>" /> -->
	<input type="hidden" name="bro_tmpl" id="bro_tmpl" value="<?php echo $this->bro->bro_tmpl; ?>" />
	<input type="hidden" name="bro_theme" id="bro_theme" value="<?php echo $this->bro->bro_theme; ?>" />
	<!-- <input type="hidden" name="bro_modified_by" id="bro_modified_by" value="<?php echo $this->user->get('id'); ?>" /> -->
	<input type="hidden" name="bro_current_version" id="bro_current_version" value="<?php echo $this->bro->bro_current_version; ?>" />
	<input type="hidden" name="task" id="task" value="saveBrochure" />

</form>