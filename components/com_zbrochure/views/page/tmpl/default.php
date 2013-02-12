<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">
<head>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base().'components/com_zbrochure/assets/css/tmpl-base.css'; ?>" />
<?php echo $this->loadTemplate('styles');?>
</head>
<body class="pdf">
<?php
$this->page->width = $this->tmpl->tmpl_page_width;
$this->page->height = $this->tmpl->tmpl_page_height;
$this->page->unit_of_measure = $this->tmpl->tmpl_unit_of_measure;
$this->page->page_number = $i;
$this->page->page_number_formatted = sprintf( $this->tmpl->tmpl_page_number_format, $i );	
echo $this->loadTemplate( 'layout' );
?>
</body>
</html>