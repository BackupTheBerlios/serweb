{* Smarty *}
{* $Id: a_ser_moni.tpl,v 1.2 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

	<h2 class="swTitle">{$lang_str.ser_moni_transaction_statistics}</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>{$lang_str.ser_moni_general_values}</em></span>
		<span class="swSMdval"><em>{$lang_str.ser_moni_diferencial_values}</em></span>
	</div>
	{$values_html.ts_current}
	{$values_html.ts_waiting}
	{$values_html.ts_total}
	{$values_html.ts_total_local}
	<br/>
	{$values_html.ts_replied}
	<br/>

	<h2 class="swTitle">{$lang_str.ser_moni_completion_status}</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>{$lang_str.ser_moni_general_values}</em></span>
		<span class="swSMdval"><em>{$lang_str.ser_moni_diferencial_values}</em></span>
	</div>
	{$values_html.ts_6xx}
	{$values_html.ts_5xx}
	{$values_html.ts_4xx}
	{$values_html.ts_3xx}
	{$values_html.ts_2xx}

	<h2 class="swTitle">{$lang_str.ser_moni_stateless_server_statis}</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>{$lang_str.ser_moni_general_values}</em></span>
		<span class="swSMdval"><em>{$lang_str.ser_moni_diferencial_values}</em></span>
	</div>
	{$values_html.sl_200}
	{$values_html.sl_202}
	{$values_html.sl_2xx}
	<br />
	{$values_html.sl_300}
	{$values_html.sl_301}
	{$values_html.sl_302}
	{$values_html.sl_3xx}
	<br />
	{$values_html.sl_400}
	{$values_html.sl_401}
	{$values_html.sl_403}
	{$values_html.sl_404}
	{$values_html.sl_407}
	{$values_html.sl_408}
	{$values_html.sl_483}
	{$values_html.sl_4xx}
	<br />
	{$values_html.sl_500}
	{$values_html.sl_5xx}
	<br />
	{$values_html.sl_6xx}
	<br />
	{$values_html.sl_xxx}
	
{foreach from=$ul_params item='row' name='ul_params'}
	{* concatenate 'ul_' , $row and '_reg' in order to get name of stat *}
	{assign var='stat_reg' value="ul_`$row`_reg"}
	{assign var='stat_exp' value="ul_`$row`_exp"}

	{if $smarty.foreach.ul_params.first}
	<h2 class="swTitle">{$lang_str.ser_moni_usrLoc_stats}</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>{$lang_str.ser_moni_general_values}</em></span>
		<span class="swSMdval"><em>{$lang_str.ser_moni_diferencial_values}</em></span>
	</div>
	{/if}

	<div class="swSMdomain"><em>{$lang_str.domain}:</em> <strong>{$row}</strong></div>
	{$values_html.$stat_reg}
	{$values_html.$stat_exp}
	<br />
{/foreach}

<br />
{include file='_tail.tpl'}

