<h2>Usercenter</h2>

Profil ändern

Profil anzeigen

Gästebuch

Freundes- & Buddyliste

Schnellmenü einrichten

Optionen

.bodyline {boxes.css (Linie 15)
border-color:#CAD1D1;
clear:both;
padding:10px;
}
.bodyline, #modalBox {boxes.css (Linie 9)
background:#E4ECEC url(image/bodyline.png) repeat-x scroll left bottom;
margin-left:15px;
margin-right:15px;
}
fieldset, .bodyline, .box {boxes.css (Linie 4)
border:1px solid #ACB7C4;
margin-bottom:5px;
padding:5px;
}


<div class="bodyline">
    <form onsubmit="return false" id="record" name="record" method="post" action="/trial/modules/base/myaccount.php">
    <input type="hidden" value="" name="command" id="command"/>
    
    <h1><span>My Account</span></h1>

    <fieldset>
        <legend>Name</legend>
        <p id="nameP">Greg McSalesManager</p>
    </fieldset>
    
    <fieldset>
        <legend>Change Password</legend>
        <p>
            <label for="curPass">current password</label><br/>
            <input type="password" maxlength="32" name="curPass" id="curPass"/>
        </p>
        
        <p>
            <label for="newPass">new password</label><br/>
            <input type="password" maxlength="32" name="newPass" id="newPass"/>
        </p>
        <p>
            <label for="confirmPass">re-type new password</label><br/>
            <input type="password" maxlength="32" name="confirmPass" id="confirmPass"/>
        </p>
    </fieldset>
    <p>
        <button onclick="changePass()" class="Buttons" type="button">Change Password</button>
    </p>
    
    <fieldset>
        <legend>Contact Information</legend>

            <p><label for="email">e-mail address</label>
                <br/>
                <input type="text" maxlength="64" size="32" value="gmcsalesmanager@icecactus.com" name="email" id="email"/>
                <button title="Send E-Mail" onclick="openEmail('email')" class="graphicButtons buttonEmail" type="button" id="emailButton">
                <span>send e-mail</span></button>
            </p>
            
            <p><label for="phone">phone/extension</label><br/><input type="text" maxlength="64" size="32" value="" name="phone" id="phone"/></p>

    </fieldset>
    <p><button onclick="changeContact()" class="Buttons" type="button">Update Contact Information</button></p>
    
    <fieldset>
        <legend>Access / Assigned Roles</legend>
        <ul>
        <li>shipping</li><li>sales</li><li>sales manager</li><li>accounts receivable</li></ul>
    </fieldset>
    </form>
</div>