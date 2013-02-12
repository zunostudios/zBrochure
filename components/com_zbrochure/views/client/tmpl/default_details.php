<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="form-column">
	
	<div class="inner">
			
		<div class="form-group">
			
			<div class="form-row">
				<label for="client_version_name">Company Name:</label>
				<input class="inputbox" type="text" name="client_version_name" id="client_version_name" value="<?php echo $this->client->client_version_name; ?>" />
			</div>
			
			<div class="form-row">
				<label for="client_version_address">Address:</label>
				<textarea class="inputbox" name="client_version_address" id="client_version_address"><?php echo $this->client->client_version_address; ?></textarea>
			</div>
			
		</div>
		
	</div>
	
</div>

<div class="form-column">

	<div class="inner">
	
		<div class="form-group">
		
			<div class="form-row">
				<label for="client_version_email">Company Email:</label>
				<input class="inputbox" type="text" name="client_version_email" id="client_version_email" value="<?php echo $this->client->client_version_email; ?>" />
			</div>
			
			<div class="form-row">
				<label for="client_version_phone_1">Phone:</label>
				<input class="inputbox" type="text" name="client_version_phone_1" id="client_version_phone_1" value="<?php echo $this->client->client_version_phone_1; ?>" />
			</div>
			
			<div class="form-row">
				<label for="client_version_phone_2">Phone 2:</label>
				<input class="inputbox" type="text" name="client_version_phone_2" id="client_version_phone_2" value="<?php echo $this->client->client_version_phone_2; ?>" />
			</div>
			
		</div>
	
	</div>

</div>

<div class="form-column">

	<div class="inner">

		<div class="form-group">
		
			<div class="form-row">
				<label for="client_version_website">Website:</label>
				<input class="inputbox" type="text" name="client_version_website" id="client_version_website" value="<?php echo $this->client->client_version_website; ?>" />
			</div>
		
			<div class="form-row">
				<label for="client_version_fax_1">Fax:</label>
				<input class="inputbox" type="text" name="client_version_fax_1" id="client_version_fax_1" value="<?php echo $this->client->client_version_fax_1; ?>" />
			</div>
		
		</div>
		
	</div>

</div>