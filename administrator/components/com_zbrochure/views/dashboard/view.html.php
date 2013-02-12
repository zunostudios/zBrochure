<?php
/**
 * Zuno Studios
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for the dashboard.
 *
 * @package		Zuno.Administrator
 * @subpackage	com_zbrochure
 * @since		2.5
 */
class ZbrochureViewDashboard extends JView{

	/**
	 * Display the view
	 *
	 * @return	void
	 */
	public function display($tpl = null){
		
		$this->_addToolbar();
		
		parent::display($tpl);
		
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	2.5
	 */
	protected function _addToolbar(){
	
		JToolBarHelper::title(JText::_('COM_ZBROCHURE_DASHBOARD_TITLE'), 'article.png');
		JToolBarHelper::preferences('com_zbrochure');
		JToolBarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER');
		
	}
}
