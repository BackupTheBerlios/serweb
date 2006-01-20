{* Smarty *}
{* $Id: a_aliases.tpl,v 1.3 2006/01/20 14:43:58 kozlik Exp $ *}

{include file='_head.tpl' no_select_tab=1}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

{literal}
<style type="text/css">
	#al_username,   #al_domain   {width:150px;}
</style>	
{/literal}


{if $action=="ack"}
	<h2 class="swTitle">{$lang_str.ack_values}</h2>
	<div>{$lang_str.uri_already_exists}</div>
	{if $is_to_warning}<br /><div class="swWarning">{$lang_str.is_to_warning}</div>{/if}

	<br />
	
	<table border=0 cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><td class="swAckTableName" >{$lang_str.ff_alias}({$lang_str.ff_username}):</td>
	    <td class="swAckTableValue">{$uri_for_ack.username}</td></tr>
	<tr><td class="swAckTableName" >{$lang_str.ff_alias}({$lang_str.ff_domain}):</td>
	    <td class="swAckTableValue">{$uri_for_ack.domain}</td></tr>
	<tr><td class="swAckTableName" >{$lang_str.ff_is_canon}:</td>
	    <td class="swAckTableValue">{if $uri_for_ack.is_canon}X{else}&nbsp;{/if}</td></tr>
	<tr><td class="swAckTableName" >{$lang_str.ff_uri_is_to}:</td>
	    <td class="swAckTableValue">{if $uri_for_ack.is_to}X{else}&nbsp;{/if}</td></tr>
	<tr><td class="swAckTableName" >{$lang_str.ff_uri_is_from}:</td>
	    <td class="swAckTableValue">{if $uri_for_ack.is_from}X{else}&nbsp;{/if}</td></tr>
	</table>


	<br />
 
	<table border=0 cellpadding="1" cellspacing="0" align="center" width="250">
	<tr><td width="50%"><a href="{$url_deny}">{$lang_str.l_deny}</a></td>
	    <td width="50%"><a href="{$url_ack}">{$lang_str.l_ack}</a></td></tr>
	</table>

	<h2 class="swTitle">{$lang_str.uris_with_same_uname_did}:</h2>

	{foreach from=$ack_uris item='row' name='ack_uris'}
		{if $smarty.foreach.ack_uris.first}
		<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
		<tr>
		<th>{$lang_str.th_uid}</th>
		<th>{$lang_str.th_alias}</th>
		<th>{$lang_str.th_is_canon}</th>
		<th>{$lang_str.th_uri_is_to}</th>
		<th>{$lang_str.th_uri_is_from}</th>
		</tr>
		{/if}
	
		<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
		<td align="left" style="padding-right:1em;">{$row.uid}</td>
		<td align="right" style="padding-right:1em;">{$row.username}@{$row.domain}</td>
		<td align="center">{if $row.is_canon}X{else}&nbsp;{/if}</td>
		<td align="center">{if $row.is_to}X{else}&nbsp;{/if}</td>
		<td align="center">{if $row.is_from}X{else}&nbsp;{/if}</td>
		</tr>
		{if $smarty.foreach.ack_uris.last}
		</table>
		{/if}
	{/foreach}

{else}

	<h2 class="swTitle">{$lang_str.change_aliases_of_user}: {$uname}</h2>
	
	<div class="swForm">
	{$form.start}
		<table border="0" cellspacing="2" cellpadding="0" align="center">
		<tr>
		<td><label for="al_username">{$lang_str.ff_alias}({$lang_str.ff_username}):</label></td>
		<td>{$form.al_username}</td>
		</tr>
		<tr>
		<td><label for="al_domain">{$lang_str.ff_alias}({$lang_str.ff_domain}):</label></td>
		<td>{$form.al_domain}</td>
		</tr>
		<tr>
		<td><label for="al_is_canon">{$lang_str.ff_is_canon}:</label></td>
		<td>{$form.al_is_canon}</td>
		</tr>
		<tr>
		<td><label for="al_is_to">{$lang_str.ff_uri_is_to}:</label></td>
		<td>{$form.al_is_to}</td>
		</tr>
		<tr>
		<td><label for="al_is_from">{$lang_str.ff_uri_is_from}:</label></td>
		<td>{$form.al_is_from}</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td align="right">{$form.okey}</td>
		</tr>
		</table>
	{$form.finish}
	</div>
	
	
	
	{foreach from=$aliases item='row' name='aliases'}
		{if $smarty.foreach.aliases.first}
		<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
		<tr>
		<th>{$lang_str.th_alias}</th>
		<th>{$lang_str.th_is_canon}</th>
		<th>{$lang_str.th_uri_is_to}</th>
		<th>{$lang_str.th_uri_is_from}</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		</tr>
		{/if}
	
		<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
		<td align="right" style="padding-right:1em;">
			{assign var='uri_class' value='swUriEnabled'}
			{if $row.disabled} {assign var='uri_class' value='swUriDisabled'} {/if}
			{if $row.s_uris}
				{include file="popup_show_uri.tpl" uris=$row.s_uris assign="popup_text"}
			
				<a href="javascript:void(0);" class="swPopupLink" {popup text=$popup_text caption="`$lang_str.uid_with_alias`: `$row.username`@`$row.domain`"}><span class="{$uri_class}">{$row.username}@{$row.domain}</span></a>
			{else}<span class="{$uri_class}">{$row.username}@{$row.domain}</span>
			{/if}
		</td>
		<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.is_canon}</td>
		<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.is_to}</td>
		<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.is_from}</td>
		<td align="center" style="width:6em;">{if $row.allow_change}<a href="{$row.url_edit}">{$lang_str.l_change}</a>{else}&nbsp;{/if}</td>
		<td align="center" style="width:6em;">{if $row.allow_change}<a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_you_want_delete_this_alias}')">{$lang_str.l_delete}</a>{else}&nbsp;{/if}</td>
		</tr>
		{if $smarty.foreach.aliases.last}
		</table>
		{/if}
	{foreachelse}
	<div class="swNumOfFoundRecords">{$lang_str.user_have_not_any_aliases}</div>
	{/foreach}
{/if}

<div class="swBackToMainPage"><a href="{url url='users.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

</div>
{include file='_tail.tpl'}
