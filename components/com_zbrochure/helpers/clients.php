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
class ZbrochureHelperClients{

	/**
	 * Method to get all the clients
	 * Need to filter this by app owner
	 */
	public function getClients( $active=null, $render='json' ){
	
		try{
			
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select( 'c.*, v.*' );
			$query->from( '#__zbrochure_clients AS c' );
			$query->join( 'LEFT', '#__zbrochure_client_versions AS v ON v.client_version_id = c.client_current_version' );
			$query->where( 'c.client_published = 1' );
			$query->order( 'v.client_version_name' );
			
			$db->setQuery($query);
			$clients = $db->loadObjectList();
			
			if( $error = $db->getErrorMsg() ){

				throw new Exception( $error );

			}

			if( empty( $clients ) ){
			
				return JError::raiseError( 404, JText::_( 'COM_ZBROCHURE_CLIENTS_NOT_FOUND' ) );
			
			}
			
			switch( $render ){
			
				case 'genericlist':
					$clients = ZbrochureHelperClients::_buildGenericList( $active, $clients );
				break;
				
				case 'unorderedlist':
					$clients = $clients;
			
			}
		
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
				
		return $clients;	
	
	}
	
	private function _buildGenericList( $active=null, $clients ){
		
		$options[]		= JHTML::_('select.option', '', JText::_( '-- Select a Client --' ) );
		
		foreach( $clients as $client ){
	
			$options[] = JHTML::_( 'select.option', $client->client_id, JText::_( $client->client_version_name ) );
	
		}

		return JHTML::_('select.genericlist', $options, 'bro_client', 'class="inputbox"', 'value', 'text', $active );
	
	}
	
	private function _buildUnorderedList( $active=null, $clients ){
		
		//$options[]		= JHTML::_('select.option', '', JText::_( '-- Select a Client --' ) );
		
		$ul = '<span class="active-client">'.$active.'</span>';
		
		$ul .= '<ul>';
		
		foreach( $clients as $client ){
	
			$ul .= JHTML::_( 'select.option', $client->client_id, JText::_( $client->client_version_name ) );
			
			$ul .= '';
	
		}
		
		$ul .= '<ul>';

		//return JHTML::_('select.genericlist', $options, 'bro_client', 'class="inputbox"', 'value', 'text', $active );
	
	}

}