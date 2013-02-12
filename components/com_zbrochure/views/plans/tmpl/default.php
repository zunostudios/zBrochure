<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			
			<h1><?php echo JText::_( 'BENEFIT_PLANS' ); ?></h1>
					
		</div>
	</div>
</div>

<div class="view-wrapper<?php echo ' '.$this->columns_class; ?>">
	
	<?php if( $this->left_modules ){ ?>
	<div id="left">
		<div class="inner">
		<?php echo $this->left_modules; ?>
		</div>
	</div>
	<?php } ?>
	
	<div id="middle">

		<div class="inner">
			
			<p style="margin:0 0 20px 0;padding:0 0 20px 0;border-bottom:1px solid #D6D6D6"><strong>This area is ONLY to assign plans to packages or brochures.</strong><br />If you want to create a new plan it must be done from a package <a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=packages' ); ?>">here</a>.</p>
			
			<table id="clients-sort" class="sortable" cellpadding="0" cellspacing="0" border="1" width="100%">
				<thead>
					<tr>
						<th width="50"><?php echo JText::_('TABLE_LIST_ID'); ?></th>
						<th width="200"><?php echo JText::_('TABLE_LIST_PLANNAME'); ?></th>
						<th><?php echo JText::_('TABLE_LIST_PACKAGE'); ?></th>
						<th width="100"><?php echo JText::_('TABLE_LIST_BROID'); ?></th>
						<th width="100">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $this->plans as $plan ){ ?>
					<tr>
						<td align="center"><?php echo $plan->plan_id; ?></td>
						<td><?php echo $plan->plan_name; ?></td>
						<td><a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$plan->package_id ); ?>" class="edit-package-link"><?php echo $plan->package_name; ?></a></td>
						<td><?php if($plan->brochure_id == 0){echo 'Pre-defined';}else{echo $plan->brochure_id;} ?></td>
						<td align="center">
	
							<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=plan&id='.$plan->plan_id.'&pid='.$data->package_id ); ?>" class="edit-plan-link btn edit-icon-btn icon-only-btn"><span><?php echo JText::_( 'EDIT_PLAN' ); ?></span></a>
	
							<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=deletePlan&type=plan&id='.$plan->plan_id.'&pid='.$data->package_id ); ?>" class="delete-plan-link btn delete-icon-btn icon-only-btn"><span><?php echo JText::_( 'DELETE_PLAN' ); ?></span></a>

						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		
		</div>
		
	</div>

	<div class="clear"><!-- --></div>

</div>

<div id="delete-plan-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_PLAN'); ?>">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p style="text-align:center"><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" id="delete-plan-modal-btn" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_PLAN' ); ?></span></a>

</div>
<?php
echo $this->loadTemplate( 'scripts' );
?>