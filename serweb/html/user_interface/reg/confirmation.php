<?
/*
 * $Id: confirmation.php,v 1.18 2003/11/03 01:54:27 jiri Exp $
 */

include "reg_jab.php";

require "prepend.php";

put_headers();

do{
	if (isset($nr)){  // Is there data to process?

		$db = connect_to_db();
		if (!$db){ $errors[]="cannot connect to sql server"; break;}


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

		
		// get the max alias number 
		if (!$alias=get_alias_number($errors)) break;

		$q="insert into ".$config->table_subscriber." select * from ".$config->table_pending." where confirmation='$nr'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		// add alias to fifo
		$message=add_new_alias($sip_address, $alias, $errors);
		if ($errors) break;
		
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
<title><?echo $config->title;?></title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin();
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);

	if ($ok==1){
?>
<span class="txt_norm">Congratulations! Your <?echo $config->realm;?> account was set up!</span>
<? }elseif ($ok>=2 && $ok<10) {
error_log("SERWEB:jabber registration failed: <$user_id> [$ok]\n");?>
<span class="txt_norm">Your <?echo $config->realm;?> account was set up!<br><b>But your <?echo $config->realm;?> Jabber Gateway registration failed-
<? echo "$ok"; ?>
</b><br>Please contact <a href="mailto:<?echo $config->infomail;?>"><?echo $config->infomail;?></a> for further assistance.</span>
<? }elseif ($errors) {?>
<span class="txt_norm">We regret but your <?echo $config->realm;?> confirmation attempt failed.<br>
Please contact <a href="mailto:<?echo $config->infomail;?>"><?echo $config->infomail;?></a> for further assistance.</span>
<?}?>

<br>
<hr>
<div align="center" class="txt_norm">Back to <a href="../index.php">login form</a>. </div>
<hr>

<?print_html_body_end();?>
</html>
