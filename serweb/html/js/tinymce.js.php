<?php
/*
 * $Id: tinymce.js.php,v 1.2 2005/10/27 13:28:50 kozlik Exp $
 */

Header("content-type: text/js");

$_phplib_page_open = array("sess" => "phplib_Session");

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "../";
$_PHPLIB["libdir"]  = "../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");
require ($_SERWEB["serwebdir"] . "load_phplib.php");

$js_url = $config->js_src_path."tinymce/tiny_mce_src.js";
?>

var tinyMCEmode = false;
var tinyMCEsess = "<? echo urlencode($sess->name)."=".$sess->id; ?>";

function toogleEditorMode(sEditorID) {
    if(tinyMCEmode) {
        tinyMCE.removeMCEControl(tinyMCE.getEditorId(sEditorID));
        tinyMCEmode = false;
    } else {
        tinyMCE.addMCEControl(document.getElementById(sEditorID), sEditorID);
        tinyMCEmode = true;
    }
}

function openFileManager(){
    var template = new Array();
    
    template['file']   = '<?php echo $config->js_src_path; ?>tinymce/plugins/filemanager/InsertFile/insert_file.php'; // Relative to theme
	template['file'] += "?callback=none"
    if (typeof(window.tinyMCEsess) != "undefined"){
		template['file'] += "&"+window.tinyMCEsess
    }
    template['width']  = 660;
    template['height'] = 500;

    tinyMCE.openWindow(template, new Array());
}

document.write('<script language="javascript" type="text/javascript" src="<? echo $js_url; ?>"></script>');
