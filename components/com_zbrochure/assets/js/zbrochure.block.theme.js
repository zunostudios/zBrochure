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
 
function editBlockTheme(){
	
	//this.block_type	= block_type;
	this.block	= '';

	this.edit = function( id, block, url ){
		
		var dialog = $('<div id="theme-dialog-'+id+'" style="display:none" class="loading"></div>').appendTo('body');
		
		dialog.load(url).dialog({
		
			close: function( event, ui ){
			
				//(this).remove();
				
				location.reload();
			
			},
			modal: true,
			width: 960,
			height: 400
		
		});
		
		
			
	}

}