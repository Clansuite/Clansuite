<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from shockvoiceviewer%5Ctemplates%5Cwidget_shockvoiceviewer.tpl */ ?>

<!-- [Start] Widget: Shockvoice Viewer -->
<div class="td_header">Shockvoice Viewer</div>

<div class="cell1">

<?php if (isset ( $this->_tpl_vars['serverinfos'] ) && $this->_tpl_vars['serverinfos']['request_ok'] == true): ?>

    <div style="float:right; clear:both;"><a href="svlink::<?php echo $this->_tpl_vars['serverinfos']['servername']; ?>
:<?php echo $this->_tpl_vars['serverinfos']['port']; ?>
">Connect</a></div>

    <?php echo $this->_tpl_vars['serverinfos']['servername']; ?>
 - Shockvoice
        <br />

    <?php $_from = $this->_tpl_vars['serverinfos']['channels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['channel'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['channel']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['channel']):
        $this->_foreach['channel']['iteration']++;
?>

        <?php if (! empty ( $this->_tpl_vars['channel']['name'] )): ?>
            <?php echo $this->_tpl_vars['channel']['image']; ?>
 <?php echo $this->_tpl_vars['channel']['name']; ?>
 <br />
        <?php endif; ?>


        <?php if (! empty ( $this->_tpl_vars['serverinfos']['users'] )): ?>
            <?php $_from = $this->_tpl_vars['serverinfos']['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['user'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['user']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['user']):
        $this->_foreach['user']['iteration']++;
?>

            <?php if (isset ( $this->_tpl_vars['user']['channelid'] ) && isset ( $this->_tpl_vars['channel']['id'] ) && ( $this->_tpl_vars['user']['channelid'] == $this->_tpl_vars['channel']['id'] )): ?>
                <?php echo $this->_tpl_vars['user']['name']; ?>

            <?php endif; ?>

            <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>

        <?php if (! empty ( $this->_tpl_vars['channel']['subchannels'] )): ?>
            <?php $_from = $this->_tpl_vars['channel']['subchannels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subchan']):
?>

                &nbsp;
                <?php if (! empty ( $this->_tpl_vars['subchan']['name'] )): ?>
                    <?php echo $this->_tpl_vars['subchan']['image']; ?>
 <?php echo $this->_tpl_vars['subchan']['name']; ?>

                    <br/>

                    <?php if (! empty ( $this->_tpl_vars['serverinfos']['users'] )): ?>
                        <?php $_from = $this->_tpl_vars['serverinfos']['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['user'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['user']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['user']):
        $this->_foreach['user']['iteration']++;
?>

                        <?php if (isset ( $this->_tpl_vars['user']['channelid'] ) && isset ( $this->_tpl_vars['channel']['id'] ) && ( $this->_tpl_vars['user']['channelid'] == $this->_tpl_vars['subchan']['id'] )): ?>
                            &nbsp; &nbsp; <?php echo $this->_tpl_vars['user']['image']; ?>
 <?php echo $this->_tpl_vars['user']['name']; ?>

                            <br/>
                        <?php endif; ?>

                        <?php endforeach; endif; unset($_from); ?>

                    <?php endif; ?>

                <?php endif; ?>

            <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>

    <?php endforeach; endif; unset($_from); ?>

<?php else: ?>

        <br />
    <span style="color: red; font-weight: bold;">Server offline</span>

<?php endif; ?>

</div>
<!-- [-End-] Widget: Shockvoice Viewer -->