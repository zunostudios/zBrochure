<?php /*no direct access*/ defined('_JEXEC') or die('Restricted access');

//remove caption.js and J! Mootools file
$headerstuff=$this->getHeadData();
reset($headerstuff['scripts']);
foreach($headerstuff['scripts'] as $key=>$value){
	unset($headerstuff['scripts'][$key]);
}

$this->setHeadData($headerstuff);

$this->setGenerator('Zuno Studios');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
	<jdoc:include type="head" />

	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/base.css" type="text/css" />
	
	<!--[if lte IE 7]>
	<![endif]-->	
</head>

<body class="print">

<jdoc:include type="modules" name="notification" style="none" />

<a name="top"></a>

<div id="print-wrapper">

	<jdoc:include type="message" />
	<jdoc:include type="component" />

</div>

</body>
</html>