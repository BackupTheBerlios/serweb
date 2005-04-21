
{literal}
<style type="text/css">
.mmyForm input{width: 100%;}
</style>
{/literal}


{include file='_head.tpl'}

		{$form.start}

{foreach from=$speed_dials item='row' name='speed_dials'}
{assign var='f_name' value="fname_`$row.index`"}
{assign var='l_name' value="lname_`$row.index`"}
{assign var='new_uri' value="new_uri_`$row.index`"}
{if $smarty.foreach.speed_dials.first}
		<span class="myForm">
		<table border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>
		<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
		 <tr>
		  <th ><a href="{$url_sort_from_uri}">{$lang_str.th_speed_dial}</a></th>
		  <th ><a href="{$url_sort_fname}">{$lang_str.ff_first_name}</a></th>
		  <th ><a href="{$url_sort_lname}">{$lang_str.ff_last_name}</a></th>
		  <th ><a href="{$url_sort_to_uri}">{$lang_str.th_new_uri}</a></th>
		 </tr>
{/if}
		 <tr class="{cycle values='swTrOdd,swTrEven'}">
		  <td >{$row.sd_username}</td>
		  <td >{$form.$f_name}</td>
		  <td >{$form.$l_name}</td>
		  <td >{$form.$new_uri}</td>
		 </tr>
{if $smarty.foreach.speed_dials.last}
		</table>
		</td></tr>

		<tr><td class="swSearchLinks">&nbsp;{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;' link_limit=4}</td></tr>
		<tr><td align="right">{$form.okey}</td></tr>
		</table>

		</span>


{/if}
{/foreach}
		
	

		
{$form.finish}

<br>
{include file='_tail.tpl'}

