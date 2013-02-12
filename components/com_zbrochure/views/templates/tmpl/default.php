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
			
			<h1><?php echo JText::_( 'TEMPLATES_TITLE' ); ?></h1>
						
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
	
			<ul>
			<?php foreach( $this->templates as $template ){
			
				$link	= JRoute::_( 'index.php?option=com_zbrochure&view=template&id='.$template->tmpl_id );
				echo '<li><a href="'.$link.'" title="Edit '.$template->tmpl_name.'">'.$template->tmpl_name.'</a></li>';
			
			} ?>
			</ul>
	
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>

</div>