<?
/*
 * $Id: list_of_admins.php,v 1.5 2004/04/05 19:31:03 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin,change_priv");

if (!$sess->is_registered('sess_list_of_admins')) $sess->register('sess_list_of_admins');
if (!isset($sess_list_of_admins)) $sess_list_of_admins=new Cfusers(array('show_adminsonly'=>true, 'show_onlineonly'=>false, 'show_domain'=>true));

$sess_list_of_admins->init();

do{
	if (!$db = connect_to_db($errors)) break;

	$query_c=$sess_list_of_admins->get_query_where_phrase('s');


	// get num of users
	if ($sess_list_of_admins->adminsonly)
		$q="select count(*) 
			from ".$config->table_subscriber." s left join ".$config->table_admin_privileges." p on
				(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin')
			where p.priv_value and ".$query_c;
	else
		$q="select count(*) 
			from ".$config->table_subscriber." s 
			where ".$query_c;

	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); break;}

	$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
	$num_rows=$row[0];
	$res->free();


	// get admins
	if ($sess_list_of_admins->adminsonly)
		$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address 
			from ".$config->table_subscriber." s left join ".$config->table_admin_privileges." p on
				(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin')
			where p.priv_value and ".$query_c."
			order by s.domain, s.username 
			limit ".$sess_list_of_admins->act_row.", ".$config->num_of_showed_items;
	else
		$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address 
			from ".$config->table_subscriber." s 
			where ".$query_c."
			order by s.domain, s.username 
			limit ".$sess_list_of_admins->act_row.", ".$config->num_of_showed_items;

	$admin_res=$db->query($q);
	if (DB::isError($admin_res)) {log_errors($admin_res, $errors); break;}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">filter:</h2>

<?$sess_list_of_admins->print_form();?>

<h2 class="swTitle">List of users</h2>

<?if (!DB::isError($admin_res) and $admin_res->numRows()){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>domain</th>
	<th>name</th>
	<th>email</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while ($row=$admin_res->fetchRow(DB_FETCHMODE_OBJECT)){
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
	<?} //while
	$admin_res->free();?>
	</table>

	<? if ($num_rows){?>
	<div class="swNumOfFoundRecords">Showed users <?echo ($sess_list_of_admins->act_row+1)." - ".((($sess_list_of_admins->act_row+$config->num_of_showed_items)<$num_rows)?($sess_list_of_admins->act_row+$config->num_of_showed_items):$num_rows);?> from <?echo $num_rows;?></div>

	<div class="swSearchLinks"><?
		$url="list_of_admins.php?kvrk=".uniqid("")."&act_row=";
		print_search_links($sess_list_of_admins->act_row, $num_rows, $config->num_of_showed_items, $url);
	?></div>
	<?} // if ($num_rows)?>

<?}else{?>
<div class="swNumOfFoundRecords">No users found</div>
<?} // if (!DB::isError($admin_res) and $admin_res->numRows())?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
