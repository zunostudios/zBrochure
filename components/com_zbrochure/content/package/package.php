<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure text element
 */
class ZbrochureContentPackage{
	
	/**
	 * @var string output
	 */	
	protected $_data;
	
	/**
	 * @var string output
	 */	
	protected $_output;
	
	/**
	 * Method to render an image content element
	 */
	public function getContent( $block, $edit ){
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery( true );
		
		$query->select( 'b.id, b.data' );
		$query->from( '#__'.$block->content_type_table.' AS b' );
		
		//If this block has never been edited, let's get the default value
		//If the block has been edited, let's get the current version of the content -MM
		$query->where( 'b.id = '.$block->content_block_current_version );
			
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
				
		$output = ZbrochureContentPackage::renderContent( null, $block, $edit );
			
		return $output;
	
	}
	
	/**
	 * Method to get the plans assigned to this brochure
	 */
	public function getPlans( $data, $admin_edit ){
	
		$db		= JFactory::getDbo();
		$plans	= array();
		
		if( $data ){
		
			foreach( $data as $plan ){
			
				$plans[] = (int)$plan;
			
			}
		
		}
		
		$query	= $db->getQuery( true );
		$query->select( 'a.*' );
		$query->from( '#__zbrochure_package_plans AS a' );
		$query->where( 'a.plan_id IN ('.implode( ',', $plans ).')' );
		
		if( $admin_edit ){
			
			$query->where( 'a.brochure_id = 0' );
			
		}
		
		$query->order( 'a.plan_ordering' );
		
		$db->setQuery( $query );
		$plans_obj	= $db->loadObjectList();
		
		if( $plans_obj ){
		
			foreach( $plans_obj as $plan_obj ){
				
				$plan_obj->plan_subplan = json_decode( $plan_obj->plan_subplan, true );
				$plan_obj->plan_details	= json_decode( $plan_obj->plan_details, true );
				
			}
		
		}
		
		return $plans_obj;
		
	}
	
	/**
	 * Method to get the plans assigned to this brochure
	 */
	public function getPackagePlans( $package_id, $admin_edit ){
	
		$db		= JFactory::getDbo();
		
		
		$query	= $db->getQuery( true );
		$query->select( 'a.*' );
		$query->from( '#__zbrochure_package_plans AS a' );
		$query->where( 'a.package_id = ' . $package_id );
		$query->order( 'a.plan_ordering' );
		
		$db->setQuery( $query );
		$plans_obj	= $db->loadObjectList();
		
		if( $plans_obj ){
		
			foreach( $plans_obj as $plan_obj ){
				
				$plan_obj->plan_subplan = json_decode( $plan_obj->plan_subplan, true );
				$plan_obj->plan_details	= json_decode( $plan_obj->plan_details, true );
				
			}
		
		}
		
		return $plans_obj;
		
	}
	
	/**
	 * Method to get the plans assigned to this brochure
	 */
	 public function getPackage( $pid ){
		 
		 $db	= JFactory::getDBO();
		 $query	= $db->getQuery( true );
		 
		 $query->select( 'p.*' );
		 $query->from( '#__zbrochure_packages AS p' );
		 $query->where( 'p.package_id = '.$pid );
		 
		 $db->setQuery( $query );
		 $package	= $db->loadObject();
		 
		 return $package;
		 
	 }	
	
