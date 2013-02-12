<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<?php //print_r( $pages ); ?>

<div id="tmpl-layout-desc" class="tmpl-layout-desc">

	<div id="tmpl-main-preview">
		<?php echo JHTML::_( 'image.site', $pages[0]->tmpl_layout_thumbnail, '/media/zbrochure/tmpl/'.$pages[0]->tmpl_id.'/th/', '', '', 'Page layout preview', '' ); ?>
	</div>
		
	<div class="tmpl-layout-desc-inner"></div>

</div>


<div id="tmpl-layouts">
	
	<ul class="carousel">
	<?php foreach( $pages as $page ){ ?>
		<li class="tmpl-layout-th tmpl-layout" id="tmpl-layout-th-<?php echo $page->tmpl_layout_id; ?>" des="<?php echo $page->tmpl_layout_name.'::'.$page->tmpl_layout_details.'::'.$page->tmpl_layout_desc; ?>" rel="<?php echo JURI::base().'media/zbrochure/tmpl/'.$page->tmpl_id.'/th/'.$page->tmpl_layout_thumbnail; ?>"><?php echo JHTML::_( 'image.site', $page->tmpl_layout_thumbnail, '/media/zbrochure/tmpl/'.$page->tmpl_id.'/th/', '', '', $page->tmpl_layout_name . ' thumbnail', 'width="100"' ); ?></li>
	<?php } ?>
	</ul>
	
</div>

<div id="tmpl-layout-covers">
	<ul class="carousel">
	<?php foreach( $pages as $cover ){
	
	if( $cover->tmpl_layout_type == 1 ){?>
	
		<li class="tmpl-layout-cover-th tmpl-layout" id="tmpl-layout-cover-th-<?php echo $cover->tmpl_layout_key; ?>">
			<?php echo JHTML::_( 'image.site', $cover->tmpl_layout_thumbnail, '/media/zbrochure/images/tmpl/'.$cover->tmpl_id.'/th/', '', '', $cover->tmpl_layout_name . ' thumbnail', 'width="100"' ); ?><p><?php echo $cover->tmpl_layout_name; ?></p>
		</li>
	
	<?php }
	} ?>
	</ul>
</div>

<script type="text/javascript">
	
	initial_desc = $( '#tmpl-layout-th-<?php echo $pages[0]->tmpl_layout_id; ?>' ).attr( 'des' ).split('::');
	
	$('#tmpl-layout-desc div.tmpl-layout-desc-inner').replaceWith('<div class="tmpl-layout-desc-inner"><strong>'+initial_desc[0]+'</strong><br /><span>'+initial_desc[1]+'</span><p>'+initial_desc[2]+'</p></div>');
	
	initial_active = $( '#tmpl-layout-th-<?php echo $pages[0]->tmpl_layout_id; ?>' ).find( 'img' );
	initial_active.addClass( 'selected' );
	
	//Create the carousel for the page layout thumbnails
	$('#tmpl-layouts').carousel( { dispItems:4 } );
	
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
			
			
			$( '#tmpl-layouts li.tmpl-layout img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
			var desc = $(this).attr( 'des' ).split('::');
			
			$('#tmpl-layout-desc div.tmpl-layout-desc-inner').replaceWith('<div class="tmpl-layout-desc-inner"><strong>'+desc[0]+'</strong><br /><span>'+desc[1]+'</span><p>'+desc[2]+'</p></div>');
			
			
		});
	
	});
	
	$( '#tmpl-layout-covers-holder' ).empty();
	$( '#tmpl-layout-covers-holder' ).append( $( '#tmpl-layout-covers' ) );
	
	//Create the carousel for the page layout thumbnails
	$('#tmpl-layout-covers').carousel( { dispItems:1 } );
	
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