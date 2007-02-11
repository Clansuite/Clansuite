{doc_raw}
    {* Prototype + Scriptaculous + Smarty_Ajax *}
    <script src="{$www_core_tpl_root}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_core_tpl_root}/javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script src="{$www_core_tpl_root}/javascript/smarty_ajax.js" type="text/javascript"></script>
    {* Tablegrid Extension *}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tablegrid.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/css/tablegrid.css" />
{/doc_raw}

<table cellpadding="0" cellspacing="0" border="0" id="profile" width="100%">
    <tr>
        <td class="td_header" width="130px">
            {translate}Fields{/translate}
        </td>
        <td class="td_header" id="zipcode">
            {translate}Values{/translate}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Birthday{/translate}
        </td>
        <td class="profile_editcell" id="birthday">
            {$info.birthday}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Gender{/translate}
        </td>
        <td class="profile_editcell" id="gender">
            {$info.gender}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Height{/translate}
        </td>
        <td class="profile_editcell" id="height">
            {$info.height}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Address{/translate}
        </td>
        <td class="profile_editcell" id="address">
            {$info.address}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}ZIP code{/translate}
        </td>
        <td class="profile_editcell" id="zipcode">
            {$info.zipcode}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}City{/translate}
        </td>
        <td class="profile_editcell" id="city">
            {$info.city}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Country{/translate}
        </td>
        <td class="profile_editcell" id="country">
            {$info.country}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Homepage{/translate}
        </td>
        <td class="profile_editcell" id="homepage">
            {$info.homepage}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}ICQ{/translate}
        </td>
        <td class="profile_editcell" id="icq">
            {$info.icq}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}MSN{/translate}
        </td>
        <td class="profile_editcell" id="msn">
            {$info.msn}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Skype{/translate}
        </td>
        <td class="profile_editcell" id="skype">
            {$info.skype}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Phone{/translate}
        </td>
        <td class="profile_editcell" id="phone">
            {$info.phone}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Mobile{/translate}
        </td>
        <td class="profile_editcell" id="mobile">
            {$info.mobile}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            {translate}Custom Text{/translate}
        </td>
        <td class="profile_editcell" id="custom_text">
            {$info.custom_text}
        </td>
    </tr>
</table>
<script type="text/javascript">new TableGrid('profile', '2', 'index.php?mod=account&sub=profile&action=ajax_update', 'profile_editcell');</script>