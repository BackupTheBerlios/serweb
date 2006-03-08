{* Smarty *}
{* $Id: inline_aliases.tpl,v 1.2 2006/03/08 15:36:28 kozlik Exp $ *}
{foreach from=$uris item='inc_ia_row' name='inc_ia_uris'}
	{assign var='uri_class' value='swUriEnabled'}
	{if $inc_ia_row->is_disabled()} {assign var='uri_class' value='swUriDisabled'} {/if}

	{include file="includes/popup_uri_details.tpl" uri=$inc_ia_row assign="popup_text"}
	<a href="javascript:void(0);" class="swPopupLink" {popup text=$popup_text caption=$inc_ia_row->to_string()|escape|escape}>
	   <span class="{$uri_class}">{$inc_ia_row->get_username()|escape}</span></a>{if !$smarty.foreach.inc_ia_uris.last},{/if}
{foreachelse}
&nbsp;
{/foreach}

