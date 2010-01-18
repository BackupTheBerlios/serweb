{* Smarty *}
{* $Id: u_uri.tpl,v 1.1 2010/01/18 15:02:14 kozlik Exp $ *}

{include file='_head.tpl'}
{include file='_popup_init.tpl'}

<h2 class="swTitle">{$lang_str.user_uris}</h2>


{if $action=="insert" or $action=="edit"}

{literal}
<style type="text/css">
	#uri_un,   #uri_did,
    #al_usage_info_used, #al_usage_info_not_used {
        width:150px;
    }
</style>	
{/literal}

<div class="swForm">
{$form.start}
    <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
    <td><label for="uri_un">{$lang_str.ff_uri}({$lang_str.ff_username}):</label></td>
    <td>{$form.uri_un}<span id="aliasSuggestionsPlace"></span><a href="{$url_uri_generate|escape}" class="helperLink">{$lang_str.l_generate}</a></td>
    </tr>
    <tr>
    <td><label for="uri_did">{$lang_str.ff_uri}({$lang_str.ff_domain}):</label></td>
    <td>{$form.uri_did}</td>
    </tr>
	<tr>
    <td>&nbsp;</td>
    <td><div id="al_usage_info_used" class="usageInfoUsed" style="display:none">{$lang_str.uri_not_available}<br /><a href="{$url_uri_suggest}">{$lang_str.l_uri_suggest}</a></div>
        <div id="al_usage_info_not_used" class="usageInfoNotUsed" style="display:none">{$lang_str.uri_available}</div>
    </td>
    </tr>
    <tr>
    <td><label for="uri_is_canon">{$lang_str.ff_is_canon}:</label></td>
    <td>{$form.uri_is_canon}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>{$form.cancel}&nbsp;{$form.okey}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    </table>
{$form.finish}
</div>

{else}

    {foreach from=$uris item='row' name='uri'}
        {if $smarty.foreach.uri.first}
        <table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
        <tr>
        <th><a href="{$url_sort_uri|escape}">{$lang_str.th_uri}</a></th>
        <th>{$lang_str.th_is_canon}</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        </tr>
        {/if}
    
        <tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
        <td align="left">{$row.as_string|escape|empty2nbsp}</td>
		<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.is_canon}</td>
        <td align="center">{if $row.edit_allowed}<a href="{$row.url_edit|escape}" class="actionsrow">{$lang_str.l_edit}</a>{/if}</td>
        <td align="center">{if $row.edit_allowed}<a href="{$row.url_dele|escape}" class="actionsrow">{$lang_str.l_delete}</a>{/if}</td>
        </tr>
    
        {if $smarty.foreach.uri.last}
        </table>
    
        <div class="swNumOfFoundRecords">{$lang_str.displaying_records} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>
    
        <div class="swSearchLinks">&nbsp;
        {pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
        </div>
        {/if}
    {foreachelse}
    <div class="swNumOfFoundRecords">{$lang_str.no_records}</div>
    {/foreach}

    <div id="orphanlinks"><a href="{$url_insert|escape}">{$lang_str.l_insert}</a></div>

    <div class="swBackToMainPage"><a href="{url url='my_account.php' uniq=1}">{$lang_str.l_back_to_my_account}</a></div>

{/if}


<br />
{include file='_tail.tpl'}
