{* Smarty *}
{* $Id: _head.tpl,v 1.2 2004/08/09 23:04:57 kozlik Exp $ *}

{if $parameters.user_name}
	<div class="swHeadingUser">{$parameters.user_name.fname} {$parameters.user_name.lname} &lt;{$parameters.user_name.uname}@{$parameters.user_name.domain}&gt;</div>
{/if}

{if $parameters.logout}
	<div class="swHeadingLogout"><a href="{url url='logout.php'}">{$lang_str.l_logout}</a></div>
{/if}

	<br class="cleaner" /><br />

{if $parameters.tab_collection}

	{html_tabs tabs=$parameters.tab_collection 
	           path=$parameters.path_to_pages 
			   selected=$parameters.selected_tab}
	
	{* count tabs *}
	{assign var="num_of_tabs" value="0"}
	{foreach from=$parameters.tab_collection item='tab'}
	{if $tab->enabled}{assign var="num_of_tabs" value="`$num_of_tabs+1`"}{/if}
	{/foreach}

	<div id="swContent">

	<!-- content of div must be sufficient wide in order to tabs displays in one line -->
	<div style="height:1px; width:{$num_of_tabs*100-50}px;">&nbsp;</div>
	
{else}
	<div id="swContentNoTabs">
{/if}

{include file='_errors.tpl' errors=$parameters.errors}
{include file='_message.tpl' message=$parameters.message}

{if $parameters.errors or $parameters.message}
	<br />
{/if}
