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
class ZbrochureModelClient extends JModelItem{

	var $_client		= null;
	var $_themes		= null;
	var $_logos			= null;
	var $_allversions	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.client';
	
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
	 * Method to get current client data
	 */
	public function getClient(){
		
		if( empty($this->_client) ){
			
			try{
			
				
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'c.*, v.*' );
				$query->from( '#__zbrochure_client_versions AS v' );
				$query->join( 'INNER', '#__zbrochure_clients AS c ON c.client_current_version = v.client_version_id' );
				$query->where( 'c.client_id = '.$this->_id.' AND v.client_version_id = c.client_current_version' );
				
				$this->_db->setQuery( $query );
				$this->_client = $this->_db->loadObject();
				
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
		
		}
		
		return $this->_client;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getLogos(){
		
		if( empty($this->_logos) ){
			
			try{
			
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'l.*' );
				$query->from( '#__zbrochure_client_logos AS l' );
				$query->where( 'l.client_id = '.$this->_id );
				
				$this->_db->setQuery( $query );
				$this->_logos = $this->_db->loadObjectList();
				
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
		
		}
		
		return $this->_logos;
		
	}
		
	/**
	 * Method to get current client data
	 */
	public function getThemes(){
	
		if( empty($this->_themes) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_themes AS a "
			;
					
			$this->_db->setQuery( $query );
			$this->_themes = $this->_db->loadObjectList();
		
		}
		
		return $this->_themes;
		
	}



	/**
	 * Method to duplicate the client
	 */
	public function duplicate( $id ){
	
	 	$app		= JFactory::getApplication();
		$user		= JFactory::getUser()->get('id');
		$client		= $post['client_id'];
		$row		= $this->getTable();
		$com_params	= JComponentHelper::getParams('com_zbrochure');	
		
		//Create a new client for the duplication
		try{
		
			
			$query	= $this->_db->getQuery(true);
			
			$query->insert( '#__zbrochure_clients' )
    				->columns( 'client_created_by, client_published, client_current_version, app_owner' )
    				->values( $user.', 1, 0, 1' );
							
			$this->_db->setQuery($query);
			$this->_db->query();
    	
    		$client = $this->_db->insertid();

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
		
		
		//Duplicate the client_versions row in the db		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_client_versions' );
			$query->where( 'client_id = '.(int)$id );
			$query->order( 'client_version_id DESC' );
			
			$this->_db->setQuery( $query );
			$data = $this->_db->loadObject();
			
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
		
	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->client_version_id = '';
		$row->client_id	= $client;
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );			
			return false;
		
		}
		
		//update the client version number with the new client_version_id number
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->update( '#__zbrochure_clients' )
    				->set( 'client_current_version = '.$row->client_version_id )
    				->set( 'client_version = client_version + 1' )
    				->where( 'client_id = '.$client );
							
			$this->_db->setQuery($query);
			$this->_db->query();
    	
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
		
		//Almost done. Let's duplicate all the logos
		$query	= $this->_db->getQuery(true);
		
		foreach( $this->getLogos() as $logo ){
			
			$query->insert( '#__zbrochure_client_logos' )
			->columns( 'client_id, client_logo_default, client_logo_name, client_logo_filename, client_logo_filetype, client_logo_created_by' )
			->values( $client.', '.$logo->client_logo_default.', "'.$logo->client_logo_name.'", "'.$logo->client_logo_filename.'", "'.$logo->client_logo_filetype.'", '.$user );
			
			$this->_db->setQuery($query);
			$this->_db->query();
			
			if( $error = $this->_db->getErrorMsg() ){throw new Exception( $error );}
			
			//Let's move the logos
			$client_dir	= $com_params->get( 'client_file_path' );
			$tmp_dir	= $client_dir.DS.'tmp'.DS;
			
			$old_file	= $client_dir.DS.$id.DS.'logos'.DS.$logo->client_logo_filename.'.'.$logo->client_logo_filetype;
			$old_th		= $client_dir.DS.$id.DS.'logos'.DS.'th'.DS.$logo->client_logo_filename.'.'.$logo->client_logo_filetype;
			
			$new_file	= $client_dir.DS.$client.DS.'logos'.DS.$logo->client_logo_filename.'.'.$logo->client_logo_filetype;
			$new_th		= $client_dir.DS.$client.DS.'logos'.DS.'th'.DS.$logo->client_logo_filename.'.'.$logo->client_logo_filetype;
			
			if( !JFolder::exists($client_dir.DS.'logos'.DS.$client.DS) ){
				JFolder::create($client_dir.DS.'logos'.DS.$client.DS);
				//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
			}
			
			if( !JFolder::exists($client_dir.DS.$client.DS.'logos'.DS.'th'.DS) ){
				JFolder::create($client_dir.DS.$client.DS.'logos'.DS.'th'.DS);
				//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
			}	
			
			if( JFile::exists($old_file) ){
				JFile::move( $old_file, $new_file );
			}
			
			if( JFile::exists($old_th) ){
				JFile::move( $old_th, $new_th );
			}
			
		}
	
		return $client;
	
	}

