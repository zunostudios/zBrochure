<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
$(function() {	

	tab_counter = 2;

	$plantabs = $( "#plantabs" ).tabs({
	
		tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
		add: function( event, ui ) {
			var tab_content = "Tab " + tab_counter + " content.";
			$( ui.panel ).append( "<div class=\"form-row plans-container-"+tab_counter+" \"></div><div id=\"genTable-"+tab_counter+"\"></div>" );
		}

	});
	
	//Prevents live() from executing twice on event -MM 03.19.2012
	$( "#plantabs span.ui-icon-close" ).die();
	
	//Remove tab
	$( "#plantabs span.ui-icon-close" ).live( "click", function() {
		var index = $( "li", $plantabs ).index( $( this ).parent() );
		$plantabs.tabs( "remove", index );
	});
	
});

function getPackages(id){

	if( id != 0 ){

		$('#plans-here').css('display', 'block');
	
		$.get('index.php?option=com_zbrochure&task=getPlans&package='+id+'&tab='+(tab_counter - 1), function(data) {
			
			$('div.plans-container').html( data );
			
			savePackage(id);
			
			packageId = id;
				
		});
	}
	
}

function getPlans(id, tab){

	if( id != 0 ){

		var plans = $('#plans-'+tab).val();
		
		var selectedPlan = $("#plans-"+tab+" option:selected").text();
		
		$('#plantabs .ui-tabs-nav li:nth-child('+tab+') a').html(selectedPlan);
	
		$.get('index.php?option=com_zbrochure&task=buildTable&package='+packageId+'&plan='+plans+'&tab='+tab, function(data) {
			
			if( tab.length != 0 ){
				
				$('#genTable-'+tab).html( data );
				
				savePlan(tab);
				
			}else{
				$('#genTable-1').html( data );
			}
		});
		
		
	
	}else{
	
		$('#genTable-'+tab).html( '' );
		$('#plantabs .ui-tabs-nav li:nth-child('+tab+') a').html('Select a Plan');
	
	}
	
}

function savePlan(tab){
	$.post('index.php?option=com_zbrochure&task=savePlanJQ', $('#saveplan-'+tab).serialize(),function(data){
		$('#saveplan-'+tab+' #plan_id').val(data);
	});
}

function savePackage(id){
	$.post('index.php?option=com_zbrochure&task=duplicatePackage&id='+id, function(data){
		$('#newpackageid').val(data);
		newPackageId = data;
	});
}
	
function addTab(packageId) {
	
	//var tab_title = $tab_title_input.val() || "Tab " + tab_counter;
	
	$plantabs.tabs( "add", "#tabs-" + tab_counter + "a", "Placer Plan Name" );
	
	$.get('index.php?option=com_zbrochure&task=getPlans&package='+packageId+'&tab='+(tab_counter), function(data) {
		
		$('div.plans-container-'+(tab_counter-1)).html( data );	
	});
	
	tab_counter++;

}
</script>

<div class="form-row">
	<label for="packages">Select Package:</label>
	<?php echo $this->packages; ?>
	<input type="hidden" id="newpackageid" name="newpackageid" />
</div>

<div class="form-row" id="plans-here" style="display:none">

	<a href="javascript:void(0);" onclick="addTab(packageId);">+ Add A Plan</a>
	
	<!-- START Build tabs for content types -->
	<div id="plantabs">
		<ul>
			<li><a href="#tabs-1a">Plan 1</a>  <span class='ui-icon ui-icon-close'>Remove Tab</span></li>
		</ul>
		
		<div id="tabs-1a">
		
			<div class="form-row plans-container">
				<?php //echo $this->plans; ?>
			</div>
		
			<div class="form-row">
				<div id="genTable-1"><!--   --></div>
			</div>
		</div>

	</div>
	<!-- END Build tabs for content types -->
	
</div>

<form action="index.php?option=com_zbrochure&task=savePackageblock">
	<input type="hidden" name="btable_id" />
	<input type="hidden" name="btable_package" />
	<input type="hidden" name="btable_plans[]" />
</form>

<style>
	#plantabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>