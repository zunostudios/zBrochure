<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); 

$app = JFactory::getApplication();
$stateVar = $app->getUserState( "com_zbrochure_brochures_filter_order", 'state1' );
JHtml::_ ( 'behavior.formvalidation' );

?>

<style>
	#<?php echo str_replace('b.', '', $stateVar); ?>{background-color:#1FB7FF}
</style>

<div id="sub-header">
	<div class="wrapper">
		<div id="top-bar">
			
			<h1><?php echo JText::_( 'ALL_BROCHURES' ); ?></h1>
		
			<div class="btn-container">
				<a href="<?php echo JRoute::_( 'index.php?option=com_zbrochure&view=brochure&task=newBrochure' ); ?>" title="<?php echo JText::_( 'CREATE_NEW_BROCHURE' ); ?>" class="btn add-btn icon-btn">
					<span class="icon"><!-- --></span>
					<span class="btn-text"><?php echo JText::_( 'NEW_BROCHURE' ); ?></span>
				</a>
			</div>
			
		</div>
	</div>
</div>

<div class="view-wrapper">

	<div id="bro-sort">
		Sort by: <a class="btn sort-btn" id="bro_title" href="javascript:void(0);" onclick="brochureSort( 'b.bro_title' );">A-Z</a>&nbsp;&nbsp;
		<a class="btn sort-btn" href="javascript:void(0);" onclick="brochureSort( 'b.bro_client' );" id="bro_client">Client</a>&nbsp;&nbsp;
		<?php if( count( $this->user_teams ) > 1 ){ ?>
		<a class="btn sort-btn" id="team_id" href="javascript:void(0);" onclick="brochureSort( 'b.team_id' );">Team</a>&nbsp;&nbsp;
		<?php } ?>
		<a class="btn sort-btn" href="javascript:void(0);" onclick="brochureSort( 'b.bro_created' );" id="bro_created">Date</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<form method="post" action="index.php?option=com_zbrochure&task=searchBrochures" style="display:inline" class="form-validate">
			<input type="text" class="required inputbox" name="search" id="bro-search" style="width:200px" placeholder="Search for a brochure" value="<?php echo $this->searchterm['search']; ?>" />
			<button type="submit" class="btn search-btn"><?php echo JText::_( 'GO' ); ?></button>
		</form>
		
	</div>

	<div class="inner">
	
	<?php
	
	if( $this->brochures ){
	
		switch( $stateVar ){
			
			case 'search':
			
				$searchterm = $stateVar = $app->getUserState( "com_zbrochure_brochures_filter_searchterm");
				echo '<h3>Search Results for "'.$searchterm['search'].'"</h3>';
				echo $this->loadTemplate('grid');
			
			break;
			
			case 'b.bro_client':
			
				echo $this->loadTemplate('clients');
			
			break;
			
			case 'b.team_id':
			
				echo $this->loadTemplate('teams');
			
			break;
			
			default:
			
				echo $this->loadTemplate('grid');
			
		}
	
	}else{
		
		echo $this->loadTemplate('no-brochures');
		
	}
	
	?>
		
	</div>
	
</div>

<form id="sortBrochures" action="index.php?option=com_zbrochure&task=sortBrochures" method="post">
	<input type="hidden" id="order" name="order" value="<?php echo $stateVar; ?>" />
	<input type="hidden" id="orderDir" name="orderDir" value="" />
</form>

<div id="delete-dialog" title="<?php echo JText::_('DELETE_BROCHURE_TITLE'); ?>">
	
	<div class="form-row add-padding package-container-header">
	
		<div id="delete-dialog-preview"></div>
	
	</div>
	
	<a href="#" class="delete-btn icon-btn btn" style="display:block;margin:10px auto;width:175px"><span class="icon"><!-- --></span><span class="btn-text"><?php echo JText::_( 'YES_DELETE_BROCHURE' ); ?></span></a>
	
</div>

<div id="preview-dialog" class="loading" title="Brochure Preview"></div>

<?php echo $this->loadTemplate( 'scripts' ); ?>