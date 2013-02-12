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
 * zBrochure Media Model
 */
class ZbrochureModelMedia extends JModelItem{
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.media';
		
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
			
		parent::__construct();
		
	}
	
	/**
	 * Build the media library
	 * @return object
	 */
	public function buildLibrary( $client, $template ){
		
		try{
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( '*' );
			$query->from( '#__zbrochure_media_items AS m' );
			$query->where( 'm.media_item_published = 1' );
			
			$where = '';
			
			if( $client ){
			
				$where .= 'm.client_id = '.$client.' OR ';
			
			}elseif( $template ){
			
				$where .= 'm.template_id = '.$template.' OR ';
			
			}
			
			$where .= 'm.client_id = 0 OR ';
			$where .= 'm.template_id = 0';
			
			$query->where( $where );
		
			$db->setQuery($query);
			$media_items = $db->loadObjectList();
	
			if( $error = $db->getErrorMsg() ){
	
				throw new Exception( $error );
	
			}
	
			if( empty( $media_items ) ){
			
				return JError::raiseError( 404, JText::_('COM_ZBROCHURE_MEDIA_ITEMS_NOT_FOUND') );
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		$rendered_media_items = $this->_renderMediaLibrary( $media_items );
		
		return $rendered_media_items;
	
	}
	
	private function _renderMediaLibrary( $items ){
	
		$output = '<script type="text/javascript">
		
			$(document).ready(function(){
			
				$("#library-tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
				$("#library-tabs li").removeClass("ui-corner-top").addClass("ui-corner-right");
			
			});
		
		</script>';
		
		$output .= '<div id="library-tabs">';
		
		$output .= '<ul>';
		$output .= '<li><a href="#library-tabs-template">Template Specific</a></li>';
		$output .= '<li><a href="#library-tabs-client">Client Specific</a></li>';
		$output .= '<li><a href="#library-tabs-general">General</a></li>';
		$output .= '</ul>';
		
		
		$output .= '<div id="library-tabs-general">';
		
		foreach( $items as $item ){
			
			if( $item->client_id == 0 && $item->template_id == 0 ){
			
				$output .= '<a href="#" class="media-library-item" rel="'.$item->media_item_full.'"><img src="'.JURI::base().'/media/zbrochure/images/library/thumbnails/'.$item->media_item_thumbnail.'" width="100" title="'.$item->media_item_name.'" alt="'.$item->media_item_name.'">&nbsp;'.$item->media_item_name.'</a>'.PHP_EOL;
				$output .= '<br />'.PHP_EOL;	
			
			}
		
		}
		
		$output .= '</div>';
		
		$output .= '<div id="library-tabs-client">';
		
		foreach( $items as $item ){
			
			if( $item->client_id ){
			
				$output .= '<a href="#" class="media-library-item" rel="'.$item->media_item_full.'"><img src="'.JURI::base().'/media/zbrochure/images/library/thumbnails/'.$item->media_item_thumbnail.'" width="100" title="'.$item->media_item_name.'" alt="'.$item->media_item_name.'">&nbsp;'.$item->media_item_name.'</a>'.PHP_EOL;
				$output .= '<br />'.PHP_EOL;	
			
			}
		
		}
		
		$output .= '</div>';
		
		$output .= '<div id="library-tabs-template">';
		
		foreach( $items as $item ){
			
			if( $item->template_id ){
			
				$output .= '<a href="#" class="media-library-item" rel="'.$item->media_item_full.'"><img src="'.JURI::base().'/media/zbrochure/images/library/thumbnails/'.$item->media_item_thumbnail.'" width="100" title="'.$item->media_item_name.'" alt="'.$item->media_item_name.'">&nbsp;'.$item->media_item_name.'</a>'.PHP_EOL;
				$output .= '<br />'.PHP_EOL;	
			
			}
		
		}
		
		$output .= '</div>';
		
		
		
		
		$output .= '</div>';
		
		return $output;	
	
	}
	
}