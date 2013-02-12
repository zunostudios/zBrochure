/**
 * zBrochure - Edit Blocks
 *
 * Edit control for logo content blocks
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */
 
function editBlockLogo(){
	
	//this.block_type	= block_type;
	//this.id			= '';
	//this.image		= '';
	//this.dialog		= '';
	//this.controls	= '';
	
	//alert(' test ');
	
	this.init = function( block_id, url, no_logos ){
		
		this.modal( block_id );
				
		$( '#logo_container_'+block_id ).click(function(){
			$( '#edit-logo-'+block_id ).dialog( 'open' );
		});
		
		$( '#logo_'+block_id ).click(function(){
			$( '#edit-logo-'+block_id ).dialog( 'open' );
		});
		
		//Make the logo draggable. So much easier than using the direction arrows.
		$( '#logo_'+block_id ).draggable({
			containment: "parent",
			scroll:false,
			cursor: "move",
			stop: function( event, ui ){
				
				cssTop = $( '#logo_'+block_id ).css( 'top' );
				$( '#image_y-'+block_id ).val( parseInt(cssTop) );
				
				cssLeft = $( '#logo_'+block_id ).css( 'left' );
				$( '#image_x-'+block_id ).val( parseInt(cssLeft) );
				
			}
		});
		
		if( no_logos == 0 ){

			this.slider( block_id );
			this.btns( block_id );
		
			var move		= 1;
			
			$( '#change-btns-'+block_id ).click(function(){
				
				$( '#logomanager-modal' ).attr( 'src', url );
				change_logo_dialog.dialog( "open" );
				
			});
			
			
			$( '#edit-logo-'+block_id+' button.up-btn' ).click(function(){
	
				cssTop = $( '#logo_'+block_id ).css( 'top' );
				newTop = parseInt(cssTop) - move;
				
				$( '#logo_'+block_id ).css( 'top', newTop+'px' );
				$( '#image_y-'+block_id ).val( newTop );
				
			});
			
			$( '#edit-logo-'+block_id+' button.down-btn' ).click(function(){
				
				cssBottom = $( '#logo_'+block_id ).css( 'top' );
				newBottom = parseInt(cssBottom) + move;
				
				$( '#logo_'+block_id ).css( 'top', newBottom+'px' );
				$( '#image_y-'+block_id ).val( newBottom );
							
			});
				
			$( '#edit-logo-'+block_id+' button.left-btn' ).click(function(){
				
				cssLeft = $( '#logo_'+block_id ).css( 'left' );
				newLeft = parseInt(cssLeft) - move;
				
				$( '#logo_'+block_id ).css( 'left', newLeft+'px' );
				$( '#image_x-'+block_id ).val( newLeft );
		
			});
			
			$( '#edit-logo-'+block_id+' button.right-btn' ).click(function(){
	
				cssRight = $( '#logo_'+block_id ).css( 'left' );
				newRight = parseInt(cssRight) + move;
				
				$( '#logo_'+block_id ).css( 'left', newRight+'px' );
				$( '#image_x-'+block_id ).val( newRight );
				
			});
			
			$( '#image_bg-'+block_id ).click(function(){
				
				if( $(this).attr( 'checked' ) ){
							
					$( '#logo_container_'+block_id ).css( 'background-color', 'rgb('+$( '#image_bg_color-'+block_id ).val()+')' );
				
				}else{
					
					$( '#logo_container_'+block_id ).css( 'background-color', 'transparent' );
					
				}
				
			});
			
			$( '#edit-logo-'+block_id+' button.center-btn' ).click(function(){

				$( '#logo_'+block_id ).css( 'left', '0' );
				$( '#image_x-'+block_id ).val( '0' );
				
				$( '#logo_'+block_id ).css( 'top', '0' );
				$( '#image_y-'+block_id ).val( '0' );
				
				
			});
		
		}
				
	}
	
	this.btns = function( block_id ){
	
		up		= $( '#edit-logo-'+block_id+' button.up-btn' ).button({icons:{primary:"ui-icon-triangle-1-n"},text:false});
		down	= $( '#edit-logo-'+block_id+' button.down-btn' ).button({icons:{primary:"ui-icon-triangle-1-s"},text:false});

		left	= $( '#edit-logo-'+block_id+' button.left-btn' ).button({icons:{primary:"ui-icon-triangle-1-w"},text:false});
		right	= $( '#edit-logo-'+block_id+' button.right-btn' ).button({icons:{primary:"ui-icon-triangle-1-e"},text:false});
		
		center	= $( '#edit-logo-'+block_id+' button.center-btn' ).button({icons:{primary:"ui-icon-arrow-center"},text:false});
		
	}

	this.modal = function( block_id ){
		
		$( '#edit-logo-'+block_id ).dialog({
			autoOpen: false,
			resizable: false,
			width: 320,
			open: function(){
				$( '#logo_container_'+block_id ).parent().addClass( 'border-active' );
			},
			close: function(){
				$( '#logo_container_'+block_id ).parent().removeClass( 'border-active' );
				$( '#vars-dialog' ).dialog( 'close' );							
			}
		});	
		
	}
	
	this.slider = function( block_id ){
		
		scale	= $( '#logo_'+block_id ).attr('height').replace( '%', '' );
		
		//Create the scale slider
		$( '#logo-edit-scale-slider-'+block_id ).slider({
			range:"max",
			value: scale,
			min:5,
			max:300,
			slide:function( event, ui ){
				
				$( '#logo-edit-scale-current-'+block_id ).text( ui.value+"%" );
				$( '#logo_'+block_id ).attr( 'height', ui.value+'%' );
				$( '#image_scale-'+block_id ).val( ui.value );
							
			}
		
		});	
		
	}

}

function logoThumbnailActions(){
	
	$('.asset-item-grid').each(function( index ){
		
		var aid = $(this).attr( 'rel' );
	
		$(this).click(function(){
			
			$('#image_asset_id').val( aid );
			
			/*
var desc = $(this).attr( 'title' ).split('::');
				
			$('#asset-desc div.asset-desc-inner').replaceWith('<div class="asset-desc-inner"><strong>'+desc[0]+'</strong><br /><span>'+desc[1]+'</span><br /><span>'+desc[2]+'</span></div>');
*/
			
			$( '.asset-item-grid img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
		});
	
	});
	
}