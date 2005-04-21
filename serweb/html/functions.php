<?
/**
 * Miscellaneous functions and variable definitions
 * 
 * @author    Karel Kozlik
 * @version   $Id: functions.php,v 1.56 2005/04/21 15:09:46 kozlik Exp $
 * @package   serweb
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
		global $config, $reg_validate_email, $reg_validate_username;
		
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
		
		/** regex for phonenumber which could contain some characters as: - / <space> this characters should be removed */		
		$this->phonenumber = $config->phonenumber_regex;		// "\\+?[-/ 1-9]+"

		/** strict phonenumber - only numbers and optional initial + */
		$this->phonenumber_strict = $config->strict_phonenumber_regex;		// "\\+?[1-9]+"
		
		$this->serweb_username = $reg_validate_username;
		$this->email = $reg_validate_email;
	}

    /**
     *	Attempts to return a reference to a Creg instance.
     *	Only creating a new instance if no Creg instance currently exists.
     *
     *	@return object Creg		instance of Creg class
     *	@static
     *	@access public 
     */
	function &singleton(){
		static $instance;
		
		if(! isset($instance)) $instance = new Creg();
		
		return $instance;
	}

	/**
	 * parse domain name from sip address
	 *
	 * @param string $sip sip address
	 * @return string domain name
	 */
	function get_domainname($sip){
		return ereg_replace($this->sip_address,"\\5", $sip);
	}

	/**
	 * parse user name from sip address
	 *
	 * @param string $sip sip address
	 * @return string username
	 */
	function get_username($sip){

		$uname=ereg_replace($this->sip_address,"\\1", $sip);

		//remove the '@' at the end
		return substr($uname,0,-1);
	}
	
	/**
	 *	Parse parameters from sip uri
	 *
	 *	@param string $sip	sip uri
	 *	@return array 		associative array of parameters and their values
	 */
	function get_parameters($sip){
		$params = explode(';', $sip);
		//first element is containing part of sip uri before parameters
		unset($params[0]);
		
		$out = array();
		if (is_array($params)){
			foreach($params as $param){
				$p = explode('=', $param, 2);
				$out[$p[0]] = $p[1];
			}
		}
		
		return $out;
	}
	
	/** converts string which can be accepted by regex $this->phonenumber to string which can be accepted by regex $this->phonenumber_strict 
	 *
	 *	@param string $phonenumber
	 *	@return string
	 */
	function convert_phonenumber_to_strict($phonenumber){
		return str_replace(array('-', '/', ' ', '(', ')'), "", $phonenumber);
	}
	
	/** return javascript which do the same as method {@link convert_phonenumber_to_strict} 
	 *
	 *	@param string $in_var 	name of js variable with string for conversion 
	 *	@param string $out_var	name of js variable to which converted string will be stored 
	 *	@return string 			line of javascript code
	 */
	function convert_phonenumber_to_strict_js($in_var, $out_var){
		return $out_var." = ".$in_var.".replace(/[-\\/ ()]/g, '')";
	}
}


/* obsoleted - should be removed */

function connect_to_db(&$errors){
	global $config;

	$i = 0;
	$dsn =  $config->data_sql->type."://".
			$config->data_sql->host[$i]['user'].":".
			$config->data_sql->host[$i]['pass']."@".
			$config->data_sql->host[$i]['host'].
				(empty($config->data_sql->db_port)?
					"":
					":".$config->data_sql->db_port)."/".
			$config->data_sql->host[$i]['name'];
			
	$db = DB::connect($dsn);

	if (DB::isError($db)) { log_errors($db, $errors); return false; }

	return $db;
}


/**
 * send email
 *
 * same as PHP function mail(), but additionaly is set header "From" by 
 * config option {@link $config->mail_header_from}
 *
 * @param string $to address of recipient
 * @param string $subj subject of email
 * @param string $text email body
 * @param string $headers email headers
 * @return boolean TRUE if the mail was successfully accepted for delivery, FALSE otherwise
 *
 * @todo add error logging/reporting
 */
 
function send_mail($to, $subj, $text, $headers = ""){
	global $config;

	$headers .= "From: ".$config->mail_header_from."\n";

	@$a= mail($to, $subj, $text, $headers);
	return $a;
}

/**
 *	Write command to FIFO
 *
 *	@param string $fifo		fifo command
 *	@param array $errors	if some error occur during calling this function is writed here
 *	@param string $status	put result of command execution here
 *	$return string			result of the fifo command, FALSE on error
 */
