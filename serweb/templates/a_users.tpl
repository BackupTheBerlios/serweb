{* Smarty *}
{* $Id: a_users.tpl,v 1.2 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{$form}

{foreach from=$users item='row' name='users'}
	{if $smarty.foreach.users.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_username}</th>
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_phone}</th>
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
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{$row.url_acl}">{$lang_str.l_acl}</a></td>
	<td align="center"><a href="{$row.url_my_account}">{$lang_str.l_account}</a></td>
	<td align="center"><a href="{$row.url_accounting}">{$lang_str.l_accounting}</a></td>
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
