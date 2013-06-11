<?php
/**
 *	Javascript functions for TinyMCE
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: tinymce.js.php,v 1.6 2013/06/11 16:36:41 kozlik Exp $
 *	@package    serweb
 *	@subpackage js
 */ 

Header("content-type: text/js");

$_phplib_page_open = array("sess" => "phplib_Session");

/**  */
require("../set_dirs.php");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require ($_SERWEB["serwebdir"] . "load_phplib.php");

phplib_load();


$js_url = $config->js_src_path."tinymce/tiny_mce_src.js";
?>

var tinyMCEsess = "<? echo urlencode($sess->name)."=".$sess->id; ?>";

function toogleEditorMode(sEditorID) {
    if(tinyMCE.activeEditor) {
        // if there is active editor, just toggle it
        tinyMCE.execCommand('mceToggleEditor', false, sEditorID);
    } else {
        // otherwise add it
        tinyMCE.execCommand('mceAddControl', false, sEditorID);
    }
}

function openFileManager(){
    var open_file;
    
    open_file  = tinyMCE.baseURL+'/plugins/filemanager/InsertFile/insert_file.php'; 
	open_file += "?callback=none"
    if (typeof(window.tinyMCEsess) != "undefined"){
		open_file += "&"+window.tinyMCEsess
    }

    var ed = tinyMCE.get('dummy_fm_editor');

    ed.windowManager.open({
        file : open_file,
        width : 660,
        height : 500,
        close_previous : true,
        inline : 1
    });
}

document.write('<script language="javascript" type="text/javascript" src="<? echo $js_url; ?>"></script>');
