<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

$package 		= json_decode($this->package->package_details);
$packageArray 	= json_decode( $this->package->package_details, true);

if( $this->data->plan_details ){
	$data 		=  json_decode( $this->data->plan_details );
	$dataArray 	= json_decode( $this->data->plan_details, true);
}

//Let's get the total number of columns for the colspans
if( $data ){
	
	foreach( $data as $row ){
		if( is_object( $row ) ){
			$i = 0;
			foreach( $row as $cell ){
				$i++;
			}
			$count[] = $i;
		}
	}
	$cols = max($count);
	$colspan = $cols - ($cols - 1);
}

$rowcount = 1;

?>

<script type="text/javascript">
$(function() {
	i = 1;
	$('#input-container').hide();
	
	$('input:checked').parent().css('background-color','#EFEFEF');
	$('input:checked').parent().attr('class','checked');
	
	$('#dialog-form').dialog({
		autoOpen: false,
		height: 140,
		modal: true,
		open: function(){
			$('#addSub1').attr('disabled', false);
			$('#addSub2').attr('disabled', false);
		},
		close: function(){
			$('#addSub1').attr('disabled', true);
			$('#addSub2').attr('disabled', true);
		}
		
	});
	
	$('#addSub').click(function(){
		$('#dialog-form').dialog('open');
	});
	
});

function cellToggle(elem){

	var parent = $(elem).parent();
	
	if( parent.attr('class') == "checked" ){
		parent.css('background-color','#FFFFFF');
		parent.attr('class','');
	}else{
		parent.css('background-color','#EFEFEF');
		parent.attr('class','checked');
	}

}

function disableBlank(){

	var allinputs = $('#planform :input');
	//allinputs.attr('disabled', true);
	
	allinputs.each(function(){
	
		if( this.value == '' ){
			
			$(this).attr('disabled', true);
			$(this).addClass('disabled');		
		}
		
	});

}

function addSub(){
	$( "#input-container" ).toggle( 'slide', null, 500, function(){
	
		if( $('#input-container :input').attr('disabled') == 'disabled' ){
			$( "#input-container :input" ).attr('disabled', false);
		}else{
			$( "#input-container :input" ).attr('disabled', true);
		}
		
	});	
}

function mergeCells(elem){
	
	var parent = $(elem).parent().parent().attr('id');
	var count = $('#'+parent + ' :checked').length;
	
	if( count > 1 ){
		var cellId = $('#'+parent + ' .checked .inputbox').attr('id');
		var cellnumber = cellId.charAt( cellId.length-1 );
		var colspan = $('#'+cellId+'').parent().attr('colspan');		
		var newname = 'details['+parent+'][cell_'+cellnumber+'_'+count+']';
		
		//Let's give the first input the new name
		$('#'+cellId+'').attr('name', newname);
		$('#'+cellId+'').parent().attr('class','keep');
		
		//Now we need to delete the other checked inputs somehow
		$('#'+parent + ' td.keep').attr('colspan', count);
		$('#'+parent + ' td.checked').remove();
		
		//Remove the 'keep' class and add the 'checked' class back on the td element
		$('#'+parent + ' td.keep').attr('class', 'checked');
	}
	
}

function toggleEdit(){
	$('.toggleEdit').toggle();	
}

function toggleDisabled(){
	$('#newsub1').attr('disabled',' ');
	$('#newsub2').attr('disabled',' ');
}

function toggleSubs(){
	$('.toggleSubs').slideToggle("slow", function(){
		
		if( $('.toggleSubs').hasClass('enabled') ){
			$('#addSub1').attr('disabled', true);
			$('#addSub2').attr('disabled', true);
			$('.toggleSubs').addClass('disabled');
			$('.toggleSubs').removeClass('enabled');
		}else{
			$('#addSub1').attr('disabled', false);
			$('#addSub2').attr('disabled', false);
			$('.toggleSubs').addClass('enabled');
			$('.toggleSubs').removeClass('disabled');
		}
		
		i++;
	});
}
	
</script>


