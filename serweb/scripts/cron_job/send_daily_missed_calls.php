<?
/*
 * this script should be run after midnight - sends missed calls of previous day
 *
 * $Id: send_daily_missed_calls.php,v 1.4 2006/03/16 12:06:21 kozlik Exp $
 */

$_data_layer_required_methods=array('get_missed_calls_of_yesterday', 'get_users');

require "prepend.php";

/**
 *	function sends missed calls of user to given email address
 *	
 *	@param	string	$uid			
 *	@param	string	$email_address	
 *	@return	bool					TRUE on success, FALSE on error
 */
function send_mail_with_missed_calls($uid, $email_address){
	global $config, $data, $lang_set;
	/* get missed calls */
	if (false === $missed_calls = $data->get_missed_calls_of_yesterday($uid, null)) return false;
	if (!count($missed_calls)) return true; //there are no missed calls - nothing to do

	/* check if imap extension is loaded */
	if (!function_exists('imap_mail_compose')){
		ErrorHandler::add_errors("Can not send mail. IMAP extension for PHP is not installed.");
		return false;
	}

	/*
	 *	Create table of missed calls
	 */	
	$table='<html><body><table border="1" cellspacing="0" cellpadding="1">'."\n";
	$table.='<tr>';
	$table.='<th>calling subscriber</th>';
	$table.='<th>time</th>';
	$table.='<th>reply status</th>';
	$table.='</tr>'."\n";

	foreach($missed_calls as $row){
		$table.='<tr>';
		$table.='<td>'.$row->from_uri.'&nbsp;</td>';
		$table.='<td>'.$row->request_timestamp.'&nbsp;</td>';
		$table.='<td>'.$row->sip_status.'&nbsp;</td>';
		$table.='</tr>'."\n";
	}

	$table.='</table></body></html>'."\n";

	/*
	 *	Get language of user
	 */
	
	if (false === $lang = Attributes::get_attribute($config->attr_names['lang'], 
	                                                array("uid"=>$uid))) {
		return false;
	}
	$lang = lang_detect($lang, 3);	//translate $lang to be a index into $available_languages array
	if (!$lang) $lang = $config->default_lang;

	/*
	 *	Read file containing the mail body
	 */
	$mail_file = multidomain_get_lang_file("mail_missed_calls.txt", "txt", $lang);
	$m = read_txt_file($mail_file, array());
	
	if ($m === false) {
		ErrorHandler::add_error("Can't read file with mail body.");
		return false;
	}

	/* get charset */
	$charset = null;
	if (isset($m['headers']['content-type']) and 
	    eregi("charset=([-a-z0-9]+)", $m['headers']['content-type'], $regs)){
		
		$charset = $regs[1];
	}

	/* add information about charset to the header */
	if ($charset)
		$m['headers']['subject'] = "=?".$charset."?Q?".imap_8bit($m['headers']['subject'])."?=";

	/*
	 *	Compose the mail message
	 */
	
	$envelope["from"]=$config->infomail;
	$envelope["to"]=$email_address;

	$part1["type"]=TYPEMULTIPART;
	$part1["subtype"]="mixed";

	$part2["type"]=TYPETEXT;
	$part2["subtype"]="plain";
	$part2["contents.data"]=$m['body'];
	if ($charset) $part2["charset"]=$charset;

	$part3["type"]=TYPETEXT;
	$part3["subtype"]="html";
	$part3["contents.data"]=$table;
	$part3["charset"]=$lang_set['charset'];

	$body[1]=$part1;
	$body[2]=$part2;
	$body[3]=$part3;

	$mail=imap_mail_compose($envelope, $body);
	list($m_header, $m_body)=split("\r\n\r\n",$mail,2);

	/*
	 *	Send mail
	 */
	
	if (!mail($email_address, $m['headers']['subject'], $m_body, $m_header))
		$errors[]="can't send missed calls to ".$email_address; 
		
	return true;
}


/**
 *	Function read array of URIs and for each URI get the domain id,
 *	obtain value of attribute 'send_missed_calls'. If at least one attribute
 *	is true, this function return 1. Otherwise return 0. 
 *
 *	If no domain has the attribute set, the output of function depends on global 
 *	attribute 'send_missed_calls'
 *	
 *	@param	array	$uris
 *	@return	int				or FALSE on error
 */
function get_send_mc_of_dom($uris){
	global $config;

	$an = $config->attr_names;
	$send = null;

	foreach ($uris as $uri){
		$da = &Domain_Attrs::singleton($uri->get_did());
		if (false === $s = $da->get_attribute($an['send_mc'])) return false;
		
		if (is_null($send)) $send = $s;
		else $send = ($send or $s);
	}
	
	if (is_null($send)) {
		$ga = &Global_Attrs::singleton();
		if (false === $send = $ga->get_attribute($an['send_mc'])) return false;
	}
	
	return $send ? 1 : 0;
}


/**
 *	send missed calls to all subescribers
 */
function send_missed_calls(){
	global $config, $data;

	$an = $config->attr_names;
	
	do{
		$opt = array('return_all' => true,
		             'get_aliases' => true);
		
		/* get list of users and values of theirs attributes up_send_daily_missed_calls */		
		if (false === ($users = $data->get_users(array(), $opt))) break;
	
		foreach($users as $row){
			$ua = &User_Attrs::singleton($row['uid']);
	
			if (false === $send  = $ua->get_attribute($an['send_mc'])) return false;
	
			/* if email address is not filled skip this user */
			if (!$row['email_address']) continue;

			if (is_null($send)) {
				if (false === $send = get_send_mc_of_dom($row['uris'])) return false;
			}
	
			if ($send) {
				if (false === send_mail_with_missed_calls($row['uid'], $row['email_address'])) return false;
			}
		}
	
	} while (false);
}

send_missed_calls();

$eh = &ErrorHandler::singleton();
$errors = &$eh->get_errors_array();
if (is_array($errors)) {
	foreach($errors as $val) {
		sw_log("send_daily_missed_call - ".$val, PEAR_LOG_ERR);
		fwrite(STDERR, $val."\n");
	}
	
	if (count($errors)){
		echo "\nThere were errors during script running. Check serweb log for more info.\n";
	}
}
?>
