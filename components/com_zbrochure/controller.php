<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

JHTML::addIncludePath( JPATH_COMPONENT.DS.'helpers' );

/**
 * zBrochure Component Controller
 */
class ZbrochureController extends JController {
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
		
		 // Make sure we have a default view
        $view = JRequest::getVar( 'view' );
        
        if( !$view ) {
		
		    JRequest::setVar( 'view', 'dashboard' );
        
        }
        
		parent::__construct();
		
	}
	
	function display() {
	      
  		parent::display(false);
		
	}
	
	
/*********************************************************/
/* Contact Block Methods
/*********************************************************/

	/**
	 * Method to save a package content block from dialog modal
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveContentBlockPackageModal(){
	
		$app			= JFactory::getApplication();
		
		$block_id 		= JRequest::getVar( 'block_id' );
		$brochure		= JRequest::getInt( 'brochure_id' );
		
		$bro_model		= $this->getModel( 'brochure' );
		
		$bro_model->saveBlock( $data, $block_id );
				
		$bro_page		= $bro_model->getPageNumber( $block_id );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure.'#page-'.$bro_page, JText::_( 'CONTENT_BLOCK_SAVED' ) );
	
	
	}

	/**
	 * Method to save content block from dialog modal
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveContentBlockPackage(){
	
		$app			= JFactory::getApplication();
		
		$post			= JRequest::get( 'post', '' );
		$blockid 		= JRequest::getVar( 'block' );
		$brochure		= JRequest::getInt( 'brochure_id' );
		
		$bro_model		= $this->getModel( 'brochure' );
		$package_model	= $this->getModel( 'package' );
		$plan_model		= $this->getModel( 'plan' );
			
		$plans			= $plan_model->checkPlans( $post, $post['package_id'], $brochure );
				
		$data_json->package	= $packageId;
		$data_json->plans	= $plans;
		
		$data 			= $post;
		
		$type 			= key( $data['data'] );
		$contentid 		= key( $data['data'][$type] );
		
		$data['data'][$type][$contentid]['package']	= $post['package_id'];
		$data['data'][$type][$contentid]['plans']	= $plans;
		
		$bro_model->saveBlock( $data, $blockid );
				
		$bro_page = $bro_model->getPageNumber( $blockid );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure.'#page-'.$bro_page, 'Content Block Saved' );
	
	
	}
	
	/**
	 * Method to save content blocks
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveBlocks(){
	
		$app	= JFactory::getApplication();
		
		$data	= JRequest::get('post','');
		
		$brochure = JRequest::getVar('brochureid');
		$page = JRequest::getVar('page');
		
		$model	= $this->getModel( 'brochure' );
		$newid	= $model->saveBlocks($data, $brochure, $page);
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure', 'Brochure Saved' );
	
	}
	
	/**
	 * Method to save content block from dialog modal
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveContentBlock(){
	
		$data		= JRequest::get( 'post', '' );
		$blockid 	= JRequest::getVar( 'block' );
		
		$model		= $this->getModel( 'brochure' );
		$newid		= $model->saveBlock( $data, $blockid );
		
		$bro_page 	= $model->getPageNumber( $blockid );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.$data['bro_id'].'#page-'.$bro_page, 'Content Block '.$newid.' Saved' );
	
	}

	
/*********************************************************/
/* Brochure Methods
/*********************************************************/

	/**
	 * Method to create a new brochure
	 *
	 * @return	string
	 * @since	1.7
	 */
	 public function newBrochure(){
	 	
	 	JRequest::setVar( 'view', 'brochure' );
	 	JRequest::setVar( 'layout', 'choose' );
	 	
	 	parent::display(false);
	 	
	 }
	 
	 /**
	 * Method to save a brochure
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveBrochure(){
		
		$data	= JRequest::get( 'post', '' );
		
		$model	= $this->getModel( 'brochure' );
		$save	= $model->store( $data );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$save->bro_id.'&refresh=1', JText::_( 'BROCHURE_SAVED' ) );
	
	}
	
	/**
	 * Method to save a brochure page
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveBrochurePageAjax(){
		
		$app	= JFactory::getApplication();
		
		$data['bro_page_id'] 				= JRequest::getInt( 'bro_page_id', 0 );
		$data['bro_page_show_page_number']	= JRequest::getInt( 'show', 1 );
		
		$model	= $this->getModel( 'brochure' );
		$save	= $model->storePage( $data );
		
		if( $save ){
			
			echo '1';
			
		}else{
			
			echo '0';
			
		}
		
		$app->close();
		
	}
	
	/**
	 * Method to build a brochure
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function buildBrochure(){
		
		$data	= JRequest::get( 'post', '' );
		
		$model	= $this->getModel( 'brochure' );
		$save	= $model->store( $data );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$save->bro_id.'&refresh=1', JText::_( 'BROCHURE_CREATED' ) );
	
	}
	
	/**
	 * Method to delete a brochure
	 *
	 * @since	1.0
	 */
	public function deleteBrochure(){

		$id			= JRequest::getVar( 'id' );
		$model		= $this->getModel( 'brochure' );
		
		$response	= $model->delete( $id );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochures', JText::_( 'BROCHURE_DELETED' ) );
		
	}
	
	/**
	 * Method to trash a brochure
	 *
	 * @since	1.0
	 */
	public function trashBrochure(){

		$id			= JRequest::getVar( 'id' );
		$model		= $this->getModel( 'brochure' );
		$response	= $model->trashBrochure( $id );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochures', JText::_( 'BROCHURE_TRASHED' ) );
		
	}
	
	/**
	 * Method to generate a PDF from a specific brochure
	 *
	 * @return	null
	 * @since	1.0
	 */
	public function generateDoc(){
		
		jimport( 'joomla.filesystem.file' );
		$app			= JFactory::getApplication();
		$dispatcher		= JDispatcher::getInstance();
		
		$store_pdf		= JRequest::getInt( 'store_pdf', 0 );
		$return_link	= JRequest::getInt( 'return_link', 0 );
		$pid			= JRequest::getInt( 'pid', 0 );
		
		$view			= $this->getView( 'Pdf', 'html' );
		
		// Get/Create the model
		if( $model = $this->getModel( 'brochure' ) ){
		
			$view->setModel($model, true);
		
		}
		
		// Set the layout
		$view->setLayout( 'default' );
		
		$bro 	= $model->getBro();
		$pages	= $model->getPages( 0, $pid );
		$theme	= $model->getTheme( $bro->bro_theme );
		$tmpl	= $model->getTemplate();
		
		$vars			= ZbrochureHelperVars::getContentVars();
		$vars_bound		= ZbrochureHelperVars::bindVarData( $vars, $bro, $tmpl );
		$vars_list		= ZbrochureHelperVars::buildVarList( $vars );
		
		$view->assign( 'bro', $bro );
		$view->assign( 'pages', $pages );
		$view->assign( 'theme', $theme );
		$view->assign( 'tmpl', $tmpl );
		
		JPluginHelper::importPlugin('zbrochure');
		$results = $dispatcher->trigger( 'onContentPrepare', array( 'com_zbrochure.brochure', $pages, $vars_bound ) );
		
		$output_dir = JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.$bro->bro_id;
		
		$datetime	= date( 'U' );
		$filename = 'b'.$bro->bro_id.'_p'.$pid.'_'.uniqid( rand() ).'.pdf';
		
		if( !JFolder::exists($output_dir) ){

			JFolder::create($output_dir);
			$index = '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>';
			JFile::write( $output_dir.'/index.html', $index );

		}
		
		ob_start();
		echo $view->loadTemplate();
		$output = ob_get_contents();
		ob_end_clean();
		
		//echo $output;
		//exit();
		
		include_once( JPATH_COMPONENT.DS.'libraries'.DS.'prince'.DS.'prince_r6.php' );
		$prince = new Prince('/usr/bin/prince');
		$prince->setLog( '/home/zunous/public_html/dev/bol/prince/prince.log' );
		$prince->convert_string_to_file( $output, $output_dir.DS.$filename, $msgs = array() );		
		
		$success = new stdClass();
		
		if( $store_pdf ){
			
			$doc->doc_bro_id		= $bro->bro_id;
			$doc->doc_client_id		= $bro->bro_client;
			$doc->doc_tmpl_id		= $bro->bro_tmpl;
			$doc->doc_theme_id		= $bro->bro_theme;
			$doc->doc_filename		= $filename;
			$doc->doc_created_by	= JFactory::getUser()->get('id');
			
			$success->stored		= $model->storeDoc( $doc );	
			
		}
		
		//If the document was stored successfully in the db and we need to render links for the user to download the PDF
		if( $return_link && $success->stored ){
			
			$link_output = '<div id="generated-icon-container" style="opacity:0">'.PHP_EOL;
			
			$link_output .= '<a href="'.JRoute::_('index.php?option=com_zbrochure&task=downloadDoc&id='.$success->stored->id).'" class="download-doc-lg" target="_blank">Download</a>'.PHP_EOL;
			$link_output .= '<a href="'.JRoute::_('index.php?option=com_zbrochure&task=downloadDoc&id='.$success->stored->id).'">Ready for download</a>'.PHP_EOL;
			
			$link_output .= '</div>'.PHP_EOL;
			
			$success->output = $link_output;
		
		//If we are not storing the document in the db or returning links to the user
		}else if( $return_link == 0 && $store_pdf == 0 ){
			
			$success->output = $output_dir.DS.$filename;
			
		}
		
		echo $success->output;
			
		$app->close();
	
	}
	
	/**
	 * Method to download a document
	 *
	 * @return	null
	 * @since	1.0
	 */
	public function downloadDoc(){
		
		$app 		= JFactory::getApplication();
		
		$doc_id		= JRequest::getInt( 'id' );
		
		$model 		= $this->getModel( 'download' );
		$doc 		= $model->getDoc( $doc_id );
		
		$file_path	= JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.$doc->doc_bro_id.DS.$doc->doc_filename;
				
		header( 'Pragma: public' ); // required
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Cache-Control: private', false ); // required for certain browsers 
		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: attachment; filename="'.urlencode( $doc->doc_filename ).'";' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Content-Length: '.filesize( $file_path ) );
		
		ob_clean();
		flush();
		
		readfile( $file_path );

		$app->close();
		
	}
	
	/**
	 * Method to generate brochure thumbnail images
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function generateBroThumb(){
		
		$app		= JFactory::getApplication();
		$bid		= JRequest::getInt('bid');
		$pid		= JRequest::getInt('pid');
		
		$output 	= ZbrochureHelperThumbnails::generateThumbs( $bid, $pid );
		
		//$data['bro_id'] = $bid;
		$data['bro_page_id']	= $pid;
		$data['bro_page_thumb']	= $output;
		
		$model 		= $this->getModel( 'brochure' );
		$success	= $model->updatePage( $data );
		
		$img_path	= JPATH_SITE.'/media/zbrochure/docs/'.$bid.'/th/'.$success->bro_page_thumb;
		$img_src	= JURI::base().'media/zbrochure/docs/'.$bid.'/th/'.$success->bro_page_thumb.'?v='.uniqid(rand());
		
		echo $img_src;
		
		$app->close();
		
	}
	
	/**
	 * Method to change a previously saved layout
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function changeLayout(){
	
		$data	= JRequest::get( 'post', '' );
		
		$model	= $this->getModel( 'brochure' );
		$save	= $model->changePageLayout( $data );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$save->bro_id.'#page-'.$data['bro_page_order'], JText::_( 'LAYOUT_CHANGED' ) );
	
	}
	
	/**
	 * Method to add a page to a brochure
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function addPage(){
	
		$data	= JRequest::get( 'post', '' );
		
		$model	= $this->getModel( 'brochure' );
		$save	= $model->addPage( $data );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$save->bro_id.'#page-'.$save->bro_page_order, JText::_( 'NEW_PAGE_ADDED' ) );
	
	}

	/**
	 * Method to add a page to a brochure
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function trashPage(){
		
		$bro_page_id	= JRequest::getInt( 'bro_page_id' );
		$bro_id			= JRequest::getInt( 'bro_id' );
		$bro_pages		= JRequest::getInt( 'bro_pages' );
		
		$model			= $this->getModel( 'brochure' );
		$save			= $model->trashPage( $bro_page_id, $bro_id, $bro_pages );
				
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$save->bro_id, JText::_( 'PAGE_DELETED' ) );
	
	}
	
	
	/**
	 * Method to set a title to a brochure page
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function pageTitle(){
	
		$data = JRequest::get('post', '');
		
		$model = $this->getModel( 'brochure' );
		$broid = $model->pageTitle($data);
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$broid, JText::_( 'PAGE_TITLE_SAVED' ) );
	
	}
	
	/**
	 * Method to save content block from dialog modal
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveContentBlockAbstract(){
		
		//$app		= JFactory::getApplication();
		$data		= JRequest::get( 'post', '' );
		
		$block = json_decode( $data['block'] );
		
		require_once JPATH_COMPONENT.'/content/'.$block->content_type_folder.'/'.$block->content_type_element;
	
		$class		= 'ZbrochureContent'.$block->content_type_folder;
		$content	= $class::saveContent( $data, $block );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.$data['bro_id'], 'Content Block '.$content.' Saved' );
	
	}
		
	/**
	 * Method to save content block from dialog modal
	 *
	 * @return	id
	 * @since	1.0
	 */
	/*
	public function saveContentBlockSvg(){
	
		$app			= JFactory::getApplication();
		
		$post			= JRequest::get( 'post', '' );
		$block_id 		= JRequest::getVar( 'svg_content_block' );
		$svg_block_id 	= JRequest::getVar( 'svg_block' );
		$brochure		= JRequest::getInt( 'bro_id' );
		
		$bro_model		= $this->getModel( 'brochure' );
		
		$style['fill-opacity']	= $post['svg_opacity'];
		
		$styles					= json_encode( $style );
		
		$data['id']				= $svg_block_id;
		$data['style']			= $styles;
		$data['theme_position']	= $post['svg_color'];
		$data['description']	= "Brochure ID " . $brochure;
		
		$bro_model->saveSvgBlock( $data, $blockid );
				
		$bro_page = $bro_model->getPageNumber( $block_id );
				
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.$brochure.'#page-'.$bro_page, 'Content Block Saved' );
	
	
	}
	*/
	
	public function broOrdering(){
		
		$data 		= JRequest::get( 'order' );
		$model		= $this->getModel( 'brochure' );
		$success	= $model->updateOrdering( $data );
		
		echo $success;
	
		exit();
	
	}
	
	/**
	 * Method to sort Brochures
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function sortBrochures(){
	
		$data = JRequest::get( 'post', '' );
		
		$app = JFactory::getApplication();
		
		$app->setUserState( "com_zbrochure_brochures_filter_order", $data['order'] );
		$app->setUserState( "com_zbrochure_brochures_filter_orderDir", $data['orderDir'] );
		$app->setUserState( "com_zbrochure_brochures_filter_searchterm", '' );
		
		$redirect = JRoute::_( 'index.php?option=com_zbrochure&view=brochures' );
		
		$this->setRedirect( $redirect );
	
	}
	
	/**
	 * Method to sort Brochures
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function searchBrochures(){
	
		$data = JRequest::get('post', '');
		
		$app = JFactory::getApplication();
		
		$app->setUserState( "com_zbrochure_brochures_filter_order", 'search' );
		$app->setUserState( "com_zbrochure_brochures_filter_searchterm", $data );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochures', '' );
	
	}
	
	/**
	 * Method to duplicate a Brochure
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateBrochure(){
	
		$app = JFactory::getApplication();
		
		$brochure_id	= JRequest::getVar( 'id' );
		$model 			= $this->getModel( 'brochure' );
		$newid 			= $model->duplicate( $brochure_id );
		
		$msgs			= JText::_( 'BROCHURE_DUPLICATED_SUCCESS_MSG' );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.(int)$newid.'&refresh=1', '' );
	}

	
/*********************************************************/
/* Template Methods
/*********************************************************/
	
	/**
	 * Method to save a brochure
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveTemplate(){
		
		$data	= JRequest::get( 'post', '' );
		
		$model	= $this->getModel( 'template' );
		$save	= $model->store( $data );
		
		//$this->setRedirect( 'index.php?option=com_zbrochure&view=template&id='.$save->tmpl_id.'&refresh=1', JText::_( 'TEMPLATE_SAVED' ) );
		$this->setRedirect( 'index.php?option=com_zbrochure&view=template&id='.$save->tmpl_id, JText::_( 'TEMPLATE_SAVED' ) );
	
	}
	
	/**
	 * Method to get the pages for a template
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function getTemplatePages(){
		
		$app		= JFactory::getApplication();
		$tmpl		= JRequest::getInt( 'tid', 0 );
		$type		= JRequest::getInt( 'type', 0 );
		$render		= JRequest::getVar( 'render', 'default' );
		
		$render		= explode( '-', $render );
		$view		= $render[0];
		$layout		= $render[1].'.php';
		
		$pages		= ZbrochureHelperTemplates::getTemplatePages( $tmpl, $type );
		
		require_once JPATH_COMPONENT.'/views/'.$view.'/tmpl/'.$layout;
		
		$app->close();
	
	}

	/**
	 * Method to generate a PDF from a specific template
	 *
	 * @return	null
	 * @since	1.0
	 */
	public function generateTemplateDoc(){
		
		jimport( 'joomla.filesystem.file' );
		$app			= JFactory::getApplication();
		$dispatcher		= JDispatcher::getInstance();
		
		$store_pdf		= JRequest::getInt( 'store_pdf', 0 );
		$return_link	= JRequest::getInt( 'return_link', 0 );
		$pid			= JRequest::getInt( 'pid', 0 );
		
		$view			= $this->getView( 'Pdftmpl', 'html' );
		
		// Get/Create the model
		if( $model = $this->getModel( 'template' ) ){
		
			$view->setModel( $model, true );
		
		}
		
		// Set the layout
		$view->setLayout( 'default' );
		
		$tmpl 	= $model->getTmpl();
		$pages	= $model->getPages( 0, $pid );
		$theme	= $model->getTheme( $tmpl->tmpl_default_theme );
		
		//$vars				= ZbrochureHelperVars::getContentVars();
		//$vars_bound		= ZbrochureHelperVars::bindVarData( $vars, $bro, $tmpl );
		//$vars_list		= ZbrochureHelperVars::buildVarList( $vars );
		
		$view->assign( 'tmpl', $tmpl );
		$view->assign( 'pages', $pages );
		$view->assign( 'theme', $theme );
		
		//JPluginHelper::importPlugin('zbrochure');
		//$results = $dispatcher->trigger( 'onContentPrepare', array( 'com_zbrochure.brochure', $pages, $vars_bound ) );
		
		$output_dir = JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'tmpl'.DS.$tmpl->tmpl_id;
		
		$datetime	= date( 'U' );
		$filename = 'b'.$tmpl->tmpl_id.'_p'.$pid.'_'.uniqid( rand() ).'.pdf';
		
		if( !JFolder::exists( $output_dir ) ){

			JFolder::create($output_dir);
			$index = '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>';
			JFile::write( $output_dir.'index.html', $index );

		}
		
		ob_start();
		echo $view->loadTemplate();
		$output = ob_get_contents();
		ob_end_clean();
				
		include_once( JPATH_COMPONENT.DS.'libraries'.DS.'prince'.DS.'prince.php' );
		$prince = new Prince('/usr/bin/prince');
		$prince->setLog( '/home/zunous/public_html/dev/bol/prince/prince.log' );
		$prince->convert_string_to_file( $output, $output_dir.DS.$filename, $msgs = array() );		
		
		$success = new stdClass();
		
		//If the document was stored successfully in the db and we need to render links for the user to download the PDF
		if( $return_link && $success->stored ){
			
			$link_output = '<div id="generated-icon-container" style="opacity:0">'.PHP_EOL;
			
			$link_output .= '<a href="'.JPATH_BASE.DS.'media'.DS.'zbrochure'.DS.'images'.DS.'tmpl'.DS.$tmpl->tmpl_id.DS.$filename.'" class="download-doc-lg" target="_blank">Download</a>'.PHP_EOL;
			$link_output .= '<a href="'.JPATH_BASE.DS.'media'.DS.'zbrochure'.DS.'images'.DS.'tmpl'.DS.$tmpl->tmpl_id.DS.$filename.'">Ready for download</a>'.PHP_EOL;
			
			$link_output .= '</div>'.PHP_EOL;
			
			$success->output = $link_output;
		
		//If we are not storing the document in the db or returning links to the user
		}else if( $return_link == 0 && $store_pdf == 0 ){
			
			$success->output = JPATH_BASE.DS.'media'.DS.'zbrochure'.DS.'tmpl'.DS.$tmpl->tmpl_id.DS.$filename;
			
		}
		
		echo $success->output;
			
		$app->close();
	
	}
	
	/**
	 * Method to generate template thumbnail images
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function generateTemplateThumb(){
		
		$app		= JFactory::getApplication();
		$tid		= JRequest::getInt( 'tid' );
		$pid		= JRequest::getInt( 'pid' );
		
		$output 	= ZbrochureHelperThumbnails::generateTemplateThumbs( $tid, $pid );
		
		$data['tmpl_layout_id']			= $pid;
		$data['tmpl_layout_thumbnail']	= $output;
		
		$model 		= $this->getModel( 'template' );
		$success	= $model->updateLayout( $data );
		
		$img_path	= JPATH_SITE.'/media/zbrochure/tmpl/'.$tid.'/th/'.$success->tmpl_layout_thumbnail;
		$img_src	= JURI::base().'media/zbrochure/tmpl/'.$tid.'/th/'.$success->tmpl_layout_thumbnail.'?v='.uniqid(rand());
		
		echo $img_src;
		
		$app->close();
		
	}
	
	/**
	 * Method to save a template layout
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveLayout(){
		
		$data	= JRequest::get( 'post', '' );
		
		$layout	= JRequest::getCmd( 'layout', 'form' );
		$tmpl	= JRequest::getCmd( 'tmpl', '' );
		
		$model	= $this->getModel( 'layout' );
		$save	= $model->store( $data );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=layout&id='.$save->tmpl_layout_id.'&layout='.$layout.'&tmpl='.$tmpl, JText::_( 'TEMPLATE_LAYOUT_SAVED' ) );
	
	}
	
	/**
	 * Method to duplicate a template layout
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateLayout(){
		
		$id				= JRequest::getInt( 'id', 0 );
		$tmpl_id		= JRequest::getInt( 'tmpl_id', 0 );
		
		$layout_model	= $this->getModel( 'layout' );
		$layout			= $layout_model->getLayout();
		$dup_layout		= $layout_model->duplicate( $id );
		
		foreach( $layout->blocks as $block ){
			
			$block_model	= $this->getModel( 'block' );			
			$new_block		= $block_model->duplicate( $block, $dup_layout->tmpl_id, 0, $dup_layout->tmpl_layout_key );
	
		}
				
		$this->setRedirect( 'index.php?option=com_zbrochure&view=template&id='.$tmpl_id, JText::_( 'TEMPLATE_LAYOUT_DUPLICATED' ) );
	
	}
	
	/**
	 * Method to duplicate a template
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateTemplate(){
	
		$id		= JRequest::getInt( 'id', 0 );
		
		$model	= $this->getModel( 'template' );
		$new_id	= $model->duplicate( $id );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=template&id='.$new_id, JText::_( 'TEMPLATE_DUPLICATED' ) );
		
	}
	

/*********************************************************/
/* Package Methods
/*********************************************************/

	/**
	 * Method to duplicate a Package
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicatePackage(){
	
		$app = JFactory::getApplication();
		
		$packageid =  JRequest::getVar('id');
		
		$model = $this->getModel( 'brochure' );
		$newid = $model->duplicatePackage($packageid);
		
		echo $newid;
		
		$app->close();
		
	}
	
	/**
	 * Method to duplicate a Package
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicatePackageRow(){
		
		$id				= JRequest::getInt( 'id' );
		$Itemid			= JRequest::getInt( 'Itemid' );
		$view			= JRequest::getCmd( 'view' );
		$option			= JRequest::getCmd( 'option' );
		$layout			= JRequest::getCmd( 'layout', 'default' );
		$tmpl			= JRequest::getCmd( 'tmpl' );
		
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		
		$model			= $this->getModel( 'package' );
		$success		= $model->duplicate( $id, $brochure_id );
		
		if( $Itemid && $layout != 'modal' ){
				
			$redirect	= JRoute::_( 'index.php?option='.$option.'&id='.$success.'&Itemid='.$Itemid );
		
		}else{
		
			$redirect	= 'index.php?option='.$option.'&id='.$success.'&view='.$view.'&layout='.$layout.'&tmpl='.$tmpl.'&brochure_id='.$brochure_id.'&block_id='.$block_id.'&action=savePackage';
			
		}
		
		if( $success ){
		
			$msg		= 'Package duplicated';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
					
		}else{
			
			$msg		= 'Package was not duplicated for some reason';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
			
		}
		
	}
	
	/**
	 * Method to get packages
	 *
	 * @since	1.0
	 */
	public function getPackages(){
	
		$app 			= JFactory::getApplication();
		
		$cid			= JRequest::getInt( 'cid', 0 );
		$format			= JRequest::getVar( 'format', 'selectlist' );
		$block			= JRequest::getInt( 'block', 0 );
		$brochure		= JRequest::getInt( 'brochure', 0 );
		$active			= JRequest::getInt( 'active', 0 );
		$bro_page_id	= JRequest::getInt( 'bro_page_id', 0 );
		
		$model 			= $this->getModel( 'packages' );
		$response		= $model->packagesSelects( $cid, $block, $brochure, $active, $bro_page_id );
		
		echo $response;
		
		$app->close();
	
	}
	
	/**
	 * Method to save a Package
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function savePackage(){
	
		$data			= JRequest::get( 'post','' );
		$packageId		= JRequest::getInt( 'package_id', 0 );
		
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$bro_page_id	= JRequest::getInt( 'page_id', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		$initial_save	= JRequest::getInt( 'initial_save', 0 );
	
		$newdata		= Array();
		
		$i = 1;
		
		//Build an array of existing package row IDs so we can check
		//against them when generating random IDs and don't generate duplicates
		$package_label_ids = array();
		
		foreach( $data['details'] as $package_label_id ){
		
			$package_label_ids[] = $package_label_id['package_label_id'];
		
		}
		
		
		foreach( $data['details'] as $row ){
			
			if( $packageId == 0 ){
			
				$newdata['tablerow_'.$i]['package_label'] = $row['package_label'];
				
				$newdata['tablerow_'.$i]['package_label_id'] = $row['package_label_id'];				
				
				if($row['is_header']){
				 
					$newdata['tablerow_'.$i]['is_header'] = 1;
				
				}
				
			}else{
			
				$newdata['tablerow_'.$i]['package_label'] = $row['package_label'];
				
				if( $row['package_label_id'] ){
				
					$newdata['tablerow_'.$i]['package_label_id'] = $row['package_label_id'];
				
				}else{
					
					$new_id					= $this->checkId( $package_label_ids );
					$package_label_ids[]	= $new_id;
								
					$newdata['tablerow_'.$i]['package_label_id'] = $new_id;
				
				}
				
				if( $row['is_header'] ){
				
					$newdata['tablerow_'.$i]['is_header'] = 1;
					
				}	
			
			}
			
			$i++;
				
		}
				
		unset($data['details']);
		$data['details'] = $newdata;
		
		$data['package_content'] = JRequest::getVar( 'package_content', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['package_footer'] = JRequest::getVar( 'package_footer', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$model		= $this->getModel( 'package' );
		$package	= $model->store( $data );
		
		if( $block_id ){
			
			$bro 				= $this->getModel( 'brochure' );
			$block 	 			= $this->getModel( 'block' );
			$bro_page_number 	= $bro->getPageNumber( $block_id );
						
			$block->updateBlock( $block_id, $package->package_id, $bro, $bro_page_id );
			
			if( $initial_save ){
			
				$redirect	= 'index.php?option=com_zbrochure&view=package&id='.$package->package_id.'&layout=modal&tmpl=modal&brochure_id='.$brochure_id.'&block_id='.$block_id.'&page_id='.$bro_page_id;
			
			}else{
				
				$redirect	= 'index.php?option=com_zbrochure&view=brochure&id='.$brochure_id.'#page-'.$bro_page_number;
				
			}
			
		}else if( $data['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$package->package_id.'&Itemid='.$data['Itemid'], false );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$package->package_id );
			
		}
		
		if( $package->package_id ){
			
			$msg		= 'Package "'.$package->package_name.'" Saved';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
		
		}else{
						
			$msg		= 'There was an error adding or saving this package';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
		
		}
			
	}
	

/*********************************************************/
/* Plan Methods
/*********************************************************/
	
	/**
	 * Method to get slected package
	 *
	 * @since	1.7
	 */
	public function buildPlanTable(){
	
		$app 			= JFactory::getApplication();
		
		$post			= JRequest::get( 'post', '' );
		
		$plan_id		= JRequest::getInt( 'plan_id', 0 );
		$package_id		= JRequest::getInt( 'package_id', 0 );	
		$tab			= JRequest::getInt( 'tab', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		
		$model			= $this->getModel( 'plan' );
		$data			= $model->getPlanTable( $tab, $block_id, $brochure_id, 1 );
		
		echo $data;
		
		$app->close();
	
	}
	
	/**
	 * Method to get plans associated with selected package
	 *
	 * @since	1.7
	 */
	public function getPlans(){
	
		$app 			= JFactory::getApplication();
		
		$package_id		= JRequest::getInt( 'package_id', 0 );
		$package_parent	= JRequest::getInt( 'package_parent', 0 );
		
		$plan_id		= JRequest::getInt( 'plan_id', 0 );
		$plan_parent	= JRequest::getInt( 'plan_parent', 0 );
		
		$tab			= JRequest::getInt( 'tab', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );	
		
		$model 	= $this->getModel( 'plans' );		
		$data 	= $model->getPlansByPackage( $package_id, $parent_package, $plan_id, $parent_plan, $tab, $block_id, $brochure_id );
		
		echo $data;
		
		$app->close();
	
	}
	
	/**
	 * Method to save a plan
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function savePlan(){
		
		$data			= JRequest::get( 'post','' );
		$plans			= $data['plans'];
		
		$layout			= JRequest::getCmd( 'layout', '' );
		$tmpl			= JRequest::getCmd( 'tmpl', '' );
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$page_id		= JRequest::getInt( 'page_id', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		
		$model			= $this->getModel( 'plan' );
	
		unset( $data['plans'] );
				
		foreach( $plans as $plan ){
			
			$data['plan_id']	= $plan['plan_id'];
			$data['plan_name']	= $plan['plan_name'];
			
			$newid[]	= $model->store( $data, $plan );
			
		}
		
		$msg	= JText::_( 'CONFIRMATION_PLAN_SAVED' );
		$type	= 'message';
		
		if( $data['return_to'] == 'package' && $layout && $block_id != 0 ){
			
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$newid[0]->package_id.'&layout='.$layout.'&tmpl='.$tmpl.'&brochure_id='.$brochure_id.'&block_id='.$block_id.'&page_id='.$page_id );	
			
		}else if( $data['return_to'] == 'package' && $block_id == 0 ){
			
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view='.$data['return_to'].'&id='.$newid[0]->package_id.'&Itemid='.$data['Itemid'], false );
			
		}else if( $data['return_to'] == 'plans' ){
			
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$data['Itemid'], false );
			
		}else if( $data['brochure_id'] ){
			
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=brochure&id='.$data['brochure_id'].'#page-'.$data['bro_page_id'] );
		
		}else if( $newid ){
			
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=plan&id='.$data['id'].'&pid='.$data['package_id'] );	
		
		}else{
			
			$msg		= JText::_( 'CONFIRMATION_PLAN_SAVED_ERROR' );
			$type		= 'error';
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=packages&Itemid='.$data['Itemid'] );
		}
		
		$this->setRedirect( $redirect, $msg, $type );
	
	}
	
	public function loadPlanAjax(){
		
		$app	= JFactory::getApplication();
		
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		
		$model	= $this->getModel( 'plan' );
		
		echo $model->getPlanTable( 0, $block_id=0, $brochure_id=0 );
		
		$app->close();
		
	}
	
	/**
	 * Method to save a plan
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function savePlanJQ(){
	
		$app	= JFactory::getApplication();
		
		$data	= JRequest::get('post','');
		
		$model	= $this->getModel( 'plan' );
		$newid	= $model->store($data, $pid);
		
		echo $newid;
		
		$app->close();
	
	}
	
	/**
	 * Method to duplicate a Plan
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicatePlan(){
		
		$id				= JRequest::getInt('id');
		$pid			= JRequest::getInt( 'pid' );
		
		$model			= $this->getModel( 'plan' );
		$newid			= $model->duplicate( $id, $pid );
		
		$layout			= JRequest::getCmd( 'layout', '' );
		$tmpl			= JRequest::getCmd( 'tmpl', '' );
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$block_id		= JRequest::getInt( 'block_id', 0 );
		
		$redirect		= JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$pid.'&layout='.$layout.'&tmpl='.$tmpl.'&block_id='.$block_id.'&brochure_id='.$brochure_id );
		
		$msg			= 'Plan Duplicated';
		$type			= 'message';
		
		$this->setRedirect( $redirect, $msg, $type );
		
	}
	
	/**
	 * Method to duplicate a Plan
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicatePlanRow(){
		
		$planid =  JRequest::getVar('id');
		
		$model = $this->getModel( 'brochure' );
		$newid = $model->duplicatePlan($planid, 0);
		
		$redirect = JRoute::_( 'index.php?option=com_zbrochure&view=plan' );
		
		$this->setRedirect( $redirect.'&id='.$newid->plan_id.'&pid='.$newid->pid, 'Plan Duplicated' );
	}
	
	/**
	 * Method to delete an item
	 *
	 * @since	1.0
	 */
	public function deletePlan(){

		$id			= JRequest::getInt( 'id' );
		$pid		= JRequest::getInt( 'pid' );
		$Itemid		= JRequest::getInt( 'Itemid' );
		$type		= JRequest::getWord( 'type', 'plan' );
		$view		= JRequest::getCmd( 'view', 'package' );
		$option		= JRequest::getCmd( 'option' );
		$layout		= JRequest::getCmd( 'layout' );
		$tmpl		= JRequest::getCmd( 'tmpl' );
		
		$brochure_id	= JRequest::getInt( 'brochure_id', 0 );
		$page_id	= JRequest::getInt( 'page_id', 0 );
		$block_id	= JRequest::getInt( 'block_id', 0 );
		
		$pid		= ($pid) ? '&id='.$pid : '';
		$layout		= ($layout) ? '&layout='.$layout : '';
		$tmpl		= ($tmpl) ? '&tmpl='.$tmpl : '';
		
		$brochure_id	= ($brochure_id) ? '&brochure_id='.$brochure_id : '';
		$page_id	= ($page_id) ? '&page_id='.$page_id : '';
		$block_id	= ($block_id) ? '&block_id='.$block_id : '';
		
		$model		= $this->getModel( $type );
		$success	= $model->delete( $id );
		
		if( $Itemid ){
				
			$redirect	= JRoute::_( 'index.php?option='.$option.'&view='.$view.$pid.'&Itemid='.$Itemid.$layout.$tmpl, false );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option='.$option.'&view='.$view.$layout.$tmpl.$pid.$block_id.$brochure_id.$page_id );
			
		}
		
		if( $success ){
		
			$msg		= ucfirst($type).' deleted';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
					
		}else{
			
			$msg		= ucfirst($type).' was not deleted for some reason';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
			
		}
		
	}

/*********************************************************/
/* Broker Methods
/*********************************************************/	
	
	/**
	 * Method to save a broker
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveBroker(){
		
		$post		= JRequest::get( 'post' );
		$files		= JRequest::get( 'files' );
		
		$model 		= $this->getModel( 'broker' );
		$broker_id 	= $model->store( $post, $files );
		
		if( $post['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=broker&id='.$broker_id.'&Itemid='.$post['Itemid'], false );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=broker&id='.$broker_id );
			
		}
		
		if( $broker_id ){
			
			$msg		= 'Broker Saved';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
		
		}else{
						
			$msg		= 'There was an error adding or saving this broker';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
		
		}
		
	}
	
	/**
	 * Method to duplicate a broker
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateBroker(){
		
		$id		= JRequest::getInt('id');
		$Itemid = JRequest::getInt('Itemid');
		
		$model 		= $this->getModel( 'broker' );
		$broker_id 	= $model->duplicate( $id );
		
		if( $broker_id ){
			$redirect = JRoute::_( 'index.php?Itemid='.$Itemid.'&view=broker&id='.$broker_id );
			$this->setRedirect( $redirect, 'Broker Duplicated' );
		
		}else{
		
			$this->setRedirect( 'index.php?Itemid='.$Itemid, 'There was an error duplicating this broker', 'error' );
		
		}
		
	}
	
	/**
	 * Method to delete a broker
	 *
	 * @since	1.0
	 */
	public function deleteBroker(){

		$data		= JRequest::getVar('id');
		$Itemid 	= JRequest::getInt('Itemid');
		
		$model		= $this->getModel( 'brokers' );
		$broker_id	= $model->deleteClient($data);
		
		$redirect = JRoute::_('index.php?Itemid='.$Itemid);
		
		$this->setRedirect( $redirect, 'Broker Deleted' );
		
	}

	
/*********************************************************/
/* Client Methods
/*********************************************************/	
	
	/**
	 * Method to save a client
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveClient(){
		
		$post		= JRequest::get( 'post' );
		$files		= JRequest::get( 'files' );
		
		$model 		= $this->getModel( 'client' );
		$client_id 	= $model->store( $post, $files );
		
		if( $post['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=client&id='.$client_id.'&Itemid='.$post['Itemid'], false );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=client&id='.$client_id );
			
		}
		
		if( $client_id ){
			
			$msg		= 'Client Saved';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
		
		}else{
						
			$msg		= 'There was an error adding or saving this client';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
		
		}
		
	}
	
	/**
	 * Method to save a client
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateClient(){
		
		$id		= JRequest::getInt('id');
		$Itemid = JRequest::getInt('Itemid');
		
		$model 		= $this->getModel( 'client' );
		$client_id 	= $model->duplicate( $id );
		
		if( $client_id ){
			$redirect = JRoute::_( 'index.php?Itemid='.$Itemid.'&view=client&id='.$client_id );
			$this->setRedirect( $redirect, 'Client Duplicated' );
		
		}else{
		
			$this->setRedirect( 'index.php?Itemid='.$Itemid, 'There was an error duplicating this client', 'error' );
		
		}
		
	}
	
	/**
	 * Method to delete a client
	 *
	 * @since	1.0
	 */
	public function deleteClient(){

		$data		= JRequest::getVar('id');
		$Itemid 	= JRequest::getInt('Itemid');
		
		$model		= $this->getModel( 'clients' );
		$client_id	= $model->deleteClient($data);
		
		$redirect = JRoute::_('index.php?Itemid='.$Itemid);
		
		$this->setRedirect( $redirect, 'Client Deleted' );
		
	}
	
	
/*********************************************************/
/* Provider Methods
/*********************************************************/	
	
	/**
	 * Method to save a provider
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveProvider(){
		
		$data		= JRequest::get('post','');
		$contacts	= $data['contact'];
		
		$model		= $this->getModel( 'provider' );
		$id			= $model->store($data);
		
		if( $contacts ){
			$model->saveContacts( $contacts, $id );
		}
		
		
		if( $data['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=provider&id='.$id.'&Itemid='.$data['Itemid'], false );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=provider&id='.$id );
			
		}
		
		if( $id ){
			
			$msg		= 'Provider Saved';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
		
		}else{
						
			$msg		= 'There was an error adding or saving this provider';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
		
		}
	
	}
	
	/**
	 * Method to save a provider
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateProvider(){
		
		$id				= JRequest::getInt('id');
		$Itemid			= JRequest::getInt('Itemid');
		
		$model			= $this->getModel( 'provider' );
		$provider_id	= $model->duplicate($id);
		
		$redirect	= JRoute::_( 'index.php?Itemid='.$Itemid.'&view=provider&id='.$provider_id, false );
		$this->setRedirect( $redirect, 'Provider Duplicated' );
	
	}
	
	/**
	 * Method to save a provider
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function deleteProvider(){
		
		$id				= JRequest::getInt('id');
		$Itemid			= JRequest::getInt('Itemid');
		
		$model			= $this->getModel( 'provider' );
		$provider_id	= $model->delete($id);
		
		$redirect	= JRoute::_( 'index.php?Itemid='.$Itemid, false );
		$this->setRedirect( $redirect, 'Provider Deleted' );
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function deleteContact(){
	
		$app 	= JFactory::getApplication();
		$id		= JRequest::getInt('contactid');		
		$model 	= $this->getModel('provider');
		$data 	= $model->deleteContact( $id );
		
		echo json_encode($data);
		
		$app->close();
	
	}
	
	
/*********************************************************/
/* Theme Methods
/*********************************************************/	
	
	/**
	 * Method to save a theme
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveTheme(){
		
		$data	= JRequest::get( 'post','' );
		$model	= $this->getModel( 'theme' );
		$theme	= $model->store( $data );
		
		
		if( $data['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=theme&id='.$theme->theme_id.'&Itemid='.$data['Itemid'], false );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=package&id='.$theme->theme_id );
			
		}
		
		if( $theme->theme_id ){
			
			$msg		= 'Theme "'.$theme->theme_name.'" Saved';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
		
		}else{
						
			$msg		= JText::_( 'ERROR_SAVING_THEME' );
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
		
		}
	
	}
	
	/**
	 * Method to delete a theme
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function deleteTheme(){
		
		$themeid	= JRequest::getInt( 'themeid' );
		
		$model 		= $this->getModel( 'theme' );
		$id 		= $model->delete( $themeid );
		
		$redirect 	= JRoute::_('index.php?option=com_zbrochure&view=themes');
		
		$this->setRedirect( $redirect, JText::_( 'THEME_DELETED' ) );
	
	}
	
	/**
	 * Method to get a theme
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function getThemes(){
		
		$app		= JFactory::getApplication();
		$client		= JRequest::getInt( 'cid', 0 );
		$tmpl		= JRequest::getInt( 'tid', 0 );
		$public		= JRequest::getBool( 'pub', 1 );
		$form		= JRequest::getBool( 'form', 1 );
		
		$themes		= ZbrochureHelperThemes::getThemes( $public, $client, $tmpl, $form );
		
		echo $themes;
		
		$app->close();
	
	}
	
	/**
	 * Method to duplicate a Theme
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateTheme(){
		
		$id		= JRequest::getInt( 'id' );
		$Itemid	= JRequest::getInt( 'Itemid' );
		$view	= JRequest::getCmd( 'view' );
		$option	= JRequest::getCmd( 'option' );
		
		$model		= $this->getModel( 'theme' );
		$success	= $model->duplicate( $id );
		
		if( $Itemid ){
				
			$redirect	= JRoute::_( 'index.php?option='.$option.'&id='.$success.'&Itemid='.$Itemid );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option='.$option.'&id='.$success.'&view='.$view );
			
		}
		
		if( $success ){
		
			$msg		= 'Theme duplicated';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
					
		}else{
			
			$msg		= 'Theme was not duplicated for some reason';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
			
		}
		
	}
	
/*********************************************************/
/* Category Methods
/*********************************************************/	
	
	/**
	 * Method to save a client
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveCategory(){
		
		$post		= JRequest::get( 'post' );
		
		$model 		= $this->getModel( 'category' );
		$cat_id 	= $model->store( $post );
		
		if( $post['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$post['Itemid'] );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=categories' );
			
		}
		
		if( $cat_id ){
			
			$msg		= 'Category Saved';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
		
		}else{
						
			$msg		= 'There was an error adding or saving this category';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
		
		}
		
	}
	
	/**
	 * Method to duplicate a category
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function duplicateCategory(){
		
		$id		= JRequest::getInt('id');
		
		$model 		= $this->getModel( 'categories' );
		$cat_id 	= $model->duplicate( $id );
		
		if( $post['Itemid'] ){
				
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&Itemid='.$post['Itemid'] );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option=com_zbrochure&view=categories' );
			
		}
		
		$msg		= 'Category Duplicated';
		$type		= 'message';
		$this->setRedirect( $redirect, $msg, $type );
		
	}
	
/*********************************************************/
/* Media Manager Methods
/*********************************************************/	
	
	/**
	 * Method to build the media library
	 *
	 * @return	string
	 * @since	1.7
	 */
	public function mediaLibrary(){
		
		$app 		= JFactory::getApplication();			
		$model 		= $this->getModel('media');
		
		$client		= JRequest::getInt('client', 0);
		$template	= JRequest::getInt('template', 0);
		
		$library 	= $model->buildLibrary( $client, $template );
		
		echo $library;
		
		$app->close();
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function uploadProviderPreview(){
	
		$app 	= JFactory::getApplication();			
		$model 	= $this->getModel('provider');
		$data 	= $model->getPreview();
		
		echo $data;
		
		$app->close();
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function getKeywords(){
	
		$app 	= JFactory::getApplication();			
		$model 	= $this->getModel('mediamanager');
		$data 	= $model->getKeywords();
		$i		= 0;
		
		foreach( $data as $keyword ){
			$d[$i]['key'] = $keyword->keyword;
			$d[$i]['value'] = $keyword->keyword;
			$i++;
		}
		
		echo json_encode($d);
		
		$app->close();
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function getAsset(){
	
		$app 	= JFactory::getApplication();
		$id		= JRequest::getVar('id');		
		$model 	= $this->getModel('asset');
		$data 	= $model->getAsset( $id );
		
		echo json_encode($data);
		
		$app->close();
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function deleteAsset(){
	
		$id		= JRequest::getVar('id');		
		$model 	= $this->getModel('asset');
		$data 	= $model->deleteAsset( $id );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=mediamanager', 'Asset Successfully Deleted' );
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function uploadAssets(){
		
		$app 	= JFactory::getApplication();			
		$model 	= $this->getModel('mediamanager');
		$data 	= $model->upload();
		
		echo $data;
		
		$app->close();
	
	}
	
	/**
	 * Method to save a plan
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveAssets(){
	
		$data = JRequest::get('post', '');
		
		$model = $this->getModel( 'mediamanager' );
		$newid = $model->store($data);
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=mediamanager', 'Assets Saved' );
	
	}
	
	/**
	 * Method to save a plan
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveAsset(){
	
		$data = JRequest::get('post', '');
		
		$model = $this->getModel( 'asset' );
		$id = $model->store($data);
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=asset&id='.$id, 'Asset Saved' );
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function uploadPreview(){
	
		$app 	= JFactory::getApplication();
		
		$context	= JRequest::getVar( 'context', 'client' );
			
		$model 	= $this->getModel( $context );
		
		$data 	= $model->getPreview();
		
		echo $data;
		
		$app->close();
	
	}
	
	/**
	 * Method to upload preview of file
	 *
	 * @since	1.7
	 */
	public function saveCat(){
	
		$app 	= JFactory::getApplication();
		
		$data 	= JRequest::getVar('data');
		
		$data 	= addslashes($data);
			
		$model 	= $this->getModel('package');
		$data 	= $model->saveCategory($data);
		
		echo json_encode($data);
		
		$app->close();
	
	}
	
	/**
	 * Method to filter/search assets in the modal grid view
	 *
	 * @return	id
	 * @since	2.5
	 */
	public function filterAssets(){
	
		$app		= JFactory::getApplication();
		$data		= JRequest::get( 'post', '' );
		
		print_r( $data );
		exit();
					
		$model 	= $this->getModel('mediamanager');
		$data 	= $model->filterAssets($data);
		
		echo $data;
		
		$app->close();
		
		
		
		$blockid 	= JRequest::getVar( 'block' );
			
		$model	= $this->getModel( 'brochure' );
		$newid	= $model->saveBlock( $data, $blockid );
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=brochure&id='.$data['bro_id'], 'Content Block '.$newid.' Saved' );
	
	}
	
/*********************************************************/
/* Content Methods
/*********************************************************/	
		
	/**
	 * Method to save a content row
	 *
	 * @return	id
	 * @since	1.0
	 */
	public function saveContent(){
		
		$data	= JRequest::get('post','');	
		
		$model = $this->getModel( 'content' );
		$id = $model->store($data);
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view=content', 'Content Successfully Stored' );
	
	}
	
	/**
	 * Method to get list of content
	 *
	 * @since	1.7
	 */
	public function getContent(){
	
		$app 	= JFactory::getApplication();
		
		$data 	= JRequest::getVar('catid');
			
		$model 	= $this->getModel('brochure');
		$data 	= $model->getContent($data);
		
		echo json_encode($data);
		
		$app->close();
	
	}
	
	
	/**
	 * Method to get list of content
	 *
	 * @since	1.7
	 */
	public function placeContent(){
	
		$app 	= JFactory::getApplication();
		
		$data 	= JRequest::getVar('id');
			
		$model 	= $this->getModel('brochure');
		$data 	= $model->placeContent($data);
		
		echo json_encode($data);
		
		$app->close();
	
	}
	
	/**
	 * Method to delete a content row
	 *
	 * @return	id
	 * @since	2.5
	 */
	public function deleteContent(){
		
		$id			= JRequest::getInt( 'id' );
		
		$model 		= $this->getModel( 'content' );
		$id 		= $model->delete( $id );
		
		$redirect 	= JRoute::_('index.php?option=com_zbrochure&view=content');
		
		$this->setRedirect( $redirect, 'Content Block Successfully Deleted' );
	
	}
	
	/**
	 * Method to duplicate a content row
	 *
	 * @return	id
	 * @since	2.5
	 */
	public function duplicateContentRow(){
		
		$id			= JRequest::getInt( 'id' );
		
		$model 		= $this->getModel( 'content' );
		$id 		= $model->duplicate( $id );
		
		$redirect 	= JRoute::_('index.php?option=com_zbrochure&view=content');
		
		$this->setRedirect( $redirect, 'Content Block Successfully Duplicated' );
	
	}
	
	
/*********************************************************/
/* Team Methods
/*********************************************************/	
	
	/**
	 * Method to get a specific team
	 *
	 * @since	1.0
	 */
	public function getTeam(){
	
		$app 	= JFactory::getApplication();
		$team 	= JRequest::getInt( 'team' );
		$model 	= $this->getModel( 'teams' );
		$data 	= $model->getTeam( $team );
		
		echo json_encode( $data );
		
		$app->close();
	
	}
	
	/**
	 * Method to add a new team
	 *
	 * @since	1.0
	 */
	public function addTeam(){
	
		$app 	= JFactory::getApplication();
		
		$team 	= JRequest::getVar('team');
			
		$model 	= $this->getModel('teams');
		$data 	= $model->addTeam( $team );
		
		echo json_encode( $data );
		
		$app->close();
	
	}
	
	/**
	 * Method to assign a user to a team
	 *
	 * @since	1.0
	 */
	public function assignToTeam(){
		
		$app 	= JFactory::getApplication();
		
		$uid 		= JRequest::getInt('uid');
		$tid 		= JRequest::getInt('tid');
			
		$model 		= $this->getModel('teams');
		$response 	= $model->assignToTeam( $uid, $tid );
		
		echo $response;
		
		$app->close();
		
	}
	
	/**
	 * Method to remove a user from a team
	 *
	 * @since	1.0
	 */
	public function removeFromTeam(){
		
		$app 	= JFactory::getApplication();
		
		$uid 		= JRequest::getInt('uid');
		$tid 		= JRequest::getInt('tid');
			
		$model 		= $this->getModel('teams');
		$response 	= $model->removeFromTeam( $uid, $tid );
		
		echo $response;
		
		$app->close();
		
	}
	
	/**
	 * Method to add a user
	 *
	 * @since	1.7
	 */
	public function addUser(){
	
		$app 	= JFactory::getApplication();
		
		$data = JRequest::get('post','');
		
		$model 	= $this->getModel('user');
		
		$userid = $model->addUser( $data );
		
		$redirect = JRoute::_('index.php?option=com_zbrochure&view=teams');
		
		$this->setRedirect( $redirect, 'New User Saved' );		
		
	}
	
	/**
	 * Method to check if email address is already ussed
	 *
	 * @since	1.0
	 */
	public function checkEmail(){
	
		$app 	= JFactory::getApplication();
		
		$email = JRequest::getVar('email');		
		$model 	= $this->getModel('user');
		
		$result = $model->checkEmail( $email );

		echo $result;
		
		$app->close();	
		
	}


/*********************************************************/
/* Block Methods
/*********************************************************/	

	/**
	 * Method for saving a block directly without going trough a brochure
	 * This is mainly for template admin
	 *
	 * @since	1.0
	 */
	public function saveBlock(){
		
		$data			= JRequest::get( 'post','' );
		
		$bro_id			= JRequest::getInt( 'bro_id', 0 );
		$bro_page_order	= JRequest::getInt( 'bro_page_order', 0 );
		
		$layout			= JRequest::getCmd( 'layout', '' );
		$tmpl			= JRequest::getCmd( 'tmpl', '' );
		$view			= JRequest::getCmd( 'view', '' );
		
		$model			= $this->getModel( 'block' );
		
		$block_id		= $model->store( $data );
		
		$layout			= ($layout) ? '&layout='.$layout : '';
		$tmpl			= ($tmpl) ? '&tmpl='.$tmpl : '';
		
		if( $view == 'brochure' ){
			
			$view_id	= $view . '&id=' . $bro_id;
			
		}else{
			
			$view_id	= $view . '&id=' . $block_id;
			
		}
		
		$bro_page_order = ($bro_page_order) ? '#page-' . $bro_page_order : '';
		
		$this->setRedirect( 'index.php?option=com_zbrochure&view='.$view_id.$layout.$tmpl.$bro_page_order, JText::_( 'BLOCKED_SAVED' ) );
		
	}
	
	/**
	 * Method for deleting a block directly without going trough a brochure
	 * This is mainly for template admin
	 *
	 * @since	1.0
	 */
	public function deleteBlock(){
		
		$app		= JFactory::getApplication();
		
		$block_id	= JRequest::getInt( 'block_id', 0 );
		$model		= $this->getModel( 'block' );
		$success	= $model->delete( $block_id );
		
		echo $success;
		
		$app->close();
	
	}


/*********************************************************/
/* General Methods
/*********************************************************/	
	
	/**
	 * Method for I'm not sure - JL
	 *
	 * @since	1.0
	 */
	private function checkId( $existing_ids ){
		
		$new_id	= mt_rand( 1, 10000 );
		
		if( in_array( $new_id, $existing_ids ) ){
			
			$this->checkId( $existing_ids );
			return false;
			
		}
		
		return $new_id;
		
	}
	
	/**
	 * Method to delete an item
	 *
	 * @since	1.0
	 */
	public function deleteItem(){

		$id		= JRequest::getInt( 'id' );
		$Itemid	= JRequest::getInt( 'Itemid' );
		$type	= JRequest::getWord( 'type' );
		$view	= JRequest::getCmd( 'view' );
		$option	= JRequest::getCmd( 'option' );
		
		$model		= $this->getModel( $type );
		$success	= $model->delete( $id );
		
		if( $Itemid ){
				
			$redirect	= JRoute::_( 'index.php?option='.$option.'&Itemid='.$Itemid );
		
		}else{
		
			$redirect	= JRoute::_( 'index.php?option='.$option.'&view='.$view );
			
		}
		
		if( $success ){
		
			$msg		= ucfirst($type).' deleted';
			$type		= 'message';
			$this->setRedirect( $redirect, $msg, $type );
					
		}else{
			
			$msg		= ucfirst($type).' was not deleted for some reason';
			$type		= 'error';
			$this->setRedirect( $redirect, $msg, $type );
			
		}
		
	}
		
/********* END CLASS **********/	
}