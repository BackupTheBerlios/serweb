<?
/*
 * $Id: functions.php,v 1.44 2004/09/06 09:44:16 kozlik Exp $
 */


$reg_validate_email="^[^@]+@[^.]+\.[^,;@ ]+$";

$reg_validate_username="^((8[0-9]*)|([a-zA-Z][a-zA-Z0-9.]*))$";

$name_of_month[1]="Jan";
$name_of_month[2]="Feb";
$name_of_month[3]="Mar";
$name_of_month[4]="Apr";
$name_of_month[5]="May";
$name_of_month[6]="Jun";
$name_of_month[7]="Jul";
$name_of_month[8]="Aug";
$name_of_month[9]="Sep";
$name_of_month[10]="Oct";
$name_of_month[11]="Nov";
$name_of_month[12]="Dec";

$sip_status_messages_array[100]="100 Trying";
$sip_status_messages_array[180]="180 Ringing";
$sip_status_messages_array[181]="181 Call Is Being Forwarded";
$sip_status_messages_array[182]="182 Queued";
$sip_status_messages_array[183]="183 Session Progress";
$sip_status_messages_array[200]="200 OK";
$sip_status_messages_array[300]="300 Multiple Choices";
$sip_status_messages_array[301]="301 Moved Permanently";
$sip_status_messages_array[302]="302 Moved Temporarily";
$sip_status_messages_array[305]="305 Use Proxy";
$sip_status_messages_array[380]="380 Alternative Service";
$sip_status_messages_array[400]="400 Bad Request";
$sip_status_messages_array[401]="401 Unauthorized";
$sip_status_messages_array[402]="402 Payment Required";
$sip_status_messages_array[403]="403 Forbidden";
$sip_status_messages_array[404]="404 Not Found";
$sip_status_messages_array[405]="405 Method Not Allowed";
$sip_status_messages_array[406]="406 Not Acceptable";
$sip_status_messages_array[407]="407 Proxy Authentication Required";
$sip_status_messages_array[408]="408 Request Timeout";
$sip_status_messages_array[410]="410 Gone";
$sip_status_messages_array[413]="413 Request Entity Too Large";
$sip_status_messages_array[414]="414 Request-URI Too Long";
$sip_status_messages_array[415]="415 Unsupported Media Type";
$sip_status_messages_array[416]="416 Unsupported URI Scheme";
$sip_status_messages_array[420]="420 Bad Extension";
$sip_status_messages_array[421]="421 Extension Required";
$sip_status_messages_array[423]="423 Interval Too Brief";
$sip_status_messages_array[480]="480 Temporarily Unavailable";
$sip_status_messages_array[481]="481 Call/Transaction Does Not Exist";
$sip_status_messages_array[482]="482 Loop Detected";
$sip_status_messages_array[483]="483 Too Many Hops";
$sip_status_messages_array[484]="484 Address Incomplete";
$sip_status_messages_array[485]="485 Ambiguous";
$sip_status_messages_array[486]="486 Busy Here";
$sip_status_messages_array[487]="487 Request Terminated";
$sip_status_messages_array[488]="488 Not Acceptable Here";
$sip_status_messages_array[491]="491 Request Pending";
$sip_status_messages_array[493]="493 Undecipherable";
$sip_status_messages_array[500]="500 Server Internal Error";
$sip_status_messages_array[501]="501 Not Implemented";
$sip_status_messages_array[502]="502 Bad Gateway";
$sip_status_messages_array[503]="503 Service Unavailable";
$sip_status_messages_array[504]="504 Server Time-out";
$sip_status_messages_array[505]="505 Version Not Supported";
$sip_status_messages_array[513]="513 Message Too Large";
$sip_status_messages_array[600]="600 Busy Everywhere";
$sip_status_messages_array[603]="603 Decline";
$sip_status_messages_array[604]="604 Does Not Exist Anywhere";
$sip_status_messages_array[606]="606 Not Acceptable";


