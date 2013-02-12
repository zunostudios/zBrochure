<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * zBrochure text element
 */
class ZbrochureContentContacts{
	
	/**
	 * @var string output
	 */	
	protected $_data;
	
	/**
	 * @var string output
	 */	
	protected $_output;
	
	/**
	 * Method to render an image content element
	 */
	public function getContent( $block, $edit ){
		
		$db		= $this->getDbo();
		$query	= $db->getQuery( true );
		
		$query->select( '*' );
		$query->from( '#__'.$block->content_type_table );
		
		//If this block has never been edited, let's get the default value
		//If we've edited before let's get the current version of the content -MM
		if( $block->content_block_current_version == 0){
			$query->where( 'id = '.$block->content_content_id );
		}else{
			$query->where( 'id = '.$block->content_block_current_version );
		}
		
		
		$db->setQuery( $query );
		$this->_data = $db->loadObject();
		
		$output	= ZbrochureContentContacts::renderContent( $this->_data, $block, $edit );
				
		return $output;
	
	}
	
	/**
	 * Method to get list of provider contacts
	 */
	public function getContacts(){
	
		$db		= $this->getDbo();
		
		$query	= $db->getQuery( true );
		$query->select( '*' );
		$query->from( '#__zbrochure_providers_contacts as c' );
		$query->join( 'LEFT','#__zbrochure_providers as p on p.provider_id = c.provider_id' );
		
		$db->setQuery( $query );
		$contacts = $db->loadObjectList();
		
		return $contacts;
	}
	
	/**
	 * Method to get list of provider contacts
	 */
	public function getProviders(){
	
		$db		= $this->getDbo();
		
		$query	= $db->getQuery( true );
		$query->select( '*' );
		$query->from( '#__zbrochure_providers' );
		$query->order( 'provider_name' );
		
		$db->setQuery( $query );
		$providers = $db->loadObjectList();
		
		return $providers;
	}
	
