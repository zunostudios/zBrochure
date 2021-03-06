<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<div id="sub-header">

	<div class="wrapper">

		<div id="top-bar">
			
			<h1><?php echo JText::_( 'ZBROCHURE_ADMIN_TITLE' ); ?></h1>
			
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
		
			Admin modules will go here.
						
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>