class Creg{

	var $alphanum;
	var $mark;
	var $unreserved;
	var $escaped;
	var $user_unreserved;
	var $user;

	var $port;
	var $hex4;
	var $hexseq;
	var $hexpart;
	var $ipv4address;
	var $ipv6address;
	var $ipv6reference;

	var $toplabel;
	var $domainlabel;
	var $hostname;
	var $host;

	var $token;
	var $param_unreserved;
	var $paramchar;
	var $pname;
	var $pvalue;
	var $uri_parameter;
	var $uri_parameters;

	var $address;
	var $sip_address;

	function Creg(){
		$this->alphanum="[a-zA-Z0-9]";
		$this->mark="[-_.!~*'()]";
		$this->unreserved="(".$this->alphanum."|".$this->mark.")";
		$this->escaped="(%[0-9a-fA-F][0-9a-fA-F])";
		$this->user_unreserved="[&=+$,;?/]";
		$this->user="(".$this->unreserved."|".$this->escaped."|".$this->user_unreserved.")+";

		$this->port="[0-9]+";
		$this->hex4="([0-9a-fA-F]{1,4})";
		$this->hexseq="(".$this->hex4."(:".$this->hex4.")*)";
		$this->hexpart="(".$this->hexseq."|(".$this->hexseq."::".$this->hexseq."?)|(::".$this->hexseq."?))";
		$this->ipv4address="([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})";
		$this->ipv6address="(".$this->hexpart."(:".$this->ipv4address.")?)";
		$this->ipv6reference="(\\[".$this->ipv6address."])";

		/* toplabel is the name of top-level DNS domain ".com" -- alphanum only
		   domainlabel is one part of the dot.dot name string in a DNS name ("iptel");
		     it must begin with alphanum, can contain special characters (-) and end
			 with alphanums
		   hostname can include any number of domain lables and end with toplable
		*/
		$this->toplabel="([a-zA-Z]|([a-zA-Z](~|".$this->alphanum.")*".$this->alphanum."))";
		$this->domainlabel="(".$this->alphanum."|(".$this->alphanum."([~-]|".$this->alphanum.")*".$this->alphanum."))";
		$this->hostname="((".$this->domainlabel."\\.)*".$this->toplabel."(\\.)?)";
		$this->host="(".$this->hostname."|".$this->ipv4address."|".$this->ipv6reference.")";

		$this->token="(([-.!%*_+`'~]|".$this->alphanum.")+)";
		$this->param_unreserved="[\\][/:&+$]";
		$this->paramchar="(".$this->param_unreserved."|".$this->unreserved."|".$this->escaped.")";
		$this->pname="((".$this->paramchar.")+)";
		$this->pvalue="((".$this->paramchar.")+)";
		$this->uri_parameter="(".$this->pname."(=".$this->pvalue.")?)";
		$this->uri_parameters="((;".$this->uri_parameter.")*)";

		$this->address="(".$this->user."@)?".$this->host."(:".$this->port.")?".$this->uri_parameters;
		$this->sip_address="[sS][iI][pP]:".$this->address;
		
		/* regex for phonenumber which could contain some characters as: - / <space> this characters should be removed */		
		$this->phonenumber="\\+?[-/ 1-9]+";

		/* strict phonenumber - only numbers and optional initial + */
		$this->phonenumber_strict="\\+?[1-9]+";
	}

	/* parse domain name fro sip address*/
	function get_domainname($sip){
		return ereg_replace($this->sip_address,"\\5", $sip);
	}

	/* parse user name fro sip address*/
	function get_username($sip){

		$uname=ereg_replace($this->sip_address,"\\1", $sip);

		//remove the '@' at the end
		return substr($uname,0,-1);
	}
	
	/* converts string which can be accepted by regex $this->phonenumber to 
	   string which can be accepted by regex $this->phonenumber_strict */
	function convert_phonenumber_to_strict($phonenumber){
		return str_replace(array('-', '/', ' '), "", $phonenumber);
	}
}


