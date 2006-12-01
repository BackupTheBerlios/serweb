<?php
/*
 * $Id: index.php,v 1.1 2006/12/01 16:41:20 kozlik Exp $
 */

$_phplib_page_open = array("sess" => "phplib_Session");
$serwebLog  = NULL;
$ok = true;

require_once("../set_dirs.php");

require_once ($_SERWEB["functionsdir"] . "class_definitions.php");
require_once ($_SERWEB["configdir"] . "config_paths.php");
require_once ($_SERWEB["configdir"] . "set_domain.php");
require_once ($_SERWEB["configdir"] . "config_data_layer.php");
require_once ($_SERWEB["configdir"] . "config_domain_defaults.php");
require_once ($_SERWEB["configdir"] . "config.php");

if (!$config->testing_facility){
	die("Testing facility is disabled");
}

//*
if (file_exists($_SERWEB["configdir"] . "config.developer.php")){
	require_once ($_SERWEB["configdir"] . "config.developer.php");
}
//*/
require_once ($_SERWEB["functionsdir"] . "functions.php");
require_once ($_SERWEB["functionsdir"] . "data_layer.php");
require_once ($_SERWEB["functionsdir"] . "page.php");

require($_SERWEB["serwebdir"] . "load_phplib.php");
phplib_load();

function check_include($file){
	if (file_exists($file)) return true;
	
	$incl_path = ini_get('include_path');
	
	if (substr(PHP_OS, 0, 3) == 'WIN') $paths = explode(";", $incl_path);
	else $paths = explode(":", $incl_path);
	
	foreach ($paths as $p){
		if (substr($p, -1, 1) == "/" or substr($p, -1, 1) == "\\") $f = $p.$file;
		else $f = $p."/".$file;

		if (file_exists($f)) return true;
	}
	
	return false;
}



function test_pear(){
	if (check_include("PEAR.php")) require_once ("PEAR.php");
	
	if (class_exists('PEAR')) return array(1, 'OK');

	$out = "PHP PEAR library is not installed or PHP is not correctly configured. ".
		   "The PEAR library should be installed in directory mentioned in ".
		   "include_path directive in your php.ini. <br />Your include_path have ".
		   "this value: '".htmlspecialchars(ini_get('include_path'))."'. <br />".
		   "You can get more info or download the PEAR library from this URL: ".
		   "<a href='http://pear.php.net'>http://pear.php.net</a>";
		   
	return array(2, $out);
}

function test_pear_db(){
	if (check_include("DB.php")) require_once ("DB.php");

	if (class_exists('DB')) return array(1, 'OK');

	$out = "PEAR DB package is not installed. Type \"pear install DB\" on your ".
	       "command line.";

	return array(2, $out);
}

function test_pear_log(){
	global $config, $serwebLog;
	if (check_include("Log.php")) require_once ("Log.php");
	
	if (class_exists('Log')) {
		$GLOBALS['serwebLog'] = Log::singleton("file", $config->log_file, "serweb test script", array(), PEAR_LOG_DEBUG);
		return array(1, 'OK');
	}

	$out = "PEAR Log package is not installed. It is not strictly required but ".
	       "you can't use logging facility without it. Logging facility ".
		   "may be very useful for finding errors. To install it type \"pear ".
		   "install Log\" on your command line.";

	if ($config->enable_logging){
		$out.="<br /><br />Logging is enabled. Either disable it or install PEAR Log ".
		      "package! You may disable it by \$config->enable_logging = false ".
		      "in config.php";

		return array(2, $out);
	}

	return array(3, $out);
}


