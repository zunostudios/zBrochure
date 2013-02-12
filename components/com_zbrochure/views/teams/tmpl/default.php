<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div id="sub-header">
	
	<div class="wrapper">
	
		<div id="top-bar">
			<h1><?php echo JText::_( 'TEAMS_TITLE' ); ?></h1>	
		</div>
		
	</div>
	
</div>

<div class="view-wrapper<?php echo ' '.$this->columns_class; ?>">
	
	<?php ///echo JHtml::_('access.usergroups', 'jform[groups]', '', true); ?>
	
	<?php if( $this->left_modules ){ ?>
	<div id="left">
		<div class="inner">
		<?php echo $this->left_modules; ?>
		</div>
	</div>
	<?php } ?>

	<div id="middle">
	
		<div class="inner">
			
			<div class="" style="margin:0 0 20px 0;padding:20px">
			
				<div id="all-teams" style="width:48%;float:left">
					<h3 class="list-title"><?php echo JText::_( 'ALL_TEAMS' ); ?></h3>
					<ul id="users-list">
						<?php foreach( $this->teams as $team ){ ?>
						<li><a href="javascript:void(0);" onclick="updateList(<?php echo $team->id; ?>,'<?php echo $team->title; ?>');" title="Click to see all users of this team"><?php echo $team->title; ?></a></li>
						<?php } ?>
					</ul>
				</div>
				
				<div id="all-admins" style="width:48%;float:right">
					<h3 class="list-title"><?php echo JText::_( 'ALL_ADMINS' ); ?></h3>
					<ul id="admin-users-list">
						<?php foreach( $this->admins as $admin ){ ?>
						<li><a href="javascript:void(0);" onclick="updateList(<?php echo $admin->id; ?>,'<?php echo $admin->title; ?>');" title="Click to see all users of this group"><?php echo $admin->title; ?></a></li>
						<?php } ?>
					</ul>
				</div>
				
				<div class="clear"><!--   --></div>
				
				<div class="form-row">
			
					<label for="addTeam"><?php echo JText::_( 'ADD_TEAM' ); ?></label>
					<input type="text" class="inputbox" name="addTeam" id="addTeam" />
					<button onclick="addTeam(); return false;" class="btn add-btn"><span>+</span></button>
					
				</div>
				
				<div class="clear"><!--   --></div>
				
			</div>
			
			<div class="fl" style="width:48%">
				
				<div class="box-module">
					<h3 id="dynamic-team-title"><span class="inner"><?php echo JText::_( 'SELECT_GROUP' ); ?></span></h3>
					<div id="group-list" class="container" style="height:250px;overflow-y:scroll">
												
						<div id="group-list-loading" style="display:none"><!-- --></div>
						<ul id="dynamic-team-list" class="draggable-list"></ul>
							
					</div>
					
				</div>
				
			</div>
			
			<div class="fr" style="width:48%">
				
				<div class="box-module">
				
					<h3 id="users-title"><span class="inner"><?php echo JText::_( 'USERS_TITLE' ); ?></span></h3>
					
					<div id="user-list" class="container" style="height:250px;overflow-y:scroll">
					
						<ul id="users-list" class="draggable-list">
							<?php foreach( $this->users as $user ){ ?>
								
								<li class="draggable-user draggable-item user-<?php echo $user->id; ?>" rel="<?php echo $user->id; ?>" id="user-<?php echo $user->id; ?>"><?php echo $user->name . ' (<span class="username">'. $user->username .'</span>)' ; ?></li>
								
							<?php }?>
						</ul>
					
					</div>
					
				</div>

			</div>
			
			<div class="clear"><!--   --></div>
			
		</div>
	
	</div>
	
	<div class="clear"><!--   --></div>
	
</div>

<script type="text/javascript">

$(document).ready(function() {

	$(".draggable-user").draggable({helper:'clone'});
	
});

