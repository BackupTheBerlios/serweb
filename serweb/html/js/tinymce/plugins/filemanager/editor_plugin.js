(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('filemanager');

    tinymce.create('tinymce.plugins.FilemanagerPlugin', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {

            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
            ed.addCommand('mceFilemanager', function(ui, value) {

                var url_separator = '?';
                var template = new Array();
                var open_file  = '/InsertFile/insert_file.php'; // Relative to theme
                if (typeof(value) != "undefined" && value!=null){
                	open_file += url_separator+"callback="+value
                	url_separator = "&";
                }

                if (typeof(window.tinyMCEsess) != "undefined"){
                    open_file += url_separator+window.tinyMCEsess
                    url_separator = "&";
                }

                ed.windowManager.open({
                    file : url + open_file,
                    width : 660,
                    height : 500,
                    close_previous : true,
                    inline : 1
                }, {
                    plugin_url : url // Plugin absolute URL
                });
            });

            // Register example button
            ed.addButton('filemanager', {
                title : 'filemanager.desc',
                cmd : 'mceFilemanager',
                image : url + '/images/filemanager.gif'
            });

        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
                return {
                        longname : 'Filemanager plugin',
                        author : 'Karel Kozlik',
                        authorurl : 'http://tinymce.moxiecode.com',
                        infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                        version : "1.0"
                };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('filemanager', tinymce.plugins.FilemanagerPlugin);
})();
