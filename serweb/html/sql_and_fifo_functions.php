<?
/*
 * $Id: sql_and_fifo_functions.php,v 1.1 2003/10/15 09:42:48 kozlik Exp $
 */

 /*
  *	get the max alias number 
  */
  
 function get_alias_number(&$errors){
 	global $config;
 
	// abs() converts string to number
	$q="select max(abs(username)) from ".$config->table_aliases." where username REGEXP \"^[0-9]+$\"";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return false;}
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



?>