<div style="padding:0 0 100px">

	<?php print_r( $data ); ?>

	<form id="planform" action="index.php?option=com_zbrochure&task=savePlan" method="post">
	
		<div class="form-row">
			<label for="plan_name">Plan Name:</label>
			<input class="inputbox" name="plan_name" id="plan_name" value="<?php echo $this->data->plan_name ?>" />
		</div>
	
		<!-- If we have data lets render the table accordingly -->
		<?php if( $data ){ ?>	
			<table border="1" width="100%">
				
				<?php if( $dataArray['subrow_1'] ){ ?>
							
				<tr style="background-color:#CCC">
				
					<td>&nbsp;</td>
					<?php foreach( $dataArray['subrow_1'] as $pre => $sub ){ $issub = explode( '_', $pre ); ?>
						<?php if( $issub[0] == 'sub' ){ ?>
							<td class="toggleEdit" style="font-size:16px" align="center">
								<?php echo $sub; ?>
							</td>
							<td class="toggleEdit" style="display:none">
								<input class="inputbox" style="font-size:16px" type="text" id="<?php echo $pre; ?>" name="details[subrow_1][sub_<?php echo $issub[1]; ?>]" value="<?php echo $sub; ?>" />
							</td>
						<?php } ?>
					<?php } ?>
					<td align="center">
						<a class="toggleEdit btn merge-btn" href="javascript:void(0);" onclick="return toggleEdit();" style="background-color:#3333CC;color:#FFF;display:block;font-size:11px;padding:5px;text-decoration:none;width:110px">
							<span>Edit Sub Plan Names</span>
						</a>
						<button type="submit" class="toggleEdit" style="display:none">
							<span>Save</span>
						</button>
					</td>
				</tr>

				<?php } ?>
				
				<tr style="background-color:#CCC">
				
					<td colspan="<?php echo $cols + 1; ?>">&nbsp;</td>
					<td align="center" width="14%">
						<a id="addSub" class="toggleEdit btn merge-btn" onclick="toggleSubs();" href="javascript:void(0);" style="background-color:#3333CC;color:#FFF;display:block;font-size:11px;padding:5px;text-decoration:none;width:110px">
							<span>Add Sub Plans</span>
						</a>
						
					</td>
					
				</tr>
				
				<tr id="addsubs">
					<td>&nbsp;</td>
					<td valign="top" colspan="<?php echo $cols; ?>">
						<div class="toggleSubs" style="display:none">
						<?php if( $cols == null || $cols == 1 ){ ?>
							<input type="text" id="addSub1" name="details[subrow_1][sub_1]" value="" style="width:48%" disabled="disabled" />
							<input type="text" id="addSub2" name="details[subrow_1][sub_2]" value="" style="width:48%" disabled="disabled" />
						<?php }else{ ?>
							<input type="text" id="addSub1" name="details[subrow_1][sub_<?php echo $cols + 1; ?>]" value="" style="width:150px" disabled="disabled" />
						<?php } ?>
							<button type="submit">
								<span>Save</span>
							</button>
						</div>
					</td>
					<td>&nbsp;</td>
				</tr>

				<!-- We already know how many rows we have since the 'package' was already created -->
				<!-- So, let's loop through each row and put fields & data in accordingly -->
				<?php foreach( $package as $namelabel => $name ){ $labelclass = explode( '_', $namelabel ); ?>
					
					<?php if( $labelclass[0] == 'row' ){ ?>
					
						<tr id="tablerow_<?php echo ($labelclass[1] + 1); ?>" class="<?php echo $labelclass[0]; ?>">
						
							<td width="25%"><?php echo $name; ?></td>
							
							
							<?php
								unset($spanarray);
								$cell = $dataArray['tablerow_'.($labelclass[1] + 1)];
								$i = 1;
								foreach( $cell as $ck => $c ){ $colspan = explode('_',$ck);
								
								if($colspan[2]){$ncs = $colspan[2];}else{$ncs = 1;}
								
							?>
							
								<td colspan="<?php echo $ncs; ?>" align="center">
									<input class="inputbox cell" id="tablerow_<?php echo $labelclass[1] + 1; ?>_cell_<?php echo $i; ?>" name="details[tablerow_<?php echo $labelclass[1] + 1; ?>][<?php echo $ck; ?>]" value="<?php echo $dataArray['tablerow_'.($labelclass[1] + 1)][$ck]; ?>" />
									<input class="mergebox" type="checkbox" id="details[tablerow_<?php echo $rowcount; ?>_join][<?php echo $ck; ?>]" onChange="return cellToggle(this);" />
								</td>
							
							<?php $i++; $spanarray[] = $ncs; } ?>
							
							<?php if( array_sum($spanarray) < $cols ){ ?>
								<td align="center" class="newcolspan<?php echo $newcolspan; ?>">
									<input class="inputbox cell" id="tablerow_<?php echo $labelclass[1] + 1; ?>_cell_<?php echo $i; ?>" name="details[tablerow_<?php echo $labelclass[1] + 1; ?>][cell_<?php echo $i; ?>]" />
									<input class="mergebox" type="checkbox" id="details[tablerow_<?php echo $rowcount; ?>_join][cell_<?php echo $i; ?>]" onChange="return cellToggle(this);" />
								</td>
							<?php } ?>
							
							<?php if( $cols > 1 ){ ?>
							<td width="14%" align="center" class="iequals<?php echo $i; ?>">
						    	<a class="btn merge-btn" href="javascript:void(0);" style="background-color:#000;color:#FFF;display:block;font-size:11px;padding:5px;text-decoration:none;width:110px" onclick="return mergeCells(this);">
						    		<span>Merge Selected Cells</span>
						    	</a>
						    </td>
					    	<?php } ?>
														

						</tr>
						
					<?php }else{ ?>
						
						<tr class="<?php echo $labelclass[0]; ?>">
							<td colspan="<?php echo $cols + 2; ?>">
								<?php echo $name; ?>
								<input type="hidden" id="tablerow_<?php echo $labelclass[1] + 1; ?>_cell_<?php echo $i; ?>" name="details[tablerow_<?php echo $labelclass[1] + 1; ?>]" value="&nbsp;" />
							</td>
						</tr>
						
					<?php } ?>
					
				<?php } ?>
			</table>
			
		<?php }else{ ?>
		
			
			<table border="1" width="100%">
				
				<tr>
					<td colspan="2"><?php print_r( $package ); ?></td>
				</tr>
				
				<tr style="background-color:#CCC">
				
					<td colspan="<?php echo $cols + 1; ?>">&nbsp;</td>
					<td align="center">
						<a id="addSub" class="toggleEdit btn merge-btn" onclick="toggleSubs();" href="javascript:void(0);" style="background-color:#3333CC;color:#FFF;display:block;font-size:11px;padding:5px;text-decoration:none;width:110px">
							<span>Add Sub Plans</span>
						</a>
						
					</td>
					
				</tr>
				
				<tr id="addsubs">
					<td>&nbsp;</td>
					<td valign="top" colspan="<?php echo $cols; ?>">
						<div class="toggleSubs" style="display:none">
						<?php if( $cols == null || $cols == 1 ){ ?>
							<input type="text" id="addSub1" name="details[subrow_1][sub_1]" value="" style="width:48%" disabled="disabled" />
							<input type="text" id="addSub2" name="details[subrow_1][sub_2]" value="" style="width:48%" disabled="disabled" />
						<?php }else{ ?>
							<input type="text" id="addSub1" name="details[subrow_1][sub_<?php echo $cols + 1; ?>]" value="" style="width:150px" disabled="disabled" />
						<?php } ?>
							<button type="submit">
								<span>Save</span>
							</button>
						</div>
					</td>
				</tr>
			
				<?php foreach( $package as $namelabel => $name ){ $labelclass = explode( '_', $namelabel ); ?>
					
					<?php if( $labelclass[0] == 'row' ){ ?>
					
						<tr class="<?php echo $labelclass[0]; ?>">
							<td width="25%"><?php echo $name; ?></td>
							<td>
								<input class="inputbox cell" id="details_<?php echo $labelclass[1]; ?>" name="details[tablerow_<?php echo $labelclass[1] + 1; ?>][cell_1]" />
							</td>
						</tr>
						
					<?php }else{ ?>
						
						<tr class="<?php echo $labelclass[0]; ?>">
							<td colspan="2"><?php echo $name; ?></td>
						</tr>
						
					<?php } ?>
					
				<?php } ?>
			</table>
			
		<?php } ?>
		
		<div class="btn-container" style="margin:20px 0">	
			<button type="submit">
				<span>Save</span>
			</button>
		</div>
		
		<input type="hidden" name="plan_id" value="<?php echo JRequest::getVar('id'); ?>" />
		<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('pid'); ?>" />
	
	</form>
	
