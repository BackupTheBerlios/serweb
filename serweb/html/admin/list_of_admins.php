<?
/*
 * $Id: list_of_admins.php,v 1.3 2004/03/24 21:39:46 kozlik Exp $
 */

require "prepend.php";

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

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">List of admins</h2>

<?if ($admin_res and MySQL_num_rows($admin_res)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>domain</th>
	<th>name</th>
	<th>email</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($admin_res)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username);?></td>
	<td align="left"><?echo nbsp_if_empty($row->domain);?></td>
	<td align="left"><?echo nbsp_if_empty($name);?></td>
	<td align="left"><a href="mailto:<?echo $row->email_address;?>"><?echo $row->email_address;?></a></td>
	<td align="center"><a href="<?$sess->purl("admin_privileges.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username)."&user_domain=".rawURLEncode($row->domain));?>">change privileges</a></td>
	</tr>
	<?}?>
	</table>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
