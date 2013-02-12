<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<h3>Predefined Content</h3>

<div class="form-row">
	<label for="categories">Choose a Category:</label>
	<?php echo $this->categories; ?>
</div>

<div class="form-row">
	<label for="contentSelect">Choose Content:</label>
	<select id="contentSelect" onchange="placeContent(this.value);"></select>
</div>

<textarea class="inputbox" style="height:300px;width:100%" id="editor"></textarea>