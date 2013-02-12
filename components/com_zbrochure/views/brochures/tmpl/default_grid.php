<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<ul id="brochures">
<?php foreach( $this->brochures as $brochure ){
	
	$preview_link	= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id.'&layout=preview&tmpl=modal' );
	$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id );
	$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateBrochure&id='.$brochure->bro_id);
	$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteBrochure&id='.$brochure->bro_id);
	
	if( $brochure->bro_page_thumb ){
	
		$thumb_class = '';
		
	}else{
		
		$thumb_class = ' no-thumb';
		
	}
	
	?>
	<li>
		<div class="bro-thumb-container">
			
			<div class="bro-thumb-actions">
				
				<div class="bro-thumb-rollover">
					
					<a href="<?php echo $preview_link; ?>" title="Preview <?php echo $brochure->bro_title; ?>" class="preview-btn icon-btn btn">
						<span class="icon"><!-- --></span>
						<span class="btn-text"><?php echo JText::_( 'PREVIEW_BROCHURE' );?></span>
					</a>
					
					<a href="<?php echo $edit_link; ?>" title="Edit <?php echo $brochure->bro_title; ?>" class="edit-btn icon-btn btn">
						<span class="icon"><!-- --></span>
						<span class="btn-text"><?php echo JText::_( 'EDIT_BROCHURE' );?></span>
					</a>
					
					<a href="<?php echo $duplicate_link; ?>" title="Duplicate <?php echo $brochure->bro_title; ?>" class="duplicate-btn icon-btn btn">
						<span class="icon"><!-- --></span>
						<span class="btn-text"><?php echo JText::_( 'DUPLICATE_BROCHURE' );?></span>
					</a>
					
					<a href="<?php echo $delete_link; ?>" title="Delete <?php echo $brochure->bro_title; ?>" class="delete-btn icon-btn btn">
						<span class="icon"><!-- --></span>
						<span class="btn-text"><?php echo JText::_( 'DELETE_BROCHURE' );?></span>
					</a>
					
					<div class="bro-thumb-overlay"><!-- --></div>
					
				</div>
				
				<div class="bro-thumb-img" style="background-image:url(<?php if( $brochure->pages[0]->bro_page_thumb ){
					echo JURI::base().'media/zbrochure/docs/'.$brochure->bro_id.'/th/'.$brochure->pages[0]->bro_page_thumb;
				}else{	
					echo JURI::base().'media/zbrochure/docs/no-thumb.gif';	
				}?>);"></div>
				
			</div>
			
			<span class="bro-thumb-title"><?php echo $brochure->bro_title; ?></span>
			
		</div>
	</li>
<?php } ?>		
</ul>