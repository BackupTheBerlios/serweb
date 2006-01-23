{* Smarty *}
{* $Id: inline_aliases.tpl,v 1.1 2006/01/23 14:15:36 kozlik Exp $ *}
{foreach from=$uris item='row' name='inc_ia_uris'}
	{assign var='uri_class' value='swUriEnabled'}
	{if $row->is_disabled()} {assign var='uri_class' value='swUriDisabled'} {/if}

	{include file="includes/popup_uri_details.tpl" uri=$row assign="popup_text"}
	<a href="javascript:void(0);" class="swPopupLink" {popup text=$popup_text caption=$row->to_string()}>
	   <span class="{$uri_class}">{$row->get_username()}</span></a>{if !$smarty.foreach.inc_ia_uris.last},{/if}
{foreachelse}
&nbsp;
{/foreach}
