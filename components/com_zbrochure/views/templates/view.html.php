<?php
/**
 * @version		v1
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2021 Zuno Enterprises, Inc. All rights reserved.
 * @license		
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for Template Listing
 */
class ZbrochureViewTemplates extends JView {
	
	protected $_templates;
	
	function display( $tpl = null ){
		
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$doc		= JFactory::getDocument();
		$model		= $this->getModel();
		
		//Get the template to edit
		$this->templates	= $model->getTemplates();
		
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
				
		parent::display($tpl);

	}
	
}