function print_errors($errors){
	if (is_Array($errors))
		foreach ($errors as $val) echo "<div class=\"errors\">".$val."</div>\n";
}

function print_message($msg){
	if ($msg) echo "<div class=\"message\">".htmlspecialchars($msg)."</div>\n";
}

function get_sess_url($url){
	global $sess;

	if ($sess) return $sess->url($url);
	else return $url;

}

/*
	return '&nbsp;' if $str is empty, otherwise return $str
*/

function nbsp_if_empty($str){
	if (ereg('^[[:space:]]*$', $str)) return "&nbsp;";
	else return $str;
}

/*
	print links to other results of search

	actual - from each item it is printed
	num - how many items total
	rows - how many items on one page
	url - href in <a> takgs
*/

function print_search_links($actual, $num, $rows, $url){

	$links=10; //max num of links at one page

	if ($num<=$rows) return;

	$lfrom=$actual-($links*$rows); if ($lfrom<0) $lfrom=0;
	$lto=$actual+($links*$rows); if ($lto>$num) $lto=$num;

	if ($actual>0) echo '<a href="'.get_sess_url($url.((($actual-$rows)>0)?($actual-$rows):0)).'" class="f12">&lt;&lt;&lt;</a>&nbsp;';
	for ($i=$lfrom; $i<$lto; $i+=$rows){
		if ($i<=$actual and $actual<($i+$rows)) echo '<span class="f14b">'.(floor($i/$rows)+1).'</span>&nbsp;';
		else echo '<a href="'.get_sess_url($url.$i).'" class="f12">'.(floor($i/$rows)+1).'</a>&nbsp;';
	}
 	if (($actual+$rows)<$num) echo '<a href="'.get_sess_url($url.($actual+$rows)).'" class="f12">&gt;&gt;&gt;</a>';

}

function connect_to_db(&$errors){
	global $config;

	$dsn = 	$config->data_sql->db_type."://".
			$config->data_sql->db_user.":".
			$config->data_sql->db_pass."@".
			$config->data_sql->db_host.
				(empty($config->data_sql->db_port)?
					"":
					":".$config->data_sql->db_port)."/".
			$config->data_sql->db_name;

	$db = DB::connect($dsn);

	if (DB::isError($db)) {	log_errors($db, $errors); return false; }
	
	return $db;
}

/* must be called before connect_to_db() */
function connect_to_ppaid_db(){
	global $config;

	@$db=MySQL_PConnect($config->ppaid->db_host, $config->ppaid->db_user, $config->ppaid->db_pass);
	if (! $db) return false;

	if (!MySQL_Select_DB($config->ppaid->db_name, $db)) return false;
	return $db;
}


function send_mail($to, $subj, $text, $headers = ""){
	global $config;

	$headers .= "From: ".$config->mail_header_from."\n";

	@$a= Mail($to, $subj, $text, $headers);
	return $a;
}

function write2fifo($fifo_cmd, &$errors, &$status){
	global $config;

	/* open fifo now */
	$fifo_handle=fopen( $config->fifo_server, "w" );
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo"; return;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$config->reply_fifo_path );


	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";

	/* write fifo command */
	if (fwrite( $fifo_handle, $fifo_cmd)==-1) {
	    @unlink($config->reply_fifo_path);
	    @fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return;
	}
	@fclose($fifo_handle);

	/* read output now */
	@$fp = fopen( $config->reply_fifo_path, "r");
	if (!$fp) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return;
	}

	$status=fgetS($fp,256);
	if (!$status) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return;
	}
	
	$rd=fread($fp,8192);
	@unlink($config->reply_fifo_path);
	
	return $rd;
}


