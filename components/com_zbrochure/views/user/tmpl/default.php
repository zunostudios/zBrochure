<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 
	
JHTML::script( 'jquery.validate.js', 'components/com_zbrochure/assets/js/' );
	
?>

<div id="sub-header">
	
	<div class="wrapper">
	
		<div id="top-bar">
			<h1><?php echo JText::_( 'ADDUSER_TITLE' ); ?></h1>	
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
		
			<form id="add-user" method="post" enctype="application/x-www-form-urlencoded" action="index.php?option=com_zbrochure&task=addUser">
			
				<div class="form-row">
					<label for="name"><?php echo JText::_('LABEL_NAME_GENERAL'); ?></label>
					<input type="text" id="name" name="name" class="inputbox required" />
				</div>
				
				<div class="form-row">
					<label for="username"><?php echo JText::_('LABEL_EMAIL_GENERAL'); ?></label>
					<input type="text" id="username" name="username" onblur="checkEmail(this.value);" class="inputbox required email" />
				</div>
			
				<?php echo JHtml::_('access.usergroups', 'jform[groups]', '', true); ?>
				
				<button type="submit" class="btn">
					<span>Submit</span>
				</button>
			
			</form>
			
			<div class="clear"><!--   --></div>
			
		</div>
	
	</div>
	
	<div class="clear"><!--   --></div>
	
</div>

<script>
$(document).ready(function(){
	$('#add-user').validate();
});


function checkEmail( email ){

	$.get('index.php?option=com_zbrochure&task=checkEmail&email='+email, function(data) {
		
		if( data != 1){
			$('#username').val('');
			$('#username').css('border', '1px solid red');
			$('#username').attr('placeholder', 'Please enter a valid email');
			alert('Email address provided is already in use');
		}else{
			$('#username').css('border', '1px solid #CCC');
		}
		
	});
	
}
</script>