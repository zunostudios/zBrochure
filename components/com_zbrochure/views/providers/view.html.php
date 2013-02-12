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
JHTML::stylesheet( 'jquery.dataTables.css', 'components/com_zbrochure/assets/css/' );
JHTML::script( 'jquery.dataTables.min.js', 'components/com_zbrochure/assets/js/' );

/**
 * HTML View class for Template Editing
 */
class ZbrochureViewProviders extends JView {
	
	protected $template;
	protected $state;
	
	function display( $tpl = null ){
		
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$doc		= JFactory::getDocument();
		
		//Get the template to edit
		$this->data				= $this->get( 'data' );
				
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		parent::display($tpl);

	}
	
}