<?
/*
 * $Id: confirmation.php,v 1.21 2004/04/04 19:42:14 kozlik Exp $
 */

include "reg_jab.php";

require "prepend.php";

put_headers();

if (isset($_GET['ok'])) $ok=$_GET['ok']; else $ok=null;
if (isset($_GET['nr'])) $nr=$_GET['nr']; else $nr=null;

do{
	if (isset($nr)){  // Is there data to process?

		if (!$db = connect_to_db($errors)) break;

		$q="select username from ".$config->table_pending." where confirmation='$nr'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

		if (!$res->numRows()){
			$q="select username from ".$config->table_subscriber." where confirmation='$nr'";
			$res1=$db->query($q);
			if (DB::isError($res1)) {log_errors($res1, $errors); break;}
			if (!$res1->numRows()){ $errors[]="Sorry. No such a confirmation number exists."; break;}
			else { $ok=1; $errors[]="Your account has already been created."; break; }
		}

		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
		if ($config->setup_jabber_account) {
			$user_id=$row->username; // needed for Jabber gw reg.
		}
		$sip_address="sip:".$row->username."@".$config->default_domain;

		
		// get the max alias number 
		if (!$alias=get_alias_number($db, $errors)) break;

		$q="insert into ".$config->table_subscriber." select * from ".$config->table_pending." where confirmation='$nr'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

		// add alias to fifo
		$message=add_new_alias($sip_address, $alias, $errors);
		if ($errors) break;
		
		$q="delete from ".$config->table_pending." where confirmation='$nr'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

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


/* ----------------------- HTML begin ---------------------- */ 
print_html_head();
print_html_body_begin($page_attributes);

	if ($ok==1){
?>
<p>Congratulations! Your <?echo $config->realm;?> account was set up!</p>
<? }elseif ($ok>=2 && $ok<10) {
error_log("SERWEB:jabber registration failed: <$user_id> [$ok]\n");?>
<p>Your <?echo $config->realm;?> account was set up!<br><b>But your <?echo $config->realm;?> Jabber Gateway registration failed-
<? echo "$ok"; ?>
</b><br>Please contact <a href="mailto:<?echo $config->infomail;?>"><?echo $config->infomail;?></a> for further assistance.</p>
<? }elseif ($errors) {?>
<p>We regret but your <?echo $config->realm;?> confirmation attempt failed.<br>
Please contact <a href="mailto:<?echo $config->infomail;?>"><?echo $config->infomail;?></a> for further assistance.</p>
<?}?>

<br>
<hr>
<div align="center">Back to <a href="../index.php">login form</a>.</div>
<hr>

<?print_html_body_end();?>
</html>
