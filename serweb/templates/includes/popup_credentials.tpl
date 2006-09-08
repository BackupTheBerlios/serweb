{* Smarty *}
{* $Id: popup_credentials.tpl,v 1.2 2006/09/08 12:27:35 kozlik Exp $ *}
{foreach from=$credentials item='row' name='cr_cr'}
<div>{$row.uname|escape|escape|empty2nbsp}{if $row.domainname}@{$row.domainname|escape|escape}{/if}</div>
{foreachelse}
	<div>{$lang_str.user_has_no_credentials}</div>
{/foreach}
