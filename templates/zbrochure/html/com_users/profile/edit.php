<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;

JHTML::stylesheet( 'tmpl-base.css', 'components/com_zbrochure/assets/c/' );

//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load( 'plg_user_profile', JPATH_ADMINISTRATOR );

$doc		= JFactory::getDocument();
$renderer	= $doc->loadRenderer( 'modules' );
$raw		= array( 'style' => 'none' );

$left_modules = $renderer->render( 'left', $raw, null );

$columns_class	= ( $left_modules ) ? 'left-middle' : 'middle';

?>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			
			<h1><?php echo JText::_( 'My Account' ); ?></h1>
	
		</div>
	</div>
</div>

<div class="view-wrapper<?php echo ' '.$columns_class; ?>">
	
	<?php if( $left_modules ){ ?>
	<div id="left">
		<div class="inner">
		<?php echo $left_modules; ?>
		</div>
	</div>
	<?php } ?>
	
	<div id="middle">
	
		<div class="inner">

			<div class="profile-edit<?php echo $this->pageclass_sfx?>">
			
			<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
			<?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>
				<?php $fields = $this->form->getFieldset($group);?>
				<?php if (count($fields)):?>
				<fieldset>
					<dl>
					<?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
						<?php if ($field->hidden):// If the field is hidden, just display the input.?>
							<?php echo $field->input;?>
						<?php else:?>
							<dt>
								<?php echo $field->label; ?>
								<?php if (!$field->required && $field->type!='Spacer'): ?>
								<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
								<?php endif; ?>
							</dt>
							<dd><?php echo $field->input; ?></dd>
						<?php endif;?>
					<?php endforeach;?>
					</dl>
				</fieldset>
				<?php endif;?>
			<?php endforeach;?>
			
					<div style="text-align:right">
								
						<button type="submit" class="validate btn save-btn"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
	
						<input type="hidden" name="option" value="com_users" />
						<input type="hidden" name="task" value="profile.save" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>
			</div>
			
		</div>
	</div>
	<div class="clear"><!-- --></div>
</div>
