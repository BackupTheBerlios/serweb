{* Smarty *}
{* $Id: popup_show_uri.tpl,v 1.2 2006/03/08 15:36:27 kozlik Exp $ *}

{foreach from=$uris item='uri' name='popup_show_uri'}
	{if $smarty.foreach.popup_show_uri.first}
		<table border='1' cellpadding='1' cellspacing='0' align='center'>
		<tr>
		<th>{$lang_str.th_uid}</th>
		<th>{$lang_str.th_alias}</th>
		<th>{$lang_str.th_is_canon}</th>
		<th>{$lang_str.th_uri_is_to}</th>
		<th>{$lang_str.th_uri_is_from}</th>
		</tr>
	{/if}
	{assign var='uri_class' value='swUriEnabled'}
	{if $uri.disabled} {assign var='uri_class' value='swUriDisabled'} {/if}
	<tr>
	<td>{$uri.uid}</td>
	<td><span class='{$uri_class}'>{$row.username|escape|escape}@{$row.domain|escape|escape}</span></td>
	<td align='center' class='swValignMid'>{include file="includes/yes_no.tpl" ok=$uri.is_canon}</td>
	<td align='center' class='swValignMid'>{include file="includes/yes_no.tpl" ok=$uri.is_to}</td>
	<td align='center' class='swValignMid'>{include file="includes/yes_no.tpl" ok=$uri.is_from}</td>
	</tr>
	{if $smarty.foreach.popup_show_uri.last}
		</table>
	{/if}
{/foreach}


