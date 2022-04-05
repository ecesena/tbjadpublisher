Media Gallery WKZ Media Browser - FCKeditor Plugin
Version: 1.0.1
Date: 2011-05-21
Author: Yoshinori Tahara - dengen - taharaxp AT gmail DOT com

This plugin is based on prior work by:
Mark R. Evans mark AT gllabs DOT org

概要
----
Media Gallery WKZ Media Browserは、Geeklogに含まれるFCKeditorのプラグインです。
このプラグインはMedia Galleryの自動タグを、記事や静的ページに簡単に挿入する
手段を提供します。インストールして、エディタのツールバー上の"MG"ボタンを押すと
Media Browserが開きます。自動タグのタイプ、メディアアイテムそして属性を選択し、
「決定」ボタンを押すと自動タグが編集領域に挿入されます。

動作要件
--------
Geeklog v1.4.0 以降のバージョン.
Media Gallery WKZ v1.6.10 以降のバージョン.

インストール
------------
mg-wkz-mb_1.0.1.tar.gz を以下に示すディレクトリに展開します。

    public_html/fckeditor/editor/plugins/

これで plugins/ の下に mediagallery/ が作成されます。

public_html/fckeditor/fckconfig.js を開いて編集します。

56行目あたりに、次のコードを追加します。

FCKConfig.Plugins.Add( 'mediagallery' );

Media BrowserボタンをFCKeditorの標準ツールバーを追加するために
fckconfig.js を編集します。次のコードで始まる部分を見つけてください。

FCKConfig.ToolbarSets["Default"] = [

'Image'の後ろに'mediagallery'を追加してください。そうするとツールバーの設定は
次のようになるでしょう。

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


public_html/fckeditor/myconfig.js を開いて編集します。

Geeklog用の標準FCKeditorツールバーのすべてにMedia Browserボタンを追加したいと思う
かもしれません。

存在する'Image'の後ろに'mediagallery'を追加すると良いでしょう。

NOTE:  "editor-toolbar1" には 'Image' がありません. これはコメント欄のための標準
ツールバーです。このツールバーにMedia Browserボタンを追加するなら行ってください。

Media Browserボタンをtoolbar1に追加する場合は、次のようにします。

  FCKConfig.ToolbarSets["editor-toolbar1"] = [
      ['Source','-','Undo','Redo','-','Link','Unlink','-','Bold','Italic',
      '-','JustifyLeft','JustifyCenter','JustifyRight','mediagallery',
      '-','OrderedList','UnorderedList','Outdent','Indent','FitWindow','About']
  ] ;


"editor-toolbar2" と "editor-toolbar3" に、'Image'の後ろに'mediagallery'を追加
してください。そうするとツールバーの設定は次のようになるでしょう。

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

ファイルを保存したら完了です！

アップグレード
--------------
アップグレードはとても簡単です。単純に古いファイルに新しいファイルを上書きコピー
してください。FCKeditorのファイルを編集する必要はなく、
fckeditor/editor/plugins/mediagallery/ディレクトリの全ファイルを置き換えるだけです。

NOTE:  アップグレードのあと、ブラウザのキャッシュを消去する必要があるかもしれません。
JavasSriptのルーチンは一般的にキャッシュされるので、念のためキャッシュを消去して
ください。

設定
----
次のファイル内の設定値を編集することにより、自動タグのデフォルト値を設定できます。
public_html/fckeditor/editor/plugins/mediagallery/config.php

使用方法
--------
アドバンストエディタモードで記事エディタを起動して、"MG"ツールバーボタンを見つけて
ください。そのボタンをクリックするとMedia Browserウィンドウが表示されます。
自動タグの種類を選択し、属性を設定し、メディアアイテムを選択し、最後に「決定」ボタン
を押すと、自動タグが自動的に編集領域に挿入されます。

更新履歴
--------
1.0.1 - サムネール表示を改善しました。
1.0.0 - 公開開始
