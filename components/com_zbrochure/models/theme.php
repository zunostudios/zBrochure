<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.modelitem' );

/**
 * zBrochure Template Model
 */
class ZbrochureModelTheme extends JModelItem{

	var $_data 			= null;
	var $_allversions	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.template';
	
	/**
	 * @var int id
	 */	
	protected $_id;
	
	/**
	 * @var int id
	 */	
	protected $_active_page;
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id = JRequest::getInt( 'id' );
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getData(){
	
		if( empty($this->_allversions) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_themes AS a "
			."WHERE a.theme_id = ".$this->_id
			;
					
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		
		}
		
		return $this->_data;
		
	}
	
	/**
	 * Method to parse CSS from JSON data
	*/
	private function _parseCss( $data ){
		
		//Need a much better way to do this. To hard-coded.
		$css	= '';
		
		$css	.= '.headline{color:'.$data['headline'].'}'.PHP_EOL;
		$css	.= '.subheadline{color:'.$data['subheading'].'}'.PHP_EOL;
		$css	.= '.textarea{color:'.$data['paragraph'].'}'.PHP_EOL;
		$css	.= '.reversed td, .reversed th{color:'.$data['paragraph'].'}'.PHP_EOL;
		$css	.= '.callout{background-color:'.$data['callout'].'}'.PHP_EOL;
		$css	.= '.reversed{color:'.$data['reversed'].'}'.PHP_EOL;
		$css	.= '.reversed .subheadline{color:'.$data['reversed'].'}'.PHP_EOL;
		$css	.= '.reversed .headline{color:'.$data['reversed'].'}'.PHP_EOL;
		$css	.= '.reversed .textarea{color:'.$data['reversed'].'}'.PHP_EOL;
		$css	.= '.textarea table, .table table, .contacts table{'.$data['package_styles_table'].'}'.PHP_EOL;
		$css	.= '.textarea thead th, .table thead th, .contacts thead th{'.$data['package_styles_thead_th'].'}'.PHP_EOL;
		$css	.= '.textarea td, .textarea th, .table td, .table th, .contacts td, .contacts th{'.$data['package_styles_th_td'].'}'.PHP_EOL;
		$css	.= '.textarea tr:nth-child(odd) td, .table tr:nth-child(odd) td, .contacts tr:nth-child(odd) td{'.$data['package_styles_alt_0_td'].'}'.PHP_EOL;
		$css	.= '.textarea tr:nth-child(even) td, .table tr:nth-child(even) td, .contacts tr:nth-child(even) td{'.$data['package_styles_alt_1_td'].'}'.PHP_EOL;
		$css	.= '.textarea td.package-label, .table td.package-label, .contacts td.package-label{'.$data['package_styles_td_package_label'].'}'.PHP_EOL;
		$css	.= '.textarea td.plan-item, .table td.plan-item, .contacts td.plan-item{'.$data['package_styles_td_plan_item'].'}'.PHP_EOL;
		$css	.= '.textarea tr.table-header-row td, .table tr.table-header-row td, .contacts tr.table-header-row td{'.$data['package_styles_header_row_td'].'}'.PHP_EOL;
		$css	.= '.textarea tfoot th, .table tfoot th, .contacts tfoot th{'.$data['package_styles_tfoot_th'].'}'.PHP_EOL;
		$css	.= '.textarea .empty-cell, .table .empty-cell, .contacts .empty-cell{'.$data['package_styles_empty_cell'].'}'.PHP_EOL;
		
		return $css;
		
	}
	
	/**
	 * Method to generate CSS file
	*/
	private function _generateCss( $styles, $filename, $theme_id ){
		
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );
		
		$root		= JPATH_SITE.'/media/zbrochure/themes';		
		$theme_path	= JPath::clean( $root.'/'.$theme_id.'/' );
		
		if( !JFolder::exists( $theme_path ) ){
		
			JFolder::create( $theme_path );
			
			$index	= '<html>'.PHP_EOL.'<body bgcolor="#FFFFFF">'.PHP_EOL.'</body>'.PHP_EOL.'</html>';
			JFile::write( $theme_path . "/index.html", $index );
		
		}
		
		JFile::write( $theme_path . $filename, $styles );
		
		if( !JFile::exists( $theme_path . $filename ) ){
			
			return false;
			
		}
		
		return;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$id		= $data['theme_id'];
		
		$parse_css		= $this->_parseCss( $data['data'] );
		$theme_styles	= $this->_generateCss( $parse_css, 'theme_styles.css', $data['theme_id'] );
		
		
		$row	= $this->getTable();
		$active = $user->get( 'active_team' );
	 	
		$jsondata = json_encode( $data['data'] );
		unset( $data['data'] );		
		
		$data['theme_data'] = $jsondata;
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( !$row->theme_id ){  	
		
			$is_new = 1;
			$row->theme_created_by	= $user->id;
		
		}else{
			
			$date = JFactory::getDate();
			$row->theme_modified_by	= $user->id;
			$row->theme_modified	= $date->toSql();			
			$row->version++;
		
		}
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		return $row;
		
	}
	
	
	/**
	 * Method to duplicate theme
	 */
	public function duplicate( $id ){
		
		$this->_id					= $id;
		
		$app						= JFactory::getApplication();
	 	$user						= JFactory::getUser();
		$row						= $this->getTable();
				
	 	if( !$row->load( $id ) ){
	 	
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		
		}
		
		//Let's get rid of the plan id so now it thinks
		//we have a new record and will store it as such i.e. duplicated bitches
		$row->theme_id				= '';
		$row->theme_name			= $row->theme_name.' Copy';
		$row->theme_created_by		= $user->id;
		$row->theme_created			= '';
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row->theme_id;
	
	}
	/**
	 * Method to delete a theme
	 */
	public function delete($id){
		
		$query = "DELETE FROM #__zbrochure_themes "
		."WHERE theme_id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function getCount( $cvid ){
	
		$db = JFactory::getDBO();
		
		$query = "SELECT a.* "
		."FROM #__zbrochure_client_versions AS a "
		."WHERE a.client_id = ".$cvid
		;
		
		$db->setQuery($query);
		$versions = $db->loadObjectList();
		
		return count($versions);
	
	}
	
	/**
	 * Method to save the client
	 */
	public function getVersions(){
	
		if( empty($this->_allversions) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_client_versions AS a "
			."WHERE a.client_id = ".$this->_id
			;
					
			$this->_db->setQuery( $query );
			$this->_allversions = $this->_db->loadObjectlist();
		
		}
		
		return $this->_allversions;
		
	}
	
	/**
	 * Method to upload the preview image
	 */
	public function getPreview(){
	
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;
		
		$client_dir = JPath::clean( JPATH_SITE.DS.'images'.DS.'client'.DS.'tmp'.DS );
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($client_dir);
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars( json_encode($result), ENT_NOQUOTES );
		
	}
	
}