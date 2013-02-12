<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<table id="general-assets" class="sortable" cellpadding="0" cellspacing="0" border="1" width="100%">
			
	<thead>
		<tr bgcolor="#CCC">
			<th align="center" width="5%">id</th>
			<th align="center" width="10%">thumb</th>
			<th align="center">title</th>
			<th align="center">date</th>
			<th align="center">keywords</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach( $this->assets as $data ){ ?>
		<tr>
			<td align="center"><?php echo $data->asset_id; ?></td>
			
			<td>
				<div style="height:50px;overflow:hidden;width:50px">
					<a href="<?php echo $this->assets_dir.'full/'.$data->asset_file; ?>" class="modal" title="<?php echo $data->asset_title; ?>"><img src="<?php echo $this->assets_dir.'full/'.$data->asset_file; ?>" width="50px" /></a>
				</div>
			</td>
			
			<td><span style="color:#000"><?php echo $data->asset_title; ?></span><br /><span style="font-size:11px"><?php echo $data->asset_file; ?></span></td>
			
			<td><?php echo $data->created; ?></td>
			
			<td>
				<ul style="list-style:disc inside">
				<?php foreach( $this->keywords as $keywords ){ ?>
					<?php if( $keywords->aid == $data->asset_id ){ ?>
						<li style="margin:0"><span style="font-size:11px"><?php echo $keywords->keyword; ?></span></li>
					<?php } ?>
				<?php } ?>
				</ul>
			</td>
			
			<td align="center">
				<button class="btn edit-btn" onclick="window.location = 'index.php?option=com_zbrochure&view=asset&id=<?php echo $data->asset_id; ?>';return false;">
					<span>Edit</span>
				</button>
				
				<button class="btn delete-btn" onclick="asset = <?php echo $data->asset_id; ?>; $('#dialog-confirm').dialog('open'); return false;">
					<span>Delete</span>
				</button>
				
			</td>
		</tr>
		<?php } ?>
	</tbody>
	
</table>