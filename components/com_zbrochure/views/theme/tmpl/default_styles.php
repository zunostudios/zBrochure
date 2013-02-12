<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

JHTML::stylesheet( 'colorpicker.css', 'components/com_zbrochure/assets/css/' );

?>				
<style type="text/css">
.item-sample{line-height:36px;margin:0}
p.reversed{padding:20px;background-color:rgb(<?php echo $this->theme_data->color[0]; ?>);}
.reversed-selector{background-color:rgb(<?php echo $this->theme_data->color[0]; ?>);}
.text-colors{margin:0 20px 0 0}
.text-colors li{float:none;position:relative;width:100%;margin:0 0 10px 0;padding:0 0 10px 0;border-bottom:1px solid #EFEFEF}
.color-select{background-image:url(<?php echo JURI::base(); ?>components/com_zbrochure/assets/images/select2.png);cursor:pointer;float:left;height:36px;margin:0 10px 0 0;position:relative;width:36px}
	.color-select div{background-image:url(<?php echo JURI::base(); ?>components/com_zbrochure/assets/images/select2.png);background-position:-4px -4px;height:28px;left:4px;position:absolute;top:4px;width:28px}

.text-picker{background-color:#FFF;border:1px solid #CCC;display:none;overflow:hidden;position:absolute;top:40px;z-index:10}
.text-picker div{cursor:pointer;float:left;height:25px;margin:5px;width:25px;border:1px solid #CCC}
									
table.package-table{
	<?php echo ($this->theme_data->package_styles_table) ? $this->theme_data->package_styles_table : $this->_com_params->get( 'package_styles_table' ); ?>
}

.package-table thead th{
	<?php echo ($this->theme_data->package_styles_thead_th) ? $this->theme_data->package_styles_thead_th : $this->_com_params->get( 'package_styles_thead_th' ); ?>
}

.package-table td, .package-table th{
	<?php echo ($this->theme_data->package_styles_th_td) ? $this->theme_data->package_styles_th_td : $this->_com_params->get( 'package_styles_th_td' ); ?>
}

.package-table tr.alternative-row-0 td{
	<?php echo ($this->theme_data->package_styles_alt_0_td) ? $this->theme_data->package_styles_alt_0_td : $this->_com_params->get( 'package_styles_alt_0_td' ); ?>
}

.package-table tr.alternative-row-1 td{
	<?php echo ($this->theme_data->package_styles_alt_1_td) ? $this->theme_data->package_styles_alt_1_td : $this->_com_params->get( 'package_styles_alt_1_td' ); ?>
}

.package-table td.package-label{
	<?php echo ($this->theme_data->package_styles_td_package_label) ? $this->theme_data->package_styles_td_package_label : $this->_com_params->get( 'package_styles_td_package_label' ); ?>
}

.package-table td.plan-item{
	<?php echo ($this->theme_data->package_styles_td_plan_item) ? $this->theme_data->package_styles_td_plan_item : $this->_com_params->get( 'package_styles_td_plan_item' ); ?>
}

.package-table tr.table-header-row td{
	<?php echo ($this->theme_data->package_styles_header_row_td) ? $this->theme_data->package_styles_header_row_td : $this->_com_params->get( 'package_styles_header_row_td' ); ?>
}

.package-table tfoot th{
	<?php echo ($this->theme_data->package_styles_tfoot_th) ? $this->theme_data->package_styles_tfoot_th : $this->_com_params->get( 'package_styles_tfoot_th' ); ?>
}

.package-table .empty-cell{
	<?php echo ($this->theme_data->package_styles_empty_cell) ? $this->theme_data->package_styles_empty_cell : $this->_com_params->get( 'package_styles_empty_cell' ); ?>
}						
</style>