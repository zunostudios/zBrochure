<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

JHTML::script( 'zbrochure.build.js', 'components/com_zbrochure/assets/js/' );

?>
<form action="<?php echo JRoute::_( 'index.php?option=com_zbrochure' ); ?>" name="bro-setup" id="bro-setup" method="post">

	<div id="sub-header">
		
		<div class="wrapper">
		
			<div id="top-bar">
			
				<h1><?php echo JText::_('NEW_BROCHURE_TITLE'); ?></h1>
				
				<div class="btn-container">
					<a href="javascript:void(0);" onclick="saveBtn('cancel')" id="cancel-btn" class="btn cancel-btn"><span><?php echo JText::_('BROCHURE_BTN_CANCEL'); ?></span></a>
					<a href="javascript:void(0);" onclick="saveBtn('saveBrochure')" id="save-btn" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_('BROCHURE_BTN_SAVE'); ?></span></a>
					<a href="javascript:void(0);" onclick="saveBtn('buildBrochure')" id="build-bro-btn" class="btn build-bro-btn"><span><?php echo JText::_('BROCHURE_BTN_BUILD'); ?></span></a>
				</div>
							
			</div>
		
		</div>
		
	</div>

	<div class="setup-wrapper content-area">
		
		<?php echo $this->loadTemplate( 'left-sidebar' ); ?>
		
		<!-- Start Middle Content -->
		<div id="main-preview" class="main-content">
		
			<div id="template-preview"><!-- --></div>	
			<div class="clear">&nbsp;</div>
			
		</div>
		<!-- End Middle Content -->
		
		<?php echo $this->loadTemplate( 'right-sidebar' ); ?>
		
		<div class="clear"><!-- --></div>
		
	</div>
	
	<input type="hidden" name="bro_tmpl" id="bro_tmpl" value="" />
	<input type="hidden" name="bro_cover" id="bro_cover" value="" />
	<input type="hidden" name="bro_theme" id="bro_theme" value="" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="view" id="view" value="" />
	<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
	
</form>