<?
/*
 * $Id: data_layer.php,v 1.4 2004/08/25 10:45:58 kozlik Exp $
 */

// variable $_data_layer_required_methods should be difined at beginning of each php script
// $_data_layer_required_methods=array();
 
class CData_Layer{

	var $db=null;						//PEAR DB object
	var $ldap=null;						//PEAR DB LDAP object

	var $act_row=0, $num_rows=0;		//used when result returns too many rows
	var $showed_rows;					//how many rows from result display

	var $_data_layer_loaded_methods=array();										

	/*
	 *   Constructor
	 */
	 
	function CData_Layer(){
		global $config;
		$this->showed_rows = &$config->num_of_showed_items;
	}

    /*
	 * static function
     * Create a new DataLayer object and agregate aditional methods
	 */

	function &create(&$errors){
		global $config;

		$obj = &new CData_Layer();

		$obj->require_and_agregate_methods();
		return $obj;

	}

	/*
	 *   dynamicaly agregate aditional methods
	 *   $m is string or array
	 */

	function add_method($m){
		global $_data_layer_required_methods;

		if (is_array($m)) 
			$_data_layer_required_methods = array_merge($_data_layer_required_methods, $m);
		else
			$_data_layer_required_methods[] = $m;
			
		$this->require_and_agregate_methods();
	}
										
	/*
	 *   dynamicaly agregate aditional methods
	 */

	function require_and_agregate_methods(){
		global $_data_layer_required_methods;
		global $_SERWEB;
		global $config;
		
		if (!isset($_data_layer_required_methods) or !is_array($_data_layer_required_methods)) 
			$_data_layer_required_methods = array();
			
		$_data_layer_required_methods = array_merge($config->data_layer_always_required_functions, $_data_layer_required_methods);
		
		reset($_data_layer_required_methods);
		while (list(, $item) = each($_data_layer_required_methods)) {
		
			if (false ===  array_search($item, $this->_data_layer_loaded_methods)){ //if required method isn't loaded yet, load it
			
				//require class with method definition
				if (file_exists($_SERWEB["serwebdir"] . "../data_layer/customized/method.".$item.".php")) 
					//if exists customized version of method, require it
					require_once ($_SERWEB["serwebdir"] . "../data_layer/customized/method.".$item.".php");
				else
					//otherwise require default version
					require_once ($_SERWEB["serwebdir"] . "../data_layer/method.".$item.".php");	
					
				//agregate methods of required class to this object
				aggregate_methods($this, "CData_Layer_".$item);								
				
				//add method to $_data_layer_loaded_methods array
				$this->_data_layer_loaded_methods[] = $item;										
				
				//add methods required by currently loaded method to $_data_layer_required_methods array
				$class_vars = get_class_vars("CData_Layer_".$item);							
				if (isset($class_vars['required_methods']) and is_array($class_vars['required_methods'])){
					$_data_layer_required_methods = array_merge($_data_layer_required_methods, $class_vars['required_methods']);
				}
			}
		}
	}


	/*
	 *   connect to sql database
	 */

	function connect_to_db(&$errors){
		global $config;

		if ($this->db) return $this->db;

		$cfg=&$config->data_sql;

		$num=count($cfg->host); //get number of SQL servers that we can use

		if ($num>1)	$serv=mt_rand(0, $num-1);
		else $serv=0;
		
		$tries=0;
		do{		
			$cont=0;
			$dsn = 	$cfg->type."://".
					$cfg->host[$serv]['user'].":".
					$cfg->host[$serv]['pass']."@".
					$cfg->host[$serv]['host'].
						(empty($cfg->host[$serv]['port'])?
							"":
							":".$cfg->host[$serv]['port'])."/".
					$cfg->host[$serv]['name'];
	
			$db = DB::connect($dsn, true);
	
			if (DB::isError($db)) {	
				//if connect failed and multiple servers is defined
				if (($db->getCode() == DB_ERROR_CONNECT_FAILED) and ($num>1)){ 
					//try another server
					$tries++;
					$serv++; $serv %= $num;
					if ($tries<$num) {
						$cont=1; continue;
					}
				}
				log_errors($db, $errors); return false; 
			}
		}while($cont);

		$this->db=$db;
		return $db;
	}

	
	/*
	 *   connect to LDAP
	 */
	
