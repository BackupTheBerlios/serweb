<?
/*
 * $Id: config_paths.php,v 1.10 2007/09/21 14:21:19 kozlik Exp $
 */

		/* the web path bellow which serweb's "admin" and "user" 
		   directories begin to spread; Don't forget trailing slash.
		   This setting depends on your apache configuration.
		   
			Hint: if path incorrect, image buttons do not show up
			
			Examples:
			- If your serweb will be accesible by this url:
			
	 		  http://www.foo.bar/some/dir/to/serweb/admin/  and
			  http://www.foo.bar/some/dir/to/serweb/user/
			  
			  set $config->root_path="/some/dir/to/serweb/";
			
			- If set up to be in root 
		      
			  http://www.foo.bar/admin/ and
			  http://www.foo.bar/user/
			  
			  set just "/" in $config->root_path. 
		*/
		$config->root_path="/serweb/";

		/* roo uri of your server */
		if (isset($_SERVER['SERVER_NAME']))
			$config->root_uri="http://".$_SERVER['SERVER_NAME'];
		else
			$config->root_uri="";

		/* where is your zone file on your server ? */
		$config->zonetab_file =   "/usr/share/zoneinfo/zone.tab";

		/* relative paths of serweb tree */
		$config->img_src_path =		$config->root_path."img/";
		$config->js_src_path =    	$config->root_path."js/";
		$config->style_src_path = 	$config->root_path."styles/";
		$config->user_pages_path = 	$config->root_path."user/";
		$config->admin_pages_path =	$config->root_path."admin/";
		$config->domains_path =		$config->root_path."domains/";

        /* Location of 'host' comand on your system */
		$config->cmd_host = "/usr/bin/host";

		/* Directory where smarty stores compiled templates */
		$config->smarty_compile_dir = "/tmp/serweb/";

		/* spool directory with voicemail messages */
		$config->voice_silo_dir = '/var/spool/voicemail/'; 

		/* directory with voicemail greetings */
		$config->greetings_spool_dir = '/var/greetings/';

		/* serweb talks to SER via FIFO -- this is FIFO's name,
		   it must have the same value as SER's fifo config param
		*/
		$config->fifo_server="/tmp/ser_fifo";

		/* values used for names of reply fifos -- they change radnomly 
		   this values shouldn't be changed unless you well know what are
		   you doing
		 */
		$config->reply_fifo_filename="webfifo_".rand();
		$config->reply_fifo_path="/tmp/".$config->reply_fifo_filename;
		

		/* names of HTML documents surrounding
		   serweb pages -- these may typically include banner, trailers,
		   and whatever else appropriate to your web design; make sure
		   the values point to existing files; the files should include
		   at least:
           prolog: <body> or <body><h1>, etc.
		   separator: may be empty, or </h1><hr> etc.
           epilog: </body>
		*/

		$config->html_prolog="prolog.html";
		$config->html_separator="separator.html";
		$config->html_epilog="epilog.html";


		/************************************************************
		 *	Configs for multidomain support
		 ************************************************************/

		/* Directory containing virtual hosts. Directory to which 
		 * directive VirtualDocumentRoot from apache config pointing
		 * For more info see http://httpd.apache.org/docs/2.1/vhosts/mass.html
		 */
		 
		$config->apache_vhosts_dir = "/var/httpd/vhosts/";


/*
 * load developer config if exists
 */
 
$config_paths_developer = dirname(__FILE__) . "/config_paths.developer.php";
if (file_exists($config_paths_developer)){
	require_once ($config_paths_developer);
}
unset($config_paths_developer);
 
?>
