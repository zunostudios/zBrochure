/**
 * zBrochure - Media Manager
 *
 * Media manager
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */

function deleteAsset(){
	window.location = 'index.php?option=com_zbrochure&task=deleteAsset&id='+asset;
}


      
function createUploader(){
            
    var uploader = new qq.FileUploader({

        element: document.getElementById('file-uploader'),
        allowedExtensions: ['jpg','jpeg','gif','png'],
        action: 'index.php?option=com_zbrochure&task=uploadAssets',
        debug: true,
        onSubmit: function(id, fileName){

        },
        onComplete: function(id, fileName, responseJSON){
        
        	var newfilename = responseJSON.newname+'.'+responseJSON.extension;
        	var thumb_dir	= responseJSON.thumbnail_dir;
        
        	//create the input to store the filename in database
        	var newInput = document.createElement('input');
        	newInput.setAttribute('type','hidden');
        	newInput.setAttribute('name','asset['+id+'][asset_file]');
        	newInput.setAttribute('value',newfilename);
        	
        	$('#assetBody').append(newInput);
        	
        	var newRow = '<tr><td>'+(id+1)+'</td><td><div style="height:50px;overflow:hidden;width:50px"><img src="'+thumb_dir+newfilename+'" width="50" /></div></td><td><input disabled="disabled" value="'+newfilename+'" style="width:150px" /></td><td><input type="text" class="inputbox" name="asset['+id+'][asset_title]" /></td><td id="keyword'+id+'"><select id="keywords'+id+'" name="asset['+id+'][keywords]"></select><input type="hidden" value="'+id+'" name="tempid" /></td></tr>';
        	
        	//var autoComplete = $('keywords').clone(true);
        	//var autoComplete = $(cloneKeywords).attr('id','keywords'+id);
        	
        	$('#assetBody').append(newRow);
        	//$('#keyword'+id).append(autoComplete);
        
		    $('#keywords'+id).fcbkcomplete({
			    json_url: "index.php?option=com_zbrochure&task=getKeywords",
			    addontab: true,                   
			    maxitems: 10,
			    input_min_size: 0,
			    height: 3,
			    cache: true,
			    newel: true,
			    select_all_text: "select",
			});
        
        }
    });           
}

function thumbnailActions(){
	
	$('.asset-item-grid').each(function( index ){
		
		var aid = $(this).attr( 'rel' );
	
		$(this).click(function(){
			
			$('#image_asset_id').val( aid );
			$('#image_reset').val( '1' );
			
			var desc = $(this).attr( 'title' ).split('::');
				
			$('#asset-desc div.asset-desc-inner').replaceWith('<div class="asset-desc-inner"><strong>'+desc[0]+'</strong><br /><span>'+desc[1]+'</span><br /><span>'+desc[2]+'</span></div>');
			
			$( '.asset-item-grid img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
		});
	
	});
	
}