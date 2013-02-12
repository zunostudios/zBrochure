/**
 * zBrochure - Edit Blocks
 *
 * Edit control for image content blocks
 *
 * @version		1.0
 *
 * @license		GPL
 * @author		Jonathan Lackey / Zuno Studios
 * @copyright	Author
 */
 
$(document).ready(function(){
	
	edit_package_dialog = $( '<div style="display:none" class="loading" title="Edit Package &amp; Plans"><iframe id="edit-package-modal" width="100%" height="100%" frameBorder="0">No iframe support</iframe></div>').appendTo('body');
			
	edit_package_dialog.dialog({
	
		autoOpen:false,
		width: 1000,
		height: 600,
		modal: true,
		open: function( event, ui ){
			disable_scroll();
		},
		close: function( event, ui ){
			enable_scroll();
			$('#edit-package-modal').attr( 'src', '' );
		}
	
	});
	
});
 
function addColumn( plan, block_id, tab ){
	
	var colspan = parseInt( $( '#planName-'+block_id+'-'+tab ).attr('colspan') ) + 1;
	
	$( '#planName-'+block_id+'-'+tab ).attr('colspan', parseInt( colspan ) );
	
	$('#plan-'+block_id+'-'+tab+' tr.package-plan-row').each( function( index, element ){
		
		var packageLabelID = $(element).attr('id');
		
		$(element).append('<td class="plan-item"><textarea type="text" class="inputbox plan-inline-textarea" name="plans['+tab+'][plan]['+packageLabelID+'][plan_cell][]"></textarea></td>');
		
	});
	
	$( '#plan-'+block_id+'-'+tab+' td.table-header-row-td' ).each(function(){
		
		header_colspan = $(this).attr('colspan');
		
		$(this).attr( 'colspan', parseInt( header_colspan ) + 1 );
		
	});
	
	if( colspan == 2 ){
		
		$( '#plan-'+block_id+'-'+tab+' thead tr:first-child th:first-child' ).attr( 'rowspan', 2 );
		
		$( '#plan-'+block_id+'-'+tab+' thead' ).append( '<tr class="table-header plan-subs"></tr>' );
		$( '#plan-'+block_id+'-'+tab+' thead tr:last-child' ).append( '<th><textarea type="text" class="inputbox plan-inline-textarea" name="plans['+tab+'][network][]"></textarea></th>' );
		
		$( '#plan-'+block_id+'-'+tab+' tfoot tr th:last-child' ).append( '<button type="button" class="btn delete-btn icon-btn delete-col-btn"><span class="icon"><!-- --></span><span class="btn-text">Delete</span></button>' );
		
	}
	
	$( '#plan-'+block_id+'-'+tab+' thead tr:last-child' ).append( '<th><textarea type="text" class="inputbox plan-inline-textarea" name="plans['+tab+'][network][]"></textarea></th>' );
	
	$( '#plan-'+block_id+'-'+tab+' tfoot tr' ).append( '<th><button type="button" class="btn delete-btn icon-btn delete-col-btn"><span class="icon"><!-- --></span><span class="btn-text">Delete</span></button></th>' );
	
	assignColumnDeleteAction( plan, block_id, tab );
	
}

function deleteColumn( col, plan, block_id, tab ){
	
	var colspan = $( '#planName-'+block_id+'-'+tab ).attr('colspan');
	
	if( colspan == 2 ){
		
		$( '#plan-'+block_id+'-'+tab+' thead tr:last-child' ).remove();
		
	}
	
	$( '#planName-'+block_id+'-'+tab ).attr('colspan', parseInt( colspan ) - 1 );
	
	$('#plan-'+block_id+'-'+tab+' tr > td:nth-child( '+col+' )').remove();
	$('#plan-'+block_id+'-'+tab+' tfoot tr > th:nth-child( '+col+' )').remove();
	
	$( '#plan-'+block_id+'-'+tab+' td.table-header-row-td' ).each(function(){
		
		header_colspan = $(this).attr('colspan');
		
		$(this).attr( 'colspan', parseInt( header_colspan ) - 1 );
		
	});
	
	$('#plan-'+block_id+'-'+tab+' thead tr:nth-child(2) > th:nth-child('+(parseInt( col ) - 1 )+')').remove();
	
	assignColumnDeleteAction( plan, block_id, tab );
	
}

function assignColumnDeleteAction( plan, block_id, tab ){
	
	var cols 	= $( '#plan-'+block_id+'-'+tab+' tfoot th' );
	var btns	= $( '.delete-col-btn' );
	var count	= btns.length;
	
	if( cols.length <= 2 ){
	
		btns.remove();
	
	}else{		
			
		$('.delete-col-btn').each(function(){
			
			$(this).unbind( 'click' );
			
			var col = $(this).closest( 'th' );
			
			$(this).click(function(){
				
				var col_index = $(col).parent().children().index(col);				
				deleteColumn( parseInt( col_index ) + 1 , plan, block_id, tab );
				
			});
			
		});
		
	}
	
}

