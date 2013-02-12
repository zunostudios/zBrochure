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
 * zBrochure Content Model
 */
class ZbrochureModelContent extends JModelItem{

	var $_data 			= null;
	var $_allversions	= null;
	var $_keyword		= null;
	var $_keywords		= null;
	var $_content		= null;
	var $_categories	= null;
	var $_place			= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.content';
	
	/**
	 * @var int id
	 */	
	protected $_id;
		
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
	
		//Load the id state from the request
		$this->_id = JRequest::getInt( 'id' );
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current content data
	 */
	public function getData(){

		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'a.*, c.*, c.cat_created AS catcreated, a.created AS content_created' );
			$query->from( '#__zbrochure_content AS a' );
			$query->join( 'LEFT', '#__zbrochure_categories AS c ON c.cat_id = a.catid' );
			$query->where( 'c.team_id = 0 OR c.team_id = '.(int)$active );
			$query->order( 'a.id' );
			
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObjectList();
			
			if( $error = $this->_db->getErrorMsg() ){

				throw new Exception( $error );

			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return $this->_data;
		
	}
	
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );

		$row = JTable::getInstance( 'content', 'Table' );
		$date = JFactory::getDate();
		
		$data['data'] = JRequest::getVar( 'data', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
	 	if( !$row->bind( $data ) ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		
		//if there are keywords associated with this asset
		if( is_array( $data['keywords'] ) ){
			
			//This is where we'll handle the keywords that were entered twice
			$newarray = array_map( 'strtolower', $data['keywords'] );
			unset($data['keywords']);
			$data['keywords'] = array_unique( $newarray );
			
			foreach( $data['keywords'] as $keyword  ){
			
				$kid[] = $this->checkKeyword($keyword);			    	
		    				
			}
			
			$row->keywords = json_encode($kid);
				
		}else{
			$row->keywords = '';
		}
		
		if( !$row->id ){
			$row->created = $date->toMySQL();
		}
		
		$row->modified = $date->toMySQL();
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * Method to delete content row
	 */
	public function delete($id){
		
		$query = "DELETE FROM #__zbrochure_content "
		."WHERE id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
		
	}
	
	/**
	 * Method to duplicate content row
	 */
	public function duplicate($id){
		
		$query	= $this->_db->getQuery( true );
		
		$query->select( '*' );
		$query->from( '#__zbrochure_content' );
		$query->where( 'id = '.(int)$id );
		
		$this->_db->setQuery( $query );
		$data = $this->_db->loadObject();
		
		$data->id = '';
		$data->created = '';
		$data->modified = '';
		$data->modified_by = 0;
		
		$array=array();
		
/*
		foreach( $data as $key => $value ){
			$array[$key] = $value;
		}
*/

		$row = JTable::getInstance( 'content', 'Table' );
		
	 	if( !$row->bind( $data ) ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function getContent($catid){
	
		if( empty($this->_content) ){
	
			$db = JFactory::getDBO();
			
			$query = "SELECT a.* "
			."FROM #__zbrochure_content AS a "
			."WHERE a.catid = ".$catid
			;
			
			$db->setQuery($query);
			$this->_content = $db->loadObjectList();
		
		}
		
		return $this->_content;
	
	}
	
	/**
	 * Method to save the client
	 */
	public function placeContent( $id ){
	
		if( empty($this->_place) ){
	
			$db = JFactory::getDBO();
			
			$query = "SELECT a.* "
			."FROM #__zbrochure_content AS a "
			."WHERE a.id = ".$id
			;
			
			$db->setQuery($query);
			$this->_place = $db->loadObject();
		
		}
		
		return $this->_place;
	
	}
	
	/**
	 * Method to save the client
	 */
	public function getCategories(){
	
		if( empty($this->_categories) ){
	
			$db = JFactory::getDBO();
			
			$query = "SELECT a.* "
			."FROM #__zbrochure_categories AS a "
			."ORDER BY a.cat_name "
			;
			
			$db->setQuery($query);
			$this->_categories = $db->loadObjectList();
		
		}
		
		return $this->_categories;
	
	}
	
	/**
	 * Method to check if a keyword exists
	 */
	public function checkKeyword($keyword){
		
		$query = "SELECT * "
		."FROM #__zbrochure_assets_keywords "
		."WHERE keyword = '".$keyword."' "
		;
		
		$this->_db->setQuery( $query );
		$this->_keyword = $this->_db->loadObject();
	
		if( !$this->_keyword ){
		
			//create an array with all of the database fields
			$asset = array(
				'keyword'		=> $keyword
			);
			
			//create a table instance to bind the asset to
			$insert = JTable::getInstance( 'keywords', 'table' );
			
			//bind the array with the table instance
			if( !$insert->bind( $asset ) ){
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			//store the asset to the database
			if( !$insert->store() ){
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			$this->_kid = $insert->keyword_id;
		
		}else{
			$this->_kid = $this->_keyword->keyword_id;
		}
				
		return $this->_kid;
		
	}
	
	/**
	 * Method to get all keywords
	 */
	public function getKeywords(){
		
		if( empty($this->_keywords) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_assets_keywords AS a "
			;
					
			$this->_db->setQuery( $query );
			$this->_keywords = $this->_db->loadObjectlist();
		
		}
		
		return $this->_keywords;
	}
	
	/**
	 * Method to upload the preview image
	 */
	public function upload(){
	
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;
		
		$asset_dir = JPath::clean( JPATH_SITE.DS.'images'.DS.'assets'.DS );
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($asset_dir);
		
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		
	}
	
}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
        	print_r($uploadDirectory);
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}