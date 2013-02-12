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
JHTML::script( 'jquery.tablesorter.min.js', 'components/com_zbrochure/assets/js/' );

/**
 * HTML View class for Template Editing
 */
class ZbrochureViewUser extends JView {
	
	protected $template;
	protected $state;
	
	function display( $tpl = null ){
		
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$doc		= JFactory::getDocument();
		
		//Get the template to edit
		$this->users			= $this->get( 'users' );
		$this->teams			= $this->get( 'teams' );
		$this->admins			= $this->get( 'admins' );
		$this->teamrel			= $this->get( 'teamrel' );
				
		$renderer				= $doc->loadRenderer( 'modules' );
		$raw					= array( 'style' => 'raw' );
		$this->left_modules		= $renderer->render( 'left', $raw, null );
		$this->columns_class	= ( $this->left_modules ) ? 'left-middle' : 'middle';
		
		parent::display($tpl);

	}
	
}