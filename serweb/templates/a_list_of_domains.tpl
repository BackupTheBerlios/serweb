{* Smarty *}
{* $Id: a_list_of_domains.tpl,v 1.10 2008/03/07 15:20:02 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#dom_id,  #lid {width:150px;}
	#dom_name, #lname {width:150px;}
	#customer, #lcustomer {width:150px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="dom_id"  id="lid" >{$lang_str.d_id}</label></td>
<td><label for="dom_name" id="lname">{$lang_str.d_name}</label></td>
<td><label for="customer" id="lcustomer">{$lang_str.owner}</label></td>
</tr>

<tr>
<td>{$form.dom_id}</td>
<td>{$form.dom_name}</td>
<td>{$form.customer}</td>
</tr>
				
<tr><td colspan="3">
    {if $allow_display_deleted}
        <label for="deleted" style="display: inline;">{$lang_str.ff_show_deleted_domains}:</label>{$form.deleted}
    {/if}&nbsp;
</td></tr>
{*<tr><td colspan="3" height="5"></td></tr>*}
<tr><td colspan="3" class="note">{$lang_str.filter_wildcard_note}</td></tr>
<tr><td colspan="3" align="right">{$form.okey}{$form.f_clear}</td></tr>
</table>
{$form.finish}
</div>

<h2 class="swTitle">{$lang_str.list_of_domains}</h2>

{foreach from=$domains item='row' name='domains'}
	{if $smarty.foreach.domains.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr valign="top">
	<th>{$lang_str.d_id}</th>
	<th>{$lang_str.d_name}</th>
	<th>{$lang_str.owner}</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven' advance=false} informationrow">
	<td align="left">{$row.id|escape|empty2nbsp}</td>
	<td align="left" width="400">
		{assign var='dom_class' value='swDomainEnabled'}
		{if $row.disabled} {assign var='dom_class' value='swDomainDisabled'} {/if}
		{foreach from=$row.names item='dom' name='one_domain'}
			<span class="{$dom_class}">{$dom.name|escape}</span>{if !$smarty.foreach.one_domain.last}, {/if}
		{/foreach}&nbsp;
	</td>
	<td align="left">{$row.customer|escape|empty2nbsp}</td>
	</tr>

	<tr valign="top"  class="{cycle values='swTrOdd,swTrEven'} actionsrow">
	<td align="left" colspan="3">
        {if $row.deleted}
            <div class="actionsrownote">** {$lang_str.deleted_domain} **</div>
            <a href="{$row.url_undele}">{$lang_str.l_undelete}</a>
            <a href="{$row.url_purge}" onclick="return confirmDelete(this, '{$lang_str.realy_purge_domain}')">{$lang_str.l_purge}</a>
        {else}
    		{if $row.url_layout}<a href="{$row.url_layout}">{$lang_str.l_change_layout}</a>{else}&nbsp;{/if}
    		{if $row.url_attributes}<a href="{$row.url_attributes}">{$lang_str.l_domain_attributes}</a>{else}&nbsp;{/if}
            {if $hostmaster_actions}
                {if $row.url_edit}<a href="{$row.url_edit}">{$lang_str.l_edit}</a>{else}&nbsp;{/if}
                {if $row.disabled}
                    {if $row.url_enable}<a href="{$row.url_enable}">{$lang_str.l_enable}</a>{else}&nbsp;{/if}
            	{else}
            		{if $row.url_disable}<a href="{$row.url_disable}">{$lang_str.l_disable}</a>{else}&nbsp;{/if}
            	{/if}
        		{if $row.url_dele}<a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_delete_domain}')">{$lang_str.l_delete}</a>{else}&nbsp;{/if}
            {/if}
        {/if}
	</td>
	</tr>
	{if $smarty.foreach.domains.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.showed_domains} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_domains_found}</div>
{/foreach}

{if $hostmaster_actions}
<div id="orphanlinks"><a href="javascript: open_wizard_win('{url url=$url_new_domain uniq=1}');">{$lang_str.l_create_new_domain}</a></div>
{/if}
	
<br>
{include file='_tail.tpl'}
