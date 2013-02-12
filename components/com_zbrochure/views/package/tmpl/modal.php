<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */

defined('_JEXEC') or die('Restricted access');

$data			= json_decode( $this->package->package_details );
$dataArray		= json_decode( $this->package->package_details, true );

echo $this->loadTemplate( 'styles' );

if( $this->package->brochure_id == $this->brochure_id && $this->package->brochure_id != 0 ){
	$target = "_parent";
}else{
	$target = "_self";
}

?>
<form id="package-form" action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" onsubmit="clearClone();"<?php echo ' target="'.$target.'"'; ?>>
	
	<div class="form-row edit-plan">
			
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		
			<tbody>
			
				<tr class="nosort">
					
					<td valign="top">
						<div class="form-row" style="padding:0 20px">
							<label for="package_name"><?php echo JText::_( 'FORM_LABEL_PACKAGE_NAME' ); ?></label>
							<input id="package_name" name="package_name" class="inputbox" style="width:98%" value="<?php echo $this->package->package_name; ?>" />
						</div>
					</td>
					
					
					<td width="20%" valign="top">
						<div class="form-row" style="border-left:1px solid #CCC;border-right:1px solid #CCC;padding:0 20px">
							<label for="package_categories"><?php echo JText::_( 'FORM_LABEL_CREATED' ); ?></label>
							<input disabled="disabled" class="inputbox" style="width:90%" value="<?php echo $this->package->package_created; ?>" />
						</div>
					</td>
					
					<td width="20%" valign="top">
						<div class="form-row" style="padding:0 20px">
							<label for="published"><?php echo JText::_( 'FORM_LABEL_MODIFIED' ); ?></label>
							<input disabled="disabled" class="inputbox" style="width:90%" value="<?php echo $this->package->package_modified; ?>" />
						</div>
					</td>
					<!--
					<td width="33%" valign="top">
						<div class="form-row" style="border-left:1px solid #CCC;border-right:1px solid #CCC;padding:0 20px">
							<label for="package_categories"><?php echo JText::_( 'CATEGORY' ); ?></label>
							<?php echo $this->categories; ?>
						</div>
					</td>
					
					<td width="33%" valign="top">
						<div class="form-row" style="padding:0 20px">
							<label for="published"><?php echo JText::_( 'FORM_LABEL_PUBLISHED' ); ?></label>
							<?php echo $this->published; ?>
						</div>
					</td>
					-->
					
				</tr>
				
				<!--<tr>
					
					<td colspan="3">
						<div class="form-row" style="padding:0 20px">
							<label for="package_desc"><?php echo JText::_( 'FORM_LABEL_PACKAGE_DESC' ); ?></label>
							<textarea name="package_desc" id="package_desc" class="inputbox" style="width:98%;height:30px"><?php echo $this->package->package_desc; ?></textarea>
						</div>
					</td>
					
				</tr>-->
				
				<tr>
					
					<td colspan="3">
						<div class="form-row">
							<label for="package_content"><?php echo JText::_( 'FORM_LABEL_PACKAGE_CONTENT' ); ?></label>
							<textarea name="package_content" id="package_content" class="inputbox" style="width:98%;height:30px"><?php echo $this->package->package_content; ?></textarea>
						</div>
					</td>
					
				</tr>
				
			</tbody>
			
		</table>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id="addpackage" style="margin:10px 0 20px 0">
			
			<thead>
			
				<tr class="nosort">
					<th><!-- --></th>
					<th style="text-align:left" width="60%"><?php echo JText::_( 'PACKAGE_LABEL_HEADER' ); ?></th>
					<th style="text-align:left">&nbsp;</th>
					<th style="text-align:center" width="5%">&nbsp;</th>
					<th style="text-align:center" width="5%">&nbsp;</th>
				</tr>
			
			</thead>
			
			<tbody id="sortable">
					
				<tr class="toClone newrow nosort">
					<td class="handle-td">
						<span class="handle" style="cursor:move">drag</span>
					</td>
					
					<td style="padding:5px 0">
						<textarea id="labelinput" name="" class="inputbox plan-inline-textarea"></textarea>
					</td>
					<td><label><?php echo JText::_('MAKE_HEADER'); ?>&nbsp;<input name="" type="checkbox" class="isheader" onchange="makeHeader(this);" /></label></td>
					<td>
						<button onclick="addRow(this); return false;" class="btn plus-icon-btn icon-only-btn"><span>+</span></button>
					</td>
					<td>
						<button onclick="deleteRow(this); return false;" class="btn minus-icon-btn icon-only-btn"><span>-</span></button>
					</td>
				</tr>
					
				<?php if( !$data ){ ?>
					
					<tr id="row1" class="package-row">
							
							<td class="handle-td">
								<span class="handle" style="cursor:move">drag</span>
							</td>
							
							<td style="padding:5px 0">
								<textarea name="details[1][package_label]" class="inputbox plan-inline-textarea"></textarea>
							</td>
							
							<td><label><?php echo JText::_('MAKE_HEADER'); ?>&nbsp;<input id="<?php echo $rowtype[1]; ?>" name="details[1][is_header]" type="checkbox" class="isheader" onchange="makeHeader(this);" /></label></td>
							
							<td>
								<button onclick="addRow(this); return false;" class="btn plus-icon-btn icon-only-btn"><span>+</span></button>
							</td>
							
							<td>
								<button onclick="deleteRow(this); return false;" class="btn minus-icon-btn icon-only-btn"><span>-</span></button>
							</td>
							
						</tr>
					
				<?php }else{ ?>
				
					<?php
					$i=1;
					foreach( $data as $key => $row ){ ?>
							
						<tr id="row<?php echo $i; ?>" class="package-row">
							
							<td class="handle-td">
								<span class="handle" style="cursor:move">drag</span>
							</td>
							
							<td style="padding:5px 0">
								<textarea name="details[<?php echo $i; ?>][package_label]" class="inputbox plan-inline-textarea"><?php echo $row->package_label; ?></textarea>
								<input name="details[<?php echo $i; ?>][package_label_id]" type="hidden" value="<?php echo $row->package_label_id; ?>" />
							</td>
							
							<td><label><?php echo JText::_('MAKE_HEADER'); ?>&nbsp;<input id="<?php echo $rowtype[1]; ?>" name="details[<?php echo $i; ?>][is_header]" <?php if($row->is_header){echo 'checked="checked"';} ?> type="checkbox" class="isheader" onchange="makeHeader(this);" /></label></td>
							
							<td>
								<button onclick="addRow(this); return false;" class="btn plus-icon-btn icon-only-btn"><span>+</span></button>
							</td>
							
							<td>
								<button onclick="deleteRow(this); return false;" class="btn minus-icon-btn icon-only-btn"><span>-</span></button>
							</td>
							
						</tr>
						
					<?php $i++; } ?>
				
				<?php } ?>
				
			</tbody>
			
		</table>
		
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		
			<tbody>
					
				<tr>
					
					<td>
						<div class="form-row">
							<label for="package_footer"><?php echo JText::_( 'FORM_LABEL_PACKAGE_FOOTER' ); ?></label>
							<textarea name="package_footer" id="package_footer" class="inputbox" style="width:98%;height:30px"><?php echo $this->package->package_footer; ?></textarea>
						</div>
					</td>
					
				</tr>
		
			</tbody>
			
		</table>
		
		<div class="form-row" style="padding:20px 20px 0 20px;margin:0">	
			
			<?php if( $this->package->brochure_id == $this->brochure_id && $this->package->brochure_id != 0 ){ ?>
			<button type="button" id="add-plan-btn" class="btn duplicate-btn icon-btn fr"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'NEW_BTN_PLAN' ); ?></span></button>
			<?php } ?>
			
			<div class="clear"><!-- --></div>
			
		</div>
		
		<div class="package-plan-list-table" style="border:none;border-radius:0;background-color:#FFF;padding:0 10px 10px 10px;width:7.2in;margin:0 auto">
			<div class="package-plan-list-table-inner">
			<?php if( $this->package->brochure_id == $this->brochure_id && $this->package->brochure_id != 0 ){
			
				echo $this->plans_output->render;
			
			}else{ 
		
				echo '<div style="height:100px;line-height:100px;text-align:center;font-weight:bold;background-color:#FF3300;color:#FFF;border-radius:5px">'.JText::_( 'PACKAGE_SAVE_FIRST' ).'</div>';
		
			}?>
			</div>
		
		</div>
		
	</div>
			
		<div class="package-plan-list" style="margin:0 0 20px 0;background-color:#FFF"></div>
		
		<div class="btns-container" style="padding:10px 20px 20px 20px;text-align:right">
				
			<button type="button" class="btn cancel-btn icon-btn" onclick="parent.window.edit_package_dialog.dialog('close'); return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
			
			<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
			
		</div>
		
		<input type="hidden" name="option" value="com_zbrochure" />
		<input type="hidden" name="layout" value="modal" />
		<input type="hidden" name="tmpl" value="modal" />
		<input type="hidden" name="view" value="package" />
		<input type="hidden" name="task" value="<?php echo JRequest::getVar( 'action', 'savePackage' ); ?>" />
		<input type="hidden" name="brochure_id" value="<?php echo $this->brochure_id; ?>" />
		<input type="hidden" name="id" value="<?php echo $this->brochure_id; ?>" />
		<input type="hidden" name="page_id" value="<?php echo $this->page_id; ?>" />
		<input type="hidden" name="block_id" value="<?php echo $this->block_id; ?>" />
		<input type="hidden" name="version" value="<?php echo $this->package->version; ?>" />
		
		<input type="hidden" name="package_cat" value="<?php echo $this->package->package_cat; ?>" />
		
		<?php if( $this->package->brochure_id == $this->brochure_id && $this->package->brochure_id != 0 ){ ?>
		<input type="hidden" name="package_id" value="<?php echo ($this->package->package_id) ? $this->package->package_id : ''; ?>" />
		<?php }else{ ?>
		<input type="hidden" name="initial_save" value="1" />
		<input type="hidden" name="package_parent" value="<?php echo ($this->package->package_id); ?>" />
		<?php } ?>
		<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
		
			
</form>

<div id="delete-plan-dialog" title="Delete Plan">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p style="text-align:center"><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="#" id="delete-plan-modal-btn" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_PLAN' ); ?></span></a>

</div>

<?php

$this->dataCount	= count($dataArray);
echo $this->loadTemplate( 'scripts' );

?>