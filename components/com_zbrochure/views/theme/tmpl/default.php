<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

if($this->theme->theme_data){
	$this->theme_data	= json_decode( $this->theme->theme_data );
	$this->dataCount	= count($this->theme_data->color);
}else{
	$this->dataCount = 4;
}

?>
<form id="clientForm" name="clientForm" action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" enctype="multipart/form-data">

	<div id="sub-header">
		
		<div class="wrapper">
			
			<div id="top-bar">
				
				<h1><?php echo JText::_( 'EDIT_THEME_TITLE' ); echo ($this->theme->theme_name) ? ': '.$this->theme->theme_name : ': New'; ?></h1>
				
				<div class="btn-container">
						
					<?php if( $this->theme->theme_id ){ ?>
					<button type="button" class="btn delete-btn icon-btn delete-theme" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
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
				
				<div class="form-row-3">
					
					<div class="form-row">
						<div class="form-row-inner">
							<label for="theme_name"><?php echo JText::_( 'THEME_NAME' ); ?></label>
							<input id="theme_name" name="theme_name" class="inputbox" style="width:205px" value="<?php echo $this->theme->theme_name; ?>" />
						</div>
					</div>
					
					<div class="form-row">
						<div class="form-row-inner">
							<label for="theme_class"><?php echo JText::_( 'THEME_CLASS' ); ?></label>
							<input type="text" class="inputbox" name="data[class]" id="theme_class" style="margin:0 135px 0 0;width:200px" value="<?php echo $this->theme_data->class; ?>" />
						</div>
					</div>
					
					<div class="form-row">
						<div class="form-row-inner">
							<label for="published"><?php echo JText::_( 'FORM_LABEL_PUBLISHED' ); ?></label>
							<?php echo $this->published; ?>
						</div>
					</div>
					
					<div class="clear"><!-- --></div>
					
				</div>
					
				<?php echo $this->loadTemplate( 'colors' ); ?>
				
				<div id="accordion">
				
					<h3><?php echo JText::_( 'THEME_TYPOGRAPHY' ); ?></h3>	
					<div><?php echo $this->loadTemplate( 'typography' ); ?></div>
					
					<h3><?php echo JText::_( 'THEME_TABLE_STYLES' ); ?></h3>
					<div><?php echo $this->loadTemplate( 'table-styles' ); ?></div>
					
				</div>
					
				<div class="btn-container bottom-btn-container">
						
					<?php if( $this->theme->theme_id ){ ?>
					<button type="button" class="btn delete-btn icon-btn delete-theme" style="margin:0 30px 0 0"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'DELETE_BTN_GENERAL' ); ?></span></button>
					<?php } ?>
					
					<button type="button" class="btn cancel-btn icon-btn" onclick="window.location='<?php echo JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$this->list_menu_itemid ); ?>'; return false;"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'CANCEL_BTN_GENERAL' ); ?></span></button>
					
					<button type="submit" class="btn save-btn icon-btn"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'SAVE_BTN_GENERAL' ); ?></span></button>
				</div>
			
			</div>
		
		</div>
	
		<div class="clear"><!-- --></div>
	
	</div>
	
	<input type="hidden" name="option" value="com_zbrochure" />
	<input type="hidden" name="task" value="saveTheme" />
	<input type="hidden" name="version" value="<?php echo $this->theme->version; ?>" />
	<input type="hidden" name="theme_id" id="theme_id" value="<?php echo JRequest::getVar('id');?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->list_menu_itemid; ?>" />
	
</form>

<div id="delete-dialog" title="Delete Package">

	<div class="form-row add-padding package-container-header" style="background-color:#FFF">
		<p><?php echo JText::_( 'DELETE_CONFIRMATION_MSG' ); ?></p>
	</div>
	
	<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&task=deleteItem&id='.$this->theme->theme_id.'&type=theme&Itemid='.$this->list_menu_itemid ); ?>" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_THEME' ); ?></span></a>

</div>

<?php

echo $this->loadTemplate( 'styles' );
echo $this->loadTemplate( 'scripts' );