	/**
	 * Method to render the package table and editing modal
	 */
	public function renderContent( $data, $block=null, $edit=1, $admin_edit=0 ){
		
		$this->_output		= '';
		
		if( $data == null ){
			
			$package_full		= ZbrochureContentPackage::getPackage( $block->content_block_current_version );
			$package_id			= $package_full->package_id;
			$package_cat		= $package_full->package_cat;
			$package_content	= $package_full->package_content;
			$package_footer		= $package_full->package_footer;
			$package 			= json_decode( $package_full->package_details );
			
			$plans 				= ZbrochureContentPackage::getPackagePlans( $package_id, $admin_edit );
			
		}else if( $data->package_details ){
			
			$package			= json_decode( $data->package_details );
			$package_id			= $data->package_id;
			$package_cat		= $data->package_cat;
			$package_content	= $data->package_content;
			$package_footer		= $data->package_footer;
			$plans_to_get		= json_decode( $data->data );
			$plans_to_get		= $plans_to_get->plans;
			
			$plans				= ZbrochureContentPackage::getPackagePlans( $package_id, $admin_edit );
			
		}else{
			
			$data_decode		= json_decode( $data->data );
			
			$package_full		= ZbrochureContentPackage::getPackage( $data_decode->package );
			$package_id	 		= $package_full->package_id;
			$package_cat		= $package_full->package_cat;
			$package_content	= $data->package_content;
			$package_footer		= $data->package_footer;
			$package 			= json_decode( $package_full->package_details );
			
			$plans_to_get		= $data_decode->plans;	
		
			$plans				= ZbrochureContentPackage::getPackagePlans( $package_id, $admin_edit );
			
		}
		
		if( $edit == 1 ){
			
			$brochure_id		= JRequest::getInt('id');
			$categories 		= ZbrochureContentPackage::getCategories();
			$packages			= ZbrochureContentPackage::getPackages();
			$brochure_packages	= ZbrochureContentPackage::getPackages( $brochure_id );
						
			//************************** START DATA BLOCK **************************
			$this->_output .= '<div id="packageblock-'.$block->content_block_id.'" class="editable-content">'.PHP_EOL;
		
		}else{
			
			//************************** START DATA BLOCK **************************
			$this->_output .= '<div class="plans-list-content">'.PHP_EOL;
			
		}
		
		if( !$package_id ){
		
			$this->_output .= '<div style="background-color:#EFEFEF;padding:10px;text-align:center">'.PHP_EOL;
			$this->_output .= '<h2 style="margin-bottom:0">'.JText::_( 'PLEASE_CHOOSE_PACKAGE' ).'</h2>'.PHP_EOL;
			$this->_output .= '</div>'.PHP_EOL;
		
		}else if( !$plans ){
		
			$this->_output .= '<div style="background-color:#EFEFEF;padding:10px;text-align:center">'.PHP_EOL;
			$this->_output .= '<h2 style="margin-bottom:0">'.JText::_( 'PLEASE_SELECT_PLANS' ).'</h2>'.PHP_EOL;
			$this->_output .= '</div>'.PHP_EOL;
		
		}else{
	
				//*************************** START Package-Plan Relationship **************************//
				$total_plans	= count( $plans, 1 );
				
				//Var for total columns needed for all plans
				$total_plan_columns	= 0;
				
				$thead_tr_1 = '';
				$thead_tr_2 = '';
				
				$rows 		= array();
				
				//Need to generate the first row of the thead for the plan names
				foreach( $plans as $plan ){
					
					//Get a count of how many sub-plans there are so we know the colspan number
					$plan_subplans			= $plan->plan_subplan;
					$plan_subplan_count		= count($plan_subplans, 1 );
					
					$colspan				= ( count($plan_subplans, 1 ) ) ? count($plan_subplans, 1 ) : 1;
					$total_plan_columns		= $total_plan_columns + $colspan;
					
					$plan_rows				= $plan->plan_details;
				
					//Build the thead cells here
					//The first pass builds the sub-plan row
					if( $plan_subplans ){
						
						$rowspan = 1;
							
						foreach( $plan_subplans as $subplan ){
							
							$thead_tr_2 .= '<th class="plan-subplan-title-th">'.nl2br($subplan).'</th>'.PHP_EOL;
							
						}
					
					}else{
						
						$rowspan = 2;
						
					}
					
					//Build the main plan cell
					$thead_tr_1 .= '<th class="plan-title-th" colspan="'.$colspan.'" rowspan="'.$rowspan.'">'.PHP_EOL;
					$thead_tr_1 .= nl2br($plan->plan_name).PHP_EOL;
					
					if( $admin_edit ){
						
						$thead_tr_1 .= '<div class="plan-action-links"><ul>'.PHP_EOL;
						
						$thead_tr_1 .= '<li><a href="'.JRoute::_( 'index.php?option=com_zbrochure&view=plan&id='.$plan->plan_id.'&pid='.$data->package_id ).'" class="edit-plan-link">'.JText::_( 'EDIT_PLAN' ).'</a></li>'.PHP_EOL;
						
						$thead_tr_1 .= '<li><a href="'.JRoute::_( 'index.php?option=com_zbrochure&task=duplicatePlan&id='.$plan->plan_id.'&pid='.$data->package_id ).'" class="duplicate-plan-link">'.JText::_( 'DUPLICATE_PLAN' ).'</a></li>'.PHP_EOL;
						
						$thead_tr_1 .= '<li><a href="'.JRoute::_( 'index.php?option=com_zbrochure&task=deletePlan&type=plan&id='.$plan->plan_id.'&pid='.$data->package_id ).'" class="delete-plan-link">'.JText::_( 'DELETE_PLAN' ).'</a></li>'.PHP_EOL;
						
						$thead_tr_1 .= '</ul>'.PHP_EOL.'</div>'.PHP_EOL;
						
					}
					
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
								
									$value = ( $plan_row_cells[$sub_plan_counter] ) ? $plan_row_cells[$sub_plan_counter] : 'NO VALUE';
									$cell .= '<td class="plan-item subplans">'.nl2br($value).'</td>'.PHP_EOL;
									
									$sub_plan_counter++;
									
								}	
								
							}else{
								
								$value = ( $plan_row_cells[0] ) ? $plan_row_cells[0] : 'NO VALUE';
								$cell .= '<td class="plan-item no-subplans">'.nl2br($value).'</td>'.PHP_EOL;
								
							}
							
							$rows[ $v->package_label_id ][] = $cell;
							
						}
						
					}	
					
				}
				
							
				$total_plan_columns = $total_plan_columns + 1;
				
				$table	= '<table cellpadding="0" cellspacing="0" border="0" pid="'.$package_id.'" id="package-table-'.$block->content_block_id.'" width="100%" class="package-table">'.PHP_EOL;
				
				//assemble the thead
				$thead	= '<thead class="thead">'.PHP_EOL;
				$thead	.= '<tr class="thead-tr table-header">'.PHP_EOL;
				
				if( $total_plan_columns > $total_plans ){
					
					$thead .= '<th class="empty-cell empty-cell-th" rowspan="2"></th>'.PHP_EOL;
					
				}else{
					
					$thead .= '<th class="empty-cell empty-cell-th" rowspan="1"></th>'.PHP_EOL;
					
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
					
						$tbody .= '<tr class="table-header-row"><td colspan="'.$total_plan_columns.'">'.nl2br($v->package_label).'</td></tr>'.PHP_EOL;
					
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
				if( $package_footer ){
				
					$tfoot .= '<tfoot>'.PHP_EOL;
					$tfoot .= '<tr class="tfoot-tr"><th class="plan-footer-th" colspan="'.$total_plan_columns.'">'.$package_footer.'</th></tr>'.PHP_EOL;
					$tfoot .= '</tfoot>'.PHP_EOL;
					
				}
				
				$table_close = '</table>'.PHP_EOL;
				
				
				if( $package_content ){
					
					$this->_output .= '<div class="textarea package-textarea">'.$package_content.'</div>';
					
				}
				
				
				$this->_output .= $table.$thead.$tbody.$tfoot.$table_close;
			
		}
		
		//End data block
		$this->_output .= '</div>'.PHP_EOL;
		
		if( $edit == 1 ){
			
			$doc = JFactory::getDocument();
			
			$this->_edit_js = '//Block #'.$block->content_block_id.PHP_EOL;
			$this->_edit_js	.= '$(document).ready(function(){'.PHP_EOL;
			
			if( !$data_decode->package && !$admin_edit ){
								
				$this->_edit_js	.= '$( "#packageblock-'.$block->content_block_id.'" ).click(function(){			
									$("#editpackage-'.$block->content_block_id.'").dialog(\'open\');
								});'.PHP_EOL;
			
			}
			
			//If we have a package category generate the package select field
			if( $package_cat ){
				
				//cat_id, block_id, brochure_id, active, bro_page_id
				$this->_edit_js .= 'var getPackages_'.$block->content_block_id.' = getPackages( '.$package_cat.', '.$block->content_block_id.', '.$brochure_id.', '.$package_full->package_id.', '.$block->bro_page_id.' );'.PHP_EOL;
				
			}
			
			
			
			if( !$data_decode->package && !$admin_edit ){
			
				$this->_edit_js	.= '$( "#editpackage-'.$block->content_block_id.'" ).dialog({'.PHP_EOL;
				$this->_edit_js	.= ' autoOpen: false,'.PHP_EOL;
				$this->_edit_js	.= ' resizable: true,'.PHP_EOL;
				$this->_edit_js	.= ' height:200,'.PHP_EOL;
				$this->_edit_js	.= ' width:400,'.PHP_EOL;
				//$this->_edit_js	.= ' modal:true,'.PHP_EOL;
				$this->_edit_js	.= ' close: function(){'.PHP_EOL;
				//$this->_edit_js	.= '  $( \'#vars-dialog\' ).dialog(\'close\');'.PHP_EOL;								
				$this->_edit_js	.= ' },'.PHP_EOL;
				
				$this->_edit_js	.= ' open: function(){'.PHP_EOL;
				
				if( $package_full->package_id ){
					//package_id, brochure_id, block_id, page_id
					$this->_edit_js .= '    setPackage( '.$package_full->package_id.', '.$brochure_id.', '.$block->content_block_id.', '.$block->bro_page_id.' );'.PHP_EOL;
					//$this->_edit_js .= '    alert( '.$block->bro_page_id.' );'.PHP_EOL;
				}
				
				//$this->_edit_js .= '  activePlans_'.$block->content_block_id.'();'.PHP_EOL;
				$this->_edit_js	.= ' }'.PHP_EOL;
				
				$this->_edit_js	.= '});'.PHP_EOL.PHP_EOL;
				
				//**************** Start Dialog	****************//
				$this->_edit_output = '<div id="editpackage-'.$block->content_block_id.'" class="block-'.$block->content_block_id.'" title="Edit Package Block">'.PHP_EOL;
				$this->_edit_output .= '<form action="'.JRoute::_('index.php?option=com_zbrochure&task=saveContentBlockPackage').'" method="post" name="form-'.$block->content_block_id.'">'.PHP_EOL;
				
				$this->_edit_output .= '<div class="form-row package-container" style="height:auto">'.PHP_EOL;
				
				
				$this->_edit_output .= '<div class="form-row add-padding package-container-header">'.PHP_EOL;
				
				//************ START CATEGORIES SELECT LIST ****************//
				$cat_options[] = JHTML::_('select.option', '0', JText::_( '-- Choose a Category --' ) );
				
				foreach( $categories as $k => $v ){
				
					$cat_options[] = JHTML::_( 'select.option', $k, $v->cat_name );
				
				};
						
				$cat_list = JHTML::_( 'select.genericlist', $cat_options, 'cat_id', 'onchange="getPackages( this.value, '.$block->content_block_id.', '.$brochure_id.', 0, '.$block->bro_page_id.' )"', 'value', 'text', $package_cat, 'categories-'.$block->content_block_id );
				
				$this->_edit_output .= '<div class="category-select-container">'.PHP_EOL;
				
				$this->_edit_output .= '<label for="categories-'.$block->content_block_id.'">'.JText::_( 'CHOOSE_CATEGORY' ).'</label>'.PHP_EOL;
				$this->_edit_output .= $cat_list.PHP_EOL;
				$this->_edit_output .= '<div class="clear"><!-- --></div>'.PHP_EOL;
				
				$this->_edit_output .= '</div>'.PHP_EOL;
				//************ END CATEGORIES SELECT LIST ****************//
				
				$this->_edit_output .= '<div id="package-select-container-'.$block->content_block_id.'"><!-- --></div>'.PHP_EOL;
				
				$this->_edit_output .= '<div class="clear"><!-- --></div>'.PHP_EOL;
				
				$this->_edit_output .= '</div>'.PHP_EOL;
				
				$this->_edit_output .= '</div>'.PHP_EOL;
							
				$this->_edit_output .= '<div class="form-row btn-container add-padding">'.PHP_EOL;
				$this->_edit_output .= '<button class="btn save-btn fr" type="button" onclick="edit_package_dialog.dialog( \'open\' );"><span>'.JText::_('NEXT_STEP').'</span></button>'.PHP_EOL;
				$this->_edit_output .= '</div>'.PHP_EOL;
				
				$this->_edit_output .= '</form>'.PHP_EOL;
				
				$this->_edit_output .= '<style type="text/css">.ui-icon-close{float: left;margin: 0.3em 0.2em 0 0;cursor: pointer; }</style>'.PHP_EOL;
			
				$this->_edit_output .= '</div>'.PHP_EOL;
				//**************** End Dialog	****************//
				
				$this->_edit_js	.= '});'.PHP_EOL.PHP_EOL;
				
				$doc->addScriptDeclaration( $this->_edit_js );
			
			}
			
		}
		
		$output->render = $this->_output;
		$output->edit	= $this->_edit_output;
				
		return $output;
	
	}
	
	/**
	 * Method to get content categories
	 */
	public function getCategories(){
				
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
				
		$query->select( 'c.*' );
		$query->from( '#__zbrochure_categories AS c' );
		$query->where( 'c.published = 1' );
		$query->order( 'c.cat_name' );
		
		$db->setQuery($query);
	
		return $db->loadObjectList( 'cat_id' );
	
	}
	
	/**
	 * Method to get packages
	 */
	public function getPackages( $brochure_id=0 ){
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select( '*' );
		$query->from( '#__zbrochure_packages' );
		$query->where( 'published = 1' );
		
		if( !$brochure_id ){
			
			$query->where( 'brochure_id = 0' );
			
		}else{
			
			$query->where( 'brochure_id = '.$brochure_id );
			
		}
		
		$db->setQuery( $query );
		
		return $db->loadObjectList( 'package_id' );
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
				
		//Duplicate package
		$row	= JTable::getInstance( 'package', 'Table' );
	
		if( !$row->load( $id ) ){
		
			$this->setError( $db->getErrorMsg() );
			return false;
		
		}
		
		if( $this->_duplicate_id ){
			
			$row->brochure_id = $this->_duplicate_id;
			
		}
		
		$row->package_id			= '';
		$row->brochure_id			= $block->content_bro_id;
		$row->package_created_by	= $user;
		$row->package_created		= $date;
		$row->package_modified_by	= 0;
		$row->package_modified		= '0000-00-00 00:00:00';	
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $db->getErrorMsg() );
			return false;
		
		}
		
		//Get related plans based on the old package id
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select( '*' );
		$query->from( '#__zbrochure_package_plans' );
		$query->where( 'package_id = ' . $id );
		
		$db->setQuery($query);
		$plans = $db->loadObjectList();
		
		foreach( $plans as $plan ){
			
			$plan_row	= JTable::getInstance( 'plan', 'Table' );
	
			if( !$plan_row->load( $plan->plan_id ) ){
			
				$this->setError( $db->getErrorMsg() );
				return false;
			
			}
			
			$plan_row->plan_id 			= '';
			$plan_row->package_id		= $row->package_id;
			$plan_row->plan_created_by	= $user;
			$plan_row->plan_created		= $date;
			$plan_row->plan_modified_by	= 0;
			$plan_row->plan_modified	= '0000-00-00 00:00:00';
			
			//alright, good to go. Store it to the Joomla db
			if( !$plan_row->store() ){
							
				$this->setError( $db->getErrorMsg() );
				return false;
			
			}
			
		}
		
		return $row->package_id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'package', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_CONTACTS' ) );
			return false;
		
		}
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->delete();
		$query->from( '#__zbrochure_package_plans' );
		$query->where( 'package_id = ' . $id );
		
		$db->setQuery( $query );
		$db->query();
		
		return true;
		
	}
	

}