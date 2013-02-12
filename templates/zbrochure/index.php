<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */

//no direct access
defined('_JEXEC') or die('Restricted access');

//import Joomla HTML Parameter for JParameter
jimport( 'joomla.html.parameter' );

//get a reference to the global application object
$app = JFactory::getApplication();

//get a reference to the current user object
$user = JFactory::getUser();

//get the site name from the global configuration
$sitename = $app->getCfg('sitename');

//get the component name
$com = str_replace( 'com_', '', JRequest::getCmd('option', '') );

//get the view
$view = JRequest::getCmd('view', '');

//get the category/article ID
$id = (JRequest::getInt('id')) ? JRequest::getInt('id') : JRequest::getInt('catid');

//add a class with an ID so we can target specific categories or articles
switch( $view ){
	
	case 'category':
		$id = ' category-'.$id;
	break;
	
	case 'section':
		$id = ' section-'.$id;
	break;
	
	case 'article':
		$id = ' article-'.$id;
	break;
	
	case 'contact':
		$id = ' contact-'.$id;
	break;
	
	default:
		$id = '';
	break;

}

//get the current active menu item.
//use this to detect whether we're on the home page or not.
$menu = JSite::getMenu();
$active = $menu->getActive();

//get the active menu item's parameters
if( $active ){

	$params = new JParameter($active->params);
	
	//get the page class suffix from the active menu item
	$sfx = ($params->get( 'pageclass_sfx' )) ? ' '.$params->get( 'pageclass_sfx' ) : '';
	
	//get the page title from the active menu item
	$title = $params->get( 'page_heading' );
	
	$page_id = ($active->home) ? 'home' : 'sec';
	
}else{

	$sfx = ' no-id';
	$title = '';
	$page_id = 'sec';
	
}

//count modules
$main_menu = $this->countModules('main_menu');

$marquee = $this->countModules('marquee');
$cta = $this->countModules('cta');

$top = $this->countModules('top');
$top_columns = $this->countModules('top_columns');
$content_top = $this->countModules('content_top');
$breadcrumbs = $this->countModules('breadcrumbs');

//create variables to assign to the content
//area based on the page having left and right sidebars
$left = $this->countModules('left');
$right = $this->countModules('right');

$left_col = ( $left ) ? '-left' : '';
$right_col = ( $right ) ? '-right' : '';

$bottom = $this->countModules('bottom');
$bottom_columns = $this->countModules('bottom_columns');
$content_bottom = $this->countModules('content_bottom');

$footer = $this->countModules('footer');

//Need to get rid of the Base meta tag
$this->base = '';

//remove caption.js file
$headerstuff = $this->getHeadData();

reset($headerstuff['scripts']);
reset($headerstuff['styleSheets']);

//remove the caption.js file added by Joomla
foreach( $headerstuff['scripts'] as $key=>$value ){

   if("/media/system/js/caption.js" == $key)
      unset($headerstuff['scripts'][$key]);

   //if("/media/system/js/mootools.js" == $key)
      //unset($headerstuff['scripts'][$key]);
      
   //if("/media/system/js/mootools-core.js" == $key)
      //unset($headerstuff['scripts'][$key]);
      
}

//remove the modal.css file added by Joomla
//so we can override those styles in our CSS file
foreach( $headerstuff['styleSheets'] as $key=>$value ){   
   if("/media/system/css/modal.css" == $key)
      unset($headerstuff['styleSheets'][$key]);
}
       
$this->setHeadData( $headerstuff );

//Get rid of the Joomla generator meta tag
//mostly for security purposes
$this->setGenerator('Zuno Studios');

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

	<head>
		
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/base.css" type="text/css" />
		
	</head>

	<body id="<?php echo $page_id; ?>" class="<?php echo $com . ' ' . $view . $id . ' ' . $sfx; ?>">
	
		<div id="header">
			
			<div class="wrapper">
			
				<div id="logo-box"><a href="<?php echo JURI::base(); ?>" title="Bolton Brochure Generator Home">Home</a></div>
				
				<div id="header-sep"><!-- --></div>
				
				<div id="tagline"><h3>brochure generator 1.0</h3></div>
				
				<div id="utility-menu">
					
					<?php if( $user->guest ){ ?>
						
						<jdoc:include type="modules" name="user_menu_logged_out" style="zun" />
						
					<?php }else{ ?>
					
						<jdoc:include type="modules" name="user_menu_logged_in" style="zun" />
					
					<?php } ?>
					
					<jdoc:include type="modules" name="utility_module" style="zun" />
					<div class="clear"><!-- --></div>
				</div>
				
				<?php if( $main_menu ){ ?>
				<div id="main-menu">
					<jdoc:include type="modules" name="main_menu" style="zun" />
					<div class="clear"><!-- --></div>
				</div>
				<?php } ?>

				<div class="clear"><!-- --></div>
				
				<jdoc:include type="message" />
						
			</div>
			
		</div>
		
		<div id="content-wrapper">
		
			<jdoc:include type="component" />
		
		</div>
		
		<div id="footer">
		
			<jdoc:include type="modules" name="bottom" style="title" />
		
		</div>
		
		<jdoc:include type="modules" name="debug" />
		
	</body>
	
</html>