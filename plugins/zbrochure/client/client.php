<?php
/**
 * @package		Zuno.Plugin
 * @subpackage	Zbrochure.client
 * @copyright	Copyright (C) 2012 Zuno Enterprises, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class plgZbrochureClient extends JPlugin{

	/**
	 * Plugin that replaces variables with live data
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	Brochure Pages object.
	 * @param	object	Bound Variable Data
	 */
	public function onContentPrepare( $context, $pages, $vars ){
		
		foreach( $pages as $page ){
		
			foreach( $page->content_blocks as $block ){
													
				foreach( $vars as $k=>$v ){
					
					$block['content']->data->render = str_replace( '{'.$k.'}', $v, $block['content']->data->render );
									
				}
								
			}
							
		}
		
	}

}
