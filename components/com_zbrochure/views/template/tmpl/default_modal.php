<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<!-- START Jquery UI Modal -->
<div id="dialog-edit" title="Edit Text Block">

	<!-- START Build tabs for content types -->
	<div id="contenttabs">
		<ul>
		<?php $i = 1; foreach( $this->contenttypes as $type ){ ?>
			<li><a href="#tabs-<?php echo $i; ?>"><?php echo $type->content_type_name; ?></a></li>
		<?php $i++; } ?>
		</ul>
		
		<?php $c = 1; foreach( $this->contenttypes as $type ){ ?>
			<div id="tabs-<?php echo $c; ?>">
				<?php echo $this->loadTemplate($type->content_type_alias); ?>
			</div>
		<?php $c++; } ?>
	</div>
	<!-- END Build tabs for content types -->

</div>
<!-- End Jquery UI Modal -->