function write2fifo($fifo_cmd, &$errors, &$status){
	global $config;

	/* open fifo now */
	$fifo_handle=fopen( $config->fifo_server, "w" );
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo"; return false;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$config->reply_fifo_path );


	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";

	/* write fifo command */
	if (fwrite( $fifo_handle, $fifo_cmd)==-1) {
	    @unlink($config->reply_fifo_path);
	    @fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return false;
	}
	@fclose($fifo_handle);

	/* read output now */
	@$fp = fopen( $config->reply_fifo_path, "r");
	if (!$fp) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return false;
	}

	$status=fgetS($fp,256);
	if (!$status) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return false;
	}
	
	$rd=fread($fp,8192);
	@unlink($config->reply_fifo_path);
	
	return $rd;
}

/**
 *	Filter result of calling fifo command t_uac_dlg. Is used by function {@link click_to_dial}
 *
 * 	@param string $in
 *	@result string
 *	@access private
 */
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

/**
 *	Initiate dial request to $target from $uri
 *
 *	@param string $target	sip uri of callee
 *	@param string $uri		sip uri of caller
 *	@param array $errors	if some error occur during calling this function is writed here
 */
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
	
	if (!empty($config->ctd_secret)) $secret = "Secret: ".$config->ctd_secret."\n";
	else $secret = "";

/* initiate dummy INVITE with pre-3261 "on-hold"
   (note the dots -- they mean in order of appearance:
   outbound uri, end of headers, end of body; eventualy
   the FIFO request must be terminated with an empty line) */
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"INVITE\n".
		$uri."\n".
		$outbound_proxy."\n".
		$secret.
		$fixed_dlg."\n".
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
		$fixed_dlg."\n".
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

 
/**
 *	Return path to file from directory for concrete domain
 *
 *	Path in this case mean path in html tree. If file is not found in directory
 *	for specified domain, path to file for default domain directory is returned.
 *
 *	@param string $filename
 *	@retrun string
 */ 
function multidomain_get_file($filename){
	global $config;
	
	$dir=dirname(__FILE__)."/domains/";

	if (file_exists($dir.$config->domain."/".$filename)) 
		return $config->domains_path.$config->domain."/".$filename;
	else {
		sw_log("Useing file from default domain for filename: ".$filename, PEAR_LOG_INFO);
		return $config->domains_path."_default/".$filename;
	}
}

/**
 *	Return path to file for specified language mutation
 *
 *	Path in this case mean filesystem path. This function searching in some 
 *  directories and if corresponfing file is found, retrun path to it. Directories
 *	are scanned in this order:
 *		- dir for specified language
 *		- dir for default language
 *	If file isn't found, function return false
 *
 *	This function only prefix $filename by language (in which file exists) in 
 *	order to it can be also used as path in html tree
 *
 *	@param string $filename		name of file is searching for
 *	@param string $ddir			subdirectory within html dir
 *	@param string $lang			language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@retrun string				path to file on success, false on error
 */
function get_file_by_lang($filename, $ddir, $lang){
	global $config, $reference_language, $available_languages;
	
	$dir=dirname(__FILE__); //html dir
	$ln = $available_languages[$lang][2];
	$ref_ln = $available_languages[$reference_language][2];

	if (!empty($ddir) and substr($ddir, -1) != "/") $ddir.="/";

	if (file_exists($dir."/".$ddir.$ln."/".$filename)) 
		return $ln."/".$filename;

	else if (file_exists($dir."/".$ddir.$ref_ln."/".$filename)){
		sw_log("Useing file in default language (requested lang: ".$ln.") for filename: ".$filename, PEAR_LOG_INFO);
		return $ref_ln."/".$filename;
	}

	else {
		sw_log("File not found. Filename:".$filename.", Language:".$ln.", Subdir:".$ddir, PEAR_LOG_ERR);	
		return false;
	}
}

/**
 *	Return path to button image
 *
 *	@param string $button 	name of file with button image
 *	@param string $lang		language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@return string
 */
function get_path_to_buttons($button, $lang){
	global $config;
	return $config->img_src_path."int/".get_file_by_lang("buttons/".$button, "img/int", $lang);
}

