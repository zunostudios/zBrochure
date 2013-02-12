
//UI stuff that needs to happen after jQuery and the page is loaded
$(document).ready(function(){
	
	//Create the carousel for the page layout thumbnails
	$('#templates').carousel( { dispItems:1 } );
	
	//Create the carousel for the page layout thumbnails
	$('#themes').carousel( { dispItems:1 } );
	
	//Create the carousel for the page layout thumbnails
	$('#client-themes').carousel( { dispItems:1 } );
	
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
	
	//Add click event for each template
	//this will load previews and cover page styles
	$('#templates li.tmpl-th').each(function( index ){
		
		var tid = $(this).attr('zid');		
		
		$(this).click(function(){
			
			//AJAX call to get all template pages
			$.ajax({
				type: 'POST',
				url: 'index.php',
				data: 'option=com_zbrochure&task=getTemplatePages&tid='+tid+'&render=brochure-choose_preview',
				success: function(html){
				
					$('#template-preview').hide().html(html).fadeIn(800);
					
				}
			});
			
			$( '#templates li.tmpl-th img' ).each(function(index){
			
				$(this).removeClass( 'selected' );
			
			});
			
			var container = $(this).find( 'img' );
			container.addClass( 'selected' );
			
			//Set the hidden input value so we know the choosen template ID on POST
			$('#bro_tmpl').val(tid);
			$('#bro_cover').val('');
			
		});
	
	});
	
});

function saveBtn( method ){

	switch( method ){
	
		case 'cancel':
		
			location.href = 'index.php?option=com_zbrochure';
			return;
		
		break;
		
		case 'saveBrochure':
		case 'buildBrochure':
			
			//Need to do some form validation here before submitting the form
			$('#task').val(method);
			$('#bro-setup').submit();
		
		break;
		
	
	}

}