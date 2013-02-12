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
class ZbrochureHelperThumbnails{

	/**
	 * Method to get all the clients
	 * Need to filter this by app owner
	 */
	public function generateThumbs( $bro, $page=0 ){
		
		jimport( 'joomla.filesystem.file' );
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		
		$url		= JURI::base().'index.php?option=com_zbrochure&task=generateDoc&id='.$bro.'&store_pdf=0&return_link=0&pid='.$page;
		$ch			= curl_init( $url );
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$pdf_file	= curl_exec( $ch );
		curl_close( $ch );
				
		// phpThumb
		$output_format		= $com_params->get( 'bro_thumb_output_format', 'png' );
		$output_filename	= 'b'.$bro.'_p'.$page.'_th.'.$output_format;
	    
	    $output_dir			= JPATH_SITE.'/media/zbrochure/docs/'.$bro.'/th/';
	    
	    if( !JFolder::exists( $output_dir ) ){
		
			JFolder::create( $output_dir );
			$index = '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>';
			JFile::write( $output_dir.'index.html', $index );
		
		}
	    
	    $target				= $output_dir.$output_filename;
	    
	    require_once( JPATH_COMPONENT.DS.'libraries'.DS.'phpThumb'.DS.'phpthumb.class.php');
	    $phpThumb  = new phpThumb();
	    
	    // parameters
	    $phpThumb->setSourceFilename( $pdf_file );
	    $phpThumb->setParameter('w',    $com_params->get( 'bro_thumb_width', 64 ) );
	    $phpThumb->setParameter('far',  'C');
	    $phpThumb->setParameter('f',    $output_format );
	    $phpThumb->setParameter('q',    $com_params->get( 'bro_thumb_jpg_quality', 75) );
	    $phpThumb->setParameter('bg',   $com_params->get( 'bro_thumb_background_color', 'FFFFFF' ) );
	    
	    /*
	    if ($pluginParams->get('grayscale', 0)) {
	        $phpThumb->setParameter('fltr', 'gray' );
	    }
	    */
	
	    // generate
	    if( !$phpThumb->GenerateThumbnail() ){
	    
	    	return;
	    
	    }
	
	    // render
	    if( !$phpThumb->RenderToFile( $target ) ){
	    
	    	return;
	    	
	    }else{
		    
			unset( $phpThumb );
			
			//Delete the PDF after we're done with it to keep from having an insane number of files we don't really need
			JFile::delete( $pdf_file );
			
			return $output_filename;    
		    
	    }	    
		
	}
	
	/**
	 * Method to get all the clients
	 * Need to filter this by app owner
	 */
	public function generateTemplateThumbs( $tmpl, $page=0 ){
		
		jimport( 'joomla.filesystem.file' );
		$com_params	= JComponentHelper::getParams('com_zbrochure');
		
		$url		= JURI::base().'index.php?option=com_zbrochure&task=generateTemplateDoc&id='.$tmpl.'&return_link=0&pid='.$page;
		$ch			= curl_init( $url );
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$pdf_file	= curl_exec( $ch );
		curl_close( $ch );
				
		// phpThumb
		$output_format		= $com_params->get('bro_thumb_output_format', 'png');
		$output_filename	= 'b'.$tmpl.'_p'.$page.'_th.'.$output_format;
		
		$output_dir			= JPATH_SITE.DS.'media'.DS.'zbrochure'.DS.'tmpl'.DS.$tmpl.DS.'th'.DS;
		
		if( !JFolder::exists( $output_dir ) ){
		
			JFolder::create( $output_dir );
			$index = '<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>';
			JFile::write( $output_dir.'index.html', $index );
		
		}	
		
	    $target				= $output_dir.$output_filename;
	    
	    require_once( JPATH_COMPONENT.DS.'libraries'.DS.'phpThumb'.DS.'phpthumb.class.php');
	    $phpThumb  = new phpThumb();
	    
	    // parameters
	    $phpThumb->setSourceFilename( $pdf_file );
	    
	    $phpThumb->setParameter('w',    $com_params->get( 'bro_thumb_width', 64 ) );
	    $phpThumb->setParameter('far',  'C');
	    $phpThumb->setParameter('f',    $output_format );
	    $phpThumb->setParameter('q',    $com_params->get( 'bro_thumb_jpg_quality', 75) );
	    $phpThumb->setParameter('bg',   $com_params->get( 'bro_thumb_background_color', 'FFFFFF' ) );
	    
	    $gray	= $com_params->get( 'bro_thumb_grayscale', 0 );
	    
	    if( $gray == 1 ){
	    
	    	$phpThumb->setParameter( 'fltr', 'gray' );
	    
	    }
	
	    // generate
	    if( !$phpThumb->GenerateThumbnail() ){
	    
	    	return;
	    
	    }
	
	    // render
	    if( !$phpThumb->RenderToFile( $target ) ){
	    
	    	return;
	    	
	    }else{
		    
			unset( $phpThumb );
			
			//Delete the PDF after we're done with it to keep from having an insane number of files we don't really need
			JFile::delete( $pdf_file );
			
			return $output_filename;    
		    
	    }	    
		
	}
	
}