function filter_fl($in){
	$line=0;
	$result="";
	
	$in_arr=explode("\n", $in);
	
	foreach($in_arr as $row){
		$line++;
		
		//skip body
		if ($row=="") break;
		
		// uri and outbound uri at line 2,3: copy and paste
		if ($line==1 || $line==2) {
			$result.=$row."\n";
			continue;
		}
		
		// line 4: Route; empty if ".", copy and paste otherwise
		if ($line==3 && $row=".") continue;
		// if non-empty, copy and paste it
		if ($line==3) {
			$result.=$row."\n";
			continue;
		}

		// filter out to header field for use in next requests	
		if (ereg ("^(To|t):", $row)){
			$result.=$row."\n";
			continue;
		}
		
		// anything else will be ignored
		
	}
	
	return $result;
}

function click_to_dial($target, $uri, &$errors){
	global $config;

	$from="<".$config->ctd_from.">";
	$callidnr = uniqid("");
	$callid = $callidnr.".fifouacctd";
	$cseq=1;
	$fixed_dlg="From: ".$from.";tag=".$callidnr."\nCall-ID: ".$callid."\nContact: <sip:caller@!!>";
	$status="";
	$outbound_proxy=".";
	
	if (!empty($config->ctd_outbound_proxy)) $outbound_proxy=$config->ctd_outbound_proxy;
	

/* initiate dummy INVITE with pre-3261 "on-hold"
   (note the dots -- they mean in order of appearance:
   outbound uri, end of headers, end of body; eventualy
   the FIFO request must be terminated with an empty line) */
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"INVITE\n".
		$uri."\n".
		$outbound_proxy."\n".
		"$fixed_dlg\n".
		"To: <".$uri.">\n".
		"CSeq: ".$cseq." INVITE\n".
		'Reject-Contact: *;automata="YES"'."\n".
		"Content-Type: application/sdp\n".
		".\n".
		"v=0\n".
		"o=click-to-dial 0 0 IN IP4 0.0.0.0\n".
		"s=session\n".
		"c=IN IP4 0.0.0.0\n".
		"b=CT:1000\n".
		"t=0 0\n".
		"m=audio 9 RTP/AVP 0\n".
		"a=rtpmap:0 PCMU/8000\n".
		".\n\n";
		
	$dlg=write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }

	$dlg=filter_fl($dlg);
	
	$cseq++;
	
	// start reader now so that it is ready for replies
	// immediately after a request is out
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"REFER\n".
		$dlg.		//"\n".
		"$fixed_dlg\n".
		"CSeq: ".$cseq." REFER\n".
		"Referred-By: ".$from."\n".
		"Refer-To: ".$target."\n".
		".\n".
		".\n\n";

	write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }

	$cseq++;

/* well, URI is trying to call TARGET but still maintains the
   dummy call we established with previous INVITE transaction:
   tear it down */

	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"BYE\n".
		$dlg.  // "\n".
		$fixed_dlg."\n".
		"CSeq: ".$cseq." BYE\n".
		".\n".
		".\n\n";

	write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }
   
}


/**************************************
 *         find users class
 **************************************/

class Cfusers{
	var $classname='Cfusers';
	var $persistent_slots = array("usrnm", "domain", "fname", "lname", "email", 
		"onlineonly", "act_row", "adminsonly", "template");

	var $usrnm, $fname, $lname, $email, $domain, $onlineonly=0, $act_row=0;
	var $adminsonly=0;

	var $f;
	var $smarty, $template;

	function Cfusers($cfg=null){
		if (is_array($cfg)){

			if (isset($cfg['adminsonly']) and $cfg['adminsonly']){
				$this->adminsonly=1;
			}

			if (isset($cfg['onlineonly']) and $cfg['onlineonly']){
				$this->onlineonly=1;
			}

			if (isset($cfg['template'])){
				$this->template=$cfg['template'];
			}
		}
		
		$this->smarty = new Smarty_Serweb;
	}
	
