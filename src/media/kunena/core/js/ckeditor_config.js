/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'social', groups: [ 'social' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	var remove_buttons_url_image = Joomla.getOptions('com_kunena.ckeditor_remove_buttons_url_image');
	if(Joomla.getOptions('com_kunena.ckeditor_buttons_configuration') !== undefined && remove_buttons_url_image==!1)
	{
		config.removeButtons = 'Anchor,Paste,' + Joomla.getOptions('com_kunena.ckeditor_buttons_configuration');
	}
	else
	{
		if (remove_buttons_url_image)
		{
			config.removeButtons = 'Anchor,Paste,Image,Link,Unlink';
		}
		else
		{
			config.removeButtons = 'Anchor,Paste';
		}
	}

	config.extraPlugins = 'ebay,twitter,instagram,map,soundcloud,video,confidential,hidetext,spoiler,code,polls';
	var emoticons = Joomla.getOptions('com_kunena.ckeditor_emoticons');
	var obj = jQuery.parseJSON( emoticons );
	var list_emoticons = [];

	jQuery.each(obj, function( index, value ) {
		list_emoticons.push(value);
	});

	if (Joomla.getOptions('com_kunena.ckeditor_subfolder')!==undefined)
	{
		config.smiley_path = Joomla.getOptions('com_kunena.ckeditor_subfolder')+'/media/kunena/emoticons/';
	}
	else
	{
		config.smiley_path = '/media/kunena/emoticons/';
	}

	config.smiley_images = list_emoticons;
};