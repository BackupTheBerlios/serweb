{* Smarty *}
{* $Id: a_users.tpl,v 1.6 2005/10/19 10:26:26 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#usrnm, #lusrnm {width:120px;}
	#domain, #ldomain {width:120px;}
	#fname, #lfname {width:120px;}
	#lname, #llname {width:120px;}
	#email, #lemail {width:120px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="usrnm" id="lusrnm">{$lang_str.ff_username}</label></td>
{if $multidomain}<td><label for="domain" id="ldomain">{$lang_str.ff_domain}</label></td>{/if}
<td><label for="fname" id="lfname">{$lang_str.ff_first_name}</label></td>
<td><label for="lname" id="llname">{$lang_str.ff_last_name}</label></td>
<td><label for="email" id="lemail">{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.usrnm}</td>
{if $multidomain}<td>{$form.domain}</td>{/if}
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
	{if $multidomain}<th>{$lang_str.th_domain}</th>{/if}
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_phone}</th>
	<th>{$lang_str.th_alias}</th>
	<th>{$lang_str.th_email}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.username|empty2nbsp}</td>
	{if $multidomain}<td align="left">{$row.domain|empty2nbsp}</td>{/if}
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="right">{$row.phone|empty2nbsp}</td>
	<td align="right">{$row.aliases|empty2nbsp}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{url url='acl.php' uniq=1}&{$row.get_param}">{$lang_str.l_acl}</a></td>
	<td align="center"><a href="{url url='aliases.php' uniq=1}&{$row.get_param}">{$lang_str.l_aliases}</a></td>
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
