{* Smarty *}
{* $Id: u_phonebook.tpl,v 1.12 2007/02/06 10:36:10 kozlik Exp $ *}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

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
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>{$form.okey}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$phonebook item='row' name='phonebook'}
	{if $smarty.foreach.phonebook.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th><a href="{$url_sort_name}">{$lang_str.th_name}</a></th>
	<th><a href="{$url_sort_sip_uri}">{$lang_str.th_sip_address}</a></th>
	<th>{$lang_str.th_aliases}</th>
	<th>{$lang_str.th_status}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="left">{if $config->enable_ctd}<a href="{$row.url_ctd}">{/if}{$row.sip_uri|escape}{if $config->enable_ctd}</a>{/if}</td>
	<td align="left">{include file="includes/inline_aliases.tpl" uris=$row.uris no_flags=1}</td>
	<td align="center">{$row.status|empty2nbsp|user_status}</td>
	<td align="center"><a href="{$row.url_edit}" class="actionsrow">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}" class="actionsrow">{$lang_str.l_delete}</a></td>
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

{if $config->enable_whitepages}
<div id="orphanlinks">
<div class="swLinkToTabExtension"><a href="{url url='whitepages.php'}">{$lang_str.l_find_user}</a></div>
</div>
{/if}

<br>
{include file='_tail.tpl'}