	function init(){
		global $sess, $config, $HTTP_POST_VARS, $HTTP_GET_VARS;

		if (isset($HTTP_POST_VARS['usrnm'])) $this->usrnm=$HTTP_POST_VARS['usrnm'];
		if (isset($HTTP_POST_VARS['fname'])) $this->fname=$HTTP_POST_VARS['fname'];
		if (isset($HTTP_POST_VARS['lname'])) $this->lname=$HTTP_POST_VARS['lname'];
		if (isset($HTTP_POST_VARS['email'])) $this->email=$HTTP_POST_VARS['email'];
		if (isset($HTTP_POST_VARS['domain'])) $this->domain=$HTTP_POST_VARS['domain'];

		if (isset($HTTP_POST_VARS['okey_x'])){
			if (isset($HTTP_POST_VARS['onlineonly'])) $this->onlineonly=$HTTP_POST_VARS['onlineonly'];
			else $this->onlineonly=0;

			if (isset($HTTP_POST_VARS['adminsonly'])) $this->adminsonly=$HTTP_POST_VARS['adminsonly'];
			else $this->adminsonly=0;
		}

		if (isset($HTTP_GET_VARS['act_row'])) $this->act_row=$HTTP_GET_VARS['act_row'];
		if (isset($HTTP_POST_VARS['okey_x'])) $this->act_row=0;

//		if (!$this->act_row or isset($http_get_vars['okey_x'])) $sess_act_row=0;
		
		$this->f = new form;                   // create a form object
		
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"usrnm",
									 "size"=>11,
									 "maxlength"=>50,
		                             "value"=>$this->usrnm,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"fname",
									 "size"=>11,
									 "maxlength"=>25,
		                             "value"=>$this->fname,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"lname",
									 "size"=>11,
									 "maxlength"=>45,
		                             "value"=>$this->lname,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"email",
									 "size"=>11,
									 "maxlength"=>50,
		                             "value"=>$this->email,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"domain",
									 "size"=>11,
									 "maxlength"=>128,
	        	                     "value"=>$this->domain,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"checkbox",
		                             "value"=>1,
									 "checked"=>$this->onlineonly,
	    	                         "name"=>"onlineonly"));
		$this->f->add_element(array("type"=>"checkbox",
		                             "value"=>1,
									 "checked"=>$this->adminsonly,
	    	                         "name"=>"adminsonly"));
		
		$this->f->add_element(array("type"=>"submit",
		                             "name"=>"okey",
		                             "src"=>$config->img_src_path."butons/b_find.gif",
									 "extrahtml"=>"alt='find'"));
	
	}
	
	function get_query_where_phrase($tablename=""){
		if ($tablename) $tablename.=".";
	
		$query_c="";
		if ($this->usrnm) $query_c.=$tablename."username like '%".$this->usrnm."%' and ";
		if ($this->fname) $query_c.=$tablename."first_name like '%".$this->fname."%' and ";
		if ($this->lname) $query_c.=$tablename."last_name like '%".$this->lname."%' and ";
		if ($this->email) $query_c.=$tablename."email_address like '%".$this->email."%' and ";
		if ($this->domain) $query_c.=$tablename."domain like '%".$this->domain."%' and ";
		$query_c.="1 ";
		
		return $query_c;
	
	}
	
	function get_form(){
		global $lang_str;
		$this->smarty->assign_phplib_form('form', $this->f, array('jvs_name'=>'form'));
		$this->smarty->assign_by_ref('lang_str', $lang_str);
		return $this->smarty->fetch($this->template);
	}
	
	function print_form(){
		echo $this->get_form();
	}
	
}
 
 
function multidomain_get_file($filename){
	global $config;
	
	$dir=dirname(__FILE__)."/domains/";

	if (file_exists($dir.$config->domain."/".$filename)) return $config->domains_path.$config->domain."/".$filename;
	else return $config->domains_path."_default/".$filename;
}