	function connect_to_ldap(&$errors){
		global $config;

		if ($this->ldap) return $this->ldap;

		$cfg=&$config->data_ldap;

		$num=count($cfg->host); //get number of LDAP servers that we can use

		if ($num>1)	$serv=mt_rand(0, $num-1);
		else $serv=0;
		
		$tries=0;
		do{		
			$cont=0;
		
			if ($cfg->version==2) $dsn = "ldap2";
			else if ($cfg->version==3) $dsn = "ldap3";
			else $dsn = "ldap";
	
			$dsn .=	"://".
					$cfg->host[$serv]['login_dn'].":".
					$cfg->host[$serv]['login_pass']."@".
					$cfg->host[$serv]['host'].
						(empty($cfg->host[$serv]['port'])?
							"":
							":".$cfg->host[$serv]['port'])."/".
					$cfg->base_dn;
	
			$ldap = DB::connect($dsn, true);
	
			if (DB::isError($ldap)) {	
				//if connect failed and multiple servers is defined
				if (($ldap->getCode() == DB_ERROR_CONNECT_FAILED) and ($num>1)){ 
					//try another server
					$tries++;
					$serv++; $serv %= $num;
					if ($tries<$num) {
						$cont=1; continue;
					}
				}
				log_errors($ldap, $errors); return false; 
			}
		}while($cont);
			
			
		$this->ldap=$ldap;
		return $ldap;
	}



	function set_num_rows($num_rows){
		$this->num_rows=$num_rows;
	}

	function get_num_rows(){
		return $this->num_rows;
	}

	function set_act_row($act_row){
		$this->act_row=$act_row;
	}

	function get_act_row(){
		return $this->act_row;
	}

	function get_showed_rows(){
		return $this->showed_rows;
	}

	function get_res_from(){
		return $this->get_act_row()+1;
	}

	function get_res_to(){
		global $config;
		return ((($this->get_act_row()+$this->get_showed_rows())<$this->get_num_rows())?
				($this->get_act_row()+$this->get_showed_rows()):
				$this->get_num_rows());
	}

	
	/* if act_row is bigger then num_rows, correct it */
	function correct_act_row(){
		if ($this->get_act_row() >= $this->get_num_rows()) 
			$this->set_act_row(max(0, $this->get_num_rows()-$this->get_showed_rows()));
	}
	
	
	/* return where phrase for sql commands depending on how are user's indexed */
	
	function get_indexing_sql_where_phrase($user, $uuid_col='uuid', $uname_col='username', $domain_col='domain'){
		global $config;
		if ($config->users_indexed_by=='uuid') 
			return $uuid_col."='".$user->uuid."'";
		else 
			return "(".$uname_col."='".addslashes($user->uname)."' and ".$domain_col."='".addslashes($user->domain)."')";
	}

	/* return where phrase for sql commands depending on how are user's indexed - tables where users are indexed by sip uri */

	function get_indexing_sql_where_phrase_uri($user, $uuid_col='uuid', $r_uri_col='r_uri'){
		global $config;
		if ($config->users_indexed_by=='uuid') 
			return $uuid_col."='".$user->uuid."'";
		else 
			return $r_uri_col." like 'sip:".$user->uname."@".$user->domain."%'";
	}
	

	/* return attributes and values for sql insert commands depending on how are user's indexed */

	function get_indexing_sql_insert_attribs($user, $uuid_col='uuid', $uname_col='username', $domain_col='domain'){
		global $config;

		if ($config->users_indexed_by=='uuid') {
			$attributes=$uuid_col;
			$values="'".$user->uuid."'";
		}
		else{
			$attributes=$uname_col.", ".$domain_col;
			$values="'".addslashes($user->uname)."', '".addslashes($user->domain)."'";
		}

		return array('attributes'=>$attributes, 'values'=>$values);
	}

	/* return filter for ldap commands depending on how are user's indexed */
	
	function get_indexing_ldap_filter($user, $uuid_col='uuid', $uname_col='username', $domain_col='domain'){
		global $config;
		if ($config->users_indexed_by=='uuid') return "(".$uuid_col."=".$user->uuid.")";
		else return "(&(".$uname_col."=".$user->uname.") ".
			          "(".$domain_col."=".$user->domain."))";
	
	}

	/* function generates error message when some data container is not implemented for some type of data */

	function not_implemented(){
		global $config;

		$backtrace=debug_backtrace();

		$err = PEAR::raiseError("Possible bad configuration. Function '".$backtrace[1]['function']."' is not implemented for users idndexed by: '".$config->users_indexed_by."'",
				 NULL, NULL, NULL, "Check configuration of your data layer.");

		return $err;
	}
}

?>