/**
 *	Return path to text file for specified language mutation and concrete domain
 *
 *	Path in this case mean filesystem path. This function searching in some 
 *  directories and if corresponfing file is found, retrun path to it. Directories
 *	are scanned in this order:
 *		- dir for specified domain and specified language
 *		- dir for specified domain and default language
 *		- dir for default domain and specified language
 *		- dir for default domain and default language
 *	If file isn't found, function return false
 *
 *	@param string $filename		name of file is searching for
 *	@param string $ddir			subdirectory within domain dir
 *	@param string $lang			language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@retrun string				path to file on success, false on error
 */
function multidomain_get_lang_file($filename, $ddir, $lang){
	global $config, $reference_language, $available_languages;
	
	$dir=dirname(__FILE__)."/domains/";
	$ln = $available_languages[$lang][2];
	$ref_ln = $available_languages[$reference_language][2];

	if (!empty($ddir) and substr($ddir, -1) != "/") $ddir.="/";

	if (file_exists($dir.$config->domain."/".$ddir.$ln."/".$filename)) 
		return $dir.$config->domain."/".$ddir.$ln."/".$filename;
	
	else if (file_exists($dir.$config->domain."/".$ddir.$ref_ln."/".$filename)){
		sw_log("Useing file in default language (requested lang: ".$ln.") for filename: ".$filename, PEAR_LOG_INFO);
		return $dir.$config->domain."/".$ddir.$ref_ln."/".$filename;
	}
		
	else if (file_exists($dir."_default/".$ddir.$ln."/".$filename)){
		sw_log("Useing file from default domain for filename: ".$filename, PEAR_LOG_INFO);
		return $dir."_default/".$ddir.$ln."/".$filename;
	}

	else if (file_exists($dir."_default/".$ddir.$ref_ln."/".$filename)){
		sw_log("Useing file from default domain and in default language (requested lang: ".$ln.") for filename: ".$filename, PEAR_LOG_INFO);
		return $dir."_default/".$ddir.$ref_ln."/".$filename;
	}

	else {
		sw_log("Text file not found. Filename:".$filename.", Language:".$ln.", Subdir:".$ddir, PEAR_LOG_ERR);	
		return false;
	}
}

/**
 * 	Read txt file in specified language mutation, parse and saparate to headers and body and do replacements.
 *
 *	For more info about choice txt file read {@link multidomain_get_lang_file}
 *	Txt files in serweb (as emails, terms and conditions etc.) are stored in 
 *	special format. At the beginning (but only at beginning) of these files may 
 *	be comments. Lines with comments begins by "#". Rest of file is separated
 *	into two parts separated by empty line: headers and body. 
 *
 *	Each header contain header name and header value. Each header must be on
 *	own line. Header name and header value is separated by ":".
 *
 *	Body is the rest of txt file after first empty line.
 *
 *	When txt file is readed, function replace all strings in form #@#some_name#@#
 *	by replacement. The parametr $replacements is array of pairs. First element
 *	of each pair is name of replacement and second element from pair is value
 *	by which is replaced.
 *
 *	Function's finding replacements in body and in header values.
 *
 *	Function return array with two keys: "headers" and "body". Body is only 
 *	string. Headers contain associative array with header names as kayes.
 *
 *	@param string $filename		name of file is searching for
 *	@param string $ddir			subdirectory in of domain dir
 *	@param string $lang			language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@param array $replacements	see above
 *	@retrun array				parsed file or false on error
 */
function read_lang_txt_file($filename, $ddir, $lang, $replacements){
	$f = multidomain_get_lang_file($filename, $ddir, $lang);
	if (!$f) {
		sw_log("Can't find txt file ".$filename.", subdir:".$ddir.", lang:".$lang, PEAR_LOG_ERR);
		return false;
	}
	
	$fp = fopen($f, "r");
	if (!$fp){
		sw_log("Can't open txt file ".$filename.", subdir:".$ddir.", lang:".$lang, PEAR_LOG_ERR);
		return false;
	}

	$fcontent = "";
	$accept_comments = true;
	$reading_headers = true;
	$headers = array();
	$body = "";

	while (!feof($fp)){
		$line = fgets($fp);
		if ((substr($line, 0, 1) == "#") and $accept_comments) continue;
		
		/* accept comments only on begin of file */
		$accept_comments = false;
		
		/* after empty line begins body */
		if (trim($line) == "") $reading_headers = false;
		
		if ($reading_headers){
			$h = split(':', $line, 2);
			$headers[strtolower(trim($h[0]))] = trim($h[1]);  
		}
		else{
			/* trim ends of lines of non empty lines */
			if (trim($line) != "") $line = rtrim($line)." ";
			$body .= $line;
		}
	}
	fclose($fp);

	foreach($replacements as $row){
		//do replace in body
		$body=str_replace("#@#".$row[0]."#@#", $row[1], $body);

		//do replace in headers
		foreach($headers as $k => $v){
			$headers[$k] = str_replace("#@#".$row[0]."#@#", $row[1], $headers[$k]);
		}
	}
	
	return array('headers' => $headers,
				 'body' => $body);
}

