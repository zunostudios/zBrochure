<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

echo $this->loadTemplate('styles');
echo $this->loadTemplate( 'top-bar' ); 

?>
<div class="two-column-left">
	
	<div id="main-preview" class="page-edit">
		<?php
		
		$i = 1;
		
		foreach( $this->pages as $page ){
			
			$this->page							= $page;
			$this->page->width					= $this->tmpl->tmpl_page_width;
			$this->page->height					= $this->tmpl->tmpl_page_height;
			$this->page->unit_of_measure		= $this->tmpl->tmpl_unit_of_measure;
			$this->page->page_number			= $i;
			$this->page->page_number_formatted	= sprintf( $this->tmpl->tmpl_page_number_format, $i );	
			
			echo $this->loadTemplate( 'layout' );
			
			$i++;
			
		} ?>
	</div>

</div>
<?php
echo $this->loadTemplate( 'change-layout' );
echo $this->loadTemplate( 'add-page' );
echo $this->loadTemplate( 'var-list' );
echo $this->loadTemplate( 'scripts' );