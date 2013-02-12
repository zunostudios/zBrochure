<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

JHTML::script( 'fileuploader.js', 'components/com_zbrochure/assets/js/' );
$provider_dir = JURI::base().'images'.DS.'provider'.DS.JRequest::getVar('id');
$provider_tmp = JURI::base().'images'.DS.'provider'.DS.'tmp'.DS;

?>

<link href="<?php echo JURI::base(); ?>components/com_zbrochure/assets/css/fileuploader.css" rel="stylesheet" type="text/css">	

<script type="text/javascript">
$(document).ready(function(){
	
	createUploader();
	
	$('.qq-upload-button').mouseenter( function(){
		$('div.qq-upload-drop-area').css('display','block');
	}).mouseleave(function() {
		$('div.qq-upload-drop-area').css('display','none');
	});
	
	equalHeight($('.form-column'));
	
});

/* Jquery Equal Heights */
function equalHeight(group) {
	var tallest = 0;
	group.each(function() {
		var thisHeight = $(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}

function addContact(elem){
	var parent = $(elem).parent().parent();
	$('#toAdd').clone().insertAfter(parent);
	$('#toAdd').css('display','block');
	$('#toAdd').attr('id','');
	return false;
}

function deleteContact(elem, contactid){

	var confirmed = confirm('Are you sure you want to delete this contact?');
	
	if(confirmed){

		if(contactid != null){
		
			$.get('<?php echo JRoute::_('index.php?option=com_zbrochure&task=deleteContact&contactid='); ?>'+contactid, function(data) {
				var parent = $(elem).parent().parent();
				$(parent).remove();
				
				var count = $('.add-contact-container').length;
				
				if( count <= 1 ){
					addContact();
				}
				
			});
			
		}else{
		
			var parent = $(elem).parent().parent();
			$(parent).remove();
			var count = $('.add-contact-container').length;
			if( count <= 1 ){addContact();}
			
		}
	
	}

}
   
function createUploader(){            
    var uploader = new qq.FileUploader({
        element: document.getElementById('file-uploader-demo1'),
        allowedExtensions: ['jpg','jpeg','gif','png'],
        action: 'index.php?option=com_zbrochure&task=uploadProviderPreview',
        debug: true,
        onSubmit: function(id, fileName){
        	$('#provider_logo').val(fileName);
        },
        onComplete: function(id, fileName, responseJSON){
        
			var newfilename = responseJSON.newname+'.'+responseJSON.extension;
						
			$('#client-logos').append('<li><img src="<?php echo JURI::base(); ?>images/provider/thumbnails/'+newfilename+'" /></li>');
        
        	//$('#fullcolor').css('background-image','url(<?php echo $provider_tmp; ?>'+ fileName +')');
        
        }
    });           
}

// in your app create uploader as soon as the DOM is ready
// don't wait for the window to load  
//window.onload = createUploader;     
</script>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			
			<h1>Provider</h1>
			
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
	
			<form id="clientForm" name="clientForm" action="input.php?option=com_zbrochure&task=saveProvider" method="post" enctype="multipart/form-data">
			
				<div class="three-col-form">
				
					<div class="form-column">
						
						<div class="inner">
								
							<div class="form-group">
								
								<div class="form-row">
									<label for="provider_name">Provider Name:</label>
									<input class="inputbox" type="text" name="provider_name" id="provider_name" value="<?php echo $this->data->provider_name; ?>" />
								</div>
								
								<div class="form-row">
									<label for="provider_email">Provider Email:</label>
									<input class="inputbox" type="text" name="provider_email" id="provider_email" value="<?php echo $this->data->provider_email; ?>" />
								</div>
								
								<div class="form-row">
									<label for="provider_website">Provider Website:</label>
									<input class="inputbox" type="text" name="provider_website" id="provider_website" value="<?php echo $this->data->provider_website; ?>" />
								</div>
								
								<div class="form-row">
									<label for="provider_address">Address:</label>
									<textarea class="inputbox" name="provider_address" id="provider_address"><?php echo $this->data->provider_address; ?></textarea>
								</div>
								
							</div>
							
						</div>
						
					</div>
					
					<div class="form-column">
			
						<div class="inner">
					
							<div class="form-group">
								
								<div class="form-row">
									<label for="provider_phone_1">Phone:</label>
									<input class="inputbox" type="text" name="provider_phone_1" id="provider_phone_1" value="<?php echo $this->data->provider_phone_1; ?>" />
								</div>
								
								<div class="form-row">
									<label for="provider_phone_2">Phone 2:</label>
									<input class="inputbox" type="text" name="provider_phone_2" id="provider_phone_2" value="<?php echo $this->data->provider_phone_2; ?>" />
								</div>
								
								<div class="form-row">
									<label for="provider_fax">Fax:</label>
									<input class="inputbox" type="text" name="provider_fax" id="provider_fax" value="<?php echo $this->data->provider_fax; ?>" />
								</div>
								
							</div>
							
						</div>
					
					</div>
					
					<div class="form-column">
					
						<div class="inner">
					
							<div class="form-group">
											
								<div class="form-row">
									<label for="provider_logo">Provider Logo:</label>
									<div class="client-logo-preview">
										<div id="fullcolor" class="inner">
										</div>
									</div>
								
									<input type="hidden" name="provider_logo" id="provider_logo" value="<?php echo $this->data->provider_logo; ?>" />
									<div id="file-uploader-demo1">		
										<noscript>			
											<p>Please enable JavaScript to use file uploader.</p>
											<!-- or put a simple form for upload here -->
										</noscript>         
									</div>					
			
								</div>
								
								<div class="form-row">
									<ul id="client-logos">
										<?php if( $this->data->provider_logo ){ ?>
										<li><img src="<?php echo $provider_dir.DS.$this->data->provider_logo; ?>" width="100" /></li>
										<?php } ?>
									</ul>
								</div>
							
							</div>
			
						</div>
					
					</div>
					
					<div class="clear"><!--   --></div>
					
				</div>
				
				<div id="contact-container" class="form-row">
					<h3>Add Contacts</h3>
					
					<?php if( $this->contacts ){ ?>
						<?php $i = 1; foreach( $this->contacts as $contact ){ ?>
						
							<div class="add-contact-container">
								<div class="form-row provider-contact">
									<label for="name-<?php echo $i; ?>">Name:</label>
									<input id="name-<?php echo $i; ?>" class="inputbox" name="contact[name][]" value="<?php echo $contact->contact_name; ?>" />
								</div>
								
								<div class="form-row provider-contact">
									<label for="phone-<?php echo $i; ?>">Phone Number:</label>
									<input id="phone-<?php echo $i; ?>" class="inputbox" name="contact[phone][]" value="<?php echo $contact->contact_phone; ?>" />
								</div>
								
								<div class="form-row provider-contact">
									<label for="email-<?php echo $i; ?>">Email:</label>
									<input id="email-<?php echo $i; ?>" class="inputbox" name="contact[email][]" value="<?php echo $contact->contact_email; ?>" />
								</div>
								
								<input type="hidden" name="contact[id][]" value="<?php echo $contact->contact_id; ?>" />
								
								<div class="form-row provider-contact">
									<button id="contact-<?php echo $contact->contact_id; ?>" class="btn delete-btn" title="Click to Add a Contact" onclick="deleteContact(this, <?php echo $contact->contact_id; ?>);return false;">
										<span>-</span>
									</button>
									
									<button class="btn add-btn" title="Click to Add a Contact" onclick="addContact(this);return false;">
										<span>+</span>
									</button>
								</div>
								
								<div class="clear"><!--   --></div>
							</div>
						
						<?php $i++; } ?>
					<?php } ?>
					
					
					<?php if( !$this->contacts ){ ?>
					<div class="add-contact-container">
						<div class="form-row provider-contact">
							<label for="name-1">Name:</label>
							<input id="name-1" class="inputbox" name="contact[name][]" />
						</div>
						
						<div class="form-row provider-contact">
							<label for="phone-1">Phone Number:</label>
							<input id="phone-1" class="inputbox" name="contact[phone][]" />
						</div>
						
						<div class="form-row provider-contact">
							<label for="email-1">Email:</label>
							<input id="email-1" class="inputbox" name="contact[email][]" />
						</div>
						
						<div class="form-row provider-contact">
							<button class="btn delete-btn" title="Click to Delete this Contact" onclick="deleteContact(this,null);return false;">
								<span>-</span>
							</button>
							
							<button class="btn add-btn" title="Click to Add a Contact" onclick="addContact(this);return false;">
								<span>+</span>
							</button>
						</div>
						
						<div class="clear"><!--   --></div>
					</div>
					<?php } ?>
					
				</div>
				
				<div class="btn-container">
					<?php $cancel_link = JRoute::_('index.php?option=com_zbrochure&Itemid='.$this->list_menu_itemid); ?>
					<button id="cancel" class="btn cancel-btn" onclick="window.location = '<?php echo $cancel_link;?>'; return false;">
						<span>Cancel</span>
					</button>
					
					<button id="save" class="btn save-btn" type="submit">
						<span>Save</span>
					</button>
					
				</div>
				
				<input type="hidden" name="provider_version_id" id="provider_version_id" value="" />
				<input type="hidden" name="provider_id" id="provider_id" value="<?php echo JRequest::getVar('id');?>" />
				<input type="hidden" name="Itemid" value="<?php echo $this->item_menu_itemid; ?>" />
			
			</form>
			
			<div id="toAdd" class="add-contact-container" style="display:none">
				<div class="form-row provider-contact">
					<label>Name:</label>
					<input class="inputbox" name="contact[name][]" />
				</div>
				
				<div class="form-row provider-contact">
					<label>Phone Number:</label>
					<input class="inputbox" name="contact[phone][]" />
				</div>
				
				<div class="form-row provider-contact">
					<label>Email:</label>
					<input class="inputbox" name="contact[email][]" />
				</div>
				
				<div class="form-row provider-contact">
					<button class="btn delete-btn" title="Click to Add a Contact" onclick="deleteContact(this, null);return false;">
						<span>-</span>
					</button>
				
					<button class="btn add-btn" onclick="addContact(this);return false;">
						<span>+</span>
					</button>
				</div>
				
				<div class="clear"><!--   --></div>
			</div>
			
		</div>
		
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>

<style>
.add-contact-container{display:table;padding:0 0 20px}
.provider-contact{display:table-cell;padding:0 10px 0 0;vertical-align:bottom}
</style>