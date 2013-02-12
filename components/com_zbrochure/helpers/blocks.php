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
class ZbrochureHelperBlocks{

	/**
	 * Method to get all the clients
	 * Need to filter this by app owner
	 */
	public function getBlock( $block_id ){
		
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->select( 'b.*, t.*, u.*, m.email AS modified_email, m.username AS modified_username, m.name AS modified_name' );
		$query->from( '#__zbrochure_content_blocks AS b' );
		$query->join( 'LEFT', '#__users AS u ON u.id = b.content_block_created_by' );
		$query->join( 'LEFT', '#__users AS m ON m.id = b.content_block_modified_by' );
		$query->join( 'LEFT', '#__zbrochure_content_types AS t ON t.content_type_id = b.content_block_type' );
		$query->where( 'b.content_block_id = '.$block_id );
		
		$db->setQuery($query);
		$block = $db->loadObject();
				
		return $block;
		
	}
	
	public function _getContent( $block ){
		
		require_once JPATH_COMPONENT.'/content/'.$block->content_type_folder.'/'.$block->content_type_element;		
		$class =  'ZbrochureContent'.$block->content_type_folder;
		
		$content	= $class::getContent( $block, 0 );
		
		return $content;
		
	}
	
}