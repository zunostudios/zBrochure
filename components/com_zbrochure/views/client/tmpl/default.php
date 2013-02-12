<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

//Change these to be pulled from the component parameters
$client_dir = JURI::base().'images'.DS.'client'.DS.JRequest::getVar('id');
$client_tmp = JURI::base().'images'.DS.'client'.DS.'tmp'.DS;

echo $this->loadTemplate( 'styles' );
echo $this->loadTemplate( 'scripts' );

?>
<form id="clientForm" name="clientForm" action="<?php echo JRoute::_( 'input.php?option=com_zbrochure&task=saveClient'); ?>" method="post" enctype="multipart/form-data">

	<div id="sub-header">
		
		<div class="wrapper">
			
			<div id="top-bar">
				
				<?php if( $this->client->client_version ){ ?>
				<h1><?php echo JText::_( 'CLIENT_DETAILS_TITLE'); ?> <span style="font-size:14px;font-weight:lighter;text-transform:uppercase">Current Version #<?php echo $this->client->client_version; ?></span></h1>
				<?php }else{ ?>
				<h1><?php echo JText::_( 'CLIENT_DETAILS_TITLE'); ?></h1>
				<?php } ?>
				
				<div class="btn-container">
						
					<?php if( $this->client ){ ?>
					<button type="button" class="btn delete-btn icon-btn delete-client" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
					<?php } ?>
					
					<button type="button" class="btn cancel-btn icon-btn" onclick="window.location='<?php echo JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$this->list_menu_itemid ); ?>'; return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
					
					<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
				
				</div>
				
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
			
				<div class="three-col-form" style="margin:0 0 20px 0">
				
					<?php echo $this->loadTemplate( 'details' ); ?>
					
					<div class="clear"><!--   --></div>
			
					<div id="accordion">
					
						<h3><?php echo JText::_( 'CLIENT_LOGOS' ); ?></h3>	
						
						<div style="padding:0 20px">
							<?php echo $this->loadTemplate( 'logos' ); ?>						
						</div>
						
						<h3><?php echo JText::_( 'CLIENT_THEME' ); ?></h3>	
						
						<div style="padding:0 20px">
							<?php echo $this->loadTemplate( 'themes' ); ?>
						</div>
					
					</div>
				
				</div>
				
				<div class="clear"><!-- --></div>
				
				<div class="btn-container fr">
						
					<?php if( $this->client ){ ?>
					<button type="button" class="btn delete-btn icon-btn delete-client" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
					<?php } ?>
					
					<button type="button" class="btn cancel-btn icon-btn" onclick="window.location='<?php echo JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$this->list_menu_itemid ); ?>'; return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
					
					<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
				
				</div>
				
				<div class="clear"><!-- --></div>
				
				<input type="hidden" name="client_version_id" id="client_version_id" value="" />
				<input type="hidden" name="client_id" id="client_id" value="<?php echo JRequest::getVar('id');?>" />
				<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
			
			</div>
			
		</div>
		
		<div class="clear"><!-- --></div>
	
	</div>
	
	<div id="delete-dialog" title="<?php echo JText::_('DIALOG_TITLE_DELETE_CLIENT'); ?>">
	
		<div class="form-row add-padding package-container-header" style="background-color:#FFF">
			<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
		</div>
		
		<a href="<?php echo $delete_link; ?>" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_CLIENT' ); ?></span></a>
	
	</div>

</form>