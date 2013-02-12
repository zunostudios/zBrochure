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
 * zBrochure User Model
 */
class ZbrochureModelUser extends JModelItem{
	
	var $_data 		= null;
	var $_package	= null;
	var $_pid		= null;
	var $_teams		= null;
	var $_admins	= null;
	
	/**
	 * Model context string.
	 * @var		string
	 */
	protected $_context = 'com_zbrochure.user';
	
	/**
	 * Method to auto-populate the model state
	 */
	function __construct(){
				
		parent::__construct();
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getUsers(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__users' );
			//$query->join( 'LEFT', '#__user_usergroup_map AS r ON r.user_id = u.id' );
			//$query->join( 'LEFT', '#__usergroups AS g ON g.id = r.group_id' );
			//$query->where( 'p.brochure_id = 0' );
			//$query->order( 'a.plan_id' );
			
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObjectList();
			
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
		
		return $this->_data;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getTeams(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__usergroups' );
			$query->where( 'parent_id = '.$this->_com_params->get('team_parent') );
			
			$this->_db->setQuery( $query );
			$this->_teams = $this->_db->loadObjectList();
			
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
		
		return $this->_teams;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getAdmins(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__usergroups' );
			$query->where( 'id = '.$this->_com_params->get('admin_group') . ' OR parent_id = '.$this->_com_params->get('admin_group')  );
			
			$this->_db->setQuery( $query );
			$this->_admins = $this->_db->loadObjectList();
			
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
		
		return $this->_admins;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getTeam( $team ){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( 'u.*' );
			$query->from( '#__users as u' );
			$query->join( 'LEFT', '#__user_usergroup_map AS r ON r.user_id = u.id' );
			$query->where( 'r.group_id = '.$team );
			
			$this->_db->setQuery( $query );
			$this->_team = $this->_db->loadObjectList();
			
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
		
		return $this->_team;
		
	}
	
	/**
	 * Method to add a user to a team
	 */
	public function addUser( $data ){
	
		$data['email'] = $data['username'];
		$data['groups'] = $data['jform']['groups'];
		$data['password'] = JUserHelper::genRandomPassword();
		
		$user		= JUser::getInstance();
		
		$this->sendEmail( $data );
		
		// Bind the data.
		if(!$user->bind($data)){
		
			$this->setError($user->getError());
			return false;
		
		}

		// Store the data.
		if(!$user->save()){
		
			$this->setError($user->getError());
			return false;
		}
		
		return $user->id;		
	
	}

	/**
	 * Method to send email after adding user
	 */	
	public function sendEmail( $data ){
	
		jimport('joomla.mail.helper');
	
		$app		= JFactory::getApplication();
		$mail		= JFactory::getMailer();
		$registry 	= JFactory::getConfig();
		
		$com_params	= JComponentHelper::getParams( 'com_zbrochure' );
		
		$emailaddress = $data['username'];
		$subject = JFilterOutput::cleanText( $com_params->get( 'email_subject' ) );
				
		$body = 'Hello '.$data['name'].','.PHP_EOL.PHP_EOL;
		$body .= JFilterOutput::cleanText( $com_params->get( 'email_body' ) ).PHP_EOL.PHP_EOL;
		$body .= '--------------------------'.PHP_EOL;
		$body .= 'Email/Username:  '.$data['username'].PHP_EOL;
		$body .= 'Password:  '.$data['password'].PHP_EOL;
		$body .= '--------------------------'.PHP_EOL.PHP_EOL;
		$body .= 'You can access the site by logging in at '.JURI::base().PHP_EOL;
		
		$mail->setSender( array( $com_params->get( 'email_from_email' ) , $com_params->get( 'email_from_name' )  ) );
		$mail->addRecipient( $emailaddress );
		$mail->setSubject( $subject );
		$mail->setBody( $body );
		
		if ($mail->Send()) {
		
			echo JText::_( 'Credentials were sent successfully to: ' ).$emailaddress;
		
		} else {
		
			echo JText::_( 'Email was not sent. There was an error of some sort' );
		
		}
		
		return true;
		
	}
	
	/**
	 * Method to send email after adding user
	 */	
	public function checkEmail( $email ){
	
	
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__users' );
			$query->where( 'email = "'.$email.'"' );
			
			$this->_db->setQuery( $query );
			$checkemail = $this->_db->loadObject();
			
			if( empty( $checkemail ) ){
				$result = true;
			}else{
				$result = false;
			}
			
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
		
		return $result;
	
		
	}
	
	/**
	 * Method to add a user to a team
	 */
	public function assignToTeam( $uid, $tid ){
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->insert( '#__user_usergroup_map' );
			$query->columns( array('user_id', 'group_id') );
			$query->values( $uid . ', ' . $tid );
			$this->_db->setQuery($query);
			$this->_db->query();

			// Check for a database error.
			if ($this->_db->getErrorNum()){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return true;
		
	}

	/**
	 * Method to remove a user to a team
	 */
	public function removeFromTeam( $uid, $tid ){
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->delete( '#__user_usergroup_map' );
			$query->where( 'user_id = '.$uid );
			$query->where( 'group_id = '.$tid );
			$this->_db->setQuery($query);
			$this->_db->query();

			// Check for a database error.
			if ($this->_db->getErrorNum()){
			
				$this->setError($this->_db->getErrorMsg());
				return false;
			
			}
						
		}catch( JException $e ){
		
			if( $e->getCode() == 404 ){
			
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError( 404, $e->getMessage() );
			
			}else{
			
				$this->setError($e);
			
			}

		}
		
		return true;
		
	}

	
	/**
	 * Method to get current client data
	 */
	public function addTeam( $team ){
	
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		$data['title'] = $team;
		
		$row	= $this->getTable('teams');

	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->id = '';
		$row->parent_id = $this->_com_params->get('team_parent');
		$row->title = $team;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		return $row;
		
	}
	
	/**
	 * Method to get current client data
	 */
	public function getTeamrel(){
	
		$user	= JFactory::getUser();
		$active = $user->get( 'active_team' );
		
		$this->_com_params	= JComponentHelper::getParams('com_zbrochure');
		
		try{
		
			$query	= $this->_db->getQuery( true );
			
			$query->select( '*' );
			$query->from( '#__user_usergroup_map AS r' );
			$query->join( 'LEFT', '#__usergroups AS g ON g.id = r.group_id' );
			
			$this->_db->setQuery( $query );
			$this->_teamrel = $this->_db->loadObjectList();
			
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
		
		return $this->_teamrel;
		
	}
	
	/**
	 * Method to save the client
	 */
	public function store( $data ){
	
		jimport( 'joomla.utilities.date' );
	 	jimport( 'joomla.filesystem.file' );
	
	 	$app	= JFactory::getApplication();
	 	$user	= JFactory::getUser();
		$id		= $data['plan_id'];
		$pid	= $data['package_id'];
		$row	= $this->getTable();

	 	if( !$row->bind( $data ) ){
	 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		
		}
		
		$row->plan_id = $id;
		$row->plan_details = json_encode($data['details']);
		$row->pid = $pid;
		
		//alright, good to go. Store it to the Joomla db
		if( !$row->store() ){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row->plan_id;
		
	}
	
	/*
	 * Method to initialize the Referral Form data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * 
	 */
	 function _initData(){
	 
	 	if( empty($this->_data) ){
	 	
	 		$plan								= new stdClass();
			$plan->plan_id 						= null;
			$plan->plan_details					= null;
			
	 		$this->_data						= $plan;
	 		
	 		return (boolean) $this->_data;
	 	
	 	}
	 	
	 	return true;
	 
	}
	
}