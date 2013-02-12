/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

var ckeditorBasePath = CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/"));

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.skin				= 'zbrochure,' + ckeditorBasePath + 'ckeditor/skins/zbrochure/';
		
	config.toolbar =
	[
	    [ 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike', '-', 'BulletedList', 'NumberedList', 'Indent', 'Outdent', '-', 'Table', '-', 'Image', '-', 'Styles', 'RemoveFormat' ]
	];
	
	config.stylesSet		= [];
	
	config.extraPlugins		= 'stylesheetparser';
	
	config.removePlugins	= 'elementspath';
	config.removePlugins	= 'resize';
	
};

CKEDITOR.on( 'dialogDefinition', function( ev ) {
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if ( dialogName == 'table' ) {
        var info = dialogDefinition.getContents( 'info' );

        info.get( 'txtWidth' )[ 'default' ]		= '100%';       // Set default width to 100%
        info.get( 'txtBorder' )[ 'default' ]	= '0';         // Set default border to 0
        info.get( 'txtCellPad' )[ 'default' ]	= '0';
        info.get( 'txtCellSpace' )[ 'default' ]	= '0';
    }
});