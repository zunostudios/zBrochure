<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');
$theme = json_decode($this->theme->theme_data);
?>
<style type="text/css">
@page{
	margin:0;
	padding:0;
	size:US-Letter;
}
<?php
	echo '.headline{color:'.$theme->headline.'}'.PHP_EOL;
	echo '.subheadline{color:'.$theme->subheading.'}'.PHP_EOL;
	echo '.textarea{color:'.$theme->paragraph.'}'.PHP_EOL;
	
	foreach( $theme->color as $k=>$v ){
		
		echo '.theme-bg-'.$k.'{fill:rgb('.$v.')}'.PHP_EOL;
	
	}
		
	foreach( json_decode( $this->tmpl->tmpl_default_styles ) as $class_name=>$class ){
		
		echo $class_name.'{';
			
		foreach( $class->styles as $k=>$v ){
		
		echo $k.':'.$v.';';
	
		}
		
		echo '}'.PHP_EOL;
		
	} ?>
</style>