<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="pagetitle-dialog" title="Set Page Title">	
	<form action="index.php?option=com_zbrochure&task=pageTitle" method="post">

		<div class="form-row text-container">
		
			<div class="form-row add-padding package-container-header">
				<label for="pagetitle_pagetitle">Page Title</label>
				<input style="width:98%" class="inputbox" type="text" id="pagetitle_pagetitle" name="pagetitle_pagetitle" value="" />
			</div>
	
			<input type="hidden" id="pagetitle_pageid" name="pagetitle_pageid" value="" />
			
		</div>
		
		<div class="form-row btn-container add-padding" style="margin:10px 0">
			<button class="btn save-btn fr" type="submit"><span>Save</span></button>
			<button class="btn cancel-btn fr" onclick="location.reload(); return false;"><span>Cancel</span></button>
		</div>
		
	</form>
</div>

<script type="text/javascript">
$(document).ready(function(){

	$( ".pagetitle-btn" ).click(function(){
		var page = $(this).attr('rel');
		var title = $(this).attr('title');
		$('#pagetitle_pageid').attr('value', page);
		$('#pagetitle_pagetitle').attr('value', title);
		$("#pagetitle-dialog").dialog('open');
	});

	$( "#pagetitle-dialog" ).dialog({
		autoOpen: false,
		resizable: true,
		height:190,
		width:600,
		modal: false,
		close: function(){}
	});

});
</script>