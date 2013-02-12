<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure' ); ?>" name="bro-settings" id="bro-settings" method="post">

<div id="left-sidebar" class="page-thumbnails sidebar">
	
	<div class="bro-details">
	
		<div class="bro-details-inner">
			<ul>
				<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_TITLE' ).'</strong> '.$this->bro->bro_title; ?></li>
				<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CLIENT' ).'</strong> '.$this->bro->client_version_name; ?></li>
				<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED' ).'</strong> '.JHtml::_('date', $this->bro->bro_created, JText::_('DATE_FORMAT_LC3')); ?></li>
				<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_CREATED_BY' ).'</strong> <a href="mailto:'.$this->bro->email.'">'.$this->bro->username.'</a>'; ?></li>
				<li><?php echo '<strong>'.JText::_( 'DETAILS_BROCHURE_TMPL' ).'</strong> '.$this->tmpl->tmpl_name; ?></li>
			</ul>
		</div>
	
	</div>
	
	<div id="accordion">
	
		<h3><a href="#thumbs"><?php echo JText::_( 'THUMBNAILS_TITLE' ); ?></a></h3>
		<div>
	
			<ul class="thumbnail-list" id="sortable">
		
			<?php
			
			$i = 1;
			
			foreach( $this->pages as $page ){
					
				$toc_txt	= ($page->bro_page_name) ? $page->bro_page_name : JText::_( 'NO_PAGE_TITLE' );
				$toc[$i] 	= '<li><a href="javascript:void(0)" onclick="$.scrollTo( $(\'#bro-page-'.$i.'\'), 1000 )">'.$toc_txt.'</a></li>';
				
				$th_img		= ($page->bro_page_thumb) ? JURI::base().'media/zbrochure/docs/'.$this->bro->bro_id.'/'.$page->bro_page_thumb : JURI::base().'media/zbrochure/images/tmpl/no-tmpl-img.png';
				
			?>
				
				<li class="tmpl-page-th" pid="<?php echo $i; ?>" id="tmpl-page-<?php echo $page->bro_page_id; ?>" zid="<?php echo $page->bro_page_id; ?>" style="background-image:url(<?php echo $th_img; ?>)" title="<?php echo $page->bro_page_id; ?>">
				<?php echo $page->bro_page_id; ?>
				
				<input type="hidden" name="ordering[<?php echo $page->bro_page_id; ?>]" value="<?php echo $page->bro_page_id; ?>" />
				
				</li>
									
			<?php 
			
			$i++;
			
			} ?>
			</ul>
			
			<div class="clear"><!--   --></div>
			
			<button type="button" class="btn add-btn icon-btn" title="<?php echo JText::_( 'ADD_PAGE' ); ?>" id="add-page-btn" style="margin:0 auto;display:block">
				<span class="icon"><!-- --></span>
				<span class="btn-text"><?php echo JText::_( 'ADD_PAGE' ); ?></span>
			</button>
			
		</div>	
		
		<h3><a href="#toc"><?php echo JText::_( 'TABLE_OF_CONTENTS' ); ?></a></h3>
		<div>
		
			<ol>
			<?php foreach( $toc as $page ){
				
				echo $page;
				
			} ?>
			</ol>
	
		</div>
		
		<h3><a href="#client"><?php echo JText::_( 'BROCHURE_CLIENT' ); ?></a></h3>
		<div>
			
			<!-- Start Clients -->
			<div id="clients">
				
				<div class="accordion-content-inner">
				<?php echo $this->clients; ?>
				</div>
			
			</div>
			<!-- End Clients -->
		
		</div>
		
		<h3><a href="#templates"><?php echo JText::_( 'BROCHURE_TEMPLATE' ); ?></a></h3>
		<div>
			
			<!-- Start Templates -->
			<div id="templates">
			
				<ul class="carousel">
					
				<?php foreach( $this->tmpls as $tmpl ){ 
				
					if( $tmpl->tmpl_id == $this->bro->bro_tmpl ){
					
						$class = 'selected';
					
					}else{
					
						$class = '';
					
					}
				
				?>
					
					<li class="tmpl-layout-th tmpl-layout" id="tmpl-layout-th-<?php echo $tmpl->tmpl_id; ?>" zid="<?php echo $tmpl->tmpl_id; ?>" rel="<?php echo JURI::base().'media/zbrochure/images/tmpl/'.$tmpl->tmpl_id.'/pv/'.$tmpl->tmpl_preview; ?>"><?php echo JHTML::_( 'image.site', $tmpl->tmpl_thumbnail, '/media/zbrochure/images/tmpl/'.$tmpl->tmpl_id.'/', '', '', $tmpl->tmpl_name . ' thumbnail', 'class="'.$class.'"' ); ?></li>
				
				<?php } ?>
				
				</ul>
			
			</div>
			<!-- End Templates -->
		
		</div>
		
		<h3><a href="#themes"><?php echo JText::_( 'BROCHURE_THEME' ); ?></a></h3>
		<div>
			
			<h4><?php echo JText::_('THEMES_CLIENT'); ?></h4>
			
			<div id="client-themes">
				
				<?php echo $this->client_themes; ?>
				
			</div>
			
			<h4><?php echo JText::_('THEMES_GENERAL'); ?></h4>
			
			<div id="themes">
			
				<?php echo $this->themes; ?>
				
			</div>
			
		</div>
	
	</div>
	
	<div class="action-btns">
		
		<div class="action-btns-inner">
		
			<button type="button" class="btn build-bro-btn" id="generate-btn"><span>Generate</span></button>
			<button type="button" class="btn save-btn" id="save-btn"><span>Save</span></button>
			
		</div>
		
	</div>
	
