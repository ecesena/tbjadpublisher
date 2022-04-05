Media Gallery WKZ Media Browser - FCKeditor Plugin
Version: 1.0.1
Date: 2011-05-21
Author: Yoshinori Tahara - dengen - taharaxp AT gmail DOT com

This plugin is based on prior work by:
Mark R. Evans mark AT gllabs DOT org

Overview
--------
Media Gallery WKZ Media Browser is a plugin for the FCKeditor that is included
with Geeklog. This plugin will allow you to easily insert Media Gallery
auto tags into your stories and static pages. Once installed, simply press the
"MG" button on your editor toolbar to open the Media Browser. Select the type of
auto tag, attribute and the media item, press "INSERT" and the auto tag will be
placed in the editor window.

Requirements
------------
Geeklog v1.4.0 or later.
Media Gallery WKZ v1.6.10 or later.

Installation
------------
Unarchive mg-wkz-mb_1.0.1.tar.gz to the following directory

    public_html/fckeditor/editor/plugins/

This should create a new directory under the plugins/ directory called
mediagallery/

Edit public_html/fckeditor/fckconfig.js

Around line 56 add

FCKConfig.Plugins.Add( 'mediagallery' );

While editing fckconfig.js, add the Media Browser button to the
default FCKeditor toolbar. Find the toolbar, it will begin with
FCKConfig.ToolbarSets["Default"] = [

Add 'mediagallery' after the 'Image' button, so your toolbar will look like
this:

  FCKConfig.ToolbarSets["Default"] = [
    ['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
    ['Smiley','SpecialChar','PageBreak','UniversalKey'], ['TextColor','BGColor'],
    ['Image','mediagallery','Flash','Table','Rule'],
    '/',
    ['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
    ['OrderedList','UnorderedList','-','Outdent','Indent'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['Link','Unlink','Anchor'],['FitWindow','-','About'],
    '/',
    ['Style','FontFormat','FontName','FontSize']
  ] ;


Edit public_html/fckeditor/myconfig.js

You will want to add the Media Browser button to all the standard Geeklog
FCKeditor toolbars:

I prefer to add 'mediagallery' right after the existing 'Image' button.

NOTE:  "editor-toolbar1" does not have an existing 'Image' button. This is the
default toolbar that is used for comments. You will need to decide if you want
to add the Media Browser button to this toolbar.

If you add the Media Browser button to toolbar1, it will look something like
this:

  FCKConfig.ToolbarSets["editor-toolbar1"] = [
      ['Source','-','Undo','Redo','-','Link','Unlink','-','Bold','Italic',
      '-','JustifyLeft','JustifyCenter','JustifyRight','mediagallery',
      '-','OrderedList','UnorderedList','Outdent','Indent','FitWindow','About']
  ] ;

For the "editor-toolbar2" and "editor-toolbar3" you can add the Media Browser
button after the 'Image' button. Your new toolbars will look like this:

  FCKConfig.ToolbarSets["editor-toolbar2"] = [
      ['Source','-','Undo','Redo','-','Link','Unlink','-','Bold','Italic','Underline','StrikeThrough',
      '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull',
      '-','OrderedList','UnorderedList','Outdent','Indent'],
      ['PasteText','PasteWord','-','FontName','FontSize','TextColor','BGColor','-','Rule','-','Image','mediagallery','Table','FitWindow','-','About']
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

Save the file and you are done!

Upgrading
---------
Upgrading is very simple, simply copy the new files over the old files.
You should not need to edit any of the FCKeditor files, just replace all the
files in the fckeditor/editor/plugins/mediagallery/ directory.

NOTE:  You will need to clear your browser's cache after upgrading.  I have
found that the JavasSript routines are generally cached, so clear your cache
just to be on the safe side.

Configuration
-------------
You can set the default values for the auto tags by editing the values in the
public_html/fckeditor/editor/plugins/mediagallery/config.php file.

Usage
-----
Fire up your story editor using the Advanced Editor and look for the new "MG"
toolbar button. Click on the button and the Media Browser window will pop-up.
Select the type of auto tag to add, set the auto tag attributes and finally
select a media item, press "INSERT" and you should have an auto tag
automatically inserted into your editor window.

ChangeLog
---------
1.0.1 - Improved thumbnail view.
1.0.0 - Initial Release
