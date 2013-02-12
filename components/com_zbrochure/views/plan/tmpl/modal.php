<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

$rowcount 		= 1;

$pid			= JRequest::getInt( 'pid' );
$plan_id		= JRequest::getInt( 'id' );

$layout			= JRequest::getCmd( 'layout', '' );
$tmpl			= JRequest::getCmd( 'tmpl', '' );
$block_id		= JRequest::getInt( 'block_id', 0 );
$brochure_id	= JRequest::getInt( 'brochure_id', 0 );

if( !empty( $pid ) ){ ?>
<form id="planform" action="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=savePlan' ); ?>" method="post" target="_parent">

	<div class="form-row edit-plan" style="padding:0;margin:0">
		
		<div id="plan-table-container" style="overflow:scroll;padding:20px 20px;height:360px">
			<?php echo $this->table; ?>
		</div>
		
	</div>
	
	<div class="btns-container" style="padding:10px 20px;text-align:right">
		
		<div style="float:left">
		<?php echo $this->plan_select; ?>
		</div>
		
		<div style="display:inline-block;padding:0 10px 0 0">
			<label for="plan_ordering"><?php echo JText::_( 'FORM_LABEL_PLAN_ORDERING' ); ?></label>
			<input type="text" id="plan_ordering" name="plan_ordering" class="inputbox" style="width:20px" value="<?php echo $this->plan->plan_ordering; ?>" />
		</div>
		
		<button class="btn cancel-btn" onclick="window.parent.edit_plan_dialog.dialog('close'); return false;" type="button"><span><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
		<button class="btn save-btn icon-btn" type="submit"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
	</div>
	
	<input type="hidden" name="plans[0][plan_id]" value="<?php echo JRequest::getInt( 'id' ); ?>" />
	<input type="hidden" name="package_id" value="<?php echo $pid; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt( 'Itemid' ); ?>" />
	<input type="hidden" name="return_to" value="package" />
	<input type="hidden" name="layout" value="<?php echo $layout; ?>" />
	<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" />
	<input type="hidden" name="block_id" value="<?php echo $block_id; ?>" />
	<input type="hidden" name="brochure_id" value="<?php echo $brochure_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $this->page_id; ?>" />
	
</form>
<?php } ?>

<?php if( !empty( $pid ) ){
	echo $this->loadTemplate( 'scripts' );
}

echo $this->loadTemplate( 'styles' );