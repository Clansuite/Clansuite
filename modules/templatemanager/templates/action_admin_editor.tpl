{* Template: /modules/templatemanager/action_admin_editor.tpl *}

{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root}/libraries/codemirror/js/codemirror.js"></script>
<script type="text/javascript" src="{$www_root}/libraries/codemirror/js/mirrorframe.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/codemirror_config.js"></script>

{literal}
<!-- Line Numbers for CodeMirror : crappy solution, because it depends on the correct line heigth -->
<style type="text/css">
      .CodeMirror-line-numbers {
        width: 2.2em;
        color: #aaa;
        background-color: #fff;
        text-align: right;
        padding-right: .3em;
        font-size: 10pt;
        font-family: monospace;
        padding-top: .4em;
      }
    </style>
{/literal}
{/move_to}

<form action="index.php?mod=templatemanager&sub=admin&action=editor" method="post">

    {* Textarea for the content of the template. *}
    <textarea rows="10" cols="80" id="codecontent">{$templateText}</textarea>

    <br />

    <div align="right">

    {* Save Button *}
    <input class="ButtonGreen" type="submit" name="from_submit" value="Save" />

    {* Reset Button *}
    <input class="Button" type="reset" value="Reset" name="reset" />

    {* This is the template name, the content is saved to. *}
    <input type="hidden" value="{$templateName}" />

    {* This is the module name, the template is created for. *}
    <input type="hidden" value="{$templateModule}" />

    </div>

</form>


<!--
Options

<br />

Create for
a) module/templates
or
b) theme

<br />

Variables and Plugins in Use (In Order Of Use):

<br />

Useable Placeholders:
-->