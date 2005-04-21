{* Smarty *}
{* $Id: u_phonebook.tpl,v 1.3 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">{$lang_str.ff_first_name}:</label></td>
	<td>{$form.fname}</td>
	</tr>
	<tr>
	<td><label for="lname">{$lang_str.ff_last_name}:</label></td>
	<td>{$form.lname}</td>
	</tr>
	<tr>
	<td><label for="sip_uri">{$lang_str.ff_sip_address}:</label></td>
	<td>{$form.sip_uri}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$phonebook item='row' name='phonebook'}
	{if $smarty.foreach.phonebook.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_sip_address}</th>
	<th>{$lang_str.th_aliases}</th>
	<th>{$lang_str.th_status}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="left"><a href="{$row.url_ctd}">{$row.sip_uri}</a></td>
	<td align="left">{$row.aliases|empty2nbsp}</td>
	<td align="center">{$row.status|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>

	{if $smarty.foreach.phonebook.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.phonebook_records} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_records}</div>
{/foreach}

<div class="swLinkToTabExtension"><a href="{url url='whitepages.php'}">{$lang_str.l_find_user}</a></div>

<br>
{include file='_tail.tpl'}

