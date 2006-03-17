{* Smarty *}
{* $Id: popup_credentials.tpl,v 1.1 2006/03/17 14:26:53 kozlik Exp $ *}
{foreach from=$credentials item='row' name='cr_cr'}
<div>{$row->get_uname()|escape|escape|empty2nbsp}{if $row->get_realm()}@{$row->get_realm()|escape|escape}{/if}</div>
{foreachelse}
	<div>{$lang_str.user_has_no_credentials}</div>
{/foreach}
