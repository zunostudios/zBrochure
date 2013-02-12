<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

//JHTML::stylesheet( 'jquery.lightbox-0.5.css', 'components/com_zbrochure/assets/css/' );
//JHTML::stylesheet( 'fcbkstyle.css', 'components/com_zbrochure/assets/css/' );

//JHTML::script( 'jquery.lightbox-0.5.min.js', 'components/com_zbrochure/assets/js/' );
//JHTML::script( 'jquery.fcbkcomplete.min.js', 'components/com_zbrochure/assets/js/' );
JHTML::script( 'jquery.equalHeights.js', 'components/com_zbrochure/assets/js/' );

?>
<form name="mediamanager" id="mediamanager" action="index.php?option=com_zbrochure&task=saveBlock" method="post" target="_parent">

<div class="asset-tabs form-row">

		<div class="form-row" style="padding:10px 20px 0 20px">
			
			<div style="float:left"><?php echo count( $this->assets )?></div>
			
			<div class="fr">
		
				<label for="asset-filter" style="margin:0">Search:&nbsp;&nbsp;
				<input type="text" name="asset-filter" id="asset-filter" class="inputbox" style="width:200px" onkeyup="filterAssets(this.value);" /></label>
				<!-- <a href="javascript:void(0)" onclick="changeTask('filterAssets')" class="btn">Go</a> -->
			</div>
			<div class="clear"><!-- --></div>
		</div>
		
		<div id="asset-tabs" style="overflow:hidden;margin-bottom:150px">
		
			<?php if( $this->tabs->client || $this->tabs->tmpl ){ ?>
				
			<ul>
				<li><a href="#general-tab"><?php echo JText::_( 'General' ); ?></a></li>
				<?php if( $this->tabs->client ){ ?><li><a href="#client-tab"><?php echo JText::_( 'Client Images' ); ?></a></li><?php } ?>
				<?php if( $this->tabs->tmpl ){ ?><li><a href="#tmpl-tab"><?php echo JText::_( 'Template Images' ); ?></a></li><?php } ?>
			</ul>
			
			<div id="general-tab" class="modal-media-group">
				<?php $this->assets = $this->tabs->general;
				echo $this->loadTemplate( 'asset-grid' ); ?>
			</div>
			
			<?php if( $this->tabs->client ){ ?>
			<div id="client-tab" class="modal-media-group">
				<?php $this->assets = $this->tabs->client;
				echo $this->loadTemplate( 'asset-grid' ); ?>		
			</div>
			<?php } ?>
			
			<?php if( $this->tabs->tmpl ){ ?>
			<div id="tmpl-tab" class="modal-media-group">
				<?php $this->assets = $this->tabs->tmpl;
				echo $this->loadTemplate( 'asset-grid' ); ?>
			</div>
			<?php } ?>
		
		<?php }else{
			
			$this->assets	= $this->tabs->general;
			$this->block_id	= $this->_block_id;
			$this->bro_id	= $this->_bro_id;
			$this->img_id	= $this->_img_id;
			echo $this->loadTemplate( 'asset-grid' ); ?>
			
		<?php } ?>
		
		</div>
		
	</div>
	
	<div class="modal-bottom-fixed">
		
		<div class="modal-bottom-fixed-inner">
		
			<div id="asset-desc"><div class="asset-desc-inner"></div></div>
			
			<div class="btn-container">
				<button class="btn cancel-btn" onclick="location.reload(); return false;" type="button"><span>Cancel</span></button>
				<button class="btn save-btn" type="submit"><span>Save</span></button>
			</div>
			
			<div class="clear"><!-- --></div>
			
		</div>
	
	</div>
	
	<input type="hidden" name="content[id]" value="<?php echo $this->content_block->content_block_current_version; ?>" />
	<input type="hidden" name="content[data]" value="<?php echo JRequest::getInt( 'data', 0 ); ?>" id="image_asset_id" />
	<input type="hidden" name="content[reset]" value="0" id="image_reset" />
	
	<input type="hidden" name="content_block_id" value="<?php echo $this->content_block->content_block_id; ?>" />
	<input type="hidden" name="content_block_type" value="<?php echo $this->content_block->content_block_type; ?>" />
	<input type="hidden" name="content_bro_id" value="<?php echo $this->content_block->content_bro_id; ?>" />
	
	<input type="hidden" name="view" value="brochure" />
	
	<input type="hidden" name="bro_id" value="<?php echo $this->content_block->content_bro_id; ?>" />
	<input type="hidden" name="bro_page_id" value="<?php echo $this->content_block->content_page_id; ?>" />
	<input type="hidden" name="bro_page_order" value="<?php echo JRequest::getInt( 'bro_page_order', 0 ); ?>" />
	
</form>
	
<script type="text/javascript">
	$(document).ready(function(){
		<?php if( $this->tabs->client || $this->tabs->tmpl ){ ?>
		$('#asset-tabs').tabs();
		<?php } ?>
		
		thumbnailActions();
		
	});
	
	function filterAssets(term){
		
		if( term.length >= 3 ){
			$('.asset-item-grid').css('display','none');
			
			$('.asset-item-grid').each( function(){
			
				var keywords = $(this).find('div.keywords span').text();
				
				var n = keywords.match(term); 
				
				if( n != null){
				
					$(this).css('display','block');
					
				}
				
			
			});
			
		}else{
			$('.asset-item-grid').css('display','block');
		}
		
	}
</script>