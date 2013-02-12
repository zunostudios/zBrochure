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
	
			<div class="profile<?php echo $this->pageclass_sfx?>">
			
			<?php echo $this->loadTemplate('core'); ?>
			
			<?php echo $this->loadTemplate('params'); ?>
			
			<?php echo $this->loadTemplate('custom'); ?>
			
			<?php if (JFactory::getUser()->id == $this->data->id) : ?>
			
			<a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>"><?php echo JText::_('COM_USERS_Edit_Profile'); ?></a>
			
			<?php endif; ?>
			
			</div>
	
		</div>
	
	</div>
	
	<div class="clear"><!-- --></div>
	
</div>