<?
/*
 * $Id: sql_and_fifo_functions.php,v 1.8 2003/12/01 21:44:46 kozlik Exp $
 */

 /*
  *	get the max alias number 
  */
  
 function get_alias_number(&$errors){
 	global $config;
 
	// abs() converts string to number
	$q="select max(abs(username)) from ".$config->table_aliases." where domain='".$config->realm."' and username REGEXP \"^[0-9]+$\"";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, file: ".__FILE__.":".__LINE__; return false;}
	$row=MySQL_Fetch_Row($res);
	$alias=is_null($row[0])?$config->first_alias_number:($row[0]+1);
	$alias=($alias<$config->first_alias_number)?$config->first_alias_number:$alias;
	
	return $alias;
}

 /*
  *	add new alias
  */

function add_new_alias($sip_address, $alias, &$errors){
 	global $config;

    if ($config->ul_replication) $replication="0\n";
    else $replication="";

	$ul_name=$alias."@".$config->default_domain."\n";

	/* construct FIFO command */
	$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
		$config->fifo_aliases_table."\n".	//table
		$ul_name.	//user
		$sip_address."\n".					//contact
		$config->new_alias_expires."\n".	//expires
		$config->new_alias_q."\n". 		//priority
		$replication."\n";

	$message=write2fifo($fifo_cmd, $errors, $status);
	if ($errors) return false;
	if (substr($status,0,1)!="2") {$errors[]=$status; return false; }

	return $message;
}

/*
 *	check if user exists
 */

function is_user_exists($uname, $udomain, &$errors){
 	global $config;

	$q="select count(*) from ".$config->table_subscriber.
		" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return -1;}

	$row=MySQL_Fetch_Row($res);
	if ($row[0]) return true;
	
	$q="select count(*) from ".$config->table_pending.
		" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return -1;}

	$row=MySQL_Fetch_Row($res);
	if ($row[0]) return true;
	
	return false;
}

 /*
  *	add new user to table subscriber (or pending)
  */

function add_user_to_subscriber($uname, $domain, $passwd, $fname, $lname, $phone, $email, $timezone, $confirm, $table, &$errors){
 	global $config;
	
	$ha1=md5($uname.":".$domain.":".$passwd);
	$ha1b=md5($uname."@".$config->domainname.":".$domain.":".$passwd);

	$q="insert into ".$table." (username, password, first_name, last_name, phone, email_address, ".
			"datetime_created, datetime_modified, confirmation, ha1, ha1b, domain, phplib_id, timezone) ".
		"values ('$uname', '$passwd', '$fname', '$lname', '$phone', '$email', now(), now(), '$confirm', ".
			"'$ha1', '$ha1b','$domain', '".md5(uniqid('fvkiore'))."', '$timezone')";

	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, file: ".__FILE__.":".__LINE__; return false;}

	return true;	
}

function dele_sip_user ($uname, $domain, &$errors){
 	global $config;

	$q="delete from subscriber where username='$uname' and domain='$domain'"; 
	$res=MySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, file: ".__FILE__.":".__LINE__; return false;}

	return true;
}

?>