</div>

<input type="hidden" name="bro_id" id="bro_id" value="<?php echo $this->bro->bro_id; ?>" />
<!-- <input type="hidden" name="bro_client" id="bro_client" value="<?php echo $this->bro->bro_client; ?>" /> -->
<input type="hidden" name="bro_tmpl" id="bro_tmpl" value="<?php echo $this->bro->bro_tmpl; ?>" />
<input type="hidden" name="bro_theme" id="bro_theme" value="<?php echo $this->bro->bro_theme; ?>" />
<!-- <input type="hidden" name="bro_modified_by" id="bro_modified_by" value="<?php echo $this->user->get('id'); ?>" /> -->
<input type="hidden" name="bro_current_version" id="bro_current_version" value="<?php echo $this->bro->bro_current_version; ?>" />
<input type="hidden" name="task" id="task" value="saveBrochure" />

</form>

<script type="text/javascript">
	
	var ordering = new Array();
	
	$(document).ready(function(){
		
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
				
				$.scrollTo( $('#bro-page-'+pid), 1000 );
				
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
		
		save_dialog = $('<div style="display:none" title="Saving"><div id="saving" class="form-row add-padding package-container-header"><div id="saving-thumb-container"></div></div><div class="progress-container"><div id="saving-progressbar"></div></div></div>').appendTo('body');
		
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
			
			save_dialog.dialog('open');
			
			page_array	= $('.page-with-content');
			page_count	= page_array.length;
			i 			= 0;
			
			generate( page_array[0] );
					
		});
		
		function generate( item ){
			
			pid = $(item).attr('zid');
				
			$.ajax({
				type: 'POST',
				url: 'index.php',
				data: 'option=com_zbrochure&task=generateBroThumb&bid=<?php echo $this->bro->bro_id; ?>&pid='+pid
			}).done(function( html ){
				
				if( i != 0 ){
					$('#generated-thumb-'+i).fadeTo( '3000', 0, function(){} );
				}
				
				i++;
				
				current_percentage = $( "#saving-progressbar" ).progressbar("option","value");
				updated_percentage = (i / page_count)*100;
				
				$( "#saving-progressbar .ui-progressbar-value" ).animate({
					
					width: updated_percentage+'%'
					
				});
				
				//$( "#saving-progressbar" ).progressbar({value: (i / page_count)*100 });
				
				msg = $( '<img src="'+html+'" class="generated-thumb" id="generated-thumb-'+i+'" style="z-index:'+i+'" />' ).hide().appendTo('#saving-thumb-container').fadeTo( '5000', 1, function(){} );
				
				if( i <= (page_count - 1) ){
					
					generate( page_array[i] );
					
				}else if( i == page_count ){
					
					$('#generated-thumb-'+i).fadeTo( '500', 0, function(){
						
						done = $( '<div class="saving-done">done</div>' ).hide().appendTo('#saving-thumb-container').fadeTo( '500', 1, function(){
							
							$('#bro-settings').submit();	
							
						});	
						
					});
					
					
					
				}
				
			}).fail(function( jqXHR, status ){
				
				alert(jqXHR +' : '+status);
				
			});
			
		}
			
	});
	
</script>