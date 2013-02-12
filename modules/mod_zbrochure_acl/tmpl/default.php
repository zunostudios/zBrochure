<?php
/**
 * @package		zBrochure.Site
 * @subpackage	mod_zbrochure_acl
 * @copyright	Copyright (C) 2012 Zuno Enterprises, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

if( $list ){
?>
<form action="<?php echo JRoute::_('index.php?Itemid='.$params->get('redirect') ); ?>" method="post" name="teamswitch" class="group-label">
	<?php if( $params->get( 'show_label' ) ){ ?>
	<label for="acl_team_id"><?php echo $params->get( 'select_label' ); ?></label>
	<?php } ?>
	<?php echo $list; ?>
</form>
<?php }else if( $single ){
	
	echo '<div class="group-label">'.$single.'</div>';
	
}?>