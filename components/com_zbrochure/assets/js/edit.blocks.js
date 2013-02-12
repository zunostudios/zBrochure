/**
 * zBrochure - Edit Blocks
 *
 * Edit control for various content blocks
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */
 
$(document).ready(function(){
				
	$("#image-edit").dialog({
		autoOpen:false,
		show:"fade",
		hide:"fade",
		width:420,
		height:200,
		position: ["right","bottom"]
	});
	
});
 
function editBlocks(){
	
	//this.block_type	= block_type;
	this.block	= '';

	this.editImage = function( block ){
		
		//Not sure if we'll need this or not quite yet
		this.block = block;
		
		//Remove the click event handler that sets the editing vars and opens the edit dialog
		block.element.unclick( block.element.handler );
		
		var center_x = block.element.getBBox().width / 2 * block.scale;
		var center_y = block.element.getBBox().height / 2 * block.scale;
		
		/**************** EDITING DIALOG BOX ****************/
		
		//Set up the UI tabs
		$("#image-edit-tabs").tabs({
		
			selected:0,
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible.");
				}
			}
			
		});	
		
		//Open the edit dialog box
		$("#image-edit").dialog("open");
		
		//Reset the click event handler so that we can edit again if we close the dialog
		$("#image-edit").dialog({
		
			beforeClose: function( event, ui ){
			
				block.element.click( block.element.handler );
				block.element.undrag();
			
			}
		
		});
		
		//Add the drag event to the image so we can reposition it by dragging
		block.element.drag( function( dx, dy ){
		
			this.attr({
			
				x: x + ( dx / block.scale ),
				y: y + ( dy / block.scale )
			
			});
		
		}, function(){
		
			x = this.attr("x");
			y = this.attr("y");
		
		});
		
		
		
		/**************** SCALING ****************/
		
		//Set the scale notification text to the saved value
		$("#image-edit-scale-current").text( Math.ceil(block.scale * 100) + "%" );
		
		//Create the scale slider
		$("#image-edit-scale-slider").slider({
			range:"max",
			value: (block.scale * 100),
			min:10,
			max:100,
			slide:function( event, ui ){
				
				$("#image-edit-scale-current").text( ui.value+"%" );
				
				block.scale = ( ui.value / 100 );
								
				block.element.transform( "S" + block.scale + ", " + block.scale + ", " + center_x + ", " + center_y + " R" + block.rotate + ", " + center_x + ", " + center_y );		
				
			}
		
		});
		
		
		
		/**************** ROTATING & FLIPPING ****************/
		
		//TO DO
		
		/*
		$("#image-edit-flip-horizontal").click(function(evnt){
		
			//alert("flip clicked");
			//alert( block.element.attr("width") );
			//alert( block.element.scale().x );			
			
		});
		*/
		
		$("#image-edit-rotate").click(function(){
			
			block.rotate = block.rotate + 5;
			
			block.element.transform( "S" + block.scale + ", " + block.scale + ", " + center_x + ", " + center_y + " R" + block.rotate + ", " + center_x + ", " + center_y );
		
		});
		
	
	}

}