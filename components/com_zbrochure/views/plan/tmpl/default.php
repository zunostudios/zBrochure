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

if( $this->plan->plan_details ){
	$data 		=  json_decode( $this->plan->plan_details );
	$dataArray 	= json_decode( $this->plan->plan_details, true);
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

$pid		= JRequest::getInt('pid');
$plan_id	= JRequest::getInt('id');

?>

<?php if( !empty( $pid ) ){ ?>
<script type="text/javascript">

function addColumn(){

	var colspan = $('#planName').attr('colspan');
	
	$('#planName').attr('colspan', (parseInt(colspan) + 1) );
	
	$('#plan tr.package-plan-row').each(function(index, element){
		var packageLabelID = $(element).attr('id');
		$(element).append('<td><textarea type="text" class="inputbox" name="plan['+packageLabelID+'][plan_cell][]" style="height:40px"></textarea></td>');
	});
	
	$('#plan tr:nth-child(2)').append('<td><textarea type="text" class="inputbox" name="network[]" style="height:40px"></textarea></td>');

}
</script>
<?php } ?>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			<h1><?php echo JText::_( 'EDIT_PLAN' ); ?></h1>
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
			
			<?php if( empty( $pid ) || empty($plan_id) ){ 
			
				echo $this->packages; ?>
				
				<script type="text/javascript">
					function assignPackage(pid){
						window.location='index.php?option=com_zbrochure&view=plan&pid='+pid;
					}
				</script>
				
			<?php } ?>
			
			<?php if( !empty( $pid ) ){ ?>
			<form id="planform" action="index.php?option=com_zbrochure&task=savePlan" method="post">
			
				<div style="padding:20px">
				
					<div class="form-row">
						<label for="plan_name"><?php echo JText::_( 'PLAN_NAME' ); ?></label>
						<input name="plan_name" id="plan_name" class="inputbox" value="<?php echo $this->plan->plan_name; ?>" style="width:250px" />
					</div>
					
					<div class="form-row">
						<a href="javascript:void(0);" onclick="addColumn();">+ Add Subplans</a>
					</div>
					
					<table id="plan" cellpadding="0" cellpadding="0" border="1" width="100%">
					<?php $details = json_decode($this->package->package_details); ?>
						
						<tbody>
						
							<tr>
								<td>&nbsp;</td>
								<td id="planName" colspan="<?php echo count(json_decode($this->plan->plan_subplan)); ?>" style="color:#404040;font-size:20px;text-align:center"><?php echo $this->plan->plan_name; ?></td>
							</tr>
						
							<tr class="plan-subs" bgcolor="#CCC">
								<td>&nbsp;</td>
								
								<?php if( $this->plan->plan_subplan ){ ?>
								
									<?php foreach( json_decode($this->plan->plan_subplan) as $subplan ){ ?>
									<td><textarea type="text" class="inputbox" name="network[]" style="height:40px"><?php echo $subplan; ?></textarea></td>
									<?php } ?>
									
								<?php }else{ ?>
								
								<td>
									<textarea type="text" class="inputbox" name="network[]" style="height:40px"></textarea>
								</td>
								
								<?php } ?>
								
							</tr>
							
							
							<!-- Start Package-Plan Relationship -->
							<?php foreach( $details as $label ){ ?>
								
								<?php if( !$label->is_header){ ?>
								
									<tr class="package-plan-row" id="<?php echo $label->package_label_id?>">
										
										<td><?php echo $label->package_label; ?></td>
										
										<?php if( !$data  ){ ?>
										
											<td>
												<textarea type="text" class="inputbox" name="plan[<?php echo $label->package_label_id; ?>][plan_cell][]" style="height:40px"></textarea>
											</td>
										
										<?php }else{ ?>
										
											<?php $i=0; foreach( $dataArray['package_label_id_'.$label->package_label_id]['cell'] as $cell ){ ?>
											<td colspan="<?php //echo $cell; ?>">
												<textarea type="text" class="inputbox" name="plan[<?php echo $label->package_label_id; ?>][plan_cell][]" style="height:40px"><?php echo $cell; ?></textarea>
											</td>
											<?php $i++; } ?>
											
										<?php } ?>
										
									</tr>
									
								<?php }else{ ?>
									<tr bgcolor="#CCCCCC">
										<td colspan="<?php echo count(json_decode($this->plan->plan_subplan)) + 1; ?>"><?php echo $label->package_label; ?></td>
									</tr>
								<?php } ?>
								
							<?php } ?>
							<!-- End Package-Plan Relationship -->
							
						</tbody>
						
					</table>
					
				</div>
				
				<div class="form-row">
					<button class="btn save-btn" type="submit">
						<span>Save Plan</span>
					</button>
				</div>
				
				<input type="hidden" name="plan_id" value="<?php echo JRequest::getInt('id'); ?>" />
				<input type="hidden" name="package_id" value="<?php echo $pid; ?>" />
				
			</form>
			<?php } ?>
			
		</div>
		
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>