/*
	get error message from PEAR_Error object and write it to errors array and to error log
*/

function log_errors($err_object, &$errors){
	global $serwebLog;

	//get name of function from which log_errors is called
	$backtrace=debug_backtrace();
	if (isset($backtrace[1]['function'])) $funct=$backtrace[1]['function'];
	else $funct=null;

	//get backtrace frame from err_object which correspond to function from which log_errors is called
	$backtrace=$err_object->getBacktrace();
	$last_frame=end($backtrace);
	
	if ($funct and $funct!=__FUNCTION__){
		do{
			if ($last_frame['function']==$funct){
				$last_frame=prev($backtrace);
				break;
			}
		}while($last_frame=prev($backtrace));

		//if matchng frame is not found, use last_frame	
		if (!$last_frame) { 
			//if logging is enabled
			if ($serwebLog){ 
				$serwebLog->log("function: LOG ERRORS - bad parametr ".$funct, PEAR_LOG_ERR);
			}

			$last_frame=end($backtrace);
		}
	}
	
	$errors[]=$err_object->getMessage().", file: ".$last_frame['file'].":".$last_frame['line'];

	//if logging is enabled
	if ($serwebLog){ 
	
		$log_message= "file: ".$last_frame['file'].":".$last_frame['line'].": ".$err_object->getMessage()." - ".$err_object->getUserInfo();
		//remove endlines from the log message
		$log_message=str_replace(array("\n", "\r"), "", $log_message);
		$log_message=ereg_replace("[[:space:]]{2,}", " ", $log_message);
		$serwebLog->log($log_message, PEAR_LOG_ERR);
		
	}

}
/*
	sets varibale gets by $_POST or by $_GET to global
*/
function set_global($var){
	global $_POST, $_GET, $GLOBALS;
	
	if (isset($_POST[$var])) $GLOBALS[$var]=$_POST[$var];
	elseif (isset($_GET[$var])) $GLOBALS[$var]=$_GET[$var];
	else $GLOBALS[$var]=null;
}

/*
	return Cserweb_auth like get param like string
*/
function userauth_to_get_param($user, $prefix){
	return $prefix."_id=".RawURLencode($user->uuid)."&".
	       $prefix."_n=".RawURLencode($user->uname)."&".
		   $prefix."_d=".RawURLencode($user->domain);
}

/*
	add to form hidden values Cserweb_auth 
*/
function userauth_to_form($user, $prefix, &$form){
	$form->add_element(array("type"=>"hidden",
	                         "name"=>$prefix."_id",
	                         "value"=>$user->uuid));

	$form->add_element(array("type"=>"hidden",
	                         "name"=>$prefix."_n",
	                         "value"=>$user->uname));
							 
	$form->add_element(array("type"=>"hidden",
	                         "name"=>$prefix."_d",
	                         "value"=>$user->domain));
}

/*
	return user info like get param like string
*/
function user_to_get_param($uuid, $uname, $domain, $prefix){
	return $prefix."_id=".RawURLencode($uuid)."&".
	       $prefix."_n=".RawURLencode($uname)."&".
		   $prefix."_d=".RawURLencode($domain);
}


/*
	return Cserweb_auth from get or post param
*/
function get_userauth_from_get_param($prefix){
	global $_GET, $_POST;
	
	if ( isset($_GET[$prefix."_id"]) and
	     isset($_GET[$prefix."_n"]) and
		 isset($_GET[$prefix."_d"])) 
		return new Cserweb_auth($_GET[$prefix."_id"], $_GET[$prefix."_n"], $_GET[$prefix."_d"]);
		
	if ( isset($_POST[$prefix."_id"]) and
	     isset($_POST[$prefix."_n"]) and
		 isset($_POST[$prefix."_d"])) 
		return new Cserweb_auth($_POST[$prefix."_id"], $_POST[$prefix."_n"], $_POST[$prefix."_d"]);
	
	return false;
}
?>
