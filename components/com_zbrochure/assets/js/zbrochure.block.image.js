/**
 * zBrochure - Edit Blocks
 *
 * Edit control for image content blocks
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */
 
function editBlockImage(){
	
	//this.block_type	= block_type;
	//this.id			= '';
	//this.image		= '';
	//this.dialog		= '';
	//this.controls	= '';
	
	//alert(' test ');
	
	this.init = function( block_id, url ){
		
		this.modal( block_id );
		this.slider( block_id );
		this.btns( block_id );
		
		var move		= 0.125;
		
		$( '#image_'+block_id ).mouseover(function(){
			$(this).css( 'cursor', 'pointer' );
		});
				
		$( '#imageClick_'+block_id ).click(function(){
			$( '#edit-image-'+block_id ).dialog( 'open' );
		});
		
		$( '#image_'+block_id ).click(function(){
			$( '#edit-image-'+block_id ).dialog( 'open' );
		});
		
		$( '#change-btns-'+block_id ).click(function(){
			
			$( '#mediamanager-modal' ).attr( 'src', url );
			change_dialog.dialog( "open" );
			
		});
		
		/*
		$( '#image_'+block_id ).draggable({
			
			containment: "parent",
			//scroll:false,
			cursor: "move",
			stop: function( event, ui ){

			}
		
		}).bind('mousedown', function(event, ui){
			
			alert( event.target );
			
			// bring target to front
			//$(event.target.parentElement).append( event.target );
		
		}).bind('drag', function(event, ui){
		
			// update coordinates manually, since top/left style props don't work on SVG	
			top_inches	= ui.position.top;
			left_inches	= ui.position.left;
			
			event.target.setAttribute( 'x', left_inches.toFixed(3) );
			event.target.setAttribute( 'y', top_inches.toFixed(3) );
			
		});
		*/
		
		
		$( '#edit-image-'+block_id+' button.up-btn' ).click(function(){

			$( '#image_'+block_id ).attr( 'y', ( parseFloat( $( '#image_'+block_id ).attr( 'y' ) ) - move).toFixed(3) );
			$( '#image_y-'+block_id ).val( $( '#image_'+block_id ).attr( 'y' ) );
			
		});
		
		$( '#edit-image-'+block_id+' button.down-btn' ).click(function(){

			$( '#image_'+block_id ).attr( 'y', ( parseFloat( $( '#image_'+block_id ).attr( 'y' ) ) + move).toFixed(3) );
			$( '#image_y-'+block_id ).val( $( '#image_'+block_id ).attr( 'y' ) );
			
		});
			
		$( '#edit-image-'+block_id+' button.left-btn' ).click(function(){

			$( '#image_'+block_id ).attr( 'x', ( parseFloat( $( '#image_'+block_id ).attr( 'x' ) ) - move).toFixed(3) );
			$( '#image_x-'+block_id ).val( $( '#image_'+block_id ).attr( 'x' ) );
			
		});
		
		$( '#edit-image-'+block_id+' button.right-btn' ).click(function(){

			$( '#image_'+block_id ).attr( 'x', ( parseFloat( $( '#image_'+block_id ).attr( 'x' ) ) + move).toFixed(3) );
			$( '#image_x-'+block_id ).val( $( '#image_'+block_id ).attr( 'x' ) );
			
		});
		
		$( '#edit-image-'+block_id+' button.center-btn' ).click(function(){

			points = $( '#imageClick_'+block_id ).attr( 'points' );
			
			points = points.replace(  / /g, ',' );
			
			points_array = points.split( ',' );
			
			$( '#image_'+block_id ).attr( 'x', points_array[0] );
			$( '#image_x-'+block_id ).val( points_array[0] );
			
			$( '#image_'+block_id ).attr( 'y', points_array[1] );
			$( '#image_y-'+block_id ).val( points_array[1] );
			
			
		});
		
		$( '#image_scale-'+block_id ).blur(function(){
			
			$( '#image_'+block_id ).attr( 'width', ( $( '#image_'+block_id ).attr( 'nwidth' ) * ( $(this).val() / 100 ) ).toFixed(3) );
			$( '#image_'+block_id ).attr( 'height', ( $( '#image_'+block_id ).attr( 'nheight' ) * ( $(this).val() / 100 ) ).toFixed(3) );
			
			$( '#image-edit-scale-slider-'+block_id ).slider( "value", $(this).val() );
			
		});
		
		
		
	}
	
	this.btns = function( block_id){
		
		up		= $( '#edit-image-'+block_id+' button.up-btn' ).button({icons:{primary:"ui-icon-triangle-1-n"},text:false});
		down	= $( '#edit-image-'+block_id+' button.down-btn' ).button({icons:{primary:"ui-icon-triangle-1-s"},text:false});
		
		left	= $( '#edit-image-'+block_id+' button.left-btn' ).button({icons:{primary:"ui-icon-triangle-1-w"},text:false});
		right	= $( '#edit-image-'+block_id+' button.right-btn' ).button({icons:{primary:"ui-icon-triangle-1-e"},text:false});
		
		center	= $( '#edit-image-'+block_id+' button.center-btn' ).button({icons:{primary:"ui-icon-arrow-center"},text:false});
		
	}
	
	this.modal = function( block_id ){
		
		$( '#edit-image-'+block_id ).dialog({
			autoOpen: false,
			width: 320,
			resizable: false,
			close: function(){
				$( '#vars-dialog' ).dialog( 'close' );									
			}
		});	
		
	}
	
	this.slider = function( block_id ){
		
		var current_width	= $( '#image_'+block_id ).attr( 'width' );
		var current_height	= $( '#image_'+block_id ).attr( 'height' );
		
		var native_width	= $( '#image_'+block_id ).attr( 'nwidth' );
		var native_height	= $( '#image_'+block_id ).attr( 'nheight' );
		
		scale	= current_width / native_width;
		
		//Create the scale slider
		$( '#image-edit-scale-slider-'+block_id ).slider({
			range:"max",
			value: (scale * 100),
			min: 5,
			max: 200,
			slide:function( event, ui ){
				
				$( '#image-edit-scale-current-'+block_id ).text( ui.value+"%" );
				
				multiplier	= ui.value / 100;
				
				$( '#image_'+block_id ).attr( 'width', (native_width * multiplier).toFixed(3) );
				$( '#image_'+block_id ).attr( 'height', (native_height * multiplier).toFixed(3) );
				
				$( '#image_scale-'+block_id ).val( ui.value );
							
			}
		
		});	
		
	}

	

}