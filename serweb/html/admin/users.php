<?
/*
 * $Id: users.php,v 1.15 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

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
	if (!$db = connect_to_db($errors)) break;

	if (isset($_GET['dele_id'])){ //delete user

		if (!dele_sip_user($_GET['dele_id'], $config->default_domain, $db, $errors)) break;

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLEncode("user deleted succesfully")));
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

		$res=$db->query($q1);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

		$num_rows=$res->numRows();
		$res->free();

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

		$user_res=$db->query($q);
		if (DB::isError($user_res)) {log_errors($user_res, $errors); break;}

	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>functions.js"></script>
<?
print_html_body_begin($page_attributes);
?>

	<h2 class="swTitle">filter:</h2>

<?$sess_fusers->print_form();?>

<?if (!DB::isError($user_res) and $user_res->numRows()){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>name</th>
	<th>phone</th>
	<th>email</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while ($row=$user_res->fetchRow(DB_FETCHMODE_OBJECT)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username);?></td>
	<td align="left"><?echo nbsp_if_empty($name);?></td>
	<td align="right"><?echo nbsp_if_empty($row->phone);?></td>
	<td align="left"><a href="mailto:<?echo $row->email_address;?>"><?echo nbsp_if_empty($row->email_address);?></a></td>
	<td align="center"><a href="<?$sess->purl("acl.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username));?>">ACL</a></td>
	<td align="center"><a href="<?$sess->purl($config->user_pages_path."my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($row->username));?>">account</a></td>
	<td align="center"><a href="<?$sess->purl("users.php?kvrk=".uniqid('')."&dele_id=".rawURLEncode($row->username));?>" onclick="return confirmDelete(this, 'Realy you want delete user?')">delete</a></td>
	</tr>
	<?} //while
	$user_res->free();?>
	</table>
	
	<? if ($num_rows){?>
	<div class="swNumOfFoundRecords">Showed users <?echo ($sess_fusers->act_row+1)." - ".((($sess_fusers->act_row+$config->num_of_showed_items)<$num_rows)?($sess_fusers->act_row+$config->num_of_showed_items):$num_rows);?> from <?echo $num_rows;?></div>

	<div class="swSearchLinks"><?
		$url="users.php?kvrk=".uniqid("")."&act_row=";
		print_search_links($sess_fusers->act_row, $num_rows, $config->num_of_showed_items, $url);
	?></div>
	<?} // if ($num_rows)?>
	
<?}else{?>
<div class="swNumOfFoundRecords">No users found</div>
<?} // if (!DB::isError($user_res) and $user_res->numRows())?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