</div>


















<?php if( $data ){ ?>
<div style="padding:100px 0">
	<h2>Plan View</h2>
	
	<h2>Dynamically-Generated Table:</h2>
	
	<table border="1">
	<?php foreach( $data as $key => $row ){ $pclass = explode( '_', $key ); ?>
		
		<?php if( is_object( $row ) ){ ?>
		
			<tr class="<?php echo $key; ?>">
				<?php if( $pclass[0] == "tablerow" ){ ?>
				<td><?php echo $packageArray[ 'row_'.($pclass[1] - 1) ]; ?></td>
				<?php }else{ ?>
				<td>&nbsp;</td>
				<?php } ?>
			
				<?php foreach( $row as $key => $cell ){ ?>
				
					<?php $cellclass = explode( '_', $key ); $cellcolspan = count( $cellclass ); ?>
					
					<!-- If the key has only one underscore its colspan is one if it has two underscores we need the last number in the key (the colspan) -->
					<?php if( $cellcolspan == 2 ){ ?>
						<td id="<?php echo $key; ?>" class="<?php echo $cellclass[0]; ?>"><?php echo $cell; ?></td>
					<?php }else{ ?>
						<td colspan="<?php echo $cellclass[($cellcolspan-1)]; ?>" id="<?php echo $key; ?>" class="<?php echo $cellclass[0]; ?>"><?php echo $cell; ?></td>
					<?php } ?>

				<?php } ?>
			</tr>
		
		<?php }else{ ?>
			<tr class="<?php echo $key; ?>">
				<td style="background-color:#CCC;border-right:none"><?php echo $packageArray[ 'header_'.($pclass[1] - 1) ]; ?></td>
				<td class="<?php echo $key; ?>" colspan="<?php echo $cols; ?>" style="background-color:#CCC;border-left:none"><?php echo $row; ?></td>
			</tr>
		<?php } ?>
		
	<?php } ?>
	</table>
</div>
<?php } ?>