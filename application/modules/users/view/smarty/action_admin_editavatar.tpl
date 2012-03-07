{* Debug: {$avatar_image} *}

<h2>{t}Userprofile{/t} - {t}Edit Avatar{/t}</h2>

<br />

<fieldset class="fieldset">
    <legend>{t}Your Current Avatar{/t}</legend>
	<table cellspacing="3" cellpadding="0" border="0">
		<tbody>
		<tr valign="top" class="tr_row1">
			<td style="border: 1px inset ; padding: 6px;">

			{if isset($avatar_image)}

                    URL: {$avatar_image}
                    <br />
                    <img src="{$avatar_image}">
                    <br />

                    <form enctype="multipart/form-data" action="/index.php?mod=users&sub=admin&action=deleteavatar&id={$smarty.session.user.user_id}"
                    name="avatar" method="post">

                        <input type="submit" class="ButtonRed" value="Delete" name="submit"/>

                    </form>

            {else}
                    <span class="smallfont">No Avatar Specified</span>
            {/if}

            </td>
			<td class="smallfont">
				<div style="margin-bottom: 10px;">Avatars are small graphics and displayed under your username.</div>
				<div style="margin-bottom: 10px;">
				    <label for="rb_avatarid_no">
				    <input type="radio" checked="checked" id="rb_avatarid_no" value="-1" name="avatarid"/>Do not use an avatar</label>
				</div>
				<div>Note: if you have a custom avatar selecting this option will delete it.</div>
			</td>
		</tr>
		</tbody>
	</table>
</fieldset>

<br />

<fieldset class="fieldset">
    <legend>{t}Chose: Pre-defined Avatar Sets{/t}</legend>
    <br />
    You may select one of the following pre-defined images to use as your avatar.
    <br />
    <b>Avatar Collections</b>
    Please select an Avatar Collection first.

</fieldset>

<br />

<fieldset class="fieldset">
    <legend>{t}Upload: Provide your own Avatar{/t}</legend>
    <br />
    You may provide an own avatar by uploading.
    <form enctype="multipart/form-data" action="/index.php?mod=users&sub=admin&action=addavatar" name="avatar" method="post">
        <b>Upload</b>
        <br />
        Info: display possible image types + attributes (png, jpeg, size)
        <br />
        <input type="file" value="" name="avatar"/>
        <input type="submit" class="ButtonGreen" value="Save" name="submit"/>
    </form>
</fieldset>

<br />

<fieldset class="fieldset">
    <legend>{t}Gravatar: use your Globally Recognized Avatar{/t}</legend>

        <b>Gravatar</b>

        <br />
        You can also use the service of <a href="http://www.gravatar.com/">Gravatar</a> (Globally Recognized Avatars) to have your avatar displayed here.

        <br />
        Sign up for an Gravatar Account, if you not already have one:
</fieldset>