<?
/*
 * this script should be run after midnight - sends missed calls of previous day
 *
 * $Id: send_daily_missed_calls.php,v 1.1 2004/03/04 14:04:44 kozlik Exp $
 */

require "prepend.php";

/*
	function sends missed calls of user with $username and $domain to $email_address
*/
function send_missed_calls($email_address, $username, $domain, &$errors){
	global $config;
	/* get missed calls */

	$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
			"FROM ".$config->table_missed_calls." t1 ".
			"WHERE t1.username='".$username."' and t1.domain='".$domain."' and ".
				"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ) ".
		"UNION ".
		"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
			"FROM ".$config->table_missed_calls." t1, ".$config->table_aliases." t2 ".
			"WHERE 'sip:".$username."@".$domain."'".
				"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain and ".
				"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ) ".
		"ORDER BY time DESC ";

	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query - ".__FILE__.":".__LINE__; return;}
	if (!MySQL_num_rows($res)) return; //there are no missed calls

	$table='<html><body><table border="1" cellspacing="0" cellpadding="1">'."\n";
	$table.='<tr>';
	$table.='<th>calling subscriber</th>';
	$table.='<th>time</th>';
	$table.='<th>reply status</th>';
	$table.='</tr>'."\n";

	while($row=MySQL_Fetch_Object($res)){
		$table.='<tr>';
		$table.='<td>'.$row->from_uri.'&nbsp;</td>';
		$table.='<td>'.$row->time.'&nbsp;</td>';
		$table.='<td>'.$row->sip_status.'&nbsp;</td>';
		$table.='</tr>'."\n";
	}
	$table.='</table></body></html>'."\n";
	
	$envelope["From"]=$config->infomail;

	$part1["type"]=TYPEMULTIPART;
	$part1["subtype"]="mixed";

	$part2["type"]=TYPETEXT;
	$part2["subtype"]="plain";
	$part2["contents.data"]=$config->send_daily_missed_calls_mail_body;

	$part3["type"]=TYPETEXT;
	$part3["subtype"]="html";
	$part3["contents.data"]=$table;

	$body[1]=$part1;
	$body[2]=$part2;
	$body[3]=$part3;

	$mail=imap_mail_compose($envelope, $body);
	list($m_header, $m_body)=split("\r\n\r\n",$mail,2);
	
	if (!mail($email_address, $config->send_daily_missed_calls_mail_subj, $m_body, $m_header))
		$errors[]="can't send missed calls to ".$email_address; 
}

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	/*get default value*/
	$q="select default_value from ".$config->table_user_preferences_types.
		" where att_name='".$config->up_send_daily_missed_calls."'";
	$res=mySQL_query($q);

	if (!$res) {$errors[]="error in SQL query - ".__FILE__.":".__LINE__; break;}
	if (!$row=MySQL_Fetch_Object($res)) {$errors[]="not found attribute '".$config->up_send_daily_missed_calls."' in user preferences"; break;}
	$default_value=$row->default_value;

	/* get list of users and values of theirs attributes up_send_daily_missed_calls */		
	$q="select s.username, s.domain, s.email_address, p.value ".
		"from ".$config->table_subscriber." s left outer join ".$config->table_user_preferences." p ".
				" on s.username=p.username and s.domain=p.domain and p.attribute='".$config->up_send_daily_missed_calls."'";

	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query - ".__FILE__.":".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($res)){
		if (is_null($row->value)) $row->value=$default_value;
		
		if ($row->value) send_missed_calls($row->email_address, $row->username, $row->domain, $errors);
	}

} while (false);

if (is_array($errors)) foreach($errors as $val) echo "error: ".$val."\n";


?>