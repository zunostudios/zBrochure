/**
 * zBrochure - Brochure Edit
 *
 * Brochure editing
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */
 
packageId	= 0;
var tab_counter	= new Array();

$(document).ready(function(){
    
    $('#main-preview').delay('250').fadeTo('5000', 1, function() {});
    
    $( '.bro-options-close' ).each(function(index){
		
		var options			= $(this).parent();
		
		$(this).click(function(){
			
			if( options.hasClass('options-0') && options.hasClass('open') ){
			
				options.animate(
					{left:'-28'},
					300,
					'easeInCubic',
					function(){
						options.toggleClass( 'open' );
					}
				);
			
			}else if( options.hasClass('options-0') ){
			
				options.animate(
					{left:'-81'},
					300,
					'easeOutCubic',
					function(){
					options.toggleClass( 'open' );
					});
			
			}
			
			if( options.hasClass('options-1') && options.hasClass('open') ){
			
				options.animate(
					{right:'-28'},
					300,
					'easeInCubic',
					function(){
						options.toggleClass( 'open' );
					}
				);
			
			}else if( options.hasClass('options-1') ){
			
				options.animate(
					{right:'-81'},
					300,
					'easeOutCubic',
					function(){
					options.toggleClass( 'open' );
					});
			
			}
						
		});
		
	});
	
	//Create the carousel for the page layout thumbnails
	$('#tmpl-layouts-change').carousel( { dispItems:5 } );
	
	$( '#tmpl-layouts-change li.tmpl-layout' ).each(function(index){
				
		$(this).click(function(){
			
			url = $(this).attr('rel');
			$('#tmpl-main-preview').empty();
			
			$(document.createElement('img'))
				.hide()
			    .attr({ src: url, alt: 'Page layout preview' })
			    .appendTo( $('#tmpl-main-preview') )
			    .fadeIn(500);
			
			
			$('#bro_page_layout').val( $(this).attr( 'kid' ) )
			
			$( '#tmpl-layouts-change li.tmpl-layout img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
			var desc = $(this).attr( 'des' ).split('::');
			
			$('#tmpl-layout-desc div.tmpl-layout-desc-inner').replaceWith('<div class="tmpl-layout-desc-inner"><strong>'+desc[0]+'</strong><br /><span>'+desc[1]+'</span><p>'+desc[2]+'</p></div>');
		
		});
	
	});
	
	
	$( '.changelayout-btn' ).click(function(){
					
		$('#bro_page_id').attr( 'value', $(this).attr( 'zpage' ) );
		$('#bro_page_order').attr( 'value', $(this).attr( 'zorder' ) );
		$('#bro_page_layout').attr( 'value', $(this).attr( 'zlayout' ) );
		
		$('#changelayout').dialog( 'option', 'title', 'Change Layout' );
		$('#changelayout').dialog( 'open' );
		
	});
	
	$( '#changelayout' ).dialog({
		autoOpen: false,
		resizable: true,
		height:600,
		width:608,
		modal: true,
		open: function(){
			disable_scroll();
		},
		close: function(){
			enable_scroll();
		}
	});
	
	
	//Create the carousel for the page layout thumbnails
	$('#tmpl-layouts-add').carousel( { dispItems:5 } );
	
	$( '#tmpl-layouts-add li.tmpl-layout' ).each(function(index){
				
		$(this).click(function(){
			
			url = $(this).attr('rel');
			$('#tmpl-main-preview-add').empty();
			
			$(document.createElement('img'))
				.hide()
			    .attr({ src: url, alt: 'Page layout preview' })
			    .appendTo( $('#tmpl-main-preview-add') )
			    .fadeIn(500);
			
			$('#bro_page_new_layout').val( $(this).attr( 'kid' ) )
			
			$( '#tmpl-layouts-add li.tmpl-layout img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
			var desc = $(this).attr( 'des' ).split('::');
			
			$('#tmpl-layout-add-desc div.tmpl-layout-desc-inner').replaceWith('<div class="tmpl-layout-desc-inner"><strong>'+desc[0]+'</strong><br /><span>'+desc[1]+'</span><p>'+desc[2]+'</p></div>');
		
		});
	
	});
	
	
	
	$( '#add-page-btn' ).click(function(){
							
		$('#add-page-modal').dialog( 'open' );
		
	});
	
	$( '#add-page-modal' ).dialog({
		autoOpen: false,
		resizable: true,
		height:600,
		width:608,
		modal: true,
		open: function(){
			disable_scroll();
		},
		close: function(){
			enable_scroll();
		}
	});

});

function activateContent( catid, blockid ){

	$('#content-'+blockid).find('option').remove();

	$.get( 'index.php?option=com_zbrochure&task=getContent&catid='+catid, function(data) {

		var newData = jQuery.parseJSON(data);
		
		$('#content-'+blockid).append('<option value=0"">-- Select Content --</option>');
		
		$.each(newData, function(index) {						
			var option = '<option value="'+newData[index].id+'">'+newData[index].title+'</option>';
			$('#content-'+blockid).append(option);
			//$('#content-'+blockid).css('display','block');
		});
		
	});

}

function placeContent( id, blockid ){

	$.get( 'index.php?option=com_zbrochure&task=placeContent&id='+id, function(data) {

		var newData = jQuery.parseJSON(data);
		CKEDITOR.instances['editor-'+blockid].setData(newData.data);
			
	});

}

function toggleNav(elem, id){

	if( $(elem).hasClass('active') ){
	
		$('#'+id).slideToggle('fast');
		$(elem).removeClass('active').addClass('inactive');
		$('#'+id).removeClass('active').addClass('inactive');
	
	}else{
	
		$('.toggleNav').removeClass('active').addClass('inactive');	
		$('.jq-hide').removeClass('active').addClass('inactive');
		$(elem).removeClass('inactive').addClass('active');
		$('#'+id).removeClass('inactive').addClass('active');
		$('#'+id).slideToggle('fast');
		$('.jq-hide.inactive').hide();
	
	}

}