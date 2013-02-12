<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="page tmpl-page<?php echo ( $this->spread ) ? ' spread-'.$this->spread_side : '' ; ?>" id="bro-page-<?php echo $this->blank_page->page_number; ?>" zpage="<?php echo $this->blank_page->page_number; ?>" style="text-align:center;width:<?php echo $this->page->width.''.$this->page->unit_of_measure;?>;height:<?php echo $this->page->height.''.$this->page->unit_of_measure;?>">

<button type="button" class="changelayout-btn btn build-bro-btn" style="margin:375px 0 0 0" zpage="0" zlayout="0" zorder="<?php echo $this->blank_page->page_number; ?>">Choose Layout</button>

</div>

<?php
if( !$this->spread || $this->spread_side == 1 ){ ?>
<div class="clear"><!-- --></div>
<?php } ?>