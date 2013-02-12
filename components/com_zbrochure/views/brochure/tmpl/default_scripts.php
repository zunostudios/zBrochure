<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">

var ordering = new Array();

$(document).ready(function(){

	change_dialog = $( '<div style="display:none" class="loading" title="<?php echo JText::_( 'MEDIA_MANAGER' ); ?>"><iframe id="mediamanager-modal" width="100%" height="100%" frameBorder="0" src="#">No iframe support</iframe></div>').appendTo('body');
			
	change_dialog.dialog({
		autoOpen:false,
		width: 1000,
		height: 600,
		modal: true,
		resizable: false,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#mediamanager-modal').attr( 'src', '' );
		}				
	});
							
	change_logo_dialog = $( '<div style="display:none" class="loading" title="<?php echo JText::_( 'LOGO_MANAGER' ); ?>"><iframe id="logomanager-modal" width="100%" height="100%" frameBorder="0" src="#">No iframe support</iframe></div>').appendTo('body');
			
	change_logo_dialog.dialog({

		autoOpen:false,
		width: 960,
		height: 400,
		modal: true,
		resizable: false,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#logomanager-modal').attr( 'src', '' );
		}	
	});
	
	//hide admin stuff
	$('.jq-hide').hide();
	
	//Create the carousel for the page layout thumbnails
	$('#templates').carousel( { dispItems:1 } );
	
	//Create the carousel for the page layout thumbnails
	$('#themes').carousel( { dispItems:1 } );
	
	//Create the carousel for the page layout thumbnails
	$('#client-themes').carousel( { dispItems:1 } );
	
	var icons = {
		header: "ui-icon-triangle-1-w",
		headerSelected: "ui-icon-triangle-1-s"
	};
	
	$( '#accordion' ).accordion({
	
		autoHeight: false,
		collapsible: true,
		icons: icons,
		navigation: true
	
	});
	
	$( '#templates li.tmpl-layout-th' ).each(function(index){
	
		$(this).click(function(){
		
			$( '#bro_tmpl' ).val( $(this).attr( 'zid' ) );
			
			$( '.tmpl-layout-th img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
		
		});
	
	});
	
	$( '#themes li.theme' ).each(function(index){
	
		$(this).click(function(){
		
			$( '#bro_theme' ).val( $(this).attr( 'zid' ) );
			
			$( 'div.theme-container' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'div.theme-container' );
			container.addClass( 'selected' );
		
		});
	
	});
	
	$( '#client-themes li.theme' ).each(function(index){
				
		$(this).click(function(){
		
			$( '#bro_theme' ).val( $(this).attr( 'zid' ) );
			
			$( 'div.theme-container' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'div.theme-container' );
			container.addClass( 'selected' );
		
		});
	
	});
	
	
	//Add onChange event for client select input
	//to add/remove themes based on selected client
	$('#bro_client').change(function(){
	
		$('#bro_client option:selected').each(function(){
		
			cid = $(this).val();
			//$( '#bro_client' ).val( $(this).val() );
		
		});
		
		$.ajax({
			type: 'POST',
			url: 'index.php',
			data: 'option=com_zbrochure&task=getThemes&cid='+cid+'&pub=0&form=0',
			success: function(html){
			
				$('#client-themes').html(html);
				
				//Create the carousel for the page layout thumbnails
				$('#client-themes').carousel( { dispItems:1 } );
				
				$( '#client-themes li.theme' ).each(function(index){
				
					$(this).click(function(){
						
							$( '#bro_theme' ).val( $(this).attr( 'zid' ) );
							
							$( 'div.theme-container' ).each(function(index){
				
								$(this).removeClass( 'selected' );
							
							});
							
							var container = $(this).find( 'div.theme-container' );
							container.addClass( 'selected' );
					
					});
				
				});
			
			}
		});
	
	});
		
	$( '#sortable li:nth-child(2)' ).css( 'clear', 'left' );
	
	$( "#sortable" ).sortable({
		
		placeholder: "ui-state-highlight",

		sort: function( event, ui ){
			
			$( '#sortable li' ).each(function(index){
			
				$(this).css( 'clear', 'none' );
			
			});
			
			$( '#sortable li:nth-child(2)' ).css( 'clear', 'left' );
		
		},
		
		stop: function( event, ui ){
			
			$( '#sortable li' ).each(function(index){
			
				//alert( $(this).attr('zid') );	
				
				var id = $(this).attr('zid');
				
				if(id){
				
					ordering[index] = id;
				
				}
				
			});
			
			
			$.ajax({
			
				url: 'index.php?option=com_zbrochure&task=broOrdering',
				type: 'POST',
				data: { order : ordering }
			
			}).done(function( html ){
			
				//alert( html );
			
			});
		
		}
	});
	
	$( "#sortable" ).disableSelection();
	
	
	$( '.tmpl-page-th' ).each(function(index){
		
		var pid = $(this).attr('pid');
		
		$(this).dblclick(function(){
			
			$.scrollTo( $('#page-'+pid), 1000 );
			
		});
		
	});
	
	generate_doc_dialog = $('<div style="display:none" title="Downloadable Brochure"><div id="generated" class="form-row add-padding package-container-header"><img src="<?php echo JURI::base().'components/com_zbrochure/assets/images/loading-animated.gif';?>" alt="loading" class="loading-animated" /></div></div>').appendTo('body');
		
	generate_doc_dialog.dialog({

		autoOpen:false,
		width: 300,
		height: 400,
		modal: true,
		resizable:false,
		show: { effect: 'drop', direction: "up" },
		open: function(){
			disable_scroll();
		},
		close: function(){
			enable_scroll();
		}

	
	});
	
	$('#generate-btn').click(function(){
	
		generate_doc_dialog.dialog('open');
		
		$.ajax({
			
			url: 'index.php?option=com_zbrochure&task=generateDoc&id=<?php echo $this->bro->bro_id; ?>&store_pdf=1&return_link=1',
			type: 'POST'
		
		}).done(function( html ){
		
			$('#generated').html( html );
			$('#generated-icon-container').delay('250').fadeTo('5000', 1, function() {});
		
		});
	
	});
	
	save_dialog = $('<div style="display:none" title="<?php echo JText::_( 'REFRESHING_THUMBNAILS' ); ?>"><div id="saving" class="form-row add-padding package-container-header"><div id="saving-thumb-container"></div></div><div class="progress-container"><div id="saving-progressbar"></div></div></div>').appendTo('body');
	
	save_dialog.dialog({

		autoOpen:false,
		width: 250,
		height: 290,
		modal: true,
		resizable:false,
		show: { effect: 'drop', direction: "up" },
		close: function( event, ui ){}
	
	});
	
	$( "#saving-progressbar" ).progressbar({value:1});
	
	$('#save-btn').click(function(){
		
		/*
		save_dialog.dialog('open');
		
		page_array	= $('.page-with-content');
		page_count	= page_array.length;
		i 			= 0;
		
		generate( page_array[0] );
		*/
				
	});
	
	function refreshThumbnails(){
		
		save_dialog.dialog('open');
		
		page_array	= $('.page-with-content');
		page_count	= page_array.length;
		i 			= 0;
		
		generate( page_array[0] );
		
	}
	
	function generate( item ){
		
		pid = $( item ).attr( 'zid' );
			
		$.ajax({
		
			type: 'POST',
			url: 'index.php',
			data: 'option=com_zbrochure&task=generateBroThumb&bid=<?php echo $this->bro->bro_id; ?>&pid='+pid
		
		}).done( function( html ){
			
			if( i != 0 ){
				$('#generated-thumb-'+i).fadeTo( '3000', 0, function(){} );
			}
			
			i++;
			
			current_percentage = $( "#saving-progressbar" ).progressbar("option","value");
			updated_percentage = (i / page_count)*100;
			
			$( "#saving-progressbar .ui-progressbar-value" ).animate({
				
				width: updated_percentage+'%'
				
			});
						
			msg = $( '<img src="'+html+'" class="generated-thumb" id="generated-thumb-'+i+'" style="z-index:'+i+'" />' ).hide().appendTo('#saving-thumb-container').fadeTo( '5000', 1, function(){} );
			
			$( '#tmpl-page-'+pid ).css( 'background-image', 'url('+html+')' );
			
			if( i <= (page_count - 1) ){
				
				generate( page_array[i] );
				
			}else if( i == page_count ){
				
				$('#generated-thumb-'+i).fadeTo( '500', 0, function(){
					
					done = $( '<div class="saving-done"><?php echo JText::_( 'DONE_GENERATING_THUMBNAILS' ); ?></div>' ).hide().appendTo('#saving-thumb-container').fadeTo( '500', 1, function(){
						
						//$('#bro-settings').submit();	
						save_dialog.dialog( 'close' );
						
					});	
					
				});
				
				
				
			}
			
		}).fail(function( jqXHR, status ){
			
			alert(jqXHR +' : '+status);
			
		});
		
	}
	
	$("#brochure-top-bar").scrollFixed();
	
	var refresh = <?php echo JRequest::getInt( 'refresh', 0 ); ?>
	
	if( refresh ){
		
		refreshThumbnails();
		
	}
	
});