function test_pear_xml_rpc(){
	global $_SERWEB;
	
	if (check_include("XML/RPC.php")) require_once ("XML/RPC.php");

	if (!class_exists('XML_RPC_Client')) {
		$out = "PEAR XML_RPC package is not installed. Type \"pear install XML_RPC\" ".
		       "on your command line.";

		return array(2, $out);
	}

	require_once ($_SERWEB["functionsdir"] . "xml_rpc_patch.php");
	
	exec("pear list", $list, $ret_val);
	if ($ret_val != 0){
		$out = "Version can't be checked. Can't execute 'pear list' command";
		return array(3, $out);
	}	

	if (!is_array($list)){
		$out = "Version can't be checked. 'pear list' command did not returned any output.";
		return array(3, $out);
	}	

	foreach ($list as $row){
		if (ereg("^ *XML_RPC +([.1-9]+).*", $row, $regs)){
			$v = explode(".", $regs[1]);
			if ($v[0] == 0 or ($v[0] == 1 and $v[1] < 4)){
				$out = "Installed PEAR XML_RPC package is old (version: ".$regs[1].
				       "). Type \"pear upgrade XML_RPC\" ".
				       "on your command line to upgrade this package to latest version. ";
				
				return array(3, $out);
			}
			else {
				return array(1, 'OK');
			}
		}
	}

	$out = "Can't check version of your PEAR XML_RPC package.";
	
	return array(3, $out);
}


function test_imap(){
	if (extension_loaded('imap')) return array(1, 'OK');

	$out = "IMAP extension for PHP is not installed. You should write <br \>".
	       "'extension=imap.so'<br /> into your php.ini. For more info about ".
		   "this extension you could see: <a href='http://www.php.net/manual/en/ref.imap.php'>http://www.php.net/manual/en/ref.imap.php</a>";
	return array(2, $out);
}


function test_curl(){
	if (extension_loaded('curl')) return array(1, 'OK');

	$out = "CURL extension for PHP is not installed. You should write <br \>".
	       "'extension=curl.so'<br /> into your php.ini. For more info about ".
		   "this extension you could see: <a href='http://www.php.net/manual/en/ref.curl.php'>http://www.php.net/manual/en/ref.curl.php</a>";
	return array(2, $out);
}


function test_db_ext(){
	global $config;
	
	if ($config->data_sql->type=="mysql") {
		$ext = "mysql"; 
		$db  = "MySQL";
		$url = "http://www.php.net/manual/en/ref.mysql.php";
	}
	elseif ($config->data_sql->type=="pgsql") {
		$ext = "pgsql"; 
		$db  = "PostgreSQL";
		$url = "http://www.php.net/manual/en/ref.pgsql.php";
	}
	else{
		$out = "Unknown database backend is selected in your config_data_layer.php. ".
		       "Please check value of variable: \$config->data_sql->type.";
		return array(2, $out);
	}

	if (extension_loaded($ext)) return array(1, 'OK');

	$out = "You selected ".$db." as your data backend. But ".$ext." extension for ".
	       "PHP is not installed. You should write <br \>".
	       "'extension=".$ext.".so'<br /> into your php.ini. For more info about ".
		   "this extension you could see: <a href='".$url."'>".$url."</a>";
	return array(2, $out);
}

function test_method_aggregation(){

	if (function_exists('aggregate_methods')){
		return array(1, 'OK');
	}

	if (function_exists('runkit_class_adopt')){
		return array(1, 'OK');
	}

	if (function_exists('classkit_aggregate_methods')){
		if (version_compare("5.0",  PHP_VERSION, "<=") and 
			version_compare("5.1",  PHP_VERSION, ">")){

			return array(1, 'OK');
		} 
	
		$out = "Classkit extension is installed. But it is not stable and seems ".
		        "to be working OK only in PHP 5.0.x. It probably will not work and ".
				"it may cause core dumps. You should replace Classkit with Runkit ".
				"extension from PECL repository. Type 'pecl install -f runkit' on your command line ".
				"for install the extension. And do not forget enable the extension in ".
				"your php.ini file.";
		return array(3, $out);
	}


	$out = ("Support for method aggregation is not installed. This is probably because ".
			"PHP 5 or later is running on this server. Try install Runkit or ".
			"Classkit extension from PECL repository (<a href='http://pecl.php.net'>http://pecl.php.net</a>). ".
			"Useing classkit is safe with ".
			"PHP 5.0, but does not work with later versions of PHP. Useing runkit ".
			"is experimental. Type 'pecl install -f runkit' on your command line ".
			"for install the extension. And do not forget enable the extension in ".
			"your php.ini file.");

	return array(2, $out);

}


