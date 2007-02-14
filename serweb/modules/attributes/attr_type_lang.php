<?php
/**
 *	Attribute type lang
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: attr_type_lang.php,v 1.5 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Attribute type lang
 * 
 *	<pre>
 *	Configuration:
 *	--------------
 *	'use_charset_only'			(string) default: 'utf-8'
 *	 display only languages that exist in specified charset. Set to empty to
 *	 show all language setting form $avaiable_languages array
 *	
 *	'save_to_session'			(bool) default: false
 *	 If true, selected language is saved into session on submit
 *
 *	'save_to_cookie'			(bool) default: false
 *	 If true, selected language is saved into cookie on submit
 *
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_attributes
 */
class Attr_type_lang extends Attr_type{
	var $timezones = array();

	function Attr_type_lang($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);

		/* set default values to $this->opt */		
		$this->opt['use_charset_only'] =	'utf-8';
		$this->opt['save_to_session'] =		false;
		$this->opt['save_to_cookie'] =		false;
	}

	function raw_type(){
		return 2;
	}

	function &get_languages(){
		global $available_languages;
		static $languages = null;

		if (!is_null($languages)) return $languages;
		
		$ln = array();
		
	    foreach($available_languages AS $id => $tmplang) {
	    	/* skip entries with charset different from $this->opt['use_charset_only'] */
			if ($this->opt['use_charset_only'] and 
				false === strpos($id, $this->opt['use_charset_only'])){
				
				continue;
			}
	    
	    	$ln[$id]= ucfirst(substr(strrchr($tmplang[0], '|'), 1)).
			                   ($this->opt['use_charset_only']?
									         "":
											 (" (".$id.")"));
	    } 		
	    
		asort($ln);
		
		$languages = &$ln;
		return $languages;
	}


	function check_value(&$value){
		global $available_languages;
		
		/* do nothing if language is not selected */
		if ($value){
			$value = $available_languages[$value][2];
		}
		return true;
	}

	function on_update($value){
		global $available_languages, $config;

		/* do nothing if language is not selected */
		if (!$value) return true;
	
		if (!($this->opt['save_to_session'] or $this->opt['save_to_cookie'])) return true;	//do nothing


		if (false === $languages = $this->get_languages()) return false;

		/* lookup for given $value in $available_languages */
		foreach($available_languages AS $k => $v) {
			if ($v[2] == substr($value, 0, 2) and isset($languages[$k])) {
				$value = $k;
				break;
			}
		}
	
		if ($this->opt['save_to_session']){
			$_SESSION['lang'] = $value;
		}

		if ($this->opt['save_to_cookie']){
			setcookie('serweb_lang', $value, time()+31536000, $config->root_path);
		}
		
		return true;
	}

	function form_element(&$form, $value, $opt=array()){
		global $available_languages, $lang_str;
		parent::form_element($form, $value, $opt);

		$options=array();

		if (false === $languages = $this->get_languages()) return false;

		if ($value){
			/* lookup for given $value in $available_languages */
			foreach($available_languages AS $k => $v) {
				if ($v[2] == substr($value, 0, 2) and isset($languages[$k])) {
					$value = $k;
					break;
				}
			}
		}
		else{
			/* if attribute has not value, add option 'not selected' */
			$options[]=array("label"=>"--- ".$lang_str['o_lang_not_selected']." ---", "value"=>"");
		}
		
		foreach ($languages as $k => $v) $options[]=array("label"=>$v, "value"=>$k);
	                             
		$form->add_element(array("type"=>"select",
	                             "name"=>$this->name,
	                             "options"=>$options,
	                             "size"=>1,
	                             "value"=>$value));
	}
}

?>
