<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="vars-dialog" title="Available Variables">
	
	<?php echo $this->vars_list; ?>
	
</div>
<script type="text/javascript">
	$(document).ready(function(){
		
		vars = $( '#vars-dialog' ).dialog({
			autoOpen: false,
			resizable: true,
			height:400,
			width:300,
			close: function(){}
		});
		
		$( '.var-btn' ).each(function(index){
		
			$(this).click(function(){
			
				vars.dialog('open');
			
			});
		
		});
		
		
		
	});
</script>