{* Smarty *}
{* $Id: a_list_of_admins.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">filter:</h2>

{$form}

<h2 class="swTitle">List of users</h2>

{foreach from=$admins item='row' name='admins'}
	{if $smarty.foreach.admins.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>domain</th>
	<th>name</th>
	<th>email</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.username|empty2nbsp}</td>
	<td align="left">{$row.domain|empty2nbsp}</td>
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{$row.url_ch_priv}">change privileges</a></td>
	</tr>
	{if $smarty.foreach.admins.last}
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