function test_ser_management(){
	global $config;

	$out = "";
	$errors = array();
	$data = CData_Layer::singleton("auth_user", $errors);


	if ($config->use_rpc){
		$out .= "SerWeb is configured to use XML-RPC to connect to SER<br />";
	
		if (!$data->connect_to_xml_rpc(null, $errors)) {
			$out .= "Can't use XML-RPC. Error: ".implode("; ", $errors);
			return array(3, $out);
		}
			
		$params = array();
		$msg = new XML_RPC_Message('core.version', $params);
		$res = $data->rpc->send($msg);
	
		if ($data->rpc_is_error($res)){
			log_errors($res, $errors); 
			$out .= "Can't use XML-RPC. Error: ".implode("; ", $errors);
			return array(3, $out);
		}

	    $val = $res->value();
	    if ($val->kindOf() != "scalar"){
			$out .= "Can't use XML-RPC. Invalid output from SER";
			return array(3, $out);
		}

		$out .= "connected to SER version: \"".$val->scalarval()."\"";
	}
	else{
		$out .= "SerWeb is configured to use FIFO to connect to SER<br />";

		$fifo_cmd=":core.version:".$config->reply_fifo_filename."\n".
		"\n";				
	
		$fifo_out=write2fifo($fifo_cmd, $err, $status);

		if ($err) {
			$errors=array_merge($errors, $err); 
			$out .= "Can't use FIFO. Error: ".implode("; ", $errors);
			return array(3, $out);
		}
	
		if (substr($status,0,1)!="2") {
			$errors[]=$status; 
			$out .= "Can't use FIFO. Error: ".implode("; ", $errors);
			return array(3, $out);
		}
			
		$out .= "connected to SER version: \"".$fifo_out."\"";
	}

	return array(1, $out);
}

function test_db_connect(){
	global $config;

	$out = "";
	$errors = array();
	$data = CData_Layer::singleton("auth_user", $errors);

	if (!$data->connect_to_db($errors)) {
		$out .= "Can't connect to DB. Error: ".implode("; ", $errors);
		return array(2, $out);
	}

	return array(1, "OK");
}

function test_db_version(){
	global $config;

	$out = "";
	$errors = array();
	$data = CData_Layer::singleton("auth_user", $errors);

	if (!$data->connect_to_db($errors)) {
		$out .= "Can't connect to DB. Error: ".implode("; ", $errors);
		return array(2, $out);
	}

	$q = "select * from version;";
	$res=$data->db->query($q);
	if (DB::isError($res)) { 
		log_errors($res, $errors); 
		$out .= "Can't check. Error: ".implode("; ", $errors);
		return array(3, $out);
	}

	$t_versions = array();
	while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)){
		$t_versions[$row['table_name']] = $row['table_version'];
	}
	$res->free();

	$warning = false;
	$tables = get_object_vars($config->data_sql);
	foreach ($tables as $k=>$v){
		if (!isset($v->table_name) or !isset($v->version)) continue;
	
		if (!isset($t_versions[$v->table_name])){
			$out .= "<div class='TversionNOK'>Expected version of table '".$v->table_name."' is ".$v->version.", but version of this table in your DB can't be checked - may be the table does not exists.</div>";
			$warning = true;
		}
		elseif ($t_versions[$v->table_name] != $v->version){
			$out .= "<div class='TversionNOK'>Expected version of table '".$v->table_name."' is ".$v->version.", but version of this table in your DB is ".$t_versions[$v->table_name]."</div>";
			$warning = true;
		}
		else {
			$out .= "<div class='TversionOK'>Version of table '".$v->table_name."' matches.</div>";
		}
	}

	if ($warning){
		$out .= "<br />Versions of some tables do not match. This may not be in the way... ".
		        "But in the case SerWeb displays errors in SQL queries, this is the reason and ".
				"you will have to reinstall your database.";
		
		return array(3, $out);
	}

	return array(1, $out);
}

