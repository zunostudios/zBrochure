<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<table id="general-assets" class="sortable" cellpadding="0" cellspacing="0" border="1" bordercolor="#D5D5D5" width="100%">
			
	<thead>
		<tr>
			<th align="center" width="5%"><?php echo JText::_('TABLE_LIST_ID'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('TABLE_LIST_THUMBNAIL'); ?></th>
			<th align="center"><?php echo JText::_('TABLE_LIST_TITLE'); ?></th>
			<th align="center"><?php echo JText::_('TABLE_LIST_DATE'); ?></th>
			<th align="center"><?php echo JText::_('TABLE_LIST_KEYWORDS'); ?></th>
			<th width="15%">&nbsp;</th>
		</tr>
	</thead>
	
	<tbody>
		<?php 
		
			foreach( $this->assets as $data ){ 
			
				$image = $this->assets_dir.'thumbnails/'.$data->asset_file;
				$edit_link		= JRoute::_( 'index.php?option=com_zbrochure&view=asset&id='.$data->assetid.'&Itemid='.$this->list_menu_itemid );
				$delete_link 	= JRoute::_( 'index.php?option=com_zbrochure&task=deleteAsset&id='.$data->assetid.'&Itemid='.$this->list_menu_itemid );
		
		?>
		
		<tr>
			<td align="center"><?php echo $data->assetid; ?></td>
			
			<td align="center">
				<div style="height:50px;overflow:hidden;width:50px">
				
					<?php if (file_exists($image)) { ?>
					
						<a href="<?php echo $this->assets_dir.'full/'.$data->asset_file; ?>" class="modal" title="<?php echo $data->asset_title; ?>">
							<img src="<?php echo $this->assets_dir.'thumbnails/'.$data->asset_file; ?>" width="50px" />
						</a>
					
					<?php }else{ ?>
						<a href="<?php echo $this->assets_dir.'full/'.$data->asset_file; ?>" class="modal" title="<?php echo $data->asset_title; ?>">
							<div style="background-color:#676767;height:50px;width:50px"><!--   --></div>
						</a>
					<?php } ?>
				
				</div>
			</td>
			
			<td>
				<?php if( !empty($data->asset_title) ){ ?>
				<span class="file-title"><?php echo $data->asset_title; ?></span><br />
				<?php }else{ ?>
				<span class="file-title"><?php echo JText::_('NO_TITLE'); ?></span><br />
				<?php }?>
				<span style="font-size:11px"><a href="<?php echo $edit_link; ?>" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ).' '.$data->asset_file; ?>"><?php echo $data->asset_file; ?></a></span>
			</td>
			
			<td><?php echo $data->created; ?></td>
			
			<td>
				<ul class="asset-keywords">
				<?php foreach( $this->keywords as $keywords ){ ?>
					<?php if( $keywords->aid == $data->assetid ){ ?>
						<li><span style="font-size:11px"><?php echo $keywords->keyword; ?></span></li>
					<?php } ?>
				<?php } ?>
				</ul>
			</td>
			
			<td align="center">
			
				<a href="<?php echo $edit_link; ?>" class="btn edit-icon-btn icon-only-btn" title="<?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'EDIT_BTN_GENERAL' ); ?></span></a>
				
				<!-- <a href="<?php echo $duplicate_link; ?>" class="btn duplicate-icon-btn icon-only-btn" style="margin:0 10px;" title="<?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DUPLICATE_BTN_GENERAL' ); ?></span></a> -->
				
				<a href="<?php echo $delete_link; ?>" class="btn delete-icon-btn icon-only-btn delete-asset" title="<?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?>"><span><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></a>
				
			</td>
		</tr>
		<?php } ?>
	</tbody>
	
</table>