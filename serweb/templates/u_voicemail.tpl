{* Smarty *}
{* $Id: u_voicemail.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">Customize greetings:</h2>

<div class="swForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center" class="swWidthAsTitle">
<tr>
	<td align="left">{$form.greeting}</td>
	<td align="right">{$form.okey}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right"><a href="{url url='play_greeting.php' uniq='1'}"><img src="{$config->img_src_path}butons/b_download_greeting.gif" width="165" height="16" border="0"></a></td>
</tr>
</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}