/**
 *	Write to serweb log if logging is enabled
 *
 *	@param mixed $message  		String or object containing the message to log.
 *	@param mixed $priority  	The priority of the message. Valid values are: PEAR_LOG_EMERG, PEAR_LOG_ALERT, PEAR_LOG_CRIT, PEAR_LOG_ERR, PEAR_LOG_WARNING, PEAR_LOG_NOTICE, PEAR_LOG_INFO, and PEAR_LOG_DEBUG.
 *	@return boolean 			True on success or false on failure
 */
 
function sw_log($message, $priority = null){
	global $serwebLog;
	if ($serwebLog){ 
		return $serwebLog->log($message, $priority);
	}
	else {
		return true;
	}
}

/**
 *	get error message from PEAR_Error object and write it to $errors array and to error log
 *
 *	@param object $err_object PEAR_Error object
 *	@param array $errors array of error messages
 */

function log_errors($err_object, &$errors){
	global $serwebLog, $config;

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
	
	$err_message = $err_object->getMessage();
	if ($config->log_error_return_location_of_error_to_html){
		$err_message .= ", file: ".$last_frame['file'].":".$last_frame['line'];
	}
	$errors[] = $err_message;

	//if logging is enabled
	if ($serwebLog){ 
	
		$log_message= "file: ".$last_frame['file'].":".$last_frame['line'].": ".$err_object->getMessage()." - ".$err_object->getUserInfo();
		//remove endlines from the log message
		$log_message=str_replace(array("\n", "\r"), "", $log_message);
		$log_message=ereg_replace("[[:space:]]{2,}", " ", $log_message);
		$serwebLog->log($log_message, PEAR_LOG_ERR);
		
	}

}

/**
 *	sets varibale getted by $_POST or by $_GET to global
 */
 
function set_global($var){
	global $_POST, $_GET, $GLOBALS;
	
	if (isset($_POST[$var])) $GLOBALS[$var]=$_POST[$var];
	elseif (isset($_GET[$var])) $GLOBALS[$var]=$_GET[$var];
	else $GLOBALS[$var]=null;
}

/**
 *	convert Cserweb_auth to string which may be used in get params
 * 
 *	@param object $user Cserweb_auth object
 *	@param string $prefix name of get params
 *	@return string
 */
 
function userauth_to_get_param($user, $prefix){
	return $prefix."_id=".RawURLencode($user->uuid)."&".
	       $prefix."_n=".RawURLencode($user->uname)."&".
		   $prefix."_d=".RawURLencode($user->domain);
}

/**
 *	convert Cserweb_auth to form hidden fields
 * 
 *	@param object $user Cserweb_auth object
 *	@param string $prefix name of form fields
 *	@param object $form phplib form object
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

/**
 *	convert user info to string which may be used in get params
 *
 *	function is similiar to function {@link userauth_to_get_param}
 * 
 *	@param string $uuid uuid of user
 *	@param string $uname username of user
 *	@param string $domain domain fo user
 *	@param string $prefix name of get params
 *	@return string
 */

function user_to_get_param($uuid, $uname, $domain, $prefix){
	return $prefix."_id=".RawURLencode($uuid)."&".
	       $prefix."_n=".RawURLencode($uname)."&".
		   $prefix."_d=".RawURLencode($domain);
}


/**
 *	Obtain user identification from GET or POST params
 *
 *	@param string $prefix name of GET or POST params
 *	@return object Cserweb_auth object on success FALSE on error
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

/**
 *	Convert string to CSV format
 *
 *	@param string $str string to convert
 *	@param string $delim delimiter
 *	@return string 
 */
 
function toCSV($str, $delim=','){
	$str = str_replace('"', '""', $str);	//double alll quotes
	$pos1 = strpos($str, '"');				// if $str contains quote or delim, quote it
	$pos2 = strpos($str, $delim);
	if (!($pos1===false and $pos2===false)) $str = '"'.$str.'"';
	return $str;
}

?>
