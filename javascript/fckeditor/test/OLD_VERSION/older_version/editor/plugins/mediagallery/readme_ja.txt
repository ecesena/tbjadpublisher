Media Gallery WKZ Media Browser - FCKeditor Plugin
Version: 1.0.1
Date: 2011-05-21
Author: Yoshinori Tahara - dengen - taharaxp AT gmail DOT com

This plugin is based on prior work by:
Mark R. Evans mark AT gllabs DOT org

�T�v
----
Media Gallery WKZ Media Browser�́AGeeklog�Ɋ܂܂��FCKeditor�̃v���O�C���ł��B
���̃v���O�C����Media Gallery�̎����^�O���A�L����ÓI�y�[�W�ɊȒP�ɑ}������
��i��񋟂��܂��B�C���X�g�[�����āA�G�f�B�^�̃c�[���o�[���"MG"�{�^����������
Media Browser���J���܂��B�����^�O�̃^�C�v�A���f�B�A�A�C�e�������đ�����I�����A
�u����v�{�^���������Ǝ����^�O���ҏW�̈�ɑ}������܂��B

����v��
--------
Geeklog v1.4.0 �ȍ~�̃o�[�W����.
Media Gallery WKZ v1.6.10 �ȍ~�̃o�[�W����.

�C���X�g�[��
------------
mg-wkz-mb_1.0.1.tar.gz ���ȉ��Ɏ����f�B���N�g���ɓW�J���܂��B

    public_html/fckeditor/editor/plugins/

����� plugins/ �̉��� mediagallery/ ���쐬����܂��B

public_html/fckeditor/fckconfig.js ���J���ĕҏW���܂��B

56�s�ڂ�����ɁA���̃R�[�h��ǉ����܂��B

FCKConfig.Plugins.Add( 'mediagallery' );

Media Browser�{�^����FCKeditor�̕W���c�[���o�[��ǉ����邽�߂�
fckconfig.js ��ҏW���܂��B���̃R�[�h�Ŏn�܂镔���������Ă��������B

FCKConfig.ToolbarSets["Default"] = [

'Image'�̌���'mediagallery'��ǉ����Ă��������B��������ƃc�[���o�[�̐ݒ��
���̂悤�ɂȂ�ł��傤�B

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


public_html/fckeditor/myconfig.js ���J���ĕҏW���܂��B

Geeklog�p�̕W��FCKeditor�c�[���o�[�̂��ׂĂ�Media Browser�{�^����ǉ��������Ǝv��
��������܂���B

���݂���'Image'�̌���'mediagallery'��ǉ�����Ɨǂ��ł��傤�B

NOTE:  "editor-toolbar1" �ɂ� 'Image' ������܂���. ����̓R�����g���̂��߂̕W��
�c�[���o�[�ł��B���̃c�[���o�[��Media Browser�{�^����ǉ�����Ȃ�s���Ă��������B

Media Browser�{�^����toolbar1�ɒǉ�����ꍇ�́A���̂悤�ɂ��܂��B

  FCKConfig.ToolbarSets["editor-toolbar1"] = [
      ['Source','-','Undo','Redo','-','Link','Unlink','-','Bold','Italic',
      '-','JustifyLeft','JustifyCenter','JustifyRight','mediagallery',
      '-','OrderedList','UnorderedList','Outdent','Indent','FitWindow','About']
  ] ;


"editor-toolbar2" �� "editor-toolbar3" �ɁA'Image'�̌���'mediagallery'��ǉ�
���Ă��������B��������ƃc�[���o�[�̐ݒ�͎��̂悤�ɂȂ�ł��傤�B

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

�t�@�C����ۑ������犮���ł��I

�A�b�v�O���[�h
--------------
�A�b�v�O���[�h�͂ƂĂ��ȒP�ł��B�P���ɌÂ��t�@�C���ɐV�����t�@�C�����㏑���R�s�[
���Ă��������BFCKeditor�̃t�@�C����ҏW����K�v�͂Ȃ��A
fckeditor/editor/plugins/mediagallery/�f�B���N�g���̑S�t�@�C����u�������邾���ł��B

NOTE:  �A�b�v�O���[�h�̂��ƁA�u���E�U�̃L���b�V������������K�v�����邩������܂���B
JavasSript�̃��[�`���͈�ʓI�ɃL���b�V�������̂ŁA�O�̂��߃L���b�V������������
���������B

�ݒ�
----
���̃t�@�C�����̐ݒ�l��ҏW���邱�Ƃɂ��A�����^�O�̃f�t�H���g�l��ݒ�ł��܂��B
public_html/fckeditor/editor/plugins/mediagallery/config.php

�g�p���@
--------
�A�h�o���X�g�G�f�B�^���[�h�ŋL���G�f�B�^���N�����āA"MG"�c�[���o�[�{�^����������
���������B���̃{�^�����N���b�N�����Media Browser�E�B���h�E���\������܂��B
�����^�O�̎�ނ�I�����A������ݒ肵�A���f�B�A�A�C�e����I�����A�Ō�Ɂu����v�{�^��
�������ƁA�����^�O�������I�ɕҏW�̈�ɑ}������܂��B

�X�V����
--------
1.0.1 - �T���l�[���\�������P���܂����B
1.0.0 - ���J�J�n
