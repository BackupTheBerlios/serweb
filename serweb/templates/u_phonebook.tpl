{* Smarty *}
{* $Id: u_phonebook.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">first name:</label></td>
	<td>{$form.fname}</td>
	</tr>
	<tr>
	<td><label for="lname">last name:</label></td>
	<td>{$form.lname}</td>
	</tr>
	<tr>
	<td><label for="sip_uri">sip address:</label></td>
	<td>{$form.sip_uri}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$pb_res item='row' name='phonebook'}
	{if $smarty.foreach.phonebook.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>name</th>
	<th>sip address</th>
	<th>aliases</th>
	<th>status</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="left"><a href="{$row.url_ctd}">{$row.sip_uri}</a></td>
	<td align="left">{$row.aliases|empty2nbsp}</td>
	<td align="center">{$row.status|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">edit</a></td>
	<td align="center"><a href="{$row.url_dele}">delete</a></td>
	</tr>

	{if $smarty.foreach.phonebook.last}
	</table>

	<div class="swNumOfFoundRecords">Phonebook records {$pager.from} - {$pager.to} from {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No records</div>
{/foreach}

<div class="swLinkToTabExtension"><a href="{url url='find_user.php'}">find user</a></div>

<br>
{include file='_tail.tpl'}

