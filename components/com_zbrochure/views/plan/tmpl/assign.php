<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

//print_r( $this->plan );
?>

<form id="planform" action="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=savePlan' ); ?>" method="post" target="_parent">

	<div class="form-row edit-plan" style="margin:0">
		
		<div class="form-row" style="margin:0 0 20px 0">
			<p>If this plan is <strong>NOT</strong> assigned to a brochure it is a pre-defined plan. Assigning this plan to a brochure will make this plan <strong>ONLY</strong> available for the specified brochure.</p>
		</div>
		
		<div style="float:left;width:50%">
					
			<div class="form-row">
				<label><?php echo JText::_( 'PLAN_NAME' ); ?></label>
				<textarea name="plan_name" id="plan_name" class="inputbox" style="width:238px;height:20px"><?php echo $this->plan->plan_name; ?></textarea>
			</div>
			
			<div class="form-row">
				<label><?php echo JText::_( 'PACKAGE' ); ?></label>
				<?php echo $this->packages; ?>
			</div>
			
			<div class="form-row">
				<label><?php echo JText::_( 'BROCHURE' ); ?></label>
				<?php echo $this->brochures; ?>
			</div>
			
		</div>

		<div style="float:left;width:50%">
			
			<div class="theme-color-list-table">
				
				<ul>
					
					<li>
						<strong><?php echo JText::_( 'CREATED' ); ?></strong>&nbsp;<?php echo JHTML::_( 'date', $this->plan->plan_created,'D, M j, Y' ); ?>
					</li>
					
					<li>
						<strong><?php echo JText::_( 'CREATED_BY' ); ?></strong>&nbsp;<abbr title="<?php echo $this->plan->author_name; ?>"><?php echo $this->plan->author_username; ?></abbr>
					</li>
					
					<?php if( $this->plan->version > 1 ){ ?>
					
					<li>
						<strong><?php echo JText::_( 'MODIFIED' ); ?></strong>&nbsp;<?php echo JHTML::_( 'date', $this->plan->plan_modified,'D, M j, Y' ); ?>
					</li>
					
					<li>
						<strong><?php echo JText::_( 'MODIFIED_BY' ); ?></strong>&nbsp;<abbr title="<?php echo $this->plan->editor_name; ?>"><?php echo $this->plan->editor_username; ?></abbr>
					</li>
					
					<?php } ?>
					
					<li>
						<strong><?php echo JText::_( 'VERSION' ); ?></strong>&nbsp;<?php echo $this->plan->version; ?>
					</li>
					
				</ul>
			
			</div>
			
		</div>
		
		<div class="clear"><!-- --></div>
		
	</div>
	
	<div class="btns-container" style="padding:10px 20px;text-align:right">
		<button class="btn cancel-btn" onclick="window.parent.edit_plan_dialog.dialog('close'); return false;" type="button"><span><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
		<button class="btn save-btn icon-btn" type="submit"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
	</div>
	
	<input type="hidden" name="plans[0][plan_id]" value="<?php echo $this->plan->plan_id; ?>" />
	<!-- <input type="hidden" name="package_id" value="<?php echo $this->plan->package_id; ?>" /> -->
	<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
	<input type="hidden" name="return_to" value="plans" />
	
</form>