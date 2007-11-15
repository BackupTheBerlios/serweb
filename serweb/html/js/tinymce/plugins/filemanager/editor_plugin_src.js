/* Import theme specific language pack */
tinyMCE.importPluginLanguagePack('filemanager', 'en');

function TinyMCE_filemanager_getControlHTML(control_name) {
    switch (control_name) {
        case "filemanager":
            return '<a id="{$editor_id}_filemanager" class="mceButtonNormal" target="_self" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceFilemanager\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceFilemanager\'); return false;" onmousedown="return false;"><img src="{$pluginurl}/images/filemanager.gif" alt="{$lang_insert_filemanager}" title="{$lang_insert_filemanager}" /></a>';
    }
    return "";
}

/**
 * Executes the mceFilemanager command.
 */
function TinyMCE_filemanager_execCommand(editor_id, element, command, user_interface, value) {
	var url_separator = '?';
    // Handle commands
    switch (command) {
        case "mceFilemanager":
            var template = new Array();
            template['file']   = '../../plugins/filemanager/InsertFile/insert_file.php'; // Relative to theme
            if (typeof(value) != "undefined" && value!=null){
				template['file'] += url_separator+"callback="+value
				url_separator = "&";
			}
            if (typeof(window.tinyMCEsess) != "undefined"){
				template['file'] += url_separator+window.tinyMCEsess
				url_separator = "&";
            }
            template['width']  = 660;
            template['height'] = 500;
            template['close_previous'] = 'no';

            tinyMCE.openWindow(template, {editor_id : editor_id});
       return true;
   }
   // Pass to next handler in chain
   return false;
}


