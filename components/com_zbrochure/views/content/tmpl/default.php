<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

$keywordArray=array();

foreach( $this->keywords as $keyword ){
		$keywordArray[$keyword->keyword_id] = $keyword->keyword;
}

?>
<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			
			<h1><?php echo JText::_('CONTENT_TITLE'); ?></h1>
			
			<div class="btn-container">
				<a href="javascript:void(0);" class="btn add-btn icon-btn" onclick="newContent();"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_('ADD_CONTENT_BTN'); ?></span></a>
			</div>
			
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
		
			<table class="sortable" cellpadding="0" cellspacing="0" width="100%" border="1">
				
				<thead>
					<tr bgcolor="#CCCCCC">
						<th width="20"><?php echo JText::_('TABLE_LIST_ID');?></th>
						<th><?php echo JText::_('TABLE_LIST_CONTENTTITLE');?></th>
						<th width="100"><?php echo JText::_('TABLE_LIST_CONTENTPREVIEW');?></th>
						<th><?php echo JText::_('TABLE_LIST_CATEGORY');?></th>
						<th><?php echo JText::_('TABLE_LIST_KEYWORDS');?></th>
						<th><?php echo JText::_('TABLE_LIST_CREATED');?></th>
						<th width="120">&nbsp;</th>
					</tr>
				</thead>
				
				<tbody>
				
					<?php foreach( $this->data as $content ){ ?>
					<tr>
						<td align="center"><?php echo $content->id; ?></td>
						<td><a href="javascript:void(0);" onclick="editContent(<?php echo $content->id; ?>, this);" id="contentTitle<?php echo $content->id; ?>"><?php echo $content->title; ?></a></td>
						<td align="center">
							<a href="javascript:void(0);" id="open-<?php echo $content->id; ?>" class="btn preview-btn" title="Preview <?php echo $content->title; ?>"><span><?php echo JText::_('PREVIEW_BTN_GENERAL'); ?></span></a>
							<pre style="display:none" id="contentData<?php echo $content->id; ?>"><?php echo htmlspecialchars($content->data); ?></pre>
							
							<div id="dialog-<?php echo $content->id; ?>" title="<?php echo $content->title; ?>">
								<div class="preview-modal"><?php echo $content->data; ?></div>
							</div>
							
							<script type="text/javascript">
			
								$(document).ready(function(){
								
									$( "#open-<?php echo $content->id; ?>" ).click(function(){
										$("#dialog-<?php echo $content->id; ?>").dialog('open');
									});
			
									$( "#dialog-<?php echo $content->id; ?>" ).dialog({
										autoOpen: false,
										resizable: true,
										width:600,
										modal: true,
										open: function( event, ui ){
											disable_scroll();
										},
										close: function( event, ui ){
											enable_scroll();
										}	
									});
				
								});
						
							</script>
							
						</td>
						<td align="center"><?php echo $content->cat_name; ?><input type="hidden" id="contentCat<?php echo $content->id; ?>" value="<?php echo $content->catid; ?>" /></td>
						<td><?php if( $content->keywords ){ ?><ul id="contentKeywords<?php echo $content->id; ?>"><?php $kws = json_decode($content->keywords); foreach( $kws as $keyword ){ echo '<li>'.$keywordArray[$keyword].'</li>'; } ?></ul><?php } ?></td>
						<td id="contentCreated<?php echo $content->id; ?>" align="center"><?php echo JText::sprintf(JHtml::_('date',$content->content_created, 'm.d.Y' )); ?></td>
						
						<td align="center">
							
							<?php 
								$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteContent&id='.$content->id.'&Itemid='.$this->item_menu_itemid );
								$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateContentRow&id='.$content->id.'&Itemid='.$this->item_menu_itemid ); 
							?>
							
							<a href="javascript:void(0);" onclick="editContent(<?php echo $content->id; ?>, this);" class="btn edit-icon-btn icon-only-btn" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?></span></a>
							
							<a href="<?php echo $duplicate_link; ?>" class="btn duplicate-icon-btn icon-only-btn" style="margin:0 10px;" title="<?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?></span></a>
																
							<a href="<?php echo $delete_link; ?>" class="btn delete-icon-btn icon-only-btn delete-content" title="<?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></a>
																
						</td>
			
					</tr>
					<?php } ?>
				
				</tbody>
				
			</table>
			
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<!-- Jquery UI Modal -->
<div id="dialog-new" title="<?php echo JText::_( 'CONTENT_EDIT_DIALOG_TITLE' ); ?>">

	<form id="contentEditor" action="index.php?option=com_zbrochure&task=saveContent" method="post">
		
		<div class="text-container">
			
			<div class="form-row add-padding package-container-header">
		
				<div class="form-row fl" style="width:33%">
					<div style="padding:0 20px 0 0">
						<label for="title"><?php echo JText::_( 'CONTENT_LABEL_CONTENT_TITLE' ); ?>:</label>
						<input class="inputbox" id="title" name="title" style="width:95%" />
					</div>
				</div>
			
				<div class="form-row fl" style="width:33%">
					<div style="padding:0 20px">
						<label for="categories"><?php echo JText::_( 'CONTENT_LABEL_CAT' ); ?>:</label>
						<?php echo $this->categories; ?>
					</div>
				</div>
				
				<div class="form-row fl" style="width:33%">
					<div style="padding:0 0 0 20px">
						<label for="keywords"><?php echo JText::_( 'CONTENT_LABEL_KEYWORDS' ); ?>:</label>
						<select id="keywords" class="inputbox" name="keywords[]"></select>
					</div>
				</div>
				
				<div class="clear"><!-- --></div>
				
			</div>
		
			<div>
				<textarea class="inputbox" style="height:300px;width:100%" id="editor" name="data"></textarea>
			</div>
		
		</div>
		
		<div class="btn-container">
			<div class="modal-bottom-fixed-inner">
			
				<button class="var-btn btn build-bro-btn fl" type="button"><span><?php echo JText::_( 'AVAILABLE_VARIABLES' ); ?></span></button>
			
				<button type="button" class="btn cancel-btn icon-btn" onclick="$('#editor').val(' ');edit_dialog.dialog('close');"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
				<button type="button" class="btn save-btn icon-btn" id="save-btn" onclick="saveContent();edit_dialog.dialog('close');"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			</div>
		</div>
		
		<input type="hidden" name="id" id="id" />
		
	</form>

</div>
<!-- End Jquery UI Modal -->

<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_CONTENT'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_CONTENT' ); ?></span></a>

</div>

<div id="vars-dialog" title="Available Variables">
	
	<?php echo $this->vars_list; ?>
	
</div>

<?php echo $this->loadTemplate('scripts'); ?>