<div class="ModuleHeading">{t}About Clansuite{/t}</div>

{move_to target="pre_head_close"}
{* Tabs with jQuery + YAML Accessible Tabs Plugin *}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.tabs.js"></script>
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
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/tabs.css" />
{/move_to}

<div class="tabs">

   {*  /---------------------------------------------------
       |
       |     Tab: Clansuite
       |
       \--------------------------------------------------- *}

<div class="tab-page">
	<h2 class="tab">Clansuite</h2>

	<img style="float:left; margin: 50px 50px 100px 20px;" src="{$www_root_themes_core}images/clansuite_logos/clansuite_clown_mini.png">
	<img style="float:right; margin: 50px 20px 100px 50px;" src="{$www_root}modules/controlcenter/images/osi-certified-72x60-t.png">

	<h2 align="center">
		Clansuite - just an eSport CMS
		<br />
		<span class="about-version">
		Version {$smarty.const.CLANSUITE_VERSION} {$smarty.const.CLANSUITE_VERSION_NAME} {$smarty.const.CLANSUITE_VERSION_STATE}
		- Revision #{$smarty.const.CLANSUITE_REVISION}
		</span>
	</h2>

	<h3 align="center"><br />{$tab1_description}</h3>
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
    <h3 align="center">{$tab2_description}</h3>
    <br />
    <br />

    <table cellspacing="5" cellpadding="2" border="0" width="100%">
    <colgroup><col width="3%" /><col width="17%" /><col width="15%" /><col width="15%" /><col width="20%" /><col width="20%" /><col width="10%" /></colgroup>
     <tbody>
        <tr>
            <td colspan="7" bgcolor="#bbbbbb"><strong>Members of the Development Team</strong></td>
        </tr>
        <tr>
          <td class="about-title"><strong>Status</strong></td>
          <td class="about-title"><strong>Name</strong></td>
          <td class="about-title"><strong>Nickname</strong></td>
          <td class="about-title"><strong>Email</strong></td>
          <td class="about-title"><strong>Position</strong></td>
          <td class="about-title"><strong>Ohloh Stats</strong></td>
          <td class="about-title"><strong>Gifts</strong></td>
        </tr>

		  {foreach item=row from=$developers1}
        <tr>
          <td class="about-entry">
				{if $row.status==1}
				<img src="{$www_root_themes_core}images/lullacons/user-plain-green.png" alt="The User is active.">
				{else}
				<img src="{$www_root_themes_core}images/lullacons/user-plain-red.png" alt="The User is active.">
				{/if}
			</td>
          <td class="about-entry"><b>{$row.name}</b></td>
          <td class="about-entry"><b>{$row.nick}</b></td>
          <td class="about-entry">{if $row.email !=''}{$row.email}{else}&nbsp;{/if}</td>
          <td class="about-entry" valign="top">{if $row.position !=''}{$row.position}{else}&nbsp;{/if}</td>
          <td class="about-entry">
					{if $row.ohloh_url !=''}<a href='{$row.ohloh_url}'><img src="{$row.ohloh_pic}" width="191" height="35" alt="{$row.alternate}" /></a>{else}&nbsp;{/if}
			</td>
          <td class="about-entry">
					{if $row.gift_url !=''}<a href="{$row.gift_url}">{$row.gift_title}</a>{else}&nbsp;{/if}
			</td>
        </tr>
		  {/foreach}


        {* Former Members of the Development Team *}
        <tr>
            <td colspan="7" bgcolor="#bbbbbb"><strong>Former Members</strong></td>
        </tr>
        <tr>
          <td class="about-title"><strong>Status</strong></td>
          <td class="about-title"><strong>Name</strong></td>
          <td class="about-title"><strong>Nickname</strong></td>
          <td class="about-title"><strong>Email</strong></td>
          <td class="about-title"><strong>Position</strong></td>
          <td class="about-title"><strong>Ohloh Stats</strong></td>
          <td class="about-title"><strong>Gifts</strong></td>
        </tr>

		  {foreach item=row from=$developers2}
        <tr>
          <td class="about-entry">
				{if $row.status==1}
				<img src="{$www_root_themes_core}images/lullacons/user-plain-green.png" alt="The User is active.">
				{else}
				<img src="{$www_root_themes_core}images/lullacons/user-plain-red.png" alt="The User is active.">
				{/if}
			</td>
          <td class="about-entry"><b>{$row.name}</b></td>
          <td class="about-entry"><b>{$row.nick}</b></td>
          <td class="about-entry">{if $row.email !=''}{$row.email}{else}&nbsp;{/if}</td>
          <td class="about-entry" valign="top">{if $row.position !=''}{$row.position}{else}&nbsp;{/if}</td>
          <td class="about-entry">
					{if $row.ohloh_url !=''}<a href='{$row.ohloh_url}'><img src="{$row.ohloh_pic}" width="191" height="35" alt="{$row.alternate}" /></a>{else}&nbsp;{/if}
			</td>
          <td class="about-entry">
					{if $row.gift_url !=''}<a href="{$row.gift_url}">{$row.gift_title}</a>{else}&nbsp;{/if}
			</td>
        </tr>
		  {/foreach}

	 </tbody>
    </table>

   <br />
   <h3 align="center"><a href=" http://www.ohloh.net/p/clansuite/contributors">You might visit Ohloh for the complete list of Contributors (based on SVN Commits).</a></h3>

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
		<br />
		For GPL-License compatibility issues, visit <a href="http://www.gnu.org/philosophy/license-list.html#GPLCompatibleLicenses">GNU/GPL Compat License List</a>.
		<br />
    </h3>

    <br />

    <table cellspacing="5" cellpadding="2" border="0" width="100%">
       <colgroup><col width="20%" /><col width="20%" /><col width="20%" /><col width="20%" /><col width="20%" /></colgroup>
        <tbody>
        <tr>
          <td colspan="5"><h2>{t}Clansuite{/t}</h2></td>
        </tr>
        <tr>
          <td colspan="5"><h2>{t}PHP{/t}</h2></td>
        </tr>
        <tr>
          <td class="about-title"><strong>Library</strong></td>
          <td class="about-title"><strong>Author</strong></td>
          <td class="about-title"><strong>Abbreviation</strong></td>
          <td class="about-title"><strong>License</strong></td>
          <td class="about-title"><strong>Link to Licensefile in SVN</strong></td>
        </tr>
        <tr>
          <td class="about-entry"><a href="http://www.doctrine-project.org/">Doctrine</a></td>
          <td class="about-entry">Doctrine Development Team</td>
          <td class="about-entry">LGPL</td>
          <td class="about-entry">Lesser General Public License</td>
          <td class="about-entry"></td>
        </tr>

        <tr><td colspan="5" style="height:30px;"></td></tr>

        <tr>
          <td colspan="5"><h2>{t}Javascript{/t}</h2></td>
        </tr>
        <tr>
          <td class="about-title"><strong>Library</strong></td>
          <td class="about-title"><strong>Author</strong></td>
          <td class="about-title"><strong>Abbreviation</strong></td>
          <td class="about-title"><strong>License</strong></td>
          <td class="about-title"><strong>Link to Licensefile in SVN</strong></td>
        </tr>

        <tr><td colspan="5" style="height:30px;"></td></tr>

         <tr>
          <td colspan="5"><h2>{t}Images and Icon Packs{/t}</h2></td>
        </tr>
        <tr>
          <td class="about-title"><strong>Name</strong></td>
          <td class="about-title"><strong>Author</strong></td>
          <td class="about-title"><strong>Abbreviation</strong></td>
          <td class="about-title"><strong>License</strong></td>
          <td class="about-title"><strong>Link to Licensefile in SVN</strong></td>
        </tr>
        <tr>
          <td class="about-entry">Crystal Project Icon Theme</td>
          <td class="about-entry"><a href="http://www.everaldo.com/">Everaldo</a></td>
          <td class="about-entry"></td>
          <td class="about-entry"></td>
          <td class="about-entry"></td>
        </tr>
        <tr>
          <td class="about-entry">Country Icons</td>
          <td class="about-entry"><a href="http://www.famfamfam.com/">FAMFAMFAM</a></td>
          <td class="about-entry"></td>
          <td class="about-entry"></td>
          <td class="about-entry"></td>
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

    <table cellspacing="5" cellpadding="2" border="0" width="100%">
    <colgroup><col width="60%" /><col width="40%" /></colgroup>
     <tbody>
        <tr>
            <td><h2>{t}Thanks for your support!{/t}</h2></td>
				<td align="center"><h3>{t}Current Donation Campaign{/t}</h3></td>
        </tr>
        <tr>
            <td class="about-sponsor">
				{t}We are passionate open-source programmers, but passion alone doesn't make software.{/t}
				{t}All donations are appreciated, no matter if they are big or small, and thanks for your support.{/t}
            </td>
            <td align="center">
                <br />
                <a href='http://www.pledgie.com/campaigns/6324' target='_new'>Unterst&uuml;tzt Clansuite!</a>
                <br /><br />
                <a href='http://www.pledgie.com/campaigns/6324'><img alt='Click here to lend your support to: Unterst�tzt Clansuite! and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6324.png?skin_name=chrome' border='0' /></a>
                <br />
            </td>
        </tr>
     </tbody>
    </table>

   <h2>{t}Our Sponsors{/t}</h2>
    <table cellspacing="5" cellpadding="2" border="0" width="100%">
    <colgroup><col width="30%" /><col width="70%" /></colgroup>
     <tbody>
        <tr>
          <td class="about-title"><strong>Name</strong></td>
          <td class="about-title"><strong>{t}Donation{/t}</strong></td>
        </tr>
        <tr>
          <td class="about-entry">Name</td>
          <td class="about-entry">Paypal</td>
        </tr>
        <tr>
          <td class="about-entry">Name</td>
          <td class="about-entry">Money</td>
        </tr>
        <tr>
          <td class="about-entry">Name</td>
          <td class="about-entry">Amazon Wishlist</td>
        </tr>
        </tbody>
       </table>
   </div>
</div>