function updateList(value, title){
	
	$('#group-list-loading').show();
	
	var active_team = value;
	
	$( '#users-list li' ).each(function(){
		
		$(this).removeClass( 'ui-state-disabled' ).addClass( 'draggable-user' );
		
	});
	
	$.post('<?php echo JURI::base(); ?>index.php?option=com_zbrochure&task=getTeam&team='+value, function(data) {
		
		$('#dynamic-team-list').empty();
		$('#dynamic-team-title span').text(title);
		
		//var i = 0;
		var users = jQuery.parseJSON(data);

		if( users.length != 0 ){
		
			for( var i=0; i < users.length; i++ ){
				
				li = $( '<li class="remove-item user-'+users[i].id+'" rel="'+users[i].id+'">'+users[i].name+' (<em>'+users[i].username+'</em>)</li>' );
				span = $( '<span class="remove-item">x</span>' );
				
				li.append( span );
				$('#dynamic-team-list').append( li );
				
				$( '#users-list .user-'+users[i].id ).addClass( 'ui-state-disabled' ).removeClass( 'draggable-user' );
							
			}
			
			$( '#dynamic-team-list li span' ).each( function(){
				
				$( this ).click(function(){
						
					var user = $( this ).parent().attr('rel');
								
					$( this ).parent().fadeOut('fast', function(){
						
						removeFromTeam( user, active_team );	
						$( '#users-list .user-'+user ).removeClass( 'ui-state-disabled' ).addClass( 'draggable-user' );
						$(this).remove();
						
						if( $('#dynamic-team-list li').length == 0 ){
							
							$('#dynamic-team-list').append('<li class="no-users">No users in this team</li>');
							
						}
					
					});
						
				});	
				
			});
			
			
			
		}else{
		
			$('#dynamic-team-list').append('<li class="no-users">No users in this team</li>');
			
		}
		
		
		$("#dynamic-team-list").droppable({
		
			accept: ".draggable-user",
			drop: function( event, ui ){
				
				$( "#dynamic-team-list li.no-users" ).remove();
				
				$(this).append( $(ui.draggable).clone().addClass('remove-item').append( '<span class="remove-item">x</span>' ) );
				$(ui.draggable).addClass( 'ui-state-disabled' );
				
				user_id	= $(ui.draggable).attr('rel');
				
				$( "#dynamic-team-list .user-"+user_id+' span' ).click(function(){
					
					var user = $( this ).parent().attr('rel');
					
					$( "#dynamic-team-list .user-"+user ).fadeOut('fast', function(){
						
						removeFromTeam( user, active_team );	
						$( '#users-list .user-'+user ).removeClass( 'ui-state-disabled' ).addClass( 'draggable-user' );
						
						if( $('#dynamic-team-list li').length == 0 ){
							
							$('#dynamic-team-list').append('<li class="no-users">No users in this team</li>');
							
						}
						
					}).remove();
					
					
						
				});
				
				assignToTeam( $(ui.draggable).attr('rel'), active_team );
							
			}
			
		});
		
		$('#group-list-loading').hide();
		
	});

}

function addTeam(){

	var team = $('#addTeam').val();

	$.post('<?php echo JURI::base(); ?>index.php?option=com_zbrochure&task=addTeam&team='+team, function(data) {
	
		var newteam = jQuery.parseJSON(data);
		
		$('#users-list').append('<li><a href="javascript:void(0);" onclick="updateList('+newteam.id+', \''+newteam.title+'\');" title="Click to see all users of this team">'+newteam.title+'</a></li>');
		
	});
	
	$('#addTeam').val('');
	
}

function assignToTeam( user_id, team_id ){
	
	$.ajax({
				
		url: '<?php echo JURI::base(); ?>index.php?option=com_zbrochure&task=assignToTeam',
		type: 'POST',
		data: { uid : user_id, tid : team_id }
	
	}).done(function( html ){
	
		//alert( html );
	
	});
	
}

function removeFromTeam( user_id, team_id ){
	
	$.ajax({
				
		url: '<?php echo JURI::base(); ?>index.php?option=com_zbrochure&task=removeFromTeam',
		type: 'POST',
		data: { uid : user_id, tid : team_id }
	
	}).done(function( html ){
	
		//alert( html );
	
	});
	
}
</script>