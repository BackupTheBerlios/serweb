<?
/*
 * $Id: sql_and_fifo_functions.php,v 1.9 2004/04/04 19:42:14 kozlik Exp $
 */

/***************************************************************************
 *
 *					Function for work with aliases
 *
 ***************************************************************************/
 
 /*
  *	get the max alias number 
  */
  
 function get_alias_number($db, &$errors){
 	global $config;
 
	// abs() converts string to number
	$q="select max(abs(username)) from ".$config->table_aliases." where domain='".$config->realm."' and username REGEXP \"^[0-9]+$\"";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return false;}
	$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
	$res->free();
	$alias=is_null($row[0])?$config->first_alias_number:($row[0]+1);
	$alias=($alias<$config->first_alias_number)?$config->first_alias_number:$alias;
	
	return $alias;
}

 /*
  *	return array of aliases of user with $sip_uri
  */

function get_aliases($sip_uri, $db, &$errors){
	global $config;
	$q=	"select username ".
		"from ".$config->table_aliases." ".
		"where contact='".$sip_uri."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return array();}

	$out=array();
	
	while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)){
		$out[]=$row->username;
	}
	$res->free();
	return $out;
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

/***************************************************************************
 *
 *					Function for work with sip users
 *
 ***************************************************************************/

/*
 *	check if user exists
 */

function is_user_exists($uname, $udomain, $db, &$errors){
 	global $config;

	$q="select count(*) from ".$config->table_subscriber.
		" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return -1;}

	$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
	$res->free();
	if ($row[0]) return true;
	
	$q="select count(*) from ".$config->table_pending.
		" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return -1;}

	$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
	$res->free();
	if ($row[0]) return true;
	
	return false;
}

 /*
  *	add new user to table subscriber (or pending)
  */

function add_user_to_subscriber($uname, $domain, $passwd, $fname, $lname, $phone, $email, $timezone, $confirm, $table, $db, &$errors){
 	global $config;
	
	$ha1=md5($uname.":".$domain.":".$passwd);
	$ha1b=md5($uname."@".$config->domainname.":".$domain.":".$passwd);

	$q="insert into ".$table." (username, password, first_name, last_name, phone, email_address, ".
			"datetime_created, datetime_modified, confirmation, ha1, ha1b, domain, phplib_id, timezone) ".
		"values ('$uname', '$passwd', '$fname', '$lname', '$phone', '$email', now(), now(), '$confirm', ".
			"'$ha1', '$ha1b','$domain', '".md5(uniqid('fvkiore'))."', '$timezone')";

	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return false;}

	return true;	
}

 /*
  *	set password for user
  */

function set_password_to_user($user_id, $passwd, $db, &$errors){
	global $config;
	
	$inp=$user_id.":".$config->realm.":".$passwd;
	$ha1=md5($inp);
	
	$inpb=$user_id."@".$config->domainname.":".$config->realm.":".$passwd;
	$ha1b=md5($inpb);

	$q="update ".$config->table_subscriber." set password='$passwd', ha1='$ha1', ha1b='$ha1b' ".
		" where username='".$user_id."' and domain='".$config->realm."'";

	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return false;}

	return true;
}

 /*
  *	delete sip user
  */

function dele_sip_user ($uname, $domain, $db, &$errors){
 	global $config;

	$q="delete from ".$config->table_aliases." where contact='sip:".$uname."@".$domain."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return false;}

	$q="delete from ".$config->table_subscriber." where username='".$uname."' and domain='".$domain."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return false;}

	return true;
}

 /*
  * get name of currently logged user
  */

function get_user_name($db, &$errors){
	global $auth, $config;

	$q="select first_name, last_name from ".$config->table_subscriber.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return false;}
	if (!$res->numRows()) return false;
	
	$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
	$res->free();

	return $row->first_name." ".$row->last_name." &lt;".$auth->auth["uname"]."@".$config->realm."&gt;";
}


 /*
  * get starus of sip user
  * return: "non-local", "unknown", "non-existent", "on line", "off line"
  */

function get_status($sip_uri, $db, &$errors){
	global $config;

	$reg=new Creg;
	if (!eregi("^sip:([^@]+@)?".$reg->host, $sip_uri, $regs)) return "<div class=\"statusunknown\">non-local</div>";

	if (strtolower($regs[2])!=strtolower($config->default_domain)) return "<div class=\"statusunknown\">non-local</div>";

	$user=substr($regs[1],0,-1);

	$q="select count(*) from ".$config->table_subscriber.
		" where username='$user' and domain='$config->realm'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return "<div class=\"statusunknown\">unknown</div>";}
	$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
	$res->free();
	if (!$row[0]) return "<div class=\"statusunknown\">non-existent</div>";


	$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
	$config->ul_table."\n".		//table
	$user."@".$config->default_domain."\n\n";	//username

	$out=write2fifo($fifo_cmd, $errors, $status);
	if ($errors) return;

	if (substr($status,0,3)=="200") return "<div class=\"statusonline\">on line</div>";
	else return "<div class=\"statusoffline\">off line</div>";
}

/***************************************************************************
 *
 *					Function for work with timezones
 *
 ***************************************************************************/

 /*
  * get list of timezones from zone.tab
  */

function get_time_zones(&$errors){
	global $config;

	@$fp=fopen($config->zonetab_file, "r");
	if (!$fp) {$errors[]="Cannot open zone.tab file"; return array();}
	
	while (!feof($fp)){
		$line=FgetS($fp, 512);
		if (substr($line,0,1)=="#") continue; //skip comments
		if (!$line) continue; //skip blank lines
		
		$line_a=explode("\t", $line);
		
		$line_a[2]=trim($line_a[2]);
		if ($line_a[2]) $out[]=$line_a[2];
	}

	fclose($fp);
	sort($out);
	return $out;
}


 /*
  * set timezone to timezone of currently logged user
  */

function set_timezone($db, &$errors){
	global $config, $auth;

	$q="select timezone from ".$config->table_subscriber.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); return;}
	$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
	$res->free();

	putenv("TZ=".$row->timezone); //set timezone	
}

/***************************************************************************
 *
 *					Function for work with net geo
 *
 ***************************************************************************/

 /*
  * get location of domainname in sip_adr
  */

function get_location($sip_adr, $db, &$errors){
	global $config;
	static $reg;
	
	$reg = new Creg();
	
	$domainname=$reg->get_domainname($sip_adr);
	
	$q="select location from ".$config->table_netgeo_cache.
		" where domainname='".$domainname."'";
	$res=$db->query($q);
	/* if this query failed netgeo is probably not installed -- ignore */
	if (DB::isError($res)) {return "n/a";}
	$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
	$res->free();

	if (!$row) return "n/a";
	return $row->location;
}

?>
