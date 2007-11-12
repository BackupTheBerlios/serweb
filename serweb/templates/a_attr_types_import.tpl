{* Smarty *}
{* $Id: a_attr_types_import.tpl,v 1.1 2007/11/12 12:45:06 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.at_import_title}</h2>

<div class="swForm">
{$form.start}
    <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr><td colspan="2">{$lang_str.ff_at_import_exists}</td></tr>
    <tr>
    <td colspan="2">
        {$form.at_exists_skip}
        <label for="at_exists_skip" style="display:inline">{$lang_str.ff_at_import_skip}</label>
    </td>
    </tr>
    <tr>
    <td colspan="2">
        {$form.at_exists_update}
        <label for="at_exists_update" style="display:inline">{$lang_str.ff_at_import_update}</label>
    </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
    <td colspan="2">
        {$form.at_purge}
        <label for="at_purge" style="display:inline">{$lang_str.ff_at_import_purge}</label>
    </td>
    </tr>
    <tr>
    <td><label for="at_file">{$lang_str.ff_xml_file}:</label></td>
    <td>{$form.at_file}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td align="right">{$form.okey}</td>
    </tr>
    </table>
{$form.finish}
</div>
<br />


<div class="swBackToMainPage"><a href="{url url='attr_types.php'}">{$lang_str.l_back_to_editing_attributes}</a></div>

<br />
{include file='_tail.tpl'}

