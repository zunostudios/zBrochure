/**
 * zBrochure - Edit Blocks
 *
 * Edit control for svg content blocks
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */
 
	
function changeColor( elem, theme_position, block_id ){
	
	var color = $(elem).css('backgroundColor');
	
	$('.theme-element').removeClass('active');
	$(elem).addClass('active');
	
	$( '#svg-'+block_id ).attr( 'fill', color );
	$( '#theme-color-chosen-'+block_id ).css( 'backgroundColor', color );
	$( '#svg-color-'+block_id ).val( theme_position );

}

function setCurrentColor( block_id ){
	
	var current_color	= $( '#svg-'+block_id ).attr( 'data-theme-position' );
	var current_opacity	= $( '#svg-'+block_id ).attr( 'fill-opacity' );
	var set_color	 	= $( '#color'+current_color ).css( 'backgroundColor' );
	
	$('.theme-element').each(function(index){
		if( $(this).css('backgroundColor') == set_color ){
			$(this).addClass('active');
		}
	});	
	
	$( '#theme-color-chosen-'+block_id ).css( 'backgroundColor', set_color );
	$( '#theme-color-chosen-'+block_id ).css( 'opacity', current_opacity );
	
	$( '#svg-color-'+block_id ).val( current_color );
	$( '#svg-opacity-'+block_id ).val( current_opacity );
	
	if( !current_opacity ){
		
		current_opacity = 1;
		
	}
	
	$( '#svg-opacity-scale-slider-'+block_id ).slider( "option", "value", ( current_opacity * 100) );
	
	
}