function getPlans( package_id, package_parent, plan_id, plan_parent, tab, block_id, brochure_id ){
	
	$('#plantabs-'+block_id+' .add-btn').css( 'display','block' );
	
	if( package_id != 0 ){
		
		$.ajax({
			
			url: 'index.php',
			data: { option:'com_zbrochure', task:'getPlans', package_id:package_id, package_parent:package_parent, plan_id:plan_id, plan_parent:plan_parent, tab:tab, block_id:block_id, brochure_id:brochure_id },
			type: 'POST'
			
		}).done(function( html ){
		
			$( '#tabs-'+block_id+'-'+tab+' div.plan-select-container' ).html( html );
			
		});
			
	}
	
	packageId = package_id;
	
}


function loadPlan( plan_id, package_id, block_id, brochure_id ){
	
	if( plan_id != 0 ){
		
		$.ajax({
			
			url: 'index.php',
			data: { option:'com_zbrochure', task:'loadPlanAjax', id:plan_id, pid:package_id, block_id:block_id, brochure_id:brochure_id },
			type: 'POST'
			
		}).done(function( html ){
		
			$( '#plan-table-container' ).html( html );
			
		});
			
	}
		
}

function getPackages( cat_id, block_id, brochure_id, active, bro_page_id ){
	
	$('#edit-package-modal').attr( 'src', '' );
	
	$.ajax({
				
		url: 'index.php',
		data: { option : "com_zbrochure", task : "getPackages", cid:cat_id, format:"selectlist", block:block_id, brochure:brochure_id, active:active, bro_page_id:bro_page_id },
		type: 'POST'
	
	}).done(function( html ){
		
		$( '#error-msg' ).remove();
			
		if( html == '0' ){
		
			$( '#package-select-container-'+block_id ).empty().append( '<span id="error-msg" class="error">No Packages</span>' );
		
		}else{
			
			$( '#package-select-container-'+block_id ).empty().append( html );
			
		}
	
	});

	
}

function clearPlans( id, block_id ){
	
	$('#plantabs-'+block_id+' ul.plan-tabs').empty();
	$('#plantabs-'+block_id+' div.ui-tabs-panel').remove();
	$('#plantabs-'+block_id+' .add-btn').css( 'display','block' );
	
	tab_counter[block_id] = 1;

}

function getData( plan_id, package_id, tab, block_id, brochure_id ){
	
	//Need to specify the tab number vs relying on ordered numbering
	
	if( plan_id != 0 ){

		$.ajax({
			
			url: 'index.php',
			data: { option : 'com_zbrochure', task : 'buildPlanTable', pid:package_id, id:plan_id, tab:tab, block_id:block_id, brochure_id:brochure_id },
			type: 'POST'
			
		}).done( function( html ){
			
			$( '#tabs-'+block_id+'-'+tab+' div.plan-table' ).html( html );
			assignColumnDeleteAction( plan_id, block_id, tab );
			
			selectedPlan	= $( '#plans-'+block_id+'-'+tab+' option:selected' ).text();
			tab_index		= $( '#tabs-'+block_id+'-'+tab ).attr('rel');
			short_title = selectedPlan.substring( 0, 10 );
			
			$( '#tab-'+block_id+'-'+tab_index ).attr( 'title', selectedPlan );
			$( '#tab-'+block_id+'-'+tab_index ).html( $.trim(short_title)+'...' );
		
		});
				
	}else{
	
		$( '#genTable-'+block_id ).html( '' );
		$( '.ui-tabs-nav li:nth-child('+block_id+') a').html( 'Select a Plan' );
	
	}
	
}

function addTab( block_id, brochure_id ){
	
	package_id = $('#predefined-packages-'+block_id+' option:selected' ).val();
	
	tabs = 'plantabs_'+block_id;
	
	$( '#plantabs-'+block_id ).tabs( "add", "#tabs-"+block_id+"-" + tab_counter[block_id], "New Plan" );
	
	getPlans( package_id, null, null, null, tab_counter[block_id], block_id, brochure_id );
	
	tab_counter[block_id] = tab_counter[block_id] + 1;

}

function setPackage( package_id, brochure_id, block_id, page_id ){
	
	$('#edit-package-modal').attr( 'src', 'index.php?option=com_zbrochure&view=package&id='+package_id+'&brochure_id='+brochure_id+'&page_id='+page_id+'&block_id='+block_id+'&action=savePackage&layout=modal&tmpl=modal' );

}