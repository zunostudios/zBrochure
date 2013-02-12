<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="bro-options">
	
	<span class="bro-options-close">Open/Close</span>
	
	<div class="btn-container">
		
		<div class="bro-option">
	
			<div class="inner">
				<a href="javascript:void(0);" zpage="<?php echo $this->page->bro_page_id; ?>" zorder="<?php echo $this->page->bro_page_order; ?>" zlayout="<?php echo $this->page->bro_page_layout; ?>" class="changelayout-btn" title="Click here to change the layout for page <?php echo $this->page->page_number; ?>."><?php echo JText::_( 'CHANGE_LAYOUT' ); ?></a>
			</div>
			
		</div>
		
		<div class="bro-option">
	
			<div class="inner">
				<a rel="<?php $this->page->bro_page_id; ?>" href="javascript:void(0);" id="pagetitle-btn-<?php echo $this->page->page_number; ?>" class="pagetitle-btn" title="<?php echo $this->page->bro_page_name; ?>"><?php echo JText::_( 'PAGE_TITLE' ); ?></a>
			</div>
			
		</div>
		
		<div class="bro-option">
	
			<div class="inner">
				<h4><?php echo $this->page->page_number; ?></h4>
				<label><input type="checkbox" onchange="alert('show page number')" /><br /><?php echo JText::_( 'SHOW_PAGE_NUMBER' ); ?></label>
			</div>
			
		</div>
	
	</div>

</div>
