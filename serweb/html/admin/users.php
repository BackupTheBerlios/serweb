<?
/*
 * $Id: users.php,v 1.11 2003/11/03 01:54:27 jiri Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

if (!$sess->is_registered('sess_fusers')) $sess->register('sess_fusers');
if (!isset($sess_fusers)) $sess_fusers=new Cfusers();

if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$sess_fusers->init();

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	if ($dele_id){ //delete user
		$q="delete from ".$config->table_aliases." where contact='sip:".$dele_id."@".$config->default_domain."'";
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$q="delete from ".$config->table_subscriber." where username='$dele_id' and domain='$config->default_domain'";
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLEncode("user seleted succesfully")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){

		$query_c=$sess_fusers->get_query_where_phrase('s');

		// get num of users
		if ($sess_fusers->onlineonly)
			$q1="select distinct s.username from ".$config->table_subscriber." s, ".$config->table_location." l ".
				" where s.username=l.username and s.domain=l.domain and s.domain='$config->realm' and ".$query_c;
		else
			$q1="select s.username from ".$config->table_subscriber." s ".
				" where s.domain='$config->realm' and ".$query_c;

		$res=MySQL_Query($q1);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$num_rows=MySQL_Num_Rows($res);

		// get users
		if ($sess_fusers->onlineonly)
			$q="select distinct s.username, s.first_name, s.last_name, s.phone, s.email_address from ".
				$config->table_subscriber." s, ".$config->table_location." l ".
				" where s.username=l.username and s.domain=l.domain and ".$query_c.
				" order by s.username limit ".$sess_fusers->act_row.", ".$config->num_of_showed_items;
		else
			$q="select s.username, s.first_name, s.last_name, s.phone, s.email_address from ".$config->table_subscriber." s ".
				" where s.domain='$config->realm' and ".$query_c.
				" order by s.username limit ".$sess_fusers->act_row.", ".$config->num_of_showed_items;
		$users_res=MySQL_Query($q);
		if (!$users_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	}

}while (false);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
<script language="JavaScript">
<!--
function confirmDelete(theLink){
    var is_confirmed = confirm("Realy you want delete user?");
    if (is_confirmed) {
        theLink.href;
    }
    return is_confirmed;
}
//-->
</script>
</head>
<?
	print_admin_html_body_begin();
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>


	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td class="title" width="480">filter:</td></tr>
	</table>

<?$sess_fusers->print_form();?>


<?if ($users_res and MySQL_num_rows($users_res)){?>
<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="85">username</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="100">name</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="70">phone</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">email</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="40">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="63">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="55">&nbsp;</td>
	</tr>
	<tr><td colspan="13" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($users_res)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12" width="85">&nbsp;<?echo $row->username;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="100">&nbsp;<?echo $name;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="right" class="f12" width="70"><?echo $row->phone;?>&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="125">&nbsp;<a href="mailto:<?echo $row->email_address;?>"><?echo $row->email_address;?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="40"><a href="<?$sess->purl("acl.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username));?>">ACL</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="63"><a href="<?$sess->purl($config->user_pages_path."my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($row->username));?>">account</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="55"><a href="<?$sess->purl("users.php?kvrk=".uniqid('')."&dele_id=".rawURLEncode($row->username));?>" onclick="return confirmDelete(this)">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>
<?}?>

<? if ($num_rows){?>
<p align="center" class="f12">Showed users <?echo ($sess_fusers->act_row+1)." - ".((($sess_fusers->act_row+$config->num_of_showed_items)<$num_rows)?($sess_fusers->act_row+$config->num_of_showed_items):$num_rows);?> from <?echo $num_rows;?>
<?}else{?>
<p align="center">No users found.
<?}?><br>


<div align="left">&nbsp;
<?
	$url="users.php?kvrk=".uniqid("")."&act_row=";
	print_search_links($sess_fusers->act_row, $num_rows, $config->num_of_showed_items, $url);
?>
</div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
