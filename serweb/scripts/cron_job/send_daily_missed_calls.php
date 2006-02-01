<?
/*
 * this script should be run after midnight - sends missed calls of previous day
 *
 * $Id: send_daily_missed_calls.php,v 1.3 2006/02/01 11:13:46 kozlik Exp $
 */

$_data_layer_required_methods=array('get_missed_calls_of_yesterday', 'get_send_mc_default_value', 'get_send_mc_list_of_users');

require "prepend.php";

/*
	function sends missed calls of $user  to $email_address
*/
function send_missed_calls($email_address, $user, $data, &$errors){
	global $config;
	/* get missed calls */
	if (false === ($missed_calls = $data->get_missed_calls_of_yesterday($user, $errors))) return;
	if (!count($missed_calls)) return; //there are no missed calls
	
	$table='<html><body><table border="1" cellspacing="0" cellpadding="1">'."\n";
	$table.='<tr>';
	$table.='<th>calling subscriber</th>';
	$table.='<th>time</th>';
	$table.='<th>reply status</th>';
	$table.='</tr>'."\n";

	foreach($missed_calls as $row){
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

	if (!function_exists('imap_mail_compose')){
		$errors[]="Can not send mail. IMAP extension for PHP is not installed.";
		return false;
	}

	$mail=imap_mail_compose($envelope, $body);
	list($m_header, $m_body)=split("\r\n\r\n",$mail,2);
	
	if (!mail($email_address, $config->send_daily_missed_calls_mail_subj, $m_body, $m_header))
		$errors[]="can't send missed calls to ".$email_address; 
}

do{
	/*get default value*/
	if (0 > ($default_value = $data->get_send_MC_default_value($errors))) break;

	/* get list of users and values of theirs attributes up_send_daily_missed_calls */		
	if (false === ($users = $data->get_send_MC_list_of_users($errors))) break;

	foreach($users as $row){
		if (is_null($row->value)) $row->value=$default_value;
		
		if ($row->value) 
			send_missed_calls($row->email_address, new Cserweb_auth($row->uuid, $row->username, $row->domain), $data, $errors);

	}

} while (false);

if (is_array($errors)) foreach($errors as $val) sw_log("send_daily_missed_call - ".$val, PEAR_LOG_ERR);

?>
