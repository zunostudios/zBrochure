<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

JHTML::stylesheet( 'jquery.lightbox-0.5.css', 'components/com_zbrochure/assets/css/' );
JHTML::stylesheet( 'fcbkstyle.css', 'components/com_zbrochure/assets/css/' );
JHTML::stylesheet( 'jquery.dataTables.css', 'components/com_zbrochure/assets/css/' );

JHTML::script( 'jquery.lightbox-0.5.min.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.fcbkcomplete.min.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.dataTables.min.js', 'components/com_zbrochure/assets/js/' );

?>

<script type="text/javascript">

	var asset_dir = '<?php echo $this->assets_dir.'full/'; ?>';
	
	$(document).ready(function(){
		$('#tabs').tabs();
			
		createUploader();
		
		$('a.modal').lightBox({fixedNavigation:true});
		
	    $("#keywords").fcbkcomplete({
		    json_url: "data.txt",
		    addontab: true,                   
		    maxitems: 10,
		    input_min_size: 0,
		    height: 10,
		    cache: true,
		    newel: true,
		    select_all_text: "select",
		});
		
		var delete_dialog = $( "#delete-dialog" ).dialog({
			autoOpen: false,
			resizable: false,
			modal: true
		});
		
		$('.delete-asset').each(function( index ){
			
			var link = $(this).attr( 'href' );
			$(this).attr( 'href', 'javascript:void(0)' );
			
			$(this).click(function(){
						
				$('#delete-dialog a.delete-btn').attr( 'href', link );
				delete_dialog.dialog( 'open' );
				
			});
			
		});
		
		$('.sortable').dataTable({
			"aoColumnDefs":[{ "bSortable": false, "aTargets": [ 4,5 ] }],
			"sPaginationType": "full_numbers",
			"bJQueryUI": true,
		});
		
	});

</script>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			<h1><?php echo JText::_('MEDIA_MANAGER_TITLE'); ?></h1>
		</div>
	</div>
</div>

<div class="view-wrapper<?php echo ' '.$this->columns_class; ?>">
	
	<?php if( $this->left_modules ){ ?>
	<div id="left">
		<div class="inner">
		<?php echo $this->left_modules; ?>
		</div>
	</div>
	<?php } ?>
	
	<div id="middle">

		<div class="inner">
	
			<div id="tabs">
				
				<ul>
				
					<li><a href="#general-tab"><?php echo JText::_( 'General' ); ?></a></li>
				
				<?php if( $this->tabs->client ){ ?>
					<li><a href="#client-tab"><?php echo JText::_( 'Client Images' ); ?></a></li>
				<?php } ?>
				
				<?php if( $this->tabs->tmpl ){ ?>
					<li><a href="#tmpl-tab"><?php echo JText::_( 'Template Images' ); ?></a></li>
				<?php } ?>
				
				</ul>
				
				<div id="general-tab">
				
					<?php
					$this->assets = $this->tabs->general;
					echo $this->loadTemplate( 'asset-table' );
					?>
						
				</div>
				
				<?php if( $this->tabs->client ){ ?>
				
				<div id="client-tab">
				
					<?php
					$this->assets = $this->tabs->client;
					echo $this->loadTemplate( 'asset-table' );
					?>
							
					<script type="text/javascript">
						$(document).ready(function(){
			
							$('#client-assets').tablesorter();
							
						});
					</script>
					
				</div>
				
				<?php } ?>
				
				<?php if( $this->tabs->tmpl ){ ?>
				
				<div id="tmpl-tab">
				
					<?php
					$this->assets = $this->tabs->tmpl;
					echo $this->loadTemplate( 'asset-table' );
					?>
					
					<script type="text/javascript">
						$(document).ready(function(){
			
							$('#tmpl-assets').tablesorter();
							
						});
					</script>
					
				</div>
				
				<?php } ?>
			
			</div>
			
			<div id="asset-uploader">
			
				<?php echo $this->loadTemplate( 'asset-uploader' ); ?>
			
			</div>
		
		</div>
		
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_ASSET'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_ASSET' ); ?></span></a>

</div>