<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<form id="asset-upload-form" action="input.php?option=com_zbrochure&task=saveAssets" method="post" enctype="multipart/form-data">

	<div id="upload-inner">
	
		<div class="form-column fl" style="width:33%">
		
			<div class="inner">
		
				<div class="form-group">
								
					<div class="form-row">
						<div class="client-logo-preview">
							<div id="fullcolor" class="inner" style="background-image:url(<?php echo $provider_dir.DS.$this->data->provider_logo; ?>)"></div>
						</div>

						<div id="file-uploader">		
							<noscript>			
								<p>Please enable JavaScript to use file uploader.</p>
								<!-- or put a simple form for upload here -->
							</noscript>         
						</div>					

					</div>
					
					<div class="form-row">
							<div class="btn-container">
		
								<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
								
							</div>
					</div>
				
				</div>

			</div>
		
		</div>
	
		<div class="fr" style="width:66%">
			
			<div class="inner">
					
				<table id="assetTable" class="sortable" cellpadding="0" cellspacing="0" border="1" width="100%">
					
					<thead>
						<tr bgcolor="#CCC">
							<th align="center" width="5%"><?php echo JText::_('TABLE_LIST_ID'); ?></th>
							<th align="center" width="10%"><?php echo JText::_('TABLE_LIST_THUMB'); ?></th>
							<th align="center"><?php echo JText::_('TABLE_LIST_FILENAME'); ?></th>
							<th align="center"><?php echo JText::_('TABLE_LIST_TITLE'); ?></th>
							<th align="center"><?php echo JText::_('TABLE_LIST_KEYWORDS'); ?></th>
						</tr>
					</thead>
					
					<tbody id="assetBody">
						
					</tbody>
					
				</table>
				
			</div>
			
		</div>
		
		<div class="clear"><!--   --></div>
		
	</div>

</form>