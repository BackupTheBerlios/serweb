<?
/*
 * $Id: list_of_admins.php,v 1.1 2004/03/04 22:47:37 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin,change_priv");

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	// get admins
	$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address from ".$config->table_subscriber." s ".
		"where s.perms='admin' ".
		"order by s.domain, s.username";
	$admin_res=MySQL_Query($q);
	if (!$admin_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

}while (false);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
</head>
<?
	print_admin_html_body_begin();
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
	
	ptitle("List of admins");
?>

<?if ($admin_res and MySQL_num_rows($admin_res)){?>
<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="85">username</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="85">domain</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="150">name</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">email</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT">&nbsp;</td>
	</tr>
	<tr><td colspan="9" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($admin_res)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12" width="85">&nbsp;<?echo $row->username;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="85">&nbsp;<?echo $row->domain;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="150">&nbsp;<?echo $name;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="125">&nbsp;<a href="mailto:<?echo $row->email_address;?>"><?echo $row->email_address;?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12"><a href="<?$sess->purl("admin_privileges.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username)."&user_domain=".rawURLEncode($row->domain));?>">change privileges</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
