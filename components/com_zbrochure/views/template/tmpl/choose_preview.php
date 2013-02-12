<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<?php //print_r( $pages ); ?>

<div id="tmpl-main-preview">
	<?php echo JHTML::_( 'image.site', $pages[0]->tmpl_layout_preview, '/media/zbrochure/images/tmpl/'.$pages[0]->tmpl_id.'/pv/', '', '', 'Page layout preview', '' ); ?>
</div>

<div id="tmpl-layouts">
	
	<ul class="carousel">
	<?php foreach( $pages as $page ){ ?>

		<li class="tmpl-layout-th tmpl-layout" id="tmpl-layout-th-<?php echo $page->tmpl_layout_id; ?>" rel="<?php echo JURI::base().'media/zbrochure/images/tmpl/'.$page->tmpl_id.'/pv/'.$page->tmpl_layout_preview; ?>"><?php echo JHTML::_( 'image.site', $page->tmpl_layout_thumbnail, '/media/zbrochure/images/tmpl/'.$page->tmpl_id.'/th/', '', '', $page->tmpl_layout_name . ' thumbnail', 'width="100"' ); ?></li>
	
	<?php } ?>
	</ul>
	
</div>

<strong><?php echo JText::_('TEMPLATE_COVER_PAGES'); ?></strong>

<div id="tmpl-layout-covers">

	<ul class="carousel">
	<?php foreach( $pages as $cover ){
	
	if( $cover->tmpl_layout_type == 1 ){?>
	
		<li class="tmpl-layout-cover-th tmpl-layout" id="tmpl-layout-cover-th-<?php echo $cover->tmpl_layout_id; ?>">
			<div class="tmpl-active-icon"><!-- --></div>
			<?php echo JHTML::_( 'image.site', $cover->tmpl_layout_thumbnail, '/media/zbrochure/images/tmpl/'.$cover->tmpl_id.'/th/', '', '', $cover->tmpl_layout_name . ' thumbnail', 'width="100"' ); ?><br /><?php echo $cover->tmpl_layout_name; ?>
		</li>
	
	<?php } } ?>
	</ul>

</div>

<script type="text/javascript">

	//Create the carousel for the page layout thumbnails
	$('#tmpl-layouts').carousel( { dispItems:3 } );
	
	//Add click event to page layout thumbnails to change large preview image
	$('.tmpl-layout-th').each(function(){
	
		$(this).click(function(){
		
			url = $(this).attr('rel');
			
			$('#tmpl-main-preview').empty();
					
			$(document.createElement('img'))
				.hide()
			    .attr({ src: url, alt: 'Page layout preview' })
			    .appendTo( $('#tmpl-main-preview') )
			    .fadeIn(500);
			
		});
	
	});
	
	//Create the carousel for the page layout thumbnails
	$('#tmpl-layout-covers').carousel( { dispItems:3 } );
	
	//Add click event to cover thumbnails to set hidden input for cover in POST
	$('.tmpl-layout-cover-th').each(function(){
	
		$(this).click(function(){
			
			$( '.tmpl-layout-cover-th img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
			id = $(this).attr('id').split('-').reverse()[0];
			$('#bro_cover').val(id);
			
		});
		
	});

</script>