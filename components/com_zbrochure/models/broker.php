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
 * zBrochure Broker Model
 */
class ZbrochureModelBroker extends JModelItem{

	var $_broker		= null;
	var $_themes		= null;
	var $_logos			= null;
	var $_allversions	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.broker';
	
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
	 * Method to get current broker data
	 */
	public function getBroker(){
		
		if( empty($this->_broker) ){
			
			try{
			
				
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'c.*, v.*' );
				$query->from( '#__zbrochure_broker_versions AS v' );
				$query->join( 'INNER', '#__zbrochure_brokers AS c ON c.broker_current_version = v.broker_version_id' );
				$query->where( 'c.broker_id = '.$this->_id.' AND v.broker_version_id = c.broker_current_version' );
				
				$this->_db->setQuery( $query );
				$this->_broker = $this->_db->loadObject();
				
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
		
		return $this->_broker;
		
	}
	
	/**
	 * Method to get current broker data
	 */
	public function getLogos(){
		
		if( empty($this->_logos) ){
			
			try{
			
				$query	= $this->_db->getQuery( true );
				
				$query->select( 'l.*' );
				$query->from( '#__zbrochure_broker_logos AS l' );
				$query->where( 'l.broker_id = '.$this->_id );
				
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
	 * Method to duplicate the broker
	 */
	public function duplicate( $id ){
	
	 	$app		= JFactory::getApplication();
		$user		= JFactory::getUser()->get('id');
		$broker		= $post['broker_id'];
		$row		= $this->getTable();
		$com_params	= JComponentHelper::getParams('com_zbrochure');	
		
		//Create a new broker for the duplication
		try{
		
			
			$query	= $this->_db->getQuery(true);
			
			$query->insert( '#__zbrochure_brokers' )
    				->columns( 'broker_created_by, broker_published, broker_current_version, app_owner' )
    				->values( $user.', 1, 0, 1' );
							
			$this->_db->setQuery($query);
			$this->_db->query();
    	
    		$broker = $this->_db->insertid();

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
		
		
		//Duplicate the broker_versions row in the db		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__zbrochure_broker_versions' );
			$query->where( 'broker_id = '.(int)$id );
			$query->order( 'broker_version_id DESC' );
			
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
		
		$row->broker_version_id = '';
		$row->broker_id	= $broker;
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );			
			return false;
		
		}
		
		//update the broker version number with the new broker_version_id number
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->update( '#__zbrochure_brokers' )
    				->set( 'broker_current_version = '.$row->broker_version_id )
    				->set( 'broker_version = broker_version + 1' )
    				->where( 'broker_id = '.$broker );
							
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
			
			$query->insert( '#__zbrochure_broker_logos' )
			->columns( 'broker_id, broker_logo_default, broker_logo_name, broker_logo_filename, broker_logo_filetype, broker_logo_created_by' )
			->values( $broker.', '.$logo->broker_logo_default.', "'.$logo->broker_logo_name.'", "'.$logo->broker_logo_filename.'", "'.$logo->broker_logo_filetype.'", '.$user );
			
			$this->_db->setQuery($query);
			$this->_db->query();
			
			if( $error = $this->_db->getErrorMsg() ){throw new Exception( $error );}
			
			//Let's move the logos
			$broker_dir	= $com_params->get( 'broker_file_path' );
			$tmp_dir	= $broker_dir.DS.'tmp'.DS;
			
			$old_file	= $broker_dir.DS.$id.DS.'logos'.DS.$logo->broker_logo_filename.'.'.$logo->broker_logo_filetype;
			$old_th		= $broker_dir.DS.$id.DS.'logos'.DS.'th'.DS.$logo->broker_logo_filename.'.'.$logo->broker_logo_filetype;
			
			$new_file	= $broker_dir.DS.$broker.DS.'logos'.DS.$logo->broker_logo_filename.'.'.$logo->broker_logo_filetype;
			$new_th		= $broker_dir.DS.$broker.DS.'logos'.DS.'th'.DS.$logo->broker_logo_filename.'.'.$logo->broker_logo_filetype;
			
			if( !JFolder::exists($broker_dir.DS.'logos'.DS.$broker.DS) ){
				JFolder::create($broker_dir.DS.'logos'.DS.$broker.DS);
				//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
			}
			
			if( !JFolder::exists($broker_dir.DS.$broker.DS.'logos'.DS.'th'.DS) ){
				JFolder::create($broker_dir.DS.$broker.DS.'logos'.DS.'th'.DS);
				//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
			}	
			
			if( JFile::exists($old_file) ){
				JFile::move( $old_file, $new_file );
			}
			
			if( JFile::exists($old_th) ){
				JFile::move( $old_th, $new_th );
			}
			
		}
	
		return $broker;
	
	}

	/**
	 * Method to save the broker
	 */
	public function store( $post, $files ){
	
	 	$app		= JFactory::getApplication();
		$user		= JFactory::getUser()->get('id');
		
		$broker		= $post['broker_id'];
		
		$row		= $this->getTable();
		
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		
		if( !$broker ){
		
			try{
			
				$query	= $this->_db->getQuery(true);
				
				$query->insert( '#__zbrochure_brokers' )
	    				->columns( 'broker_created_by, broker_published, broker_current_version, app_owner' )
	    				->values( $user.', 1, 0, 1' );
								
				$this->_db->setQuery($query);
				$this->_db->query();
	    	
	    		$broker = $this->_db->insertid();
	
				if( $error = $this->_db->getErrorMsg() ){
	
					throw new Exception( $error );
	
				}
	
				if( empty( $broker ) ){
				
					return JError::raiseError( 404, JText::_('COM_ZBROCHURE_BROKER_NOT_SAVED') );
				
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
	
		//$broker_dir = JPath::clean( JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'brokers'.DS.$broker );
		$broker_dir	= $com_params->get( 'broker_file_path' );
		$tmp_dir	= $broker_dir.DS.'tmp'.DS;
		
		if( $post['logos'] ){
		
			foreach( $post['logos'] as $logo ){
			
				$query	= $this->_db->getQuery(true);
			
				if( $logo['deletelogo'] ){
				
					
					$query->delete();
					$query->from( '#__zbrochure_broker_logos' );
					$query->where( 'broker_logo_id = '.(int)$logo['deletelogo'] );
					$this->_db->setQuery($query);
			
					if( !$this->_db->query() ){
					
						$this->setError( $this->_db->stderr() );
						return false;
					}
				
				}
				
				if( !$logo['id'] ){
				
					$query->insert( '#__zbrochure_broker_logos' )
	    					->columns( 'broker_id, broker_logo_default, broker_logo_name, broker_logo_filename, broker_logo_filetype, broker_logo_created_by' )
	    					->values( $broker.', "'.$logo['default'].'", "'.$logo['name'].'", "'.$logo['filename'].'", "'.$logo['extension'].'", '.$user );
	    					
	    		}else{
	    		
					$query->update( '#__zbrochure_broker_logos' )
			    				->set( 'broker_id = '.$broker )
			    				->set( 'broker_logo_default = "'.$logo['default'].'"' )
			    				->set( 'broker_logo_name = "'.$logo['name'].'"' )
			    				->set( 'broker_logo_filename = "'.$logo['filename'].'"' )
			    				->set( 'broker_logo_filetype = "'.$logo['extension'].'"' )
			    				->set( 'broker_logo_created_by = '.$user )
			    				->where( 'broker_logo_id = '.$logo['id'] );
			    				
	    		}
	    				
					
				$this->_db->setQuery($query);
				$this->_db->query();
				
				if( $error = $this->_db->getErrorMsg() ){ throw new Exception( $error ); }
				
				//Let's move the native and thumbnail images to the broker-specific folder
				$tmp_file = $tmp_dir.$logo['filename'].'.'.$logo['extension'];
				$new_file = $broker_dir.DS.$broker.DS.'logos'.DS.$logo['filename'].'.'.$logo['extension'];
				
				$tmp_th = $tmp_dir.'th'.DS.$logo['filename'].'.'.$logo['extension'];
				$new_th = $broker_dir.DS.$broker.DS.'logos'.DS.'th'.DS.$logo['filename'].'.'.$logo['extension'];
				
				if( !JFolder::exists($broker_dir.DS.'logos'.DS.$broker.DS) ){
					JFolder::create($broker_dir.DS.'logos'.DS.$broker.DS);
					//JFile::write($tmp_dir.DS.'index.html', '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>');
				}
				
				if( !JFolder::exists($broker_dir.DS.$broker.DS.'logos'.DS.'th'.DS) ){
					JFolder::create($broker_dir.DS.$broker.DS.'logos'.DS.'th'.DS);
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
		
		$row->broker_id	= $broker;
				
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( $this->_db->getErrorMsg() );			
			return false;
		
		}
		
		//update the broker version number with the new broker_version_id number
		try{
			
			$query	= $this->_db->getQuery(true);
			
			$query->update( '#__zbrochure_brokers' )
    				->set( 'broker_current_version = '.$row->broker_version_id )
    				->set( 'broker_version = broker_version + 1' )
    				->where( 'broker_id = '.$broker );
							
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
		
		return $broker;
		
	}
	
	/**
	 * Method to get all version of the broker
	 */
	public function getVersions(){
	
		if( empty($this->_allversions) ){
		
			$query = "SELECT a.* "
			."FROM #__zbrochure_broker_versions AS a "
			."WHERE a.broker_id = ".$this->_id
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
		$broker_dir	= $com_params->get( 'broker_file_path' );
		$tmp_dir	= $broker_dir.DS.'tmp'.DS;
		
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
	 function _initData(){
	 
	 	if( empty($this->_broker) ){
	 	
	 		$broker = new stdClass();
			$broker->id = null;
			$broker->app_name = null;
			$broker->app_alias = null;
			$broker->app_desc = null;
			$broker->app_details = null;
			$broker->app_version = null;
			$broker->app_download = null;
			$broker->app_download_type = null;
			$broker->app_download_file = null;
			$broker->app_url = null;
			$broker->cid = null;
			$broker->pid = null;
			$broker->app_th = null;
			$broker->app_icon = null;
			$broker->app_featured = null;
			$broker->button_text = null;
			$broker->button_type = null;
			$broker->app_support = null;
			$broker->app_docs = null;
			$broker->app_docs_link_text = null;
			$broker->app_install = null;
			$broker->app_it = null;
			$broker->app_config = null;		
			$broker->publish_up = null;
			$broker->publish_down = null;
			$broker->published = null;
			$broker->created = null;
			$broker->created_by = null;
			$broker->modified = null;
			$broker->modified_by = null;
			$broker->checked_out = null;
			$broker->checked_out_time = null;
			$broker->version = null;
			$broker->ordering = null;
			$broker->hits = null;
			$broker->metatitle = null;
			$broker->metakey = null;
			$broker->metadesc = null;
			
	 		$this->_broker = $broker;
	 		return (boolean) $this->_broker;
	 	
	 	}
	 	
	 	return true;
	 
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