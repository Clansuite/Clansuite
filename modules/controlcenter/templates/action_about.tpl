<div class="ModuleHeading">{t}About Clansuite{/t}</div>
<!-- <div class="ModuleHeadingSmall">{t}About - Credits.{/t}</div> -->

{move_to target="pre_head_close"}
{* Tabs with jQuery + YAML Accessible Tabs Plugin *}
<script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.tabs.js"></script>

{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $(".tabs").accessibleTabs({
                                    fx:"fadeIn",
                                    tabbody:'.tab-page',
                                    tabhead: '.tab',
                                    currentInfoText: '&raquo; ',
                                    currentInfoPosition: 'prepend',
                                    currentInfoClass: 'current-info'
                                  });
    });
</script>
{/literal}

<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/tabs.css" />
{/move_to}

<div class="tabs">

   {*  /---------------------------------------------------
       |
       |     Tab: Clansuite
       |
       \--------------------------------------------------- *}
       
   <div class="tab-page">
   <h2 class="tab">Clansuite</h2>

    <img style="float:left; margin: 10px 0px 15px 1%;" src="{$www_root_themes_core}/images/clansuite_logos/clansuite_clown_mini.png">

    <img style="float:right; margin: 10px 0px 15px;" src="{$www_root_mod}/images/osi-certified-72x60-t.png">


    <h2 align="center">

        Clansuite - just an eSport CMS
         <br />
         <font size=1>
         Version {$smarty.const.CLANSUITE_VERSION} {$smarty.const.CLANSUITE_VERSION_NAME} {$smarty.const.CLANSUITE_VERSION_STATE}
       - Revision #{$smarty.const.CLANSUITE_REVISION}
        </font>
    </h2>

    <h3 align="center">
        <br />
        "Clansuite - just an eSport CMS" is an open source Content Management System and Framework!
        <br />
        Clansuite was initially created by Jens-Andr&#233; Koch and is licensed under GNU/GPLv2 and any later license.
        <br />
        Clansuite is copyright 2005-{$smarty.now|date_format:'%Y'} of Jens-Andr&#233; Koch.
        Extensions are copyright of their respective owners.
        <br />
        <br />
         "Clansuite - just an eSport CMS" ist OSI Certified Open Source Software.
        OSI Certified is a certification mark of the <a href="http://www.opensource.org/">Open Source Initiative</a>.
       </h3>
    </div>

    {* /---------------------------------------------------
       |
       |     Tab: Clansuite Development Team
       |
       \--------------------------------------------------- *}
       
    <div class="tab-page">
    <h2 class="tab">{t}Developers{/t}</h2>

    <br />
    <h2 align="center">Clansuite Development Team</h2>
    <br />
    <h3 align="center">{t}Thanks to everyone who tested, reported bugs, made suggestions and contributed to this project. ^ _ ^ {/t}
                      <br />
                      {t}Send bugreports, fixes, enhancements, t-shirts, money, beer & pizza to ...{/t}</h3>                      
    <br />
    <br />
                      
    <table cellspacing="5" cellpadding="2" border="0" width="100%">
     <tbody><tr>
          <td bgcolor="#dddddd"><strong>Name</strong></td>
          <td bgcolor="#dddddd"><strong>Nickname</strong></td>
          <td bgcolor="#dddddd"><strong>Email</strong></td>
          <td bgcolor="#dddddd"><strong>Position</strong></td>
          <td bgcolor="#dddddd"><strong>Gifts</strong></td>
        </tr>
        <tr class="user_active">
          <td><b>Pasqual Eusterfeldhaus</b></td>
          <td valign="top"><b>thunderm00n</b></td>
          <td valign="top"><b>thundermoon@gna.org</b></td>
          <td valign="top">Graphics, Forum-Support & Moderation, Beta-Testing</td>
          <td/>
        </tr>
        <tr class="user_active">
          <td valign="top"><b>Tino Goratsch</b></td>
           <td valign="top"><b>Vyper</b></td>
           <td valign="top"><b>vyper@gna.org</b></td>
           <td valign="top">Developer, Website, Themes (especially Accessible-Theme)</td>
           <td/>
        </tr>
        <tr class="user_active">
          <td valign="top"><b>Jens-Andr&#233; Koch</b></td>
          <td valign="top"><b>vain</b></td>
          <td valign="top"><b>vain@clansuite.com</b></td>
          <td valign="top">Clansuite Project Founder & Maintainer, Benevolent Dictator for Life <br /> Developer, Website, Toolbar</td>
          <td><a href="http://www.amazon.de/gp/registry/registry.html?ie=UTF8&type=wishlist&id=2TN4SKVI467SX">{t}Amazon Wishlist{/t}</a></td>
        </tr>
        <tr class="user_inactive">
          <td valign="top"><b>Ren&#233; Stalder</b></td>
          <td valign="top"><b>nachtmeister</b></td>
          <td valign="top"><b>nachtmeister@6pounder.com</b></td>
          <td valign="top"></td>
          <td/>
        </tr>
         <tr class="user_inactive">
          <td valign="top"><b>Björn Sp.</b></td>
          <td valign="top"><b>freq77</b></td>
          <td valign="top"><b>---</b></td>
          <td valign="top">Developer (Shoutbox)</td>
          <td/>
        </tr>
        <tr class="user_inactive">
          <td valign="top"><b>Daniel Winterfeldt</b></td>
          <td valign="top"><b>rikku</b></td>
          <td valign="top"><b>rikku@gna.org</b></td>
          <td valign="top">Developer (Image-Processing-Library)</td>
          <td/>
        </tr>
        <tr class="user_inactive">
          <td valign="top"><b>Florian Wolf</b></td>
          <td valign="top"><b>xsign.dll</b></td>
          <td valign="top"><b>xsign.dll@clansuite.com</b></td>
          <td valign="top">Developer, Serveradminstrator, Javascripts and AJAX<br /> Developer of Clansuite Core v0.1</td>
          <td></td>
        </tr>
        <tr class="user_inactive">
          <td valign="top"><b>Pascal</b></td>
          <td valign="top"><b>raensen</b></td>
          <td valign="top"><b>---</b></td>
          <td valign="top"></td>
          <td/>
        </tr>
        <tr class="user_inactive">
          <td valign="top"><b>Niklas Karoly</b></td>
          <td valign="top"><b>creep7</b></td>
          <td valign="top"><b>---</b></td>
          <td valign="top"></td>
          <td/>
        </tr>
    </tbody>
    </table>
   </div>

   {*  /---------------------------------------------------
       |
       |     Tab: Components
       |
       \--------------------------------------------------- *}
    <div class="tab-page">
    <h2 class="tab">Components</h2>
    
    <h2 align="center">{t}Components{/t}</h2>
    <br />

    <h2 align="center">Clansuite</h2>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
     <tbody>
        <tr>
          <td bgcolor="#dddddd"><strong>Product</strong></td>
          <td bgcolor="#dddddd"><strong>Version</strong></td>
          <td bgcolor="#dddddd"><strong>Entwickler</strong></td>
          <td bgcolor="#dddddd"><strong>Link</strong></td>
        </tr>
        <tr>
          <td>Clansuite Core</td>
          <td>0.1</td>
          <td><a href="mailto:vain@clansuite.com">Jens-Andr&#233; Koch (vain)</a> & <a href="mailto:xsigndll@clansuite.com">Florian Wolf (xsign.dll)</a></td>
          <td><a target="_blank" href="http://www.clansuite.com/">http://www.clansuite.com</a></td>
        </tr>
        <tr>
          <td>Clansuite Core / Framework</td>
          <td>0.2</td>
          <td><a href="mailto:vain@clansuite.com">Jens-Andr&#233; Koch (vain)</a></td>
          <td></td>
        </tr>
        </tbody>
    </table>

    <br />

    <h2 align="center">Modules</h2>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <tbody><tr>
          <td bgcolor="#dddddd"><strong>{t}Module{/t}</strong></td>
          <td bgcolor="#dddddd"><strong>{t}Entwickler{/t}</strong></td>
          <td bgcolor="#dddddd"><strong>{t}Link to Module-Source{/t}</strong></td>
          <td bgcolor="#dddddd"><strong>{t}Link to Module-Project{/t}</strong></td>
        </tr>
        <tr>
          <td>News</td>
          <td>vain, xsign</td>
          <td><a target="_blank" href="http://trac.clansuite.com/browser/trunk/modules/news">Link</a></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        </tbody>
    </table>

    <br />

    <h2 align="center">{t}Themes{/t}</h2>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
    <tbody><tr>
      <td bgcolor="#dddddd" width="26%"><strong>Theme</strong></td>
      <td bgcolor="#dddddd" width="16%"><strong>Version</strong></td>
      <td bgcolor="#dddddd" width="52%"><strong>Entwickler</strong></td>
      <td bgcolor="#dddddd" width="13%"><strong>Link</strong></td>
    </tr>
    <tr>
      <td>Standard</td>
      <td>1.0</td>
      <td>vain</td>
      <td></td>
      <td/>
    </tr>
     <tr>
      <td>Accessible</td>
      <td>1.0</td>
      <td>vyper</td>
      <td></td>
      <td/>
    </tr>
    </tbody>
    </table>

    </div>

   {*  /---------------------------------------------------
       |
       |     Tab: Lizenzen
       |
       \--------------------------------------------------- *}
    <div class="tab-page">
    <h2 class="tab">{t}Licenses{/t}</h2>

    <h2 align="center">{t}Licenses & 3th Party Libraries{/t}</h2>


        <h3 align="center">
        <br />
        This is an overview of all 3th party projects and libraries used by Clansuite and their licenses.
        <br />
        For a license compatibility check, visit <a href="http://www.ohloh.net/p/clansuite/analyses/latest">Clansuite at Ohloh</a>.
        For GPL-License compatibility issues, visit <a href="http://www.gnu.org/philosophy/license-list.html#GPLCompatibleLicenses">GNU/GPL Compat License List</a>.
        <br />
       </h3>

       <br />

       <table border="0" width="100%">
        <tbody>
        <tr>
          <td colspan="4"><h2>{t}PHP{/t}</h2></td>
        </tr>
        <tr>
          <td bgcolor="#dddddd" width="14%"><strong>Library</strong></td>
          <td bgcolor="#dddddd"><strong>Author</strong></td>
          <td bgcolor="#dddddd" width="14%"><strong>Abbreviation</strong></td>
          <td bgcolor="#dddddd"><strong>License</strong></td>
          <td bgcolor="#dddddd"><strong>Link to Licensefile in SVN</strong></td>
        </tr>
        <tr>
          <td><a href="http://www.doctrine-project.org/">Doctrine</a></td>
          <td>Doctrine Development Team</td>
          <td>LGPL</td>
          <td>Lesser General Public License</td>
          <td></td>
        </tr>

        <tr>
          <td colspan="4"><h2>{t}Javascript{/t}</h2></td>
        </tr>
        <tr>
          <td bgcolor="#dddddd" width="14%"><strong>Library</strong></td>
          <td bgcolor="#dddddd"><strong>Author</strong></td>
          <td bgcolor="#dddddd" width="14%"><strong>Abbreviation</strong></td>
          <td bgcolor="#dddddd"><strong>License</strong></td>
          <td bgcolor="#dddddd"><strong>Link to Licensefile in SVN</strong></td>
        </tr>
        <tr>
          <td></td>
        </tr>

         <tr>
          <td colspan="4"><h2>{t}Images and Icon Packs{/t}</h2></td>
        </tr>
        <tr>
          <td bgcolor="#dddddd" width="14%"><strong>Name</strong></td>
          <td bgcolor="#dddddd"><strong>Author</strong></td>
          <td bgcolor="#dddddd" width="14%"><strong>Abbreviation</strong></td>
          <td bgcolor="#dddddd"><strong>License</strong></td>
          <td bgcolor="#dddddd"><strong>Link to Licensefile in SVN</strong></td>
        </tr>
        <tr>
          <td>Crystal Project Icon Theme</td>
          <td><a href="http://www.everaldo.com/">Everaldo</a></td>
          <td/>
          <td/>
          <td></td>
        </tr>
        <tr>
          <td>Country Icons</td>
          <td><a href="http://www.famfamfam.com/">FAMFAMFAM</a></td>
          <td/>
          <td></td>
          <td></td>
        </tr>

        </tbody>
        </table>
   </div>

   {*  /---------------------------------------------------
       |
       |     Tab: Sponsors
       |
       \--------------------------------------------------- *}
    <div class="tab-page">
    <h2 class="tab">{t}Sponsors{/t}</h2>

    <h2 align="center">{t}Sponsors{/t}</h2>
    <table border="0" width="100%">
     <tbody>
        <tr>
            <td><h2>{t}Thanks for your support!{/t}</h2></td><td><h2>{t}Current Donation Campaign{/t}</h2></td>
        </tr>
        <tr>
            <td>{t}We are passionate open-source programmers, but passion alone doesnï¿½t make software.{/t}
                <br />
                {t}All donations are appreciated, no matter if they are big or small, and thanks for your support.{/t}
            </td>
            <td>
                <br />
                <a href='http://www.pledgie.com/campaigns/6324' target='_new'>Unterst&uuml;tzt Clansuite!</a>
                <br /><br />
                <a href='http://www.pledgie.com/campaigns/6324'><img alt='Click here to lend your support to: Unterstï¿½tzt Clansuite! and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6324.png?skin_name=chrome' border='0' /></a>
                <br />
            </td>
        </tr>
     </tbody>
    </table>

   <h2>{t}Our Sponsors{/t}</h2>
   <table border="0" width="100%">
     <tbody>
        <tr>
          <td bgcolor="#dddddd"><strong>Name</strong></td>
          <td bgcolor="#dddddd"><strong>{t}Donation{/t}</strong></td>
        </tr>
        <tr>
          <td>Name</td>
          <td>Paypal</td>
        </tr>
        <tr>
          <td>Name</td>
          <td>Money</td>
        </tr>
        <tr>
          <td>Name</td>
          <td>Amazon Wishlist</td>
        </tr>
        </tbody>
       </table>
   </div>
</div>