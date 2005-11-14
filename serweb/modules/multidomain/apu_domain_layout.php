<?php
/**
 * Application unit domain_layout
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain_layout.php,v 1.6 2005/11/14 15:36:05 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit domain_layout
 *
 *
 *	This application unit is used for changeing web layout for given domain 
 *	(CSS, prolog, separator, epilog, images)
 *	   
 *	Configuration:
 *	--------------
 *	'layout_files'				default: array()
 *	 Array containing informations about layout files which may be edited.
 *	 Items of array contain associative arrays with keys:
 *		- filename - (required) name of file, files are searched in dir html/domain/<name of domain>
 *		- desc     - description of file
 *		- html	   - flag if file have html content (wysiswyg editor can be used)
 *		- ini      - flag if file is INI file
 *	
 *	'text_files'				default: array()
 *	 Array containing informations about text files which may be edited.
 *	 Items of array contain associative arrays with keys:
 *		- filename - (required) name of file, files are searched in dir html/domain/<name of domain>/txt/<lang>
 *		- desc     - description of file
 *	
 *	'nr_backups'				default: $config->backup_versions_nr
 *	 This variable tells how many versions of one file is stored. If is set to 
 *	 zero files are not backuped on update of them.
 *	
 *	'tmp_file'					default: $config->smarty_compile_dir."tmp.ini"
 *	 path to temporary file used for check syntax of ini files. Http server must have 
 *	 write rights to this file. If 'tmp_file' is empty syntax isn't checked.
 *	
 *	'msg_update'				default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
 *	 message which should be showed on attributes update - assoc array with keys 'short' and 'long'
 *								
 *	'form_name'					(string) default: ''
 *	 name of html form
 *	
 *	'form_submit'				(assoc)
 *	 assotiative array describe submit element of form. For details see description 
 *	 of method add_submit in class form_ext
 *	
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_layout_files'		name of smarty variable - see below
 *	'smarty_text_files'			name of smarty variable - see below
 *	'smarty_backup_files'		name of smarty variable - see below
 *	'smarty_fileinfo'			name of smarty variable - see below
 *	'smarty_url_back_to_default'name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	
 */

