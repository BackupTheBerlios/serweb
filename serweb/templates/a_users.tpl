{* Smarty *}
{* $Id: a_users.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">filter:</h2>

{$form}

{foreach from=$users item='row' name='users'}
	{if $smarty.foreach.users.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>name</th>
	<th>phone</th>
	<th>email</th>
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
	<td align="center"><a href="{$row.url_acl}">ACL</a></td>
	<td align="center"><a href="{$row.url_my_account}">account</a></td>
	<td align="center"><a href="{$row.url_accounting}">accounting</a></td>
	<td align="center"><a href="{$row.url_dele}" onclick="return confirmDelete(this, 'Realy you want delete user?')">delete</a></td>
	</tr>
	{if $smarty.foreach.users.last}
	</table>

	<div class="swNumOfFoundRecords">Showed users {$pager.from} - {$pager.to} from {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No users found</div>
{/foreach}

<br>
{include file='_tail.tpl'}