	/**
	 * Method to save the client
	 */
	public function store( $post, $files ){
	
	 	$app		= JFactory::getApplication();
		$user		= JFactory::getUser()->get('id');
		
		$client		= $post['client_id'];
		
		$row		= $this->getTable();
		
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		
		if( !$client ){
		
			try{
			
				$query	= $this->_db->getQuery(true);
				
				$query->insert( '#__zbrochure_clients' )
	    				->columns( 'client_created_by, client_published, client_current_version, app_owner' )
	    				->values( $user.', 1, 0, 1' );
								
				$this->_db->setQuery($query);
				$this->_db->query();
	    	
	    		$client = $this->_db->insertid();
	
				if( $error = $this->_db->getErrorMsg() ){
	
					throw new Exception( $error );
	
				}
	
				if( empty( $client ) ){
				
					return JError::raiseError( 404, JText::_('COM_ZBROCHURE_CLIENT_NOT_SAVED') );
				
				}
			
			}catch( JException $e ){
			
				if( $e->getCode() == 404 ){
				
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError( 404, $e->getMessage() );
				
				}else{
				
					$this->setError($e);
				
				}
	
			}	    	
		
		}
		
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
		//$client_dir = JPath::clean( JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'clients'.DS.$client );
		$client_dir	= $com_params->get( 'client_file_path' );
		$tmp_dir	= $client_dir.DS.'tmp'.DS;
		
		if( $post['logos'] ){
		
			foreach( $post['logos'] as $logo ){
			
				$query	= $this->_db->getQuery(true);
			
				if( $logo['deletelogo'] ){
				
					
					$query->delete();
					$query->from( '#__zbrochure_client_logos' );
					$query->where( 'client_logo_id = '.(int)$logo['deletelogo'] );
					$this->_db->setQuery($query);
			
					if( !$this->_db->query() ){
					
						$this->setError( $this->_db->stderr() );
						return false;
					}
				
				}
				
				if( !$logo['id'] ){
				
					$query->insert( '#__zbrochure_client_logos' )
	    					->columns( 'client_id, client_logo_default, client_logo_name, client_logo_filename, client_logo_filetype, client_logo_created_by' )
	    					->values( $client.', "'.$logo['default'].'", "'.$logo['name'].'", "'.$logo['filename'].'", "'.$logo['extension'].'", '.$user );
	    					
	    		}else{
	    		
					$query->update( '#__zbrochure_client_logos' )
			    				->set( 'client_id = '.$client )
			    				->set( 'client_logo_default = "'.$logo['default'].'"' )
			    				->set( 'client_logo_name = "'.$logo['name'].'"' )
			    				->set( 'client_logo_filename = "'.$logo['filename'].'"' )
			    				->set( 'client_logo_filetype = "'.$logo['extension'].'"' )
			    				->set( 'client_logo_created_by = '.$user )
			    				->where( 'client_logo_id = '.$logo['id'] );
			    				
	    		}
	    				
					
				$this->_db->setQuery($query);
				$this->_db->query();
				
				if( $error = $this->_db->getErrorMsg() ){ throw new Exception( $error ); }
				
				//Let's move the native and thumbnail images to the client-specific folder
				$tmp_file = $tmp_dir.$logo['filename'].'.'.$logo['extension'];
				$new_file = $client_dir.DS.$client.DS.'logos'.DS.$logo['filename'].'.'.$logo['extension'];
				
				$tmp_th = $tmp_dir.'th'.DS.$logo['filename'].'.'.$logo['extension'];
				$new_th = $client_dir.DS.$client.DS.'logos'.DS.'th'.DS.$logo['filename'].'.'.$logo['extension'];
				
				if( !JFolder::exists($client_dir.DS.'logos'.DS.$client.DS) ){
					JFolder::create($client_dir.DS.'logos'.DS.$client.DS);
					//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
				}
				
				if( !JFolder::exists($client_dir.DS.$client.DS.'logos'.DS.'th'.DS) ){
					JFolder::create($client_dir.DS.$client.DS.'logos'.DS.'th'.DS);
					//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
				}	
				
				if( JFile::exists($tmp_file) ){
					JFile::move( $tmp_file, $new_file );
				}
				
				if( JFile::exists($tmp_th) ){
					JFile::move( $tmp_th, $new_th );
				}
				
			
			}
		
		}
		
	 	if( !$row->bind( $post ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->client_id	= $client;
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );			
			return false;
		
		}
		
		//update the client version number with the new client_version_id number
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->update( '#__zbrochure_clients' )
    				->set( 'client_current_version = '.$row->client_version_id )
    				->set( 'client_version = client_version + 1' )
    				->where( 'client_id = '.$client );
							
			$this->_db->setQuery( $query );
			$this->_db->query();
    	
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
		
		return $client;
		
	}
	
