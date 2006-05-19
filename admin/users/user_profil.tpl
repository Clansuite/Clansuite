<h2><b>User-Profil</b></h2>

<form name="formular" action="index.php?action=update_profil" method="post">
<input type="hidden" name="userid" value="{parent.userinfo.user_id}">

        <label for="Login-Name">Login-Name:</label>
        <input size="25" type="text" name="Login-Name" id="Login-Name" value="{parent.userinfo.nick}">

		<label for="Login-Name"><b>Password:</b></label>
		<input size="35" type=text name="password" id="password" value="{parent.userinfo.password}">

		<label for="nick">Nick:</label>
		<input type=text name="nick" value="{parent.userinfo.nick}"> 
        
        <label for="nick">EMail:</label>
  		<input type=text name="email" value="{parent.userinfo.email}"> 

  		<label for="first_name">Firstname:</label
  		<input type=text name="first_name" value="{parent.userinfo.first_name}">  

  		<label for="last_name">Lastname:</label>
  		<input type=text name="last_name" value="{parent.userinfo.last_name}">
        
		<div class="label">Infotext:</div>
        <textarea cols="50" rows="4" name="infotext" id="infotext">{parent.userinfo.infotext}</textarea>
        
        <label for="page">Homepage:</label>
		<input type=text name="page" value="{parent.userinfo.homepage}">

		<label for="page">Status (aktiv|inaktiv) :</label>
		<select name="activ"><option value=1 selected>aktiv</option><option value=0>inaktiv</option></select>	
  		
		<label for="icq">ICQ:</label>
		<input type=text name="icq" value="{parent.userinfo.icq}">
  		
		<label for="avatar">Avatar:</label>
		<input type=text name="avatar" value="{parent.userinfo.avatar}">
  		
		<label for="first_name">Bild:</label>
		<input type=text name="bild" value="{parent.userinfo.bild}">

		<div class="label">Signatur:</div>
		<textarea cols="80" rows="5" name="signatur" id="signatur">{parent.userinfo.signatur}</textarea>
        
        <label for="first_name">Wohnort:</label>
		<input type=text name="town" value="{parent.userinfo.town}">

		<label for="bday">Geburtstag:</label>
		<input type=text name="bday" value="{parent.userinfo.bday}">

        <label for="sex">Geschlecht:</label>
		<select name="sex">
		<option value="0">keine Angabe</option>
		<option value="1">m&auml;nnlich</option>
		<option value="2">weiblich</option></select>
      
	  <label for="state">Geschlecht:</label>Staatsangehörigkeit:
      <select name="state">
        <option value="">nichts &auml;ndern?
        <option value="">-------------------------------
        <option value="al">Albania
        <option value="dz">Algeria
        <option value="as">American Samoa
        <option value="ad">Andorra
        <option value="ao">Angola
        <option value="ai">Anguilla
        <option value="aq">Antarctica
        <option value="ag">Antigua And Barbuda
        <option value="ar">Argentina
        <option value="am">Armenia
        <option value="aw">Aruba
        <option value="au">Australia
        <option value="at">Austria
        <option value="az">Azerbaijan
        <option value="bs">Bahamas
        <option value="bh">Bahrain
        <option value="bd">Bangladesh
        <option value="bb">Barbados
        <option value="by">Belarus
        <option value="be">Belgium
        <option value="bz">Belize
        <option value="bj">Benin
        <option value="bm">Bermuda
        <option value="bt">Bhutan
        <option value="bo">Bolivia
        <option value="ba">Bosnia And Herzegovina
        <option value="bw">Botswana
        <option value="bv">Bouvet Island
        <option value="br">Brazil
        <option value="io">British Indian Ocean Territory
        <option value="bn">Brunei Darussalam
        <option value="bg">Bulgaria
        <option value="bf">Burkina Faso
        <option value="bi">Burundi
        <option value="kh">Cambodia
        <option value="cm">Cameroon
        <option value="ca">Canada
        <option value="cv">Cape Verde
        <option value="ky">Cayman Islands
        <option value="cf">Central African Republic
        <option value="td">Chad
        <option value="cl">Chile
        <option value="cn">China
        <option value="cx">Christmas Island
        <option value="cc">Cocos (Keeling) Islands
        <option value="co">Colombia
        <option value="km">Comoros
        <option value="cg">Congo
        <option value="cd">Congo, The Democratic Republic Of The
        <option value="ck">Cook Islands
        <option value="cr">Costa Rica
        <option value="ci">Cote D'Ivoire
        <option value="hr">Croatia
        <option value="cu">Cuba
        <option value="cy">Cyprus
        <option value="cz">Czech Republic
        <option value="dk">Denmark
        <option value="dj">Djibouti
        <option value="dm">Dominica
        <option value="do">Dominican Republic
        <option value="tp">East Timor
        <option value="ec">Ecuador
        <option value="eg">Egypt
        <option value="sv">El Salvador
        <option value="ex">England
        <option value="gq">Equatorial Guinea
        <option value="er">Eritrea
        <option value="ee">Estonia
        <option value="et">Ethiopia
        <option value="fk">Falkland Islands (Malvinas)
        <option value="fo">Faroe Islands
        <option value="fj">Fiji
        <option value="fi">Finland
        <option value="fr">France
        <option value="gf">French Guiana
        <option value="pf">French Polynesia
        <option value="tf">French Southern Territories
        <option value="ga">Gabon
        <option value="gm">Gambia
        <option value="ge">Georgia
        <option value="de">Germany
        <option value="gh">Ghana
        <option value="gi">Gibraltar
        <option value="gr">Greece
        <option value="gl">Greenland
        <option value="gd">Grenada
        <option value="gp">Guadeloupe
        <option value="gu">Guam
        <option value="gt">Guatemala
        <option value="gn">Guinea
        <option value="gw">Guinea-Bissau
        <option value="gy">Guyana
        <option value="ht">Haiti
        <option value="hm">Heard Island And Mcdonald Islands
        <option value="va">Holy See (Vatican City State)
        <option value="hn">Honduras
        <option value="hk">Hong Kong
        <option value="hu">Hungary
        <option value="is">Iceland
        <option value="in">India
        <option value="id">Indonesia
        <option value="ir">Iran
        <option value="iq">Iraq
        <option value="ie">Ireland
        <option value="il">Israel
        <option value="it">Italy
        <option value="jm">Jamaica
        <option value="jp">Japan
        <option value="jo">Jordan
        <option value="kz">Kazakstan
        <option value="ke">Kenya
        <option value="ki">Kiribati
        <option value="kp">Korea, Democratic People'S Republic Of
        <option value="kr">Korea, Republic Of
        <option value="kw">Kuwait
        <option value="kg">Kyrgyzstan
        <option value="la">Lao People'S Democratic Republic
        <option value="lv">Latvia
        <option value="lb">Lebanon
        <option value="ls">Lesotho
        <option value="lr">Liberia
        <option value="ly">Libyan Arab Jamahiriya
        <option value="li">Liechtenstein
        <option value="lt">Lithuania
        <option value="lu">Luxembourg
        <option value="mo">Macau
        <option value="mk">Macedonia
        <option value="mg">Madagascar
        <option value="mw">Malawi
        <option value="my">Malaysia
        <option value="mv">Maldives
        <option value="ml">Mali
        <option value="mt">Malta
        <option value="mh">Marshall Islands
        <option value="mq">Martinique
        <option value="mr">Mauritania
        <option value="mu">Mauritius
        <option value="yt">Mayotte
        <option value="mx">Mexico
        <option value="fm">Micronesia, Federated States Of
        <option value="md">Moldova, Republic Of
        <option value="mc">Monaco
        <option value="mn">Mongolia
        <option value="ms">Montserrat
        <option value="ma">Morocco
        <option value="mz">Mozambique
        <option value="mm">Myanmar
        <option value="na">Namibia
        <option value="nr">Nauru
        <option value="np">Nepal
        <option value="nl">Netherlands
        <option value="an">Netherlands Antilles
        <option value="nc">New Caledonia
        <option value="nz">New Zealand
        <option value="ni">Nicaragua
        <option value="ne">Niger
        <option value="ng">Nigeria
        <option value="nu">Niue
        <option value="nf">Norfolk Island
        <option value="nx">Northern Ireland
        <option value="mp">Northern Mariana Islands
        <option value="no">Norway
        <option value="om">Oman
        <option value="pk">Pakistan
        <option value="pw">Palau
        <option value="ps">Palestinian Territory, Occupied
        <option value="pa">Panama
        <option value="pg">Papua New Guinea
        <option value="py">Paraguay
        <option value="pe">Peru
        <option value="ph">Philippines
        <option value="pn">Pitcairn
        <option value="pl">Poland
        <option value="pt">Portugal
        <option value="pr">Puerto Rico
        <option value="qa">Qatar
        <option value="re">Reunion
        <option value="ro">Romania
        <option value="ru">Russian Federation
        <option value="rw">Rwanda
        <option value="sh">Saint Helena
        <option value="kn">Saint Kitts And Nevis
        <option value="lc">Saint Lucia
        <option value="pm">Saint Pierre And Miquelon
        <option value="vc">Saint Vincent And The Grenadines
        <option value="ws">Samoa
        <option value="sm">San Marino
        <option value="st">Sao Tome And Principe
        <option value="sa">Saudi Arabia
        <option value="sx">Scotland
        <option value="sn">Senegal
        <option value="sc">Seychelles
        <option value="sl">Sierra Leone
        <option value="sg">Singapore
        <option value="sk">Slovakia
        <option value="si">Slovenia
        <option value="sb">Solomon Islands
        <option value="so">Somalia
        <option value="za">South Africa
        <option value="gs">South Georgia And The South Sandwich Islands
        <option value="es">Spain
        <option value="lk">Sri Lanka
        <option value="sd">Sudan
        <option value="sr">Suriname
        <option value="sj">Svalbard And Jan Mayen
        <option value="sz">Swaziland
        <option value="se">Sweden
        <option value="ch">Switzerland
        <option value="sy">Syrian Arab Republic
        <option value="tw">Taiwan
        <option value="tj">Tajikistan
        <option value="tz">Tanzania, United Republic Of
        <option value="th">Thailand
        <option value="tg">Togo
        <option value="tk">Tokelau
        <option value="to">Tonga
        <option value="tt">Trinidad And Tobago
        <option value="tn">Tunisia
        <option value="tr">Turkey
        <option value="tm">Turkmenistan
        <option value="tc">Turks And Caicos Islands
        <option value="tv">Tuvalu
        <option value="ug">Uganda
        <option value="ua">Ukraine
        <option value="ae">United Arab Emirates
        <option value="uk">United Kingdom
        <option value="us">United States
        <option value="um">United States Minor Outlying Islands
        <option value="uy">Uruguay
        <option value="uz">Uzbekistan
        <option value="vu">Vanuatu
        <option value="ve">Venezuela
        <option value="vn">Viet Nam
        <option value="vg">Virgin Islands, British
        <option value="vi">Virgin Islands, U.S.
        <option value="wx">Wales
        <option value="wf">Wallis And Futuna
        <option value="eh">Western Sahara
        <option value="ye">Yemen
        <option value="yu">Yugoslavia
        <option value="zm">Zambia
        <option value="zw">Zimbabwe
	</select>
      
        <div class="submit">
            <input type=submit name="submit" value="User-Profil sichern">
			<input type="button" value="Cancel" onclick="location='index.php'">
        </div>
</form>