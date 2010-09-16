<form action="index.php?mod=account&amp;sub=general&amp;action=edit" method="post" accept-charset="UTF-8" enctype="multipart/form-data" name="upload">
<table cellpadding="0" cellspacing="0" border="0" width="500" align="center" style="margin: auto">
    <tr class="tr_header">
        <td width="1%">
            {t}Info{/t}
        </td>
        <td>
            {t}Value{/t}
        </td>
    </tr>
    {foreach item=item key=key from=$profile}
        <tr class="tr_row1">
            <td width="80px">
                {if $key == custom_text}
                    {t}Custom Text{/t}
                {/if}
                {if $key == mobile}
                    {t}Mobile{/t}
                {/if}
                {if $key == phone}
                    {t}Phone{/t}
                {/if}
                {if $key == icq}
                    {t}ICQ{/t}
                {/if}
                {if $key == msn}
                    {t}MSN{/t}
                {/if}
                {if $key == homepage}
                    {t}Homepage{/t}
                {/if}
                {if $key == skype}
                    {t}Skype{/t}
                {/if}
                {if $key == country}
                    {t}Country{/t}
                {/if}
                {if $key == state}
                    {t}State{/t}
                {/if}
                {if $key == city}
                    {t}City{/t}
                {/if}
                {if $key == zipcode}
                    {t}ZIP Code{/t}
                {/if}
                {if $key == address}
                    {t}Address{/t}
                {/if}
                {if $key == height}
                    {t}Height{/t}
                {/if}
                {if $key == gender}
                    {t}Gender{/t}
                {/if}
                {if $key == birthday}
                    {t}Birthday{/t}
                {/if}
                {if $key == last_name}
                    {t}Last name{/t}
                {/if}
                {if $key == first_name}
                    {t}First name{/t}
                {/if}
                {if $key == timestamp}
                    {t}Last changed{/t}
                {/if}
                {if $key == image_id}
                    {t}Avatar location{/t}
                {/if}
            </td>
            <td>
                {if $key == gender}
                    <select name="profile[{$key}]" class="input_text">
                        <option value="">{t}---{/t}</option>
                        <option value="male">{t}Male{/t}</option>
                        <option value="female">{t}Female{/t}</option>
                    </select>
                {elseif $key == country}
                <select size="1" name="profile[{$key}]" class="input_text">
                    <option value="">{t}Select One{/t}</option>
                    <option value="US">{t}United States{/t}</option>
                    <option value="CA">{t}Canada{/t}</option>
                    <option value="DE">{t}Germany{/t}</option>
                    <option value="">{t}----------{/t}</option>
                    <option value="AF">{t}Afghanistan{/t}</option>
                    <option value="AL">{t}Albania{/t}</option>
                    <option value="DZ">{t}Algeria{/t}</option>
                    <option value="AS">{t}American Samoa{/t}</option>
                    <option value="AD">{t}Andorra{/t}</option>
                    <option value="AO">{t}Angola{/t}</option>
                    <option value="AI">{t}Anguilla{/t}</option>
                    <option value="AQ">{t}Antarctica{/t}</option>
                    <option value="AG">{t}Antigua and Barbuda{/t}</option>
                    <option value="AR">{t}Argentina{/t}</option>
                    <option value="AM">{t}Armenia{/t}</option>
                    <option value="AW">{t}Aruba{/t}</option>
                    <option value="AU">{t}Australia{/t}</option>
                    <option value="AT">{t}Austria{/t}</option>
                    <option value="AZ">{t}Azerbaidjan{/t}</option>
                    <option value="BS">{t}Bahamas{/t}</option>
                    <option value="BH">{t}Bahrain{/t}</option>
                    <option value="BD">{t}Bangladesh{/t}</option>
                    <option value="BB">{t}Barbados{/t}</option>
                    <option value="BY">{t}Belarus{/t}</option>
                    <option value="BE">{t}Belgium{/t}</option>
                    <option value="BZ">{t}Belize{/t}</option>
                    <option value="BJ">{t}Benin{/t}</option>
                    <option value="BM">{t}Bermuda{/t}</option>
                    <option value="BT">{t}Bhutan{/t}</option>
                    <option value="BO">{t}Bolivia{/t}</option>
                    <option value="BA">{t}Bosnia-Herzegovina{/t}</option>
                    <option value="BW">{t}Botswana{/t}</option>
                    <option value="BV">{t}Bouvet Island{/t}</option>
                    <option value="BR">{t}Brazil{/t}</option>
                    <option value="IO">{t}British Indian Ocean Territory{/t}</option>
                    <option value="BN">{t}Brunei Darussalam{/t}</option>
                    <option value="BG">{t}Bulgaria{/t}</option>
                    <option value="BF">{t}Burkina Faso{/t}</option>
                    <option value="BI">{t}Burundi{/t}</option>
                    <option value="KH">{t}Cambodia{/t}</option>
                    <option value="CM">{t}Cameroon{/t}</option>
                    <option value="CV">{t}Cape Verde{/t}</option>
                    <option value="KY">{t}Cayman Islands{/t}</option>
                    <option value="CF">{t}Central African Republic{/t}</option>
                    <option value="TD">{t}Chad{/t}</option>
                    <option value="CL">{t}Chile{/t}</option>
                    <option value="CN">{t}China{/t}</option>
                    <option value="CX">{t}Christmas Island{/t}</option>
                    <option value="CC">{t}Cocos (Keeling) Islands{/t}</option>
                    <option value="CO">{t}Colombia{/t}</option>
                    <option value="KM">{t}Comoros{/t}</option>
                    <option value="CG">{t}Congo{/t}</option>
                    <option value="CK">{t}Cook Islands{/t}</option>
                    <option value="CR">{t}Costa Rica{/t}</option>
                    <option value="HR">{t}Croatia{/t}</option>
                    <option value="CU">{t}Cuba{/t}</option>
                    <option value="CY">{t}Cyprus{/t}</option>
                    <option value="CZ">{t}Czech Republic{/t}</option>
                    <option value="DK">{t}Denmark{/t}</option>
                    <option value="DJ">{t}Djibouti{/t}</option>
                    <option value="DM">{t}Dominica{/t}</option>
                    <option value="DO">{t}Dominican Republic{/t}</option>
                    <option value="TP">{t}East Timor{/t}</option>
                    <option value="EC">{t}Ecuador{/t}</option>
                    <option value="EG">{t}Egypt{/t}</option>
                    <option value="SV">{t}El Salvador{/t}</option>
                    <option value="GQ">{t}Equatorial Guinea{/t}</option>
                    <option value="ER">{t}Eritrea{/t}</option>
                    <option value="EE">{t}Estonia{/t}</option>
                    <option value="ET">{t}Ethiopia{/t}</option>
                    <option value="FK">{t}Falkland Islands{/t}</option>
                    <option value="FO">{t}Faroe Islands{/t}</option>
                    <option value="FJ">{t}Fiji{/t}</option>
                    <option value="FI">{t}Finland{/t}</option>
                    <option value="CS">{t}Former Czechoslovakia{/t}</option>
                    <option value="SU">{t}Former USSR{/t}</option>
                    <option value="FR">{t}France{/t}</option>
                    <option value="FX">{t}France (European Territory){/t}</option>
                    <option value="GF">{t}French Guyana{/t}</option>
                    <option value="TF">{t}French Southern Territories{/t}</option>
                    <option value="GA">{t}Gabon{/t}</option>
                    <option value="GM">{t}Gambia{/t}</option>
                    <option value="GE">{t}Georgia{/t}</option>
                    <option value="DE">{t}Germany{/t}</option>
                    <option value="GH">{t}Ghana{/t}</option>
                    <option value="GI">{t}Gibraltar{/t}</option>
                    <option value="GB">{t}Great Britain{/t}</option>
                    <option value="GR">{t}Greece{/t}</option>
                    <option value="GL">{t}Greenland{/t}</option>
                    <option value="GD">{t}Grenada{/t}</option>
                    <option value="GP">{t}Guadeloupe (French){/t}</option>
                    <option value="GU">{t}Guam (USA){/t}</option>
                    <option value="GT">{t}Guatemala{/t}</option>
                    <option value="GN">{t}Guinea{/t}</option>
                    <option value="GW">{t}Guinea Bissau{/t}</option>
                    <option value="GY">{t}Guyana{/t}</option>
                    <option value="HT">{t}Haiti{/t}</option>
                    <option value="HM">{t}Heard and McDonald Islands{/t}</option>
                    <option value="HN">{t}Honduras{/t}</option>
                    <option value="HK">{t}Hong Kong{/t}</option>
                    <option value="HU">{t}Hungary{/t}</option>
                    <option value="IS">{t}Iceland{/t}</option>
                    <option value="IN">{t}India{/t}</option>
                    <option value="ID">{t}Indonesia{/t}</option>
                    <option value="INT">{t}International{/t}</option>
                    <option value="IR">{t}Iran{/t}</option>
                    <option value="IQ">{t}Iraq{/t}</option>
                    <option value="IE">{t}Ireland{/t}</option>
                    <option value="IL">{t}Israel{/t}</option>
                    <option value="IT">{t}Italy{/t}</option>
                    <option value="CI">{t}Ivory Coast (Cote D&#39;Ivoire){/t}</option>
                    <option value="JM">{t}Jamaica{/t}</option>
                    <option value="JP">{t}Japan{/t}</option>
                    <option value="JO">{t}Jordan{/t}</option>
                    <option value="KZ">{t}Kazakhstan{/t}</option>
                    <option value="KE">{t}Kenya{/t}</option>
                    <option value="KI">{t}Kiribati{/t}</option>
                    <option value="KW">{t}Kuwait{/t}</option>
                    <option value="KG">{t}Kyrgyzstan{/t}</option>
                    <option value="LA">{t}Laos{/t}</option>
                    <option value="LV">{t}Latvia{/t}</option>
                    <option value="LB">{t}Lebanon{/t}</option>
                    <option value="LS">{t}Lesotho{/t}</option>
                    <option value="LR">{t}Liberia{/t}</option>
                    <option value="LY">{t}Libya{/t}</option>
                    <option value="LI">{t}Liechtenstein{/t}</option>
                    <option value="LT">{t}Lithuania{/t}</option>
                    <option value="LU">{t}Luxembourg{/t}</option>
                    <option value="MO">{t}Macau{/t}</option>
                    <option value="MK">{t}Macedonia{/t}</option>
                    <option value="MG">{t}Madagascar{/t}</option>
                    <option value="MW">{t}Malawi{/t}</option>
                    <option value="MY">{t}Malaysia{/t}</option>
                    <option value="MV">{t}Maldives{/t}</option>
                    <option value="ML">{t}Mali{/t}</option>
                    <option value="MT">{t}Malta{/t}</option>
                    <option value="MH">{t}Marshall Islands{/t}</option>
                    <option value="MQ">{t}Martinique (French){/t}</option>
                    <option value="MR">{t}Mauritania{/t}</option>
                    <option value="MU">{t}Mauritius{/t}</option>
                    <option value="YT">{t}Mayotte{/t}</option>
                    <option value="MX">{t}Mexico{/t}</option>
                    <option value="FM">{t}Micronesia{/t}</option>
                    <option value="MD">{t}Moldavia{/t}</option>
                    <option value="MC">{t}Monaco{/t}</option>
                    <option value="MN">{t}Mongolia{/t}</option>
                    <option value="MS">{t}Montserrat{/t}</option>
                    <option value="MA">{t}Morocco{/t}</option>
                    <option value="MZ">{t}Mozambique{/t}</option>
                    <option value="MM">{t}Myanmar{/t}</option>
                    <option value="NA">{t}Namibia{/t}</option>
                    <option value="NR">{t}Nauru{/t}</option>
                    <option value="NP">{t}Nepal{/t}</option>
                    <option value="NL">{t}Netherlands{/t}</option>
                    <option value="AN">{t}Netherlands Antilles{/t}</option>
                    <option value="NT">{t}Neutral Zone{/t}</option>
                    <option value="NC">{t}New Caledonia (French){/t}</option>
                    <option value="NZ">{t}New Zealand{/t}</option>
                    <option value="NI">{t}Nicaragua{/t}</option>
                    <option value="NE">{t}Niger{/t}</option>
                    <option value="NG">{t}Nigeria{/t}</option>
                    <option value="NU">{t}Niue{/t}</option>
                    <option value="NF">{t}Norfolk Island{/t}</option>
                    <option value="KP">{t}North Korea{/t}</option>
                    <option value="MP">{t}Northern Mariana Islands{/t}</option>
                    <option value="NO">{t}Norway{/t}</option>
                    <option value="OM">{t}Oman{/t}</option>
                    <option value="PK">{t}Pakistan{/t}</option>
                    <option value="PW">{t}Palau{/t}</option>
                    <option value="PA">{t}Panama{/t}</option>
                    <option value="PG">{t}Papua New Guinea{/t}</option>
                    <option value="PY">{t}Paraguay{/t}</option>
                    <option value="PE">{t}Peru{/t}</option>
                    <option value="PH">{t}Philippines{/t}</option>
                    <option value="PN">{t}Pitcairn Island{/t}</option>
                    <option value="PL">{t}Poland{/t}</option>
                    <option value="PF">{t}Polynesia (French){/t}</option>
                    <option value="PT">{t}Portugal{/t}</option>
                    <option value="PR">{t}Puerto Rico{/t}</option>
                    <option value="QA">{t}Qatar{/t}</option>
                    <option value="RE">{t}Reunion (French){/t}</option>
                    <option value="RO">{t}Romania{/t}</option>
                    <option value="RU">{t}Russian Federation{/t}</option>
                    <option value="RW">{t}Rwanda{/t}</option>
                    <option value="GS">{t}S. Georgia & S. Sandwich Isls.{/t}</option>
                    <option value="SH">{t}Saint Helena{/t}</option>
                    <option value="KN">{t}Saint Kitts & Nevis Anguilla{/t}</option>
                    <option value="LC">{t}Saint Lucia{/t}</option>
                    <option value="PM">{t}Saint Pierre and Miquelon{/t}</option>
                    <option value="ST">{t}Saint Tome (Sao Tome) and Principe{/t}</option>
                    <option value="VC">{t}Saint Vincent & Grenadines{/t}</option>
                    <option value="WS">{t}Samoa{/t}</option>
                    <option value="SM">{t}San Marino{/t}</option>
                    <option value="SA">{t}Saudi Arabia{/t}</option>
                    <option value="SN">{t}Senegal{/t}</option>
                    <option value="SC">{t}Seychelles{/t}</option>
                    <option value="SL">{t}Sierra Leone{/t}</option>
                    <option value="SG">{t}Singapore{/t}</option>
                    <option value="SK">{t}Slovak Republic{/t}</option>
                    <option value="SI">{t}Slovenia{/t}</option>
                    <option value="SB">{t}Solomon Islands{/t}</option>
                    <option value="SO">{t}Somalia{/t}</option>
                    <option value="ZA">{t}South Africa{/t}</option>
                    <option value="KR">{t}South Korea{/t}</option>
                    <option value="ES">{t}Spain{/t}</option>
                    <option value="LK">{t}Sri Lanka{/t}</option>
                    <option value="SD">{t}Sudan{/t}</option>
                    <option value="SR">{t}Suriname{/t}</option>
                    <option value="SJ">{t}Svalbard and Jan Mayen Islands{/t}</option>
                    <option value="SZ">{t}Swaziland{/t}</option>
                    <option value="SE">{t}Sweden{/t}</option>
                    <option value="CH">{t}Switzerland{/t}</option>
                    <option value="SY">{t}Syria{/t}</option>
                    <option value="TJ">{t}Tadjikistan{/t}</option>
                    <option value="TW">{t}Taiwan{/t}</option>
                    <option value="TZ">{t}Tanzania{/t}</option>
                    <option value="TH">{t}Thailand{/t}</option>
                    <option value="TG">{t}Togo{/t}</option>
                    <option value="TK">{t}Tokelau{/t}</option>
                    <option value="TO">{t}Tonga{/t}</option>
                    <option value="TT">{t}Trinidad and Tobago{/t}</option>
                    <option value="TN">{t}Tunisia{/t}</option>
                    <option value="TR">{t}Turkey{/t}</option>
                    <option value="TM">{t}Turkmenistan{/t}</option>
                    <option value="TC">{t}Turks and Caicos Islands{/t}</option>
                    <option value="TV">{t}Tuvalu{/t}</option>
                    <option value="UG">{t}Uganda{/t}</option>
                    <option value="UA">{t}Ukraine{/t}</option>
                    <option value="AE">{t}United Arab Emirates{/t}</option>
                    <option value="GB">{t}United Kingdom{/t}</option>
                    <option value="UY">{t}Uruguay{/t}</option>
                    <option value="MIL">{t}USA Military{/t}</option>
                    <option value="UM">{t}USA Minor Outlying Islands{/t}</option>
                    <option value="UZ">{t}Uzbekistan{/t}</option>
                    <option value="VU">{t}Vanuatu{/t}</option>
                    <option value="VA">{t}Vatican City State{/t}</option>
                    <option value="VE">{t}Venezuela{/t}</option>
                    <option value="VN">{t}Vietnam{/t}</option>
                    <option value="VG">{t}Virgin Islands (British){/t}</option>
                    <option value="VI">{t}Virgin Islands (USA){/t}</option>
                    <option value="WF">{t}Wallis and Futuna Islands{/t}</option>
                    <option value="EH">{t}Western Sahara{/t}</option>
                    <option value="YE">{t}Yemen{/t}</option>
                    <option value="YU">{t}Yugoslavia{/t}</option>
                    <option value="ZR">{t}Zaire{/t}</option>
                    <option value="ZM">{t}Zambia{/t}</option>
                    <option value="ZW">{t}Zimbabwe{/t}</option>
                </select>
                {elseif $key == custom_text}
                    <textarea name="profile[{$key}]" rows="5" cols="45" class="input_textarea">{$item}</textarea>
                {elseif $key == birthday}
                    {$item|date_format:"<input type='text' size='2' name='profile[birthday][day]' class='input_text' value='%d' /><input type='text' size='2' name='profile[birthday][month]' class='input_text' value='%m' /><input type='text' size='4' name='profile[birthday][year]' class='input_text' value='%Y' />"}
                {elseif $key == timestamp}
                    {$item|date_format:'<input type="text" size="2" name="profile[timestamp][day]" class="input_text" value="%d" /><input type="text" size="2" name="profile[timestamp][month]" class="input_text" value="%m" /><input type="text" size="4" name="profile[timestamp][year]" class="input_text" value="%Y" />'}
                {elseif $key == image_id}
                    <select name="profile[avatar_type]" id="type" onchange="if(document.getElementById('type').options[document.getElementById('type').options.selectedIndex].text=='url'){document.getElementById('upload').type='text'}else{document.getElementById('upload').type='file';}" class="input_text">
                        <option value="upload">{t}upload{/t}</option>
                        <option value="url">{t}url{/t}</option>
                    </select>
                    <input type="file" name="avatar" id="upload" />
                {else}
                    <input name="profile[{$key}]" type="text" value="{$item}" class="input_text" />
                {/if}
            </td>
        </tr>
        {/foreach}
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="{t}Abort{/t}"/>
                <input class="ButtonGreen" type="submit" name="submit" value="{t}Edit the profile{/t}" />
                <input class="ButtonGrey" type="reset" value="{t}Reset{/t}" />
            </td>
        </tr>
    </table>

</form>