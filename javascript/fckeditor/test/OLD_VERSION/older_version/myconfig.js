FCKConfig.ToolbarSets["editor-toolbar1"] = [
    ['Bold','Italic','Underline','-','StrikeThrough','-','UnorderedList','-','Link','Unlink','-','mediagallery','Smiley']
] ;


FCKConfig.ToolbarSets["editor-toolbar2"] = [
    ['Source','-','Undo','Redo','-','Link','Unlink','-','Bold','Italic','Underline','StrikeThrough',
    '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull',
    '-','OrderedList','UnorderedList','Outdent','Indent'],
    ['PasteText','PasteWord','-','FontName','FontSize','TextColor','BGColor','-','Rule','-','Table','FitWindow','Image','mediagallery','-','About']
] ;

FCKConfig.ToolbarSets["editor-toolbar3"] = [
    ['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
    '/',
    ['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
    ['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['Link','Unlink','Anchor'],
    ['Image','mediagallery','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
    '/',
    ['Style','FontFormat','FontName','FontSize'],
    ['TextColor','BGColor'],
    ['FitWindow','ShowBlocks','-','About']        // No comma for the last row.
] ;

FCKConfig.ToolbarSets["Default"] = [
	['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','mediagallery','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor'],
	['FitWindow','ShowBlocks','-','About']		// No comma for the last row.
] ;

FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','mediagallery']
] ;

FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/office2003/' ; // This option allows you to change the default skin of the FCKeditor
FCKConfig.Plugins.Add( 'autogrow' ) ;

FCKConfig.Plugins.Add('mediagallery');

FCKConfig.FirefoxSpellChecker	= true ; // This option enables the Firefox built-in spell checker while typing. Even if word suggestions will not appear in the FCKeditor context menu, this feature is useful to quickly identify misspelled words. 
FCKConfig.ImageDlgHideAdvanced	= true ; // This option allow you to hide the "Advanced" tab in the "Image properties" window. By default it is set to 'false' so the tab is active. 
FCKConfig.ForcePasteAsPlainText = true ; //Setting it to true forces the editor to discard all formatting when pasting text. It will also disable the Paste from Word operation. 

//Configuration Options http://docs.cksource.com/FCKeditor_2.x/Developers_Guide/Configuration/Configuration_Options