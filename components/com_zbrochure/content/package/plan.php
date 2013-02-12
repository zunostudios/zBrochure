<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure table element
 */
class ZbrochureContentPlan{
	
	/**
	 * @var string output
	 */	
	protected $_output;
	
	/**
	 * Method to render a plan table
	 */
	public function renderContent( $package_full, $plan, $tab=0, $block_id=0, $brochure_id=0, $hidden=0 ){
		
		$package		= json_decode( $package_full->package_details );
		
		$total_plans	= 1;
				
		//Var for total columns needed for all plans
		$total_plan_columns	= 0;
		
		$thead_tr_1 = '';
		$thead_tr_2 = '';
		
		$rows 		= array();
		
		$plan_subplans			= json_decode( $plan->plan_subplan, true );
		$plan_subplan_count		= count( $plan_subplans, 1 );
		
		$colspan				= ( count($plan_subplans, 1 ) ) ? count($plan_subplans, 1 ) : 1;
		$total_plan_columns		= $total_plan_columns + $colspan;
		
		$plan_rows				= json_decode( $plan->plan_details, true );

		$plan_id				= ($plan->plan_id) ? $plan->plan_id : 0;
		
		
		//Build the thead cells here
		//The first pass builds the sub-plan row
		if( $plan_subplans ){
			
			$rowspan = 1;
				
			foreach( $plan_subplans as $subplan ){
				
				$thead_tr_2 .= '<th class="plan-subplan-title-th"><textarea type="text" class="inputbox plan-inline-textarea" name="plans['.$tab.'][network][]">'.nl2br($subplan).'</textarea></th>'.PHP_EOL;
				
			}
		
		}else{
			
			$rowspan = 1;
			
		}
		
		//Build the main plan cell
		$thead_tr_1 .= '<th id="planName-'.$block_id.'-'.$tab.'" class="plan-title-th" colspan="'.$colspan.'" rowspan="'.$rowspan.'">'.PHP_EOL;
		$thead_tr_1 .= '<textarea name="plans['.$tab.'][plan_name]" id="plan_name" class="inputbox plan-inline-textarea" style="width:94%">'.$plan->plan_name.'</textarea>'.PHP_EOL;
		$thead_tr_1 .= '</th>'.PHP_EOL;
		
		//Loop over every row/label in the packages
		foreach( $package as $k => $v ){
			
			$cell = '';
			
			if( !$v->is_header ){
				
				$plan_row_cells			= $plan_rows['package_label_id_'.$v->package_label_id]['cell'];
				$plan_row_cell_count	= count( $plan_row_cells, 1 );
				
				if( $plan_subplan_count ){
							
					$sub_plan_counter = 0;
					
					while( $sub_plan_counter < $plan_subplan_count ){
					
						$value = ( $plan_row_cells[$sub_plan_counter] ) ? $plan_row_cells[$sub_plan_counter] : '';						
						$cell .= '<td class="plan-item subplans"><textarea type="text" class="inputbox plan-inline-textarea" name="plans['.$tab.'][plan]['.$v->package_label_id.'][plan_cell][]">'.$value.'</textarea></td>'.PHP_EOL;

						$sub_plan_counter++;
						
					}	
					
				}else{
					
					$value = ( $plan_row_cells[0] ) ? $plan_row_cells[0] : '';
					$cell .= '<td class="plan-item no-subplans"><textarea type="text" class="inputbox plan-inline-textarea" name="plans['.$tab.'][plan]['.$v->package_label_id.'][plan_cell][]">'.$value.'</textarea></td>'.PHP_EOL;
					
				}
				
				$rows[ $v->package_label_id ][] = $cell;
				
			}
			
		}	

	
				
	$total_plan_columns = $total_plan_columns + 1;
	
	$table	= '<table cellpadding="0" cellspacing="0" border="0" id="plan-'.$block_id.'-'.$tab.'" width="100%" class="package-plan-list package-table">'.PHP_EOL;
	
	//assemble the thead
	$thead	= '<thead class="thead">'.PHP_EOL;
	$thead	.= '<tr class="thead-tr table-header">'.PHP_EOL;
	
	if( $total_plan_columns > $total_plans ){
		
		$thead .= '<th class="empty-cell empty-cell-th" rowspan="2"><button type="button" class="btn add-btn icon-btn" onclick="addColumn( '.$plan_id.', '.$block_id.', '.$tab.');"><span class="icon"><!-- --></span><span class="btn-text">'.JText::_( 'ADD_BTN_SUBPLAN' ).'</span></button></th>'.PHP_EOL;
		
	}else{
		
		$thead .= '<th class="empty-cell empty-cell-th" rowspan="'.$rowspan.'"><button type="button" class="btn add-btn icon-btn" onclick="addColumn( '.$plan_id.', '.$block_id.', '.$tab.');"><span class="icon"><!-- --></span><span class="btn-text">'.JText::_( 'ADD_BTN_SUBPLAN' ).'</span></button></th>'.PHP_EOL;
		
	}
	
	$thead .= $thead_tr_1.PHP_EOL;
	$thead .= '</tr>'.PHP_EOL;
	
	
	if( ( $total_plan_columns - 1 ) > $total_plans ){
	
		$thead .= '<tr class="thead-tr table-header">'.PHP_EOL;
		$thead .= $thead_tr_2.PHP_EOL;
		$thead .= '</tr>'.PHP_EOL;
	
	}
	
	$thead .= '</thead>'.PHP_EOL;
	
	//Assemble the tbody
	$tbody	= '<tbody>'.PHP_EOL;
	
	$i = 0;
	
	foreach( $package as $k => $v ){
		
		if( $v->is_header ){
		
			$tbody .= '<tr class="table-header-row"><td class="table-header-row-td" colspan="'.$total_plan_columns.'">'.nl2br($v->package_label).'</td></tr>'.PHP_EOL;
		
		}else{
			
			$alt = ($i % 2) ? 'alternative-row-0' : 'alternative-row-1';
			
			$tbody .= '<tr class="package-plan-row '.$alt.'" id="'.$v->package_label_id.'">'.PHP_EOL;
			$tbody .= '<td class="package-label">'.nl2br($v->package_label).'</td>'.PHP_EOL;
									
			foreach( $rows[$v->package_label_id] as $cell ){
				
				$tbody .= $cell;
				
			}
									
			$tbody .= '</tr>'.PHP_EOL;
			
			$i++;
			
		}
		
		
	}
	
	$tbody	.= '</tbody>'.PHP_EOL;
	
	//Build the footer
	$tfoot = '';
	if( $package->package_footer ){
	
		$tfoot .= '<tfoot>'.PHP_EOL;
		$tfoot .= '<tr class="tfoot-tr"><th class="plan-footer-th" colspan="'.$total_plan_columns.'">'.$package->package_footer.'</th></tr>'.PHP_EOL;
		$tfoot .= '</tfoot>'.PHP_EOL;
		
	}
	
	$table_close = '</table>'.PHP_EOL;
	
	$this->_output .= $table.$thead.$tbody.$tfoot.$table_close;
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*
$this->_output = '';
					
		$this->_output .= '<table id="plan-'.$block_id.'-'.$tab.'" class="package-plan-list package-table" cellpadding="0" cellpadding="0" border="0" style="margin:0 auto">';
		
			//Generate the thead
			$this->_output .= '<thead>';
			$this->_output .= '<tr class="table-header">';
			
			$rowspan = ($subplan_count) ? ' rowspan="2"' : '';
			
			$this->_output .= '<th class="empty-cell"'.$rowspan.' style="border-bottom:1px solid #D6D6D6"><button type="button" class="btn add-btn icon-btn" onclick="addColumn( '.$plan_id.', '.$block_id.', '.$tab.');"><span class="icon"><!-- --></span><span class="btn-text">'.JText::_( 'ADD_BTN_SUBPLAN' ).'</span></button></th>';
			
			$colspan = ($subplan_count) ? $subplan_count : 1;
			
			$this->_output .= '<th id="planName-'.$block_id.'-'.$tab.'" colspan="'.$colspan.'"><textarea name="plans['.$tab.'][plan_name]" id="plan_name" class="inputbox plan-inline-textarea" style="width:94%">'.nl2br($plan->plan_name).'</textarea></th>';
			$this->_output .= '</tr>';
			
			if( $subplans ){
					
					$this->_output .= '<tr class="table-header plan-subs">';
							
						foreach( $subplans as $subplan ){
							
							$this->_output .= '<th><textarea type="text" class="inputbox plan-inline-textarea" name="plans['.$tab.'][network][]">'.nl2br($subplan).'</textarea></th>';
							
						}
					
					$this->_output .= '</tr>';
			}
			
			$this->_output .= '</thead>';
			//End thead
			
			//Generate the tbody
			$this->_output .= '	<tbody>';
				
			$a = 0;
			foreach( $package as $label ){
				
				if( !$label->is_header ){
					
					$alt = ($a % 2) ? 'alternative-row-0' : 'alternative-row-1';
					$a++;
					
					$this->_output .= '<tr class="package-plan-row '.$alt.'" id="'.$label->package_label_id.'">';
					
					$this->_output .= '<td class="package-label">'.nl2br( $label->package_label ).' ('.$label->package_label_id.')</td>';
					
					if( !$dataArray['package_label_id_'.$label->package_label_id]['cell'] ){
						
						for( $e=0; $e <= $subplan_count; $e++ ){
						
							$this->_output .= '<td class="plan-item">';
							$this->_output .= '<textarea type="text" class="inputbox plan-inline-textarea" name="plans['.$tab.'][plan]['.$label->package_label_id.'][plan_cell][]"></textarea>';
							$this->_output .= '</td>';
						
						}
						
					}else{
						
						$i=0;			
						foreach( $dataArray['package_label_id_'.$label->package_label_id]['cell'] as $cell ){
	
							$this->_output .= '<td class="plan-item">';
							$this->_output .= '<textarea type="text" class="inputbox plan-inline-textarea" name="plans['.$tab.'][plan]['.$label->package_label_id.'][plan_cell][]">'.$cell.'</textarea>';
							$this->_output .= '</td>';
									
							$i++;
									
						}
						
					}
					
					$this->_output .= '</tr>';
					
				}else{
					
					$total_colspan = ($subplan_count) ? $subplan_count + 1 : 2;
					
					$this->_output .= '<tr class="table-header-row">';
					$this->_output .= '<td class="table-header-row-td" colspan="'.$total_colspan.'">'.nl2br($label->package_label).'</td>';
					$this->_output .= '</tr>';
					
				}
				
			}
				
			$this->_output .= '</tbody>';
			//End tbody
			
			//Generate tfoot
			$this->_output .= '<tfoot>';					
			$this->_output .= '<tr>';
			$this->_output .= '<th>&nbsp;</th>';
						
			if( $subplans ){
				
				foreach( $subplans as $subplan ){
				
				$this->_output .= '<th><button type="button" class="btn delete-btn icon-btn delete-col-btn"><span class="icon"><!-- --></span><span class="btn-text">'.JText::_( 'DELETE_BTN_GENERAL' ).'</span></button></th>';
				
				}
				
			}else{
				
				$this->_output .= '<th><!-- --></th>';
				
			}
						
			$this->_output .= '</tr>';		
			$this->_output .= '</tfoot>';
			//End tfoot
			
			$this->_output .= '</table>';

		
		*/
		
		
		if( $hidden ){	
		
			$this->_output .= '<input type="hidden" name="plans['.$tab.'][plan_id]" value="'.$plan_id.'" />';
			//$this->_output .= '<input type="hidden" name="plans['.$tab.'][plan_name]" value="'.$plan->plan_name.'" />';
			$this->_output .= '<input type="hidden" name="plans['.$tab.'][package_id]" value="'.$package_full->package_id.'" />';
			$this->_output .= '<input type="hidden" name="plans['.$tab.'][brochure_id]" value="'.$plan->brochure_id.'" />';
			
		}

				
		return $this->_output;
	
	}

}