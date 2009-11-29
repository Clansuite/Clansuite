<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from teamspeakviewer%5Ctemplates%5Cwidget_tsministatus.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'formatseconds', 'teamspeakviewer\\templates\\widget_tsministatus.tpl', 25, false),)), $this); ?>

<!-- [Start] Widget: Teamspeak Ministatus -->
<div class="td_header">Teamspeak Online Check</div>

<div class="cell1">

    <?php if ($this->_tpl_vars['serverinfo']['request_ok'] == true): ?>
    
        <div style="float:right; clear:both;">
        <a href="teamspeak://<?php echo $this->_tpl_vars['serverinfo']['server_address']; ?>
:<?php echo $this->_tpl_vars['serverinfo']['server_tcpport']; ?>
?nickname=<?php echo $this->_tpl_vars['serverinfo']['guest_nickname']; ?>
?password=<?php echo $this->_tpl_vars['serverinfo']['server_password']; ?>
" class="mainlevel">
        Connect
        </a>
        </div>

        <?php echo $this->_tpl_vars['serverinfo']['server_name']; ?>

        <br /><br />
        Status: <span style="color: green; font-weight: bold;">online</span>
        <br /><br />

        <table cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap="nowrap">Uptime:</td>
          <td width="5" />
          <td nowrap="nowrap"><?php echo ((is_array($_tmp=$this->_tpl_vars['serverinfo']['server_uptime'])) ? $this->_run_mod_handler('formatseconds', true, $_tmp) : smarty_modifier_formatseconds($_tmp)); ?>
</td>
        </tr>
                <tr>
          <td nowrap="nowrap">Users:</td>
          <td width="5" />
          <td nowrap="nowrap"><?php echo $this->_tpl_vars['serverinfo']['server_currentusers']; ?>
 / <?php echo $this->_tpl_vars['serverinfo']['server_maxusers']; ?>
</td>
        </tr>
        <tr>
          <td nowrap="nowrap">Channels:</td>
          <td width="5" />
          <td nowrap="nowrap"><?php echo $this->_tpl_vars['serverinfo']['server_currentchannels']; ?>
</td>
        </tr>
        </table>

        <br />
        <style type="text/css">
        <!--
            a.mainlevel
            <?php echo '{'; ?>

                background:transparent url(../images/default/play.png) no-repeat scroll 0 0;
                display:block;
                height:16px;
                line-height:15px;
                margin-bottom:3px;
                padding:0 0 0 20px;
            <?php echo '}'; ?>

        -->
        </style>
       
    <?php else: ?>

                <br />
        <span style="color: red; font-weight: bold;">Server offline</span>

    <?php endif; ?>

</div>
<!-- [-End-] Widget: Teamspeak Ministatus -->