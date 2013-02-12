<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<style type="text/css">			
table.package-table{
	<?php echo $this->_com_params->get( 'package_styles_table' ); ?>
}

.package-table thead th{
	<?php echo $this->_com_params->get( 'package_styles_thead_th' ); ?>
}

.package-table td, .package-table th{
	<?php echo $this->_com_params->get( 'package_styles_th_td' ); ?>
}

.package-table tr.alternative-row-0 td{
	<?php echo $this->_com_params->get( 'package_styles_alt_0_td' ); ?>
}

.package-table tr.alternative-row-1 td{
	<?php echo $this->_com_params->get( 'package_styles_alt_1_td' ); ?>
}

.package-table td.package-label{
	<?php echo $this->_com_params->get( 'package_styles_td_package_label' ); ?>
}

.package-table td.plan-item{
	<?php echo $this->_com_params->get( 'package_styles_td_plan_item' ); ?>
}

.package-table tr.table-header-row td{
	<?php echo $this->_com_params->get( 'package_styles_header_row_td' ); ?>
}

.package-table tfoot th{
	<?php echo $this->_com_params->get( 'package_styles_tfoot_th' ); ?>
}

.package-table .empty-cell{
	<?php echo $this->_com_params->get( 'package_styles_empty_cell' ); ?>
}						
</style>