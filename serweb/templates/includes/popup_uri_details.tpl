{* Smarty *}
{* $Id: popup_uri_details.tpl,v 1.1 2006/01/23 14:15:36 kozlik Exp $ *}
<table border='0'>
<tr><td align='right'>{$lang_str.ff_is_canon}:</td>
    <td>{include file="includes/yes_no.tpl" ok=$uri->is_canonical()}</td></tr>
<tr><td align='right'>{$lang_str.ff_uri_is_to}:</td>
    <td>{include file="includes/yes_no.tpl" ok=$uri->is_to()}</td></tr>
<tr><td align='right'>{$lang_str.ff_uri_is_from}:</td>
    <td>{include file="includes/yes_no.tpl" ok=$uri->is_from()}</td></tr>
<tr><td align='right'>{$lang_str.ff_is_enabled}:</td>
    <td>{if $uri->is_disabled()}{include file="includes/yes_no.tpl" ok=0}
	                      {else}{include file="includes/yes_no.tpl" ok=1}{/if}</td></tr>
</table>

