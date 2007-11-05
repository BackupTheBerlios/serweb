{* Smarty *}
{* $Id: popup_uris.tpl,v 1.1 2007/11/05 12:55:11 kozlik Exp $ *}
{foreach from=$uris item='row'}
<div>{$row->to_string()|escape|escape|empty2nbsp}</div>
{foreachelse}
	<div>{$lang_str.user_has_no_sip_uris|escape|escape|empty2nbsp}</div>
{/foreach}
