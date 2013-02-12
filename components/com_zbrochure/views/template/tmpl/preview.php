<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');
	
	$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$this->bro->bro_id );
	$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateBrochure&id='.$this->bro->bro_id);
	$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteBrochure&id='.$this->bro->bro_id);
	
?>
<div class="bro-preview">
	
	<div id="preview-left" class="preview-left-col">
		
		<div id="preview-left-img">
			<?php echo JHTML::_( 'image.site', $this->pages[1]->bro_page_thumb, '/media/zbrochure/docs/'.$this->pages[1]->bro_id.'/', '', '', $this->pages[1]->bro_page_title . ' thumbnail', '' ); ?>
		</div>
		
		<div id="preview-thumbs">
		
			<ul class="carousel">
		
			<?php
			//Loop through all the pages
			for( $i = 1; $i <= $this->bro->bro_pages; $i++ ){
				
				if( $i == 1 ){
					
					$class = ' selected';
					
				}else{
					
					$class = '';
					
				}

				
				if( $this->pages[$i] ){
										
					$this->page = $this->pages[$i];?>
					
					<li class="tmpl-layout-th preview-page" id="bro-page-th-<?php echo $this->page->bro_page_id; ?>" rel="<?php echo JURI::base().'media/zbrochure/docs/'.$this->bro->bro_id.'/'.$this->page->bro_page_thumb; ?>"><?php echo JHTML::_( 'image.site', $this->page->bro_page_thumb, '/media/zbrochure/docs/'.$this->page->bro_id.'/', '', '', $this->page->bro_title . ' thumbnail', 'class="'.$class.'" width="90" height="116"' ); ?></li>
					
		
					<?php
					
				}else{
				
					$this->blank_page->page_number = $i; ?>
					
					<li class="tmpl-layout-th preview-page" rel="<?php echo JURI::base().'media/zbrochure/images/tmpl/no-tmpl-img.png'; ?>"><?php echo JHTML::_( 'image.site', 'no-tmpl-img.png', '/media/zbrochure/images/tmpl/', '', '', 'Not set', 'class="'.$class.'" width="90"' ); ?></li>
				
				<?php }
			
			}
			
			?>
			</ul>
		
		</div>

	</div>
	
	<div id="preview-right" class="preview-right-col">
		
		<ul>
			<li><?php echo '<h2>'.$this->bro->bro_title.'</h2>'; ?></li>
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CLIENT' ).'</strong> '.$this->bro->client_version_name; ?></li>
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_TEAM' ).'</strong> '.$this->bro->team_name; ?></li>
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED' ).'</strong> '.JHtml::_('date', $this->bro->bro_created, JText::_('DATE_FORMAT_LC3')); ?></li>
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED_BY' ).'</strong> <a href="mailto:'.$this->bro->email.'" title="'.$this->bro->name.'">'.$this->bro->username.'</a>'; ?></li>
			
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_MODIFIED' ).'</strong> '.JHtml::_('date', $this->bro->bro_modified, JText::_('DATE_FORMAT_LC3')); ?></li>
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_MODIFIED_BY' ).'</strong> <a href="mailto:'.$this->bro->modified_email.'" title="'.$this->bro->modified_name.'">'.$this->bro->modified_username.'</a>'; ?></li>
			
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_TMPL' ).'</strong> '.$this->tmpl->tmpl_name; ?></li>
			<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_PAGES' ).'</strong> '.$this->bro->bro_pages; ?></li>
			
			<li class="bro-action" style="margin:50px 0 5px 0">
				<a href="<?php echo $edit_link; ?>" target="_parent" title="Edit <?php echo $brochure->bro_title; ?>" class="edit-btn icon-btn btn">
					<span class="icon"><!-- --></span>
					<span class="btn-text"><?php echo JText::_( 'EDIT_BROCHURE' );?></span>
				</a>
			</li>
			
			<li class="bro-action">
				<a href="<?php echo $duplicate_link; ?>" target="_parent" title="Duplicate <?php echo $brochure->bro_title; ?>" class="duplicate-btn icon-btn btn">
					<span class="icon"><!-- --></span>
					<span class="btn-text"><?php echo JText::_( 'DUPLICATE_BROCHURE' );?></span>
				</a>
			</li>
			
			<li class="bro-action">
				<a href="javascript:void(0);" title="Delete <?php echo $brochure->bro_title; ?>" id="delete-btn" class="delete-btn icon-btn btn">
					<span class="icon"><!-- --></span>
					<span class="btn-text"><?php echo JText::_( 'DELETE_BROCHURE' );?></span>
				</a>
			</li>
		
		</ul>
		
	</div>
	
</div>

<div id="delete-dialog" title="<?php echo JText::_('DELETE_BROCHURE_TITLE'); ?>">
	
	<div class="form-row add-padding package-container-header">
	
		<?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?>
	
	</div>
	
	<a href="<?php echo $delete_link; ?>" target="_parent" title="Delete <?php echo $brochure->bro_title; ?>" class="delete-btn icon-btn btn" style="margin:10px auto;display:block;width:175px">
		<span class="icon"><!-- --></span>
		<span class="btn-text"><?php echo JText::_( 'YES_DELETE_BROCHURE' );?></span>
	</a>
	
</div>

<script type="text/javascript">

$(document).ready(function(){

	//Create the carousel for the page layout thumbnails
	$('#preview-thumbs').carousel( { dispItems:3 } );
	
	$('.preview-page').each(function(){
		
		$(this).click(function(){
		
			url = $(this).attr('rel');
			
			$('#preview-left-img').empty();
					
			$(document.createElement('img'))
				.hide()
			    .attr({ src: url, alt: 'Page preview' })
			    .appendTo( $('#preview-left-img') )
			    .fadeIn(500);
			
			$( '.preview-page img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
		});
		
	});
	
	var delete_dialog = $('#delete-dialog').dialog({

		autoOpen:false,
		width:260,
		height: 'auto',
		resizable:false,
		modal: true
	
	});
	
	$('#delete-btn').click(function(){
		
		delete_dialog.dialog( 'open' );
		
	});

});

</script>