	/**
	 * Method to get all version of the client
	 */
	public function getVersions(){
	
		if( empty($this->_allversions) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_client_versions AS a "
			."WHERE a.client_id = ".$this->_id
			;
					
			$this->_db->setQuery( $query );
			$this->_allversions = $this->_db->loadObjectList();
		
		}
		
		return $this->_allversions;
		
	}
	
	/**
	 * Method to upload the preview image
	 */
	public function getPreview(){
		
		//import the new Joomla image manipulation class
		jimport( 'joomla.image.image' );
		jimport( 'joomla.filesystem.file' );
		
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		$client_dir	= $com_params->get( 'client_file_path' );
		$tmp_dir	= $client_dir.DS.'tmp'.DS;
		
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;
		
		$uploader = new qqFileUploader( $allowedExtensions, $sizeLimit );
		$result = $uploader->handleUpload( $tmp_dir );
		
		//This is where we handle the image manipulation
		//resizing, moving, cropping, etc.
		$tmp_file = $tmp_dir.$result['filename'].'.'.$result['extension'];
		
		$newname 	= uniqid(rand());
		$new_file	= $tmp_dir.$newname.'.'.$result['extension'];
		
		if( JFile::exists($tmp_file) ){
			JFile::move( $tmp_file, $new_file );
		}

		$response['newname']	= $newname;		
		$response['extension']	= $result['extension'];

		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars( json_encode($response), ENT_NOQUOTES );
		
	}
	
	/*
	 * Method to initialize the app data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * 
	 */
	 /*
	function _initData(){
	 
	 	if( empty($this->_client) ){
	 	
	 		$client = new stdClass();
			$client->id = null;
			$client->app_name = null;
			$client->app_alias = null;
			$client->app_desc = null;
			$client->app_details = null;
			$client->app_version = null;
			$client->app_download = null;
			$client->app_download_type = null;
			$client->app_download_file = null;
			$client->app_url = null;
			$client->cid = null;
			$client->pid = null;
			$client->app_th = null;
			$client->app_icon = null;
			$client->app_featured = null;
			$client->button_text = null;
			$client->button_type = null;
			$client->app_support = null;
			$client->app_docs = null;
			$client->app_docs_link_text = null;
			$client->app_install = null;
			$client->app_it = null;
			$client->app_config = null;		
			$client->publish_up = null;
			$client->publish_down = null;
			$client->published = null;
			$client->created = null;
			$client->created_by = null;
			$client->modified = null;
			$client->modified_by = null;
			$client->checked_out = null;
			$client->checked_out_time = null;
			$client->version = null;
			$client->ordering = null;
			$client->hits = null;
			$client->metatitle = null;
			$client->metakey = null;
			$client->metadesc = null;
			
	 		$this->_client = $client;
	 		return (boolean) $this->_client;
	 	
	 	}
	 	
	 	return true;
	 
	}
	*/
	
	
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
    
    function getName(){
    
        return $_GET['qqfile'];
    
    }
    
    function getSize(){
        
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
    function handleUpload( $uploadDirectory, $replaceOldFile = FALSE ){
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
            return array( 'success'=>true, 'filename' => $filename, 'extension' => $ext );
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}