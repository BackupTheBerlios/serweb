{* Smarty *}
{* $Id: u_account_delete.tpl,v 1.1 2012/08/29 16:06:45 kozlik Exp $ *}


{include file='_head.tpl'}
{include file='_popup_init.tpl'}


<h2 class="swTitle">{$lang_str.are_you_sure_to_delete_account}</h2>
<p>{$lang_str.delete_account_description}</p>

<div id="orphanlinks">
    <div class="swLinkToTabExtension">
        <a href="{$cancel_url}">{$lang_str.l_cancel}</a> /
        <a href="{$url_self_delete}">{$lang_str.l_yes_delete_it}</a>
    </div>
</div>

<br />
{include file='_tail.tpl'}
