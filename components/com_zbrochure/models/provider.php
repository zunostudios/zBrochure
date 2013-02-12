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
class ZbrochureModelProvider extends JModelItem{

	var $_data 			= null;
	var $_allversions	= null;
	var $_contacts		= null;
	
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
		
		try{
		
			$db		= $this->getDbo();
			$query	= $db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_providers' );
			$query->where( 'provider_id = '.(int)$this->_id );
			
			$db->setQuery( $query );
			$this->_data = $db->loadObject();
			
			if( $error = $db->getErrorMsg() ){

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
	 * Method to get list of Contacts associated with
	 */
	public function getContacts(){
	
		if( empty($this->_contacts) ){
	
			$db = JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->select( '*' );
			$query->from( '#__zbrochure_providers_contacts' );
			$query->where( 'provider_id = '.$this->_id );
			$query->order( 'contact_name' );
			
			$db->setQuery($query);
			$this->_contacts = $db->loadObjectList();
		
		}
		
		return $this->_contacts;
	
	}
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$id		= $data['provider_id'];
		$row	= $this->getTable();
		
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
		$client_dir = JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.$client.DS.($id) );		
		$thumb_dir	= JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.$client.DS.($id).DS.'thumbnails' );
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		
		$tmp_file = JPATH_SITE.DS.'images'.DS.'provider'.DS.'tmp'.DS.$row->provider_logo;
		$new_file = $client_dir.DS.$row->provider_logo;
		
		$tmp_thumb = JPATH_SITE.DS.'images'.DS.'provider'.DS.'thumbnails'.DS.$row->provider_logo;
		$new_thumb = $thumb_dir.DS.$row->provider_logo;
		
		//$row->provider_logo = $newfile;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		if( !JFolder::exists($client_dir) ){
			JFolder::create($client_dir);
			//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
		}	
		
		if( JFile::exists($tmp_file) ){
			JFile::move( $tmp_file, $new_file );
		}
		
		if( !JFolder::exists($thumb_dir) ){
			JFolder::create($thumb_dir);
			//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
		}	
		
		if( JFile::exists($tmp_thumb) ){
			JFile::move( $tmp_thumb, $new_thumb );
		}
		
		return $row->provider_id;
		
	}
	
	
	
	/**
	 * Method to duplicate the client
	 */
	public function duplicate( $id ){
	
		try{
		
			$db		= $this->getDbo();
			$query	= $db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_providers' );
			$query->where( 'provider_id = '.(int)$id );
			
			$db->setQuery( $query );
			$data = $db->loadObject();
			
			if( $error = $db->getErrorMsg() ){

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
	
		$row	= $this->getTable();
	
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->provider_id = '';
		$row->provider_created = '';
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		
		//Now let's duplicate the contacts
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select( '*' );
		$query->from( '#__zbrochure_providers_contacts' );
		$query->where( 'provider_id = '.(int)$id );
		$query->order( 'contact_name' );
		
		$db->setQuery($query);
		$contacts = $db->loadObjectList();
		
		if( !empty( $contacts ) ){
		
			foreach( $contacts as $contact ){
			
				$prow	= $this->getTable('contacts');
			
			 	if( !$prow->bind( $contact ) ){
			 	
					$this->setError($this->_db->getErrorMsg());
					return false;
				
				}
				
				$prow->contact_id = '';
				$prow->provider_id = $row->provider_id;
				
				//alright, good to go. Store it to the Joomla db
				if( !$prow->store() ){
				
					$this->setError($this->_db->getErrorMsg());
					return false;
				
				}
				
			}
			
		}
				
		jimport( 'joomla.filesystem.file' );
		
		$client_dir = JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.($id) );
		$th_dir		= JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.($id).DS.'thumbnails' );
		$old_file 	= $client_dir.DS.$row->provider_logo;
		$old_th 	= $th_dir.DS.$row->provider_logo;
		$new_dir	= JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.($row->provider_id) );
		$new_thdir	= JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.($row->provider_id).DS.'thumbnails' );
		$new_file 	= $new_dir.DS.$row->provider_logo;
		$new_th		= $new_thdir.DS.$row->provider_logo;
		
		if( !JFolder::exists($new_dir) ){
			JFolder::create($new_dir);
			//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
		}	
		
		if( !JFolder::exists($new_thdir) ){
			JFolder::create($new_thdir);
			//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
		}
		
		if( JFile::exists($old_file) ){
			JFile::copy( $old_file, $new_file );
		}
		
		if( JFile::exists($old_th) ){
			JFile::copy( $old_th, $new_th );
		}
		
		return $row->provider_id;
		
	}
	
	
	/**
	 * Method to delete a contact
	 */
	public function deleteContact( $contactid ){
	
		$query = "DELETE FROM #__zbrochure_providers_contacts "
		."WHERE contact_id = ".(int)$contactid
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
	
	}
	
	/**
	 * Method to delete a provider
	 */
	public function delete( $id ){
	
		$query = "DELETE FROM #__zbrochure_providers "
		."WHERE provider_id = ".(int)$id
		;
		
		$this->_db->setQuery( $query );
		$this->_db->Query();
		
		return true;
	
	}
	
	/**
	 * Method to save the contacts
	 */
	public function saveContacts( $data, $providerid ){
	
	 	$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	 	
	 	$count = count( $data['name'] );
	 	
	 	for( $i=0; $i<$count; $i++){
	 	
	 		$row = $this->getTable('contacts');
	 		
	 		$contact[$i]['contact_id'] = $data['id'][$i];
	 		$contact[$i]['contact_name'] = $data['name'][$i];
	 		$contact[$i]['contact_phone'] = $data['phone'][$i];
	 		$contact[$i]['contact_email'] = $data['email'][$i];
	 		$contact[$i]['provider_id'] = $providerid;	 		
	 		
		 	if( !$row->bind( $contact[$i] ) ){
		 	
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
			
			//alright, good to go. Store it to the Joomla db
			if( !$row->store() ){
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
	 	
	 	}
		
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
	
		jimport( 'joomla.image.image' );
		jimport( 'joomla.filesystem.file' );
	
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;
		
		$client_dir 		= JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.'tmp'.DS );
		$thumbnail_dir		= JPath::clean( JPATH_SITE.DS.'images'.DS.'provider'.DS.'thumbnails'.DS );
		$uploader 			= new qqFileUploader($allowedExtensions, $sizeLimit);
		$result 			= $uploader->handleUpload($client_dir);
		
		$tmp_file = $client_dir.$result['filename'].'.'.$result['extension'];
		
		//Generate the thumbnail here
		$native		= new JImage();
		$newname 	= uniqid(rand());
		$native->loadFile( $tmp_file );	
		$resized 	= $native->resize( '100', '100', true, 2 );
		$thumbnailProps = Jimage::getImageFileProperties( $tmp_file );
		$resized->toFile( $thumbnail_dir.$result['filename'].'.'.$result['extension'], $thumbnailProps->type );
		//$native->toFile( $asset_dir.$result['filename'].'.'.$result['extension'], $thumbnailProps->type );
		
		$response['newname'] = $result['filename'];
		$response['extension'] = $result['extension'];
		
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($response), ENT_NOQUOTES);
		
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
            return array('success'=>true, 'filename'=>$filename, 'extension'=>$ext);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}