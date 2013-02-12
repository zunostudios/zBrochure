<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<!-- Start Right Sidebar -->
<div id="right-sidebar" class="sidebar">
	
	<h3><span class="inner"><?php echo JText::_('TEMPLATE_COVER_PAGES'); ?></span></h3>
	<div id="tmpl-layout-covers-holder" class="sidebar-container">
		<!-- -->
	</div>
	
	<h3><span class="inner"><?php echo JText::_('TEMPLATE_VARIABLES'); ?></span></h3>
	<div class="sidebar-container">
		<div class="sidebar-container-inner">	
			<label>Start date<br />
				<input type="text" class="inputbox" name="bro_variables[start_date]" style="width:146px" />
			</label>
			
			<label>End date<br />
				<input type="text" class="inputbox" name="bro_variables[end_date]" style="width:146px" />
			</label>
		</div>		
	</div>
	
</div>
<!-- End Right Sidebar -->