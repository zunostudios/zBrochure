<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */

defined('_JEXEC') or die('Restricted access'); ?>
<style type="text/css">

.cke_editor_package_footer,.cke_editor_package_content{border:1px solid #D6D6D6}

.ui-sortable tr:hover{cursor:move}
.ui-sortable tr.nosort:hover{cursor:default}
.ui-sortable td{cursor:default}
.ui-sortable td:hover{box-shadow:0 0 0 #000}	
.ui-state-highlight{background-color:yellow}
	
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