<?
require "prepend.php";

put_headers();

do{
	if (isset($nr)){								// Is there data to process?
		$db = connect_to_db();
		if (!$db){ $errors[]="can´t connect to sql server"; break;}
	

		$q="select user_id from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
		if (!MySQL_Num_Rows($res)){ 
			$q="select user_id from ".$config->table_subscriber." where confirmation='$nr'";
			$res=mySQL_query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			if (!MySQL_Num_Rows($res)){ $errors[]="Sorry. No such a confirmation number exists."; break;}
			else { $errors[]="Your account has already been created."; break; }
		}
		
		$row=MySQL_Fetch_Object($res);
//		$user_id=$row->user_id;
		$sip_address="sip:".$row->user_id."@".$config->default_domain;
			
		$q="select max(user) from ".$config->table_aliases." where user REGEXP \"^[0-9]+$\"";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=MySQL_Fetch_Row($res);
		$alias=is_null($row[0])?$config->first_alias_number:($row[0]+1);
		$alias=($alias<$config->first_alias_number)?$config->first_alias_number:$alias;

		$q="insert into ".$config->table_subscriber." select * from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}


		$q="insert into ".$config->table_aliases." (user, contact, expires, q, callid, cseq) ".
			"values ('$alias', '$sip_address', '".$config->new_alias_expires."', ".$config->new_alias_q.", '".$config->new_alias_callid."', ".$config->new_alias_cseq.")";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}


		$q="delete from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			

        Header("Location: confirmation.php?ok=1");
		page_close();
		exit;
	}
}while (false);

	


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin();
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);

	if ($ok){
?>
<span class="txt_norm">Congratulations! Your iptel.org account was set up!</span>
<? }elseif ($errors) {?>
<span class="txt_norm">We regret but your iptel.org confirmation attempt failed.<br>
Please contact <a href="mailto:info@iptel.org">info@iptel.org</a> for further assistance.</span>
<?}?>
<br>
<hr>
<div align="center" class="txt_norm">Back to <a href="../index.php">login form</a>. </div>
<hr>

<?print_html_body_end();?>
</html>
