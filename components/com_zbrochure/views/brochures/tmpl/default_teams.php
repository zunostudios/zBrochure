<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="client-container">

	<?php foreach( $this->teams as $team ){ ?>
	<h3 class="group-name">
		<?php echo $team->title; ?>
	</h3>
	
	<ul class="brochures">
	
	<?php 
		$i = 0; foreach( $this->brochures as $brochure ){
				
			$preview_link	= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id.'&layout=preview&tmpl=modal' );
			$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id );
			$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateBrochure&id='.$brochure->bro_id);
			$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteBrochure&id='.$brochure->bro_id);
			
			if( $team->id == $brochure->team_id ){
		
				$link	= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id );
	
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
							
							<div class="bro-thumb-img" style="background-image:url(<?php if( $brochure->bro_page_thumb ){
								echo JURI::base().'media'.DS.'zbrochure'.DS.'docs'.DS.$brochure->bro_id.DS.$brochure->bro_page_thumb;
							}else{	
								echo JURI::base().'media'.DS.'zbrochure'.DS.'docs'.DS.'no-thumb.gif';	
							}?>);"></div>
							
						</div>

						<span class="bro-thumb-title"><?php echo $brochure->bro_title; ?></span>

					</div>
				</li>
				
			<?php $i++; }
		
		}
		
		if( $i == 0 ){
		
			echo '<li>No Brochures</li>';
		
		}
	
	?>
	
	</ul>
	
	<?php } ?>
	
	<h3 class="group-name"><?php echo JText::_( 'NO_TEAM_TITLE' ); ?></h3>
	
	<ul class="brochures">
	
	<?php 
		$i = 0; foreach( $this->brochures as $brochure ){
				
			$preview_link	= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id.'&layout=preview&tmpl=modal' );
			$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id );
			$duplicate_link	= JRoute::_( 'index.php?option=com_zbrochure&task=duplicateBrochure&id='.$brochure->bro_id);
			$delete_link	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteBrochure&id='.$brochure->bro_id);
			
			if( $brochure->team_id == 0 ){
		
				$link	= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure->bro_id );
	
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
							
							<div class="bro-thumb-img" style="background-image:url(<?php if( $brochure->bro_page_thumb ){
								echo JURI::base().'media'.DS.'zbrochure'.DS.'docs'.DS.$brochure->bro_id.DS.$brochure->bro_page_thumb;
							}else{	
								echo JURI::base().'media'.DS.'zbrochure'.DS.'docs'.DS.'no-thumb.gif';	
							}?>);"></div>
							
						</div>

						<span class="bro-thumb-title"><?php echo $brochure->bro_title; ?></span>

					</div>
				</li>
				
			<?php $i++; }
		
		}
	
	?>
	
	</ul>
	
	
</div>