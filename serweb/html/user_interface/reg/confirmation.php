<?
/*
 * $Id: confirmation.php,v 1.22 2004/04/14 20:51:31 kozlik Exp $
 */

include "reg_jab.php";

require "prepend.php";

put_headers();

if (isset($_GET['ok'])) $ok=$_GET['ok']; else $ok=null;
if (isset($_GET['nr'])) $nr=$_GET['nr']; else $nr=null;

$remove_from_subscriber=false;
do{
	if (isset($nr)){  // Is there data to process?

		if (!$data = CData_Layer::create($errors)) break;

		if (false === $user_id=$data->move_user_from_pending_to_subscriber($nr, $errors)) break;
		$remove_from_subscriber=true;
		
		$sip_address="sip:".$user_id."@".$config->domain;
		
		// get the max alias number 
		if (false === $alias=$data->get_alias_number($errors)) break;

		// add alias to fifo
		$message=$data->add_new_alias($sip_address, $alias, $errors);
		if ($errors) break;

		$remove_from_subscriber=false;
		if (!$data->del_user_from_pending($nr, $errors)) break;

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

if ($remove_from_subscriber) $data->del_user_from_subscriber($nr, $errors);


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
