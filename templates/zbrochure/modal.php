<?php /*no direct access*/ defined('_JEXEC') or die('Restricted access');

//Need to get rid of the Base meta tag
$this->base = '';

$this->setHeadData($headerstuff);
$this->setGenerator('Zuno Studios');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/c/base.css" type="text/css" />
</head>

<body class="modal-win">

<jdoc:include type="modules" name="notification" style="none" />

<a name="top"></a>

<div id="modal-wrapper">
	
	<jdoc:include type="message" />
	<jdoc:include type="component" />
		
</div>

</body>
</html>