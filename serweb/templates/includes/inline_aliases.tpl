{* Smarty *}
{* $Id: inline_aliases.tpl,v 1.3 2006/04/18 14:53:04 kozlik Exp $ *}

{* if param 'no_flags' is set an is true, flags of aliases are not displayed *}

{foreach from=$uris item='inc_ia_row' name='inc_ia_uris'}
	{assign var='uri_class' value='swUriEnabled'}
	{if $inc_ia_row->is_disabled()} {assign var='uri_class' value='swUriDisabled'} {/if}

	{if $no_flags}
		<a href="javascript:void(0);" class="swPopupLink" {popup text=$inc_ia_row->to_string()|escape|escape}>
	{else}
		{include file="includes/popup_uri_details.tpl" uri=$inc_ia_row assign="popup_text"}
		<a href="javascript:void(0);" class="swPopupLink" {popup text=$popup_text caption=$inc_ia_row->to_string()|escape|escape}>
	{/if}
	   <span class="{$uri_class}">{$inc_ia_row->get_username()|escape}</span></a>{if !$smarty.foreach.inc_ia_uris.last},{/if}
{foreachelse}
&nbsp;
{/foreach}