class apu_domain_layout extends apu_base_class{
	var $smarty_action='default';
	var $layout_f;
	var $text_f;
	var $languages;
	var $filename;
	/** version of file for display - name of file in backup directory */
	var $file_ver = null;
	var $lang;
	var $fileinfo = null;
	var $url_back_to_default;
	/** array containing info about old versions of file */
	var $backup_f;
	/** associative array containig path to directories with domain specific config*/
	var $dir;

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array();
	}

	/**
	 *	return array of strings - required javascript files 
	 *
	 *	@return array	array of required javascript files
	 */
	function get_required_javascript(){
		return array();
	}
	
	/**
	 *	constructor 
	 *	
	 *	initialize internal variables
	 */
	function apu_domain_layout(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['layout_files'] =			array();
		$this->opt['text_files'] =				array();

		$this->opt['tmp_file'] = 				$config->smarty_compile_dir."tmp.ini";

		$this->opt['nr_backups'] =				$config->backup_versions_nr;


		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['smarty_layout_files'] =	'layout_files';
		$this->opt['smarty_text_files'] =	'text_files';
		$this->opt['smarty_backup_files'] =	'backup_files';
		$this->opt['smarty_fileinfo'] =		'fileinfo';
		$this->opt['smarty_url_back_to_default'] =		'url_back_to_default';
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		global $sess, $available_languages;
		parent::init();

		$this->dir['domains']        = dirname(__FILE__)."/../../html/domains/";
		$this->dir['domain']         = $this->dir['domains'] . $this->controler->domain_id."/";
		$this->dir['txt']            = $this->dir['domain']  . "txt/";
		$this->dir['backup']         = $this->dir['domain']  . "backup/";
		$this->dir['default_domain'] = $this->dir['domains'] . "_default/";
		$this->dir['default_txt']    = $this->dir['default_domain'] . "txt/";


		/* create list of languages */
		$this->languages = array();
	    foreach($available_languages AS $k => $tmplang) {
	    	$this->languages[$k]= $tmplang[2];
	    } 		

		$this->languages = array_unique($this->languages);

		/* initialize array of layout files */
		$this->layout_f = &$this->opt['layout_files'];
		foreach ($this->layout_f as $k => $v){
			if (!isset($v['desc'])) $this->layout_f[$k]['desc'] = $v['filename'];
			$this->layout_f[$k]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_layout=1&filename=".RawURLEncode($v['filename']));
		}

		/* initialize array of text files */
		$this->text_f = &$this->opt['text_files'];
		foreach ($this->text_f as $k => $v){
			$this->text_f[$k]['lang'] = array();
			$this->text_f[$k]['languages'] = array();
			if (!isset($v['desc'])) $this->text_f[$k]['desc'] = $v['filename'];

			foreach ($this->languages as $klang => $vlang){
				$this->text_f[$k]['languages'][] = $vlang;
				$this->text_f[$k]['lang'][$vlang]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_text=1&filename=".RawURLEncode($v['filename'])."&lang=".$klang);
			}
		}
	}

	/**
	 * find entry with filename matching $this->filename in array of layout files and assign this entry to $this->fileinfo
	 */
	function get_layout_fileinfo(){
		$this->fileinfo = null;
	
		foreach ($this->layout_f as $k => $v){
			if ($v['filename']==$this->filename){
				$this->fileinfo = $v;
				break;
			}
		}
	}

	/**
	 * find entry with filename matching $this->filename in array of text files and assign this entry to $this->fileinfo
	 */
	function get_text_fileinfo(){
		$this->fileinfo = null;
	
		foreach ($this->text_f as $k => $v){
			if ($v['filename']==$this->filename){
				$this->fileinfo = $v;
				break;
			}
		}
	}


	/**
	 *	Write $_POST['dl_content'] into given file
	 *
	 *	@param string $filename		full filesystem path
	 */
	function write_dl_content_to_file($filename){
		$fp=fopen($filename, "w");

		/* protect ini files from reading its throught the web */
		if (!empty($this->fileinfo['ini'])){
			fwrite($fp, "; <?php die( 'Please do not access this page directly.' ); ?".">\n");
		}
	
		fwrite($fp, html_entity_decode($_POST['dl_content'], ENT_QUOTES));
		fclose($fp);
	}

	/**
	 *	Clean old versions of file in backup directory
	 *  If there are more versions of file than $this->opt['nr_backups']
	 *  the excess versions are deleted
	 *
	 *	@param string $dirname	backup dirctory 
	 *	@param string $filename	name of file
	 */
	function clean_old_backups($dirname, $filename){
		$files = array();
		$d = dir($dirname);

		/* get the list of backups of file $filename */
		while (false !== ($entry = $d->read())) {
			if (ereg("^([0-9]+)-".$filename."$", $entry, $regs)){
				$files[(int)$regs[1]] = $entry;
			}
		}
		$d->close();

		/* sort the array of backup files by timestamp */
		ksort($files, SORT_NUMERIC);
		
		/* remove excess files */
		while (count($files) > $this->opt['nr_backups']){
			/* get first file in array - file with oldest timestamp */
			$file = reset($files);
			/* delete the file */
			rm($dirname.$file);
			/* unset the first entry in array */
			unset($files[key($files)]);
		}
		
	}

	/**
	 *	Obtain info about old versions of file and store it into $this->backup_f
	 *
	 *	@param bool $txt		if true we are working with text files, otherwise with layout files
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	function get_list_of_backups($txt, &$errors){
		global $data, $serweb_auth, $lang_set, $lang_str, $sess, $available_languages;

		/* put together name of directory containing backup files */
		if ($txt){
			$ln = $available_languages[$this->lang][2];
			$dirname = $this->dir['backup']."txt/".$ln."/";
		}
		else
			$dirname = $this->dir['backup'];

		$this->backup_f = array();

		/* add entry for initial version of file */
		$url_edit = $txt ?
			$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_text=1&filename=".RawURLEncode($this->filename)."&lang=".$this->lang."&version=initial") :
			$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_layout=1&filename=".RawURLEncode($this->filename)."&version=initial");			

		$this->backup_f[0] = 
			array('filename'  => "initial-".$this->filename,
		          'timestamp' => 0,
				  'date'      => $lang_str['initial_ver'],
				  'url_edit'  => $url_edit);

		/* if directory with backups don't exists => exit */
		if (!is_dir($dirname)) return true;

		/* set timezone of admin */
		$this->controler->set_timezone($serweb_auth);

		$d = dir($dirname);
		
		/* get the list of backups of file $filename */
		while (false !== ($entry = $d->read())) {
			if (ereg("^([0-9]+)-".$this->filename."$", $entry, $regs)){
				$url_edit = $txt ?
					$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_text=1&filename=".RawURLEncode($this->filename)."&lang=".$this->lang."&version=".RawURLEncode($entry)) :
					$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_layout=1&filename=".RawURLEncode($this->filename)."&version=".RawURLEncode($entry));			

				$this->backup_f[(int)$regs[1]] = 
					array('filename'  => $entry,
				          'timestamp' => (int)$regs[1],
						  'date'      => date($lang_set['date_time_format'], (int)$regs[1]),
						  'url_edit'  => $url_edit);
			}
		}
		$d->close();

		/* sort the array of backup files by timestamp */
		ksort($this->backup_f, SORT_NUMERIC);
		
		return true;
	}
	
	/**
	 *	Return path to file which should be edited
	 *
	 *	@param bool $text_file	if true, path to file in 'txt' directory is returned
	 *	@return string			path to file
	 */
	function get_path_to_file_for_edit($text_file){
		global $available_languages;

		if ($text_file){
			$ln = $available_languages[$this->lang][2];
			if ($this->file_ver == "initial") /* get initial version of file */
				$f = multidomain_get_lang_file($this->filename, "txt", $this->lang, "_default");
			elseif ($this->file_ver)          /* get some old version of file */
				$f = $this->dir['backup']."txt/".$ln."/".$this->file_ver;
			else                              /* get current version of file */
				$f = multidomain_get_lang_file($this->filename, "txt", $this->lang, $this->controler->domain_id);
		}

		else {
			if ($this->file_ver == "initial") /* get initial version of file */
				$f = multidomain_get_file($this->filename, false, "_default");
			elseif ($this->file_ver)          /* get some old version of file */
				$f = $this->dir['backup'].$this->file_ver;
			else                              /* get current version of file */
				$f = multidomain_get_file($this->filename, false, $this->controler->domain_id);
		}
	
		return $f;
	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		global $available_languages;

		if ($_POST['dl_kind_of_file']=='text'){
			$ln = $available_languages[$_POST['dl_lang']][2];
			$dirname = $this->dir['txt'].$ln."/";
		}
		else 
			$dirname = $this->dir['domain'];

		$filename = basename($_POST['dl_filename']);

		RecursiveMkdir($dirname);
		$this->write_dl_content_to_file($dirname.$filename);
		
		if ($this->opt['nr_backups']){
			/* write the data also to backup dir for purpose to back to this version later */
			$dirname = $this->dir['backup'];
			$filename_t = time()."-".$filename;

			if ($_POST['dl_kind_of_file']=='text'){
				$dirname .= "txt/".$ln."/";
			}
	
			RecursiveMkdir($dirname);
			$this->write_dl_content_to_file($dirname.$filename_t);

			$this->clean_old_backups($dirname, $filename);
		}
	
		return array("m_dl_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/**
	 *	perform action edit text file
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */	
	function action_edit_text_file(&$errors){
		global $sess;

		if ($this->opt['nr_backups']){		
			if (false === $this->get_list_of_backups(true, $errors)) return false;
		}

		$this->smarty_action="edit_text";

		$this->url_back_to_default = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&back_to_default_text=1&filename=".RawURLEncode($this->filename)."&lang=".$this->lang);
		return true;
	}
	
	/**
	 *	perform action edit layout file
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */	
	function action_edit_layout_file(&$errors){
		global $sess;

		if ($this->opt['nr_backups']){		
			if (false === $this->get_list_of_backups(false, $errors)) return false;
		}
		
		$this->smarty_action="edit_layout";

		$this->url_back_to_default = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&back_to_default_layout=1&filename=".RawURLEncode($this->filename));
		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
			$this->filename = basename($_POST['dl_filename']);
			if ($_POST['dl_kind_of_file'] == "text") $this->get_text_fileinfo();
			else	$this->get_layout_fileinfo();
			return;
		}
		
		if (isset($_GET['edit_text'])){
			$this->action=array('action'=>"edit_text_file",
			                    'validate_form'=>false,
								'reload'=>false);
			$this->lang = $_GET['lang'];
			$this->file_ver = !empty($_GET['version']) ? basename($_GET['version']) : null;
			$this->filename = basename($_GET['filename']);
			$this->get_text_fileinfo();
			return;
		}

		if (isset($_GET['edit_layout'])){
			$this->action=array('action'=>"edit_layout_file",
			                    'validate_form'=>false,
								'reload'=>false);
			$this->file_ver = !empty($_GET['version']) ? basename($_GET['version']) : null;
			$this->filename = basename($_GET['filename']);
			$this->get_layout_fileinfo();
			return;
		}

		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
							'reload'=>false);
	}
	
	/**
	 *	create html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return null			FALSE on failure
	 */
	function create_html_form(&$errors){
		global $available_languages;
		parent::create_html_form($errors);

		$file_content = "";
		$kind = "";

		if ($this->action['action'] == "edit_text_file" or
		    $this->action['action'] == "edit_layout_file"){
		
			$kind = ($this->action['action'] == "edit_text_file") ?
			        "text" : "layout";

			$f = $this->get_path_to_file_for_edit($this->action['action'] == "edit_text_file");
	
			if(! empty($f)){
				$fp = fopen($f, "r");
				$file_content = fread($fp, 65536);
				fclose($fp);
			}
	
			/* strip first line containing die() preventing this script from displaying throught http */
			if (!empty($this->fileinfo['ini'])){
				$first_eol = strpos($file_content, "\n");
				$first_line = substr($file_content, 0, $first_eol);
				if (false !== strpos($first_line, "<?php die(")){
					$file_content = substr($file_content, $first_eol);
				}
			}		
		}
			
		$this->f->add_element(array("type"=>"textarea",
		                             "name"=>"dl_content",
									 "rows"=>25,
									 "cols"=>80,
		                             "value"=>$file_content,
									 "wrap"=>"off"));

		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"dl_filename",
		                             "value"=>$this->filename));

		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"dl_kind_of_file",
		                             "value"=>$kind));
		
		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"dl_lang",
		                             "value"=>$this->lang));
		
	}

	function ini_file_error_handler($errno, $errstr){

		$this->error_in_ini_file = $errstr;

		/* replace path to ini file in error string */
		if (substr($this->error_in_ini_file, 0 ,13) == "Error parsing"){
			$this->error_in_ini_file = str_replace($this->opt['tmp_file'], "ini file", $this->error_in_ini_file);
		}
		
	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){

		if (ini_get('magic_quotes_gpc'))
			$_POST['dl_content'] = stripslashes($_POST['dl_content']);
	
		if (false === parent::validate_form($errors)) return false;

		/* check syntax of inifile */
		if (!empty($this->fileinfo['ini']) and $this->opt['tmp_file']){
			$fp=fopen($this->opt['tmp_file'], "w");
			fwrite($fp, html_entity_decode($_POST['dl_content'], ENT_QUOTES));
			fclose($fp);
			
			$this->error_in_ini_file = false;
			set_error_handler(array(&$this, "ini_file_error_handler"));
			parse_ini_file($this->opt['tmp_file']);
			restore_error_handler();
			unlink($this->opt['tmp_file']);

			if ($this->error_in_ini_file) {
				if ($_POST['dl_kind_of_file']=='text') $this->smarty_action="edit_text";
				else $this->smarty_action="edit_layout";

				$errors[] = $this->error_in_ini_file;
				return false;
			}
		}

		return true;
	}
	
	
	/**
	 *	add messages to given array 
	 *
	 *	@param array $msgs	array of messages
	 */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_dl_updated']) and $_GET['m_dl_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);

		$smarty->assign_by_ref($this->opt['smarty_layout_files'], $this->layout_f);
		$smarty->assign_by_ref($this->opt['smarty_text_files'], $this->text_f);
		$smarty->assign_by_ref($this->opt['smarty_backup_files'], $this->backup_f);
		$smarty->assign_by_ref($this->opt['smarty_fileinfo'], $this->fileinfo);
		$smarty->assign_by_ref($this->opt['smarty_url_back_to_default'], $this->url_back_to_default);
	}
	
	/**
	 *	return info need to assign html form to smarty 
	 */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}
?>
