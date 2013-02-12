<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<!-- Start Left Sidebar -->
<div id="left-sidebar" class="sidebar page-thumbnails">
	
	<h3><span class="inner"><?php echo JText::_('BROCHURE_TITLE'); ?></span></h3>
	<div class="sidebar-container">
		<div class="sidebar-container-inner">
			
			<div class="form-row">
			
				<input type="text" class="inputbox" name="bro_title" id="bro_title" style="width:146px" />
			
			</div>
			
			<div class="form-row">

				<label for="bro_team_id"><?php echo JText::_('TEAM_LABEL'); ?></label>
				<?php echo $this->team_list; ?>

			</div>
			
		</div>
	</div>
	
	<!--
	<h3><span class="inner"><?php echo JText::_('NUMBER_OF_PAGES'); ?></span></h3>
	<div class="sidebar-container">
		<div class="sidebar-container-inner">
			<select name="bro_pages" id="bro_pages" class="inputbox">
				<option value="2">2</option>
				<option value="4">4</option>
				<option value="8">8</option>
				<option value="12">12</option>
				<option value="16">16</option>
			</select>
		</div>
	</div>
	-->
	
	<h3><span class="inner"><?php echo JText::_('CLIENT_NAME'); ?></span></h3>
	<div class="sidebar-container">
		<div class="sidebar-container-inner">
			<?php echo $this->clients; ?>
		</div>
	</div>
	
	<!-- Start Themes -->
	<h3><span class="inner"><?php echo JText::_('THEMES'); ?></span></h3>
	
	<div id="themes-container" class="sidebar-container">
		
		<h4><?php echo JText::_('THEMES_CLIENT'); ?></h4>
		<div id="client-themes"></div>
		
		<h4><?php echo JText::_('THEMES_GENERAL'); ?></h4>
		<div id="themes">
			<?php echo $this->themes; ?>
		</div>
		
	</div>
	<!-- End Themes -->
	
	<!-- Start Templates -->
	<h3><span class="inner"><?php echo JText::_('TEMPLATES'); ?></span></h3>
	
	<div id="templates" class="sidebar-container">
		
		<ul class="carousel">
		
		<?php foreach( $this->templates as $tmpl ){
			
			echo '<li class="tmpl-layout tmpl-th" zid="'.$tmpl->tmpl_id.'">';
			echo JHTML::_( 'image.site', $tmpl->tmpl_thumbnail, '/media/zbrochure/images/tmpl/'.$tmpl->tmpl_id.'/', '', '', $tmpl->tmpl_name . ' thumbnail', '' );
			echo '</li>';
		
		} ?>
		
		</ul>
		
	</div>
	<!-- End Templates -->
	
</div>
<!-- End Left Sidebar -->