	/**
	 * Method to render an image content element
	 */
	public function renderContent( $data, $block, $edit ){
		
		$providers			= ZbrochureContentContacts::getProviders();
		$contacts			= ZbrochureContentContacts::getContacts();
	
		$randomid			= rand();
	
		$this->_output		= '';
		$this->_edit_output	= '';
		
		$selected			= json_decode( $data->data );
		
		$this->_output .= '<div id="textblock-'.$randomid.'" class="editable-content">'.PHP_EOL;
		
		$this->_output .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="package-table">'.PHP_EOL;
			
			$this->_output .= '<thead>'.PHP_EOL;
			$this->_output .= '<tr class="package-header">'.PHP_EOL;
				$this->_output .= '<th>'.JText::_( 'CONTACTS_ADMINISTRATOR' ).'</th>'.PHP_EOL;
				$this->_output .= '<th>'.JText::_( 'CONTACTS_BENEFIT' ).'</th>'.PHP_EOL;
				$this->_output .= '<th>'.JText::_( 'CONTACTS_PHONE' ).'</th>'.PHP_EOL;
				$this->_output .= '<th>'.JText::_( 'CONTACTS_WEBSITE' ).'</th>'.PHP_EOL;
			$this->_output .= '</tr>'.PHP_EOL;
			$this->_output .= '</thead>'.PHP_EOL;
			
			$this->_output .= '<tbody>'.PHP_EOL;
			
			foreach( $selected as $c ){
			
				$i = 0;
			
				foreach( $contacts as $active ){
										
					if( $active->contact_id === $c ){
						
						$alt = ($i % 2) ? 'alternative-row-0' : 'alternative-row-1';
						
						$this->_output .= '<tr class="package-plan-row '.$alt.'">'.PHP_EOL;
							
							$this->_output .= '<td>'.$active->provider_name.'</td>'.PHP_EOL;
							$this->_output .= '<td>'.$active->contact_name.'</td>'.PHP_EOL;
							$this->_output .= '<td>'.$active->contact_phone.'</td>'.PHP_EOL;
							$this->_output .= '<td>'.$active->contact_email.'</td>'.PHP_EOL;
						
						$this->_output .= '</tr>'.PHP_EOL;
						
					}
					
					$i++;
					
				}
			
			}
			
		$this->_output .= '</tbody>'.PHP_EOL;	
		
		$this->_output .= '</table>'.PHP_EOL;
		
		$this->_output .= '</div>'.PHP_EOL;
		
		if( $edit == 1 ){
		
			//**************** Start Dialog	****************//
			$this->_edit_output .= '<div id="edittext-'.$randomid.'" title="Edit Contact Block">'.PHP_EOL;
			
			$this->_edit_output .= '<form action="index.php?option=com_zbrochure&task=saveBlock" method="post">'.PHP_EOL;
			
			$alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			
			$this->_edit_output .= '<div class="letter-picker-container"><div class="letter-picker"><ul>'.PHP_EOL;
			
			foreach( $alphabet as $letter ){
				
				$this->_edit_output .= '<li><a onclick="$(\'.auto-scroll\').scrollTo($(\'#'.$letter.'\'), 400);" id="letter-'.$letter.'" href="javascript:void(0);">'.$letter.'</a></li>'.PHP_EOL;
				
			}
			$this->_edit_output .= '</ul></div></div>'.PHP_EOL;
			
			$this->_edit_output .= '<div class="auto-scroll">'.PHP_EOL;
			
			$this->_edit_output .= '<div class="form-row text-container">'.PHP_EOL;
			
			$this->_edit_output .= '<div class="form-row package-container-header">'.PHP_EOL;
			
			
			//$this->_edit_output .= '<input type="text" name="data['.$block->content_type_folder.']['.$data->id.']" class="inputbox" value="'.$data->data.'" style="width:97%" />';
			
			$lettersort = '';
			$lettersused = array();
			
			foreach( $providers as $provider ){
				
				//Give the containing div of a provider an ID if it's the first with this letter
				if( !$lettersort ){
					$lettersort = substr($provider->provider_name, 0,1);
					$lettersused[] = $lettersort;
					$this->_edit_output .= '<div id="'.$lettersort.'" class="contact-item">'.PHP_EOL;
				}else{
					$newletter = substr($provider->provider_name, 0,1);
					if( ucfirst($lettersort) != ucfirst($newletter) ){
						$lettersort = ucfirst($newletter);
						$lettersused[] = $lettersort;
						$this->_edit_output .= '<div id="'.$lettersort.'" class="contact-item">';
					}else{
						$this->_edit_output .= '<div class="contact-item">'.PHP_EOL;
					}
				}
								
				$this->_edit_output .= '<h3>'.$provider->provider_name.'</h3>'.PHP_EOL;
				
				if( !empty($contacts) ){
				
					foreach( $contacts as $contact ){
						
						if( $provider->provider_id === $contact->provider_id ){
						
							foreach( $selected as $nd ){
								if( $nd === $contact->contact_id ){
									$checked = 'checked';
								}
							}
							
							if( $checked ){
								$this->_edit_output .= '<label><input checked="'.$checked.'" type="checkbox" name="content[data][]" value="'.$contact->contact_id.'" /> '.$contact->contact_name.'</label><br />'.PHP_EOL;
							}else{
								$this->_edit_output .= '<label><input type="checkbox" name="content[data][]" value="'.$contact->contact_id.'" /> '.$contact->contact_name.'</label><br />'.PHP_EOL;
							}
							
							
							
							unset($checked);
	
						}
						
					
					}
					
				}else{
					$this->_edit_output .= 'There are no contacts for this provider.'.PHP_EOL;
				}
				
				$this->_edit_output .= '</div>'.PHP_EOL;
			
			}
			
			$this->_edit_output .= '</div>'.PHP_EOL;
			
			$this->_edit_output .= '</div>'.PHP_EOL;
			
			$this->_edit_output .= '</div>'.PHP_EOL;
			
			$this->_edit_output .= '<div class="form-row btn-container add-padding" style="margin:10px 0">
										<button class="btn save-btn fr" type="submit"><span>Save</span></button>
										<button class="btn cancel-btn fr" onclick="location.reload(); return false;"><span>Cancel</span></button>
									</div>'.PHP_EOL;
			
			$this->_edit_output	.= '<input type="hidden" name="content[id]" value="'.$data->id.'" />'.PHP_EOL;
			
			$this->_edit_output .= '<input name="content_block_id" value="'.$block->content_block_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_block_type" value="'.$block->content_block_type.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="content_bro_id" value="'.$block->content_bro_id.'" type="hidden" />'.PHP_EOL;		
			$this->_edit_output .= '<input name="view" value="'.JRequest::getVar('view').'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_id" value="'.$block->bro_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_page_id" value="'.$block->bro_page_id.'" type="hidden" />'.PHP_EOL;
			$this->_edit_output .= '<input name="bro_page_order" value="'.$block->bro_page_order.'" type="hidden" />'.PHP_EOL;
			
			
			$this->_edit_output .= '</form>'.PHP_EOL;
			$this->_edit_output .= '</div>'.PHP_EOL;
			//**************** End Dialog ****************//
			
			
			$this->_edit_output .= '<script type="text/javascript">
			
									$(document).ready(function(){
									
										$( "#textblock-'.$randomid.'" ).click(function(){
											$("#edittext-'.$randomid.'").dialog(\'open\');
										});
				
										$( "#edittext-'.$randomid.'" ).dialog({
											autoOpen: false,
											resizable: true,
											height:420,
											width:700,
											modal: true,
											close: function(){}
										});
					
									});
							
									</script>'.PHP_EOL;
				
				$this->_edit_output .= '<script type="text/javascript">'.PHP_EOL;
				
				foreach( $lettersused as $letterused ){
				
					$this->_edit_output .= '$(document).ready(function(){$("#letter-'.$letterused.'").addClass("available")});'.PHP_EOL;
				
				}
				
				$this->_edit_output .= '</script>'.PHP_EOL;

									
		}
		
		$output->render = $this->_output;
		$output->edit	= $this->_edit_output;
				
		return $output;
	
	}
	
	public function saveContent( $data, $new_version=false ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
		
		if( $new_version ){
			
			unset( $data['id'] );
			
		}
				
		$row	= JTable::getInstance( 'contentcontacts', 'Table' );
		
		if( !$row->bind( $data ) ){
		
			$this->setError( JText::_( 'ERROR_BINDING_CONTENT_CONTACTS' ) );
			return false;
		
		}
		
		$row->data	= json_encode( $data['data'] );
		
		if( $new_version ){
			
			$row->created_by	= $user;
			$row->created		= '';
			$row->modified_by	= 0;
			$row->modified		= '0000-00-00 00:00:00';	
			$row->version		= 1;
			
		}else{
		
			$row->modified_by	= $user;
			$row->modified		= $date;
			$row->version++;
			
		}
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_STORING_CONTENT_CONTACTS' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function duplicateContent( $id, $block=null ){
		
		$date	= JFactory::getDate()->toSql();
		$user	= JFactory::getUser()->get( 'id' );
								
		$row	= JTable::getInstance( 'contentcontacts', 'Table' );
				
		if( !$row->load( $id ) ){
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_CONTACTS' ) );
			return false;
		
		}
		
		$row->id = '';
		$row->created_by	= $user;
		$row->created		= $date;
		$row->modified_by	= 0;
		$row->modified		= '0000-00-00 00:00:00';			
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
		
			$this->setError( JText::_( 'ERROR_SAVING_CONTENT_CONTACTS' ) );
			return false;
		
		}
		
		return $row->id;
		
	}
	
	public function deleteContent( $id ){
		
		$row	= JTable::getInstance( 'contentcontacts', 'Table' );
		
		if( !$row->delete( $id ) ){
		
			$this->setError( JText::_( 'ERROR_DELETING_CONTENT_CONTACTS' ) );
			return false;
		
		}
		
		return true;
		
	}


}