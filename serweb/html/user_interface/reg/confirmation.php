<?
/*
 * $Id: confirmation.php,v 1.9 2003/03/17 20:01:25 kozlik Exp $
 */

include "reg_jab.php";

require "prepend.php";

put_headers();

do{
	if (isset($nr)){  // Is there data to process?

		$db = connect_to_db();
		if (!$db){ $errors[]="can´t connect to sql server"; break;}
	

		$q="select username from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
		if (!MySQL_Num_Rows($res)){ 
			$q="select username from ".$config->table_subscriber." where confirmation='$nr'";
			$res=mySQL_query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			if (!MySQL_Num_Rows($res)){ $errors[]="Sorry. No such a confirmation number exists."; break;}
			else { $ok=1; $errors[]="Your account has already been created."; break; }
		}
		
		$row=MySQL_Fetch_Object($res);
		if ($config->setup_jabber_account) {
			$user_id=$row->username; // needed for Jabber gw reg.
		}
		$sip_address="sip:".$row->username."@".$config->default_domain;
			
		// get the max number alias - abs() converts string to number
		$q="select max(abs(username)) from ".$config->table_aliases." where username REGEXP \"^[0-9]+$\"";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=MySQL_Fetch_Row($res);
		$alias=is_null($row[0])?$config->first_alias_number:($row[0]+1);
		$alias=($alias<$config->first_alias_number)?$config->first_alias_number:$alias;

		$q="insert into ".$config->table_subscriber." select * from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		/* add new alias */
		/* construct FIFO command */
		$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
			$config->fifo_aliases_table."\n".	//table
			$alias."\n".						//user
			$sip_address."\n".					//contact
			$config->new_alias_expires."\n".	//expires
			$config->new_alias_q."\n\n";		//priority

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		if (substr($status,0,1)!="2") {$errors[]=$status; break; }

		$q="delete from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		if ($config->setup_jabber_account) {
			# Jabber Gateway registration
			$res = reg_jab($user_id);
			if($res!=0) { 
				$res=$res+1; Header("Location: confirmation.php?ok=$res");
			} else { 
				Header("Location: confirmation.php?ok=1"); 
			}
		} else {
				Header("Location: confirmation.php?ok=1"); 
		}
		
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

	if ($ok==1){
?>
<span class="txt_norm">Congratulations! Your iptel.org account was set up!</span>
<? }elseif ($ok>=2 && $ok<10) {?>
<span class="txt_norm">Your iptel.org account was set up!<br><b>But your iptel.org Jabber Gateway registration failed-
<? echo "$ok"; ?>
</b><br>Please contact <a href="mailto:info@iptel.org">info@iptel.org</a> for further assistance.</span>
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
