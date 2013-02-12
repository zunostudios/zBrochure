<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure Component Themes Helper
 *
 * @static
 * @package		Zuno.Generator
 * @subpackage	com_zbrochure
 * @since		1.0
 */
class ZbrochureHelperPdf{

	/**
	 * Method to get all the clients
	 * Need to filter this by app owner
	 */
	public function generatePdfFromBrochure( $store_pdf=true, $return_link=true, $generate_thumbs=false ){
	
		jimport( 'joomla.filesystem.file' );
		$dispatcher	= JDispatcher::getInstance();
				
		$view = $this->getView( 'Pdf', 'html' );
		
		// Get/Create the model
		if( $model = $this->getModel( 'brochure' ) ){
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		
		// Set the layout
		$view->setLayout( 'default' );
		
		$bro 	= $model->getBro();
		$pages	= $model->getPages( 0 );
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
		
		$datetime	= date( 'm-d-Y_g-i-s' );
		$filename = 'brochure_'.$datetime.'.pdf';
		
		if( !JFolder::exists($output_dir) ){

			JFolder::create($output_dir);

		}
		
		ob_start();
		echo $view->loadTemplate();
		$output = ob_get_contents();
		ob_end_clean();		
		
		include_once( JPATH_COMPONENT.DS.'libraries'.DS.'prince'.DS.'prince.php' );
		$prince = new Prince('/usr/bin/prince');
		$prince->setLog( '/home/zunous/public_html/dev/bol/generator/prince/prince.log' );
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
			
			$link_output = '<div id="generated-icon-container" style="opacity:0">';
			$link_output .= '<a href="'.JURI::base().'media/zbrochure/docs/'.$bro->bro_id.'/'.$filename.'" class="download-doc-lg" target="_blank">Download</a>';
			$link_output .= '<a href="'.JURI::base().'media/zbrochure/docs/'.$bro->bro_id.'/'.$filename.'" target="_blank">Ready for download</a>';
			$link_output .= '</div>';
			
			$success->output = $link_output;
		
		//If we are not storing the document in the db or returning links to the user
		}else if( $return_link == false && $store_pdf == false ){
			
			$success->output = JPATH_BASE.DS.'media'.DS.'zbrochure'.DS.'docs'.DS.$bro->bro_id.DS.$filename;
			
		}
		
		return $success;
	
	}
	
}