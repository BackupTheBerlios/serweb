{* Smarty *}
{* $Id: a_users.tpl,v 1.3 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#usrnm {width:120px;}
	#fname {width:120px;}
	#lname {width:120px;}
	#email {width:120px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="usrnm">{$lang_str.ff_username}</label></td>
<td><label for="fname">{$lang_str.ff_first_name}</label></td>
<td><label for="lname">{$lang_str.ff_last_name}</label></td>
<td><label for="email">{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.usrnm}</td>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
</tr>

<tr><td colspan="4"><label for="onlineonly" style="display: inline;">{$lang_str.ff_show_online_only}:</label>{$form.onlineonly}</td></tr>
				
<tr><td colspan="4" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>


{foreach from=$users item='row' name='users'}
	{if $smarty.foreach.users.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_username}</th>
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_phone}</th>
	<th>{$lang_str.th_alias}</th>
	<th>{$lang_str.th_email}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.username|empty2nbsp}</td>
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="right">{$row.phone|empty2nbsp}</td>
	<td align="right">{$row.aliases|empty2nbsp}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{url url='acl.php' uniq=1}&{$row.get_param}">{$lang_str.l_acl}</a></td>
	<td align="center"><a href="{$cfg->user_pages_path}{url url='my_account.php' uniq=1}&{$row.get_param}">{$lang_str.l_account}</a></td>
	<td align="center"><a href="{$cfg->user_pages_path}{url url='accounting.php' uniq=1}&{$row.get_param}">{$lang_str.l_accounting}</a></td>
	<td align="center"><a href="{$row.url_dele}" onclick="return confirmDelete(this, 'Realy you want delete user?')">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.users.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.showed_users} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_users_found}</div>
{/foreach}

<br>
{include file='_tail.tpl'}