function showPageNumber( show, bro_page_id ){
	
	switch( show ){
		
		case 'checked':
			style	= 'block';
			show	= 1;
		break;
		
		default:
			style	= 'none';
			show	= 0;
	}
	
	$.ajax({
	
		type: 'POST',
		url: 'index.php',
		data: 'option=com_zbrochure&task=saveBrochurePageAjax&bro_page_id='+bro_page_id+'&show='+show
	
	}).done(function( html ){
		
		if( html == 1 ){
					
			$( '#page-number-'+bro_page_id ).css( 'display', style );
	
		}
	
	});
	
}

function selectClient( elem, client ){

	var client_name = $(elem).text();
	$('.client-item').removeClass('selected');
	$(elem).addClass('selected');
	$('#bro_client').val(client);
	$('#selected-client').text(client_name);

}

(function($){
	$.fn.scrollFixed = function(params){
	params = $.extend( {appearAfterDiv: 0, hideBeforeDiv: 0}, params);
	var element = $(this);

	if(params.appearAfterDiv)
		var distanceTop = element.offset().top + $(params.appearAfterDiv).outerHeight(true) + element.outerHeight(true);
	else
		var distanceTop = element.offset().top;

	if(params.hideBeforeDiv)
		var bottom = $(params.hideBeforeDiv).offset().top - element.outerHeight(true) - 10;
	else
		var bottom = 200000;				

		$(window).scroll(function(){	
			if( $(window).scrollTop() > distanceTop && $(window).scrollTop() < bottom ) 		
				element.css({'position':'fixed', 'top':'0'});
			else
				element.css({'position':'relative'});				
		});			  
	};
})(jQuery);

</script>