{* Template: /modules/templatemanager/action_admin_editor.tpl *}

{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root}/libraries/codemirror/js/codemirror.js"></script>
<script type="text/javascript" src="{$www_root}/libraries/codemirror/js/mirrorframe.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/codemirror_config.js"></script>


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

{/move_to}

{modulenavigation}
<div class="ModuleHeading">{t}Templatemanager - Editor{/t}</div>
<div class="ModuleHeadingSmall">{t}You can create and edit your templates here.{/t}</div>

{if isset($templateeditor_newfile) and ($templateeditor_newfile) == 1}
    <div class="ModuleHeadingSmall">{t}You are about to create: {/t} <font color="red"> {$templateeditor_filename} </font></div>
{else}
    <div class="ModuleHeadingSmall">{t}You are editing: {/t} <font color="red"> {$templateeditor_filename} </font></div>
{/if}

<table width="100%">
<tr>
    <td width="75%">

        <form action="index.php?mod=templatemanager&sub=admin&action=save" method="post">

            {* Textarea for the content of the template. *}
            <textarea rows="10" cols="80" id="codecontent" name="templateeditor_textarea">{$templateeditor_textarea}</textarea>

            <br />

            <div align="right">

                {* This is the template name, the content is saved to. *}
                <input type="hidden" name="templateeditor_filename" value="{$templateeditor_filename}" />

                {* This is the module name, the template is created for. *}
                <input type="hidden" name="templateeditor_modulename" value="{$templateeditor_modulename}" />

                {* Save Button *}
                <input class="ButtonGreen" type="submit" value="Save" />

                {* Reset Button *}
                <input class="Button" type="reset" value="Reset" />
            </div>

        </form>

    </td>
    <td>
            Options

            <br />

            Create for
            a) module/templates
            or
            b) theme

            <br />

            Variables and Plugins in Use (In Order Of Use):

            <br />

            Useable Placeholders:Options
    </td>
</tr>
</table>
