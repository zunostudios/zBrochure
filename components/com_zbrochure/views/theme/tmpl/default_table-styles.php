<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="form-column horizontal-rows">
	
	<div class="inner">
		
		<div class="form-row">
			
			<label for="package_styles_table"><?php echo JText::_( 'table' ); ?></label>
			<input type="text" name="data[package_styles_table]" id="package_styles_table" class="inputbox" value="<?php echo ($this->theme_data->package_styles_table) ? $this->theme_data->package_styles_table : $this->_com_params->get( 'package_styles_table' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_thead_th"><?php echo JText::_( 'thead th' ); ?></label>
			<input type="text" name="data[package_styles_thead_th] " id="package_styles_thead_th" class="inputbox" value="<?php echo ($this->theme_data->package_styles_thead_th) ? $this->theme_data->package_styles_thead_th : $this->_com_params->get( 'package_styles_thead_th' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_th_td"><?php echo JText::_( 'td, th' ); ?></label>
			<input type="text" name="data[package_styles_th_td]" id="package_styles_th_td" class="inputbox" value="<?php echo ($this->theme_data->package_styles_th_td) ? $this->theme_data->package_styles_th_td : $this->_com_params->get( 'package_styles_th_td' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_alt_0_td"><?php echo JText::_( 'alt row 0 td' ); ?></label>
			<input type="text" name="data[package_styles_alt_0_td]" id="package_styles_alt_0_td" class="inputbox" value="<?php echo ($this->theme_data->package_styles_alt_0_td) ? $this->theme_data->package_styles_alt_0_td : $this->_com_params->get( 'package_styles_alt_0_td' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_alt_1_td"><?php echo JText::_( 'alt row 1 td' ); ?></label>
			<input type="text" name="data[package_styles_alt_1_td]" id="package_styles_alt_1_td" class="inputbox" value="<?php echo ($this->theme_data->package_styles_alt_1_td) ? $this->theme_data->package_styles_alt_1_td : $this->_com_params->get( 'package_styles_alt_1_td' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_td_package_label"><?php echo JText::_( 'td.package-label' ); ?></label>
			<input type="text" name="data[package_styles_td_package_label]" id="package_styles_td_package_label" class="inputbox" value="<?php echo ($this->theme_data->package_styles_td_package_label) ? $this->theme_data->package_styles_td_package_label : $this->_com_params->get( 'package_styles_td_package_label' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_td_plan_item"><?php echo JText::_( 'td.plan-item' ); ?></label>
			<input type="text" name="data[package_styles_td_plan_item]" id="package_styles_td_plan_item" class="inputbox" value="<?php echo ($this->theme_data->package_styles_td_plan_item) ? $this->theme_data->package_styles_td_plan_item : $this->_com_params->get( 'package_styles_td_plan_item' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_header_row_td"><?php echo JText::_( 'table-header-row td' ); ?></label>
			<input type="text" name="data[package_styles_header_row_td]" id="package_styles_header_row_td" class="inputbox" value="<?php echo ($this->theme_data->package_styles_header_row_td) ? $this->theme_data->package_styles_header_row_td : $this->_com_params->get( 'package_styles_header_row_td' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_tfoot_th"><?php echo JText::_( 'tfoot th' ); ?></label>
			<input type="text" name="data[package_styles_tfoot_th]" id="package_styles_tfoot_th" class="inputbox" value="<?php echo ($this->theme_data->package_styles_tfoot_th) ? $this->theme_data->package_styles_tfoot_th : $this->_com_params->get( 'package_styles_tfoot_th' ); ?>" />
			
		</div>
		
		<div class="form-row">
			
			<label for="package_styles_empty_cell"><?php echo JText::_( 'empty-cell' ); ?></label>
			<input type="text" name="data[package_styles_empty_cell]" id="package_styles_empty_cell" class="inputbox" value="<?php echo ($this->theme_data->package_styles_empty_cell) ? $this->theme_data->package_styles_empty_cell : $this->_com_params->get( 'package_styles_empty_cell' ); ?>" />
			
		</div>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="package-table">
			
			<thead class="thead">
				
				<tr class="thead-tr table-header">
					<th class="empty-cell empty-cell-th" rowspan="2">&nbsp;</th>
					<th class="plan-title-th" rowspan="2" colspan="1"><?php echo JText::_('PLAN_TITLE'); ?></th>
					<th class="plan-title-th" colspan="2"><?php echo JText::_('PLAN_TITLE'); ?></th>
				</tr>
				
				<tr class="thead-tr table-header">
					<th class="plan-subplan-title-th" colspan="1"><?php echo JText::_('SUBPLAN_TITLE'); ?></th>
					<th class="plan-subplan-title-th" colspan="1"><?php echo JText::_('SUBPLAN_TITLE'); ?></th>
				</tr>
				
			</thead>
			
			<tbody>
			
				<tr class="package-plan-row alternative-row-1">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				<tr class="package-plan-row alternative-row-0">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				
				<tr class="table-header-row">
					<td colspan="4">Package Row Header</td>
				</tr>
				
				<tr class="package-plan-row alternative-row-1">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				<tr class="package-plan-row alternative-row-0">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				
				<tr class="package-plan-row alternative-row-1">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				<tr class="package-plan-row alternative-row-0">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				
				<tr class="table-header-row">
					<td colspan="4">Package Row Header</td>
				</tr>
				
				<tr class="package-plan-row alternative-row-1">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				<tr class="package-plan-row alternative-row-0">
					<td class="package-label"><?php echo JText::_('PACKAGE_ROW_LABEL'); ?></td>
					<td class="plan-item no-subplans"><?php echo JText::_('PLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
					<td class="plan-item subplans"><?php echo JText::_('SUBPLAN_ITEM'); ?></td>
				</tr>
				
			</tbody>
			
			<tfoot>
				<tr>
					<th colspan="4">
						<sup>*</sup>Package footer textarea right here.
					</th>
				</tr>
			</tfoot>
			
		</table>
		
	</div>

</div>