function test_dirs(){
	global $config, $_SERWEB;

	if (!$config->multidomain) return array(1, 'OK');

	$out = "";
	$domain_dir = $_SERWEB["serwebdir"]."domains/";

	
	if (!is_dir($domain_dir) or !is_writable($domain_dir)) {
		$out .= "Directory '".$domain_dir."' should exists and should be writeable for user '".get_current_user()."'<br />";
	}

	if (!is_dir($config->apache_vhosts_dir) or !is_writable($config->apache_vhosts_dir)) {
		$out .= "Directory '".$config->apache_vhosts_dir."' should exists and should be writeable for user '".get_current_user()."'<br />";
	}


	if ($out) return array(3, $out);
	else return array(1, 'OK');
}

function check($label, $check_function){
	global $ok;
	
	if (!function_exists($check_function)) 
		die("Function: ".$check_function." does not exists. Terminating.");

	echo "<div><span class='label'>".$label."</span>\n";
	flush();
	
	$res = call_user_func($check_function);
	
	if ($res[0] == 1) 	  {echo "<span class='checkOk'>".$res[1]."</span>";}
	elseif ($res[0] == 2) {echo "<div class='checkError'>".$res[1]."</div>"; $ok = false; }
	elseif ($res[0] == 3) {echo "<div class='checkWarning'>".$res[1]."</div>"; $ok = false; } 
	else echo "Unknown output of check function: ".$res[1];
	
	echo "</div>";
	
	if ($res[0] == 2) return false;
	
	return true;
}

?>
<html>
<head>
	<style>
.checkOk{
	color: Green;
	font-weight: bold;
}

.checkError{
	color: Red;
	font-weight: bold;
}

.checkWarning{
	color: #FF8C00;
	font-weight: bold;
}

.TversionOK{
	color: Green;
	margin-left: 5em;
}

.TversionNOK{
	color: Red;
	margin-left: 5em;
}

	</style>
</head>
<body>
<h1>Test of PHP and serweb configuration:</h1>
<?php
	do{
		if (!check("Checking PHP PEAR library:", "test_pear")) break;
		if (!check("Checking PEAR DB extendion:", "test_pear_db")) break;
		if (!check("Checking PEAR Log extension:", "test_pear_log")) break;
		if (!check("Checking PEAR XML_RPC extension:", "test_pear_xml_rpc")) break;
		if (!check("Checking PHP IMAP extension:", "test_imap")) break;
		if (!check("Checking PHP CURL extension:", "test_curl")) break;
		if (!check("Checking PHP database extension:", "test_db_ext")) break;
		if (!check("Checking support for method aggregation:", "test_method_aggregation")) break;
//		if (!check("Trying connect to SER with its management interface:", "test_ser_management")) break;
		if (!check("Trying connect to DB:", "test_db_connect")) break;
		if (!check("Checking versions of DB tables:", "test_db_version")) break;
		if (!check("Checking access to directories:", "test_dirs")) break;
	}while (false);
?>

<br/>

<?php 
	if ($ok) echo "<h3 style='color: green;'>Congratulations! It seems your configuration is OK and everything should work well.</h3>";
	else     echo "<h3 style='color: red;'>Some errors in your configuration found. Probably some functions of serweb will not work.</h3>";
?>

<p>It is recommended to disable this testing facility after you finish 
cofiguration of serweb. You could do this by set 
<i>$config-&gt;testing_facility=false;</i> in your config.php</p>

<p>You could start useing serweb by clicking one of following links:</p>
<ul>
	<li><a href="../user">user interface</a></li>
	<li><a href="../admin">admin interface</a></li>
</ul>

<p>If you have any troubles with serweb configuration, please read README, INSTALL and FAQ
files, included in SerWeb package. Also try search archives of serweb-users mailing list:
<a href="http://lists.iptel.org/mailman/listinfo/serweb-users">http://lists.iptel.org/mailman/listinfo/serweb-users</a>.
If you will not find answer to your question, try ask at the mailing list.</p>

</body>
</html>
