<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:54
         compiled from rssreader%5Cwidget_rssreader.tpl */ ?>

<!-- ## Start: Rssreader Widget from Module Rssreader ## -->

<div class="rss-inside">

        <?php echo '
    <script type="text/javascript">
        jQuery().ready(function(){
            jQuery(\'#accordion\').accordion({
                    autoHeight: false,
                    header: \'h3\',
                    collapsible: true
                });
            });
    </script>
    '; ?>


    <!-- ## Start: RssReader Accordion ## /-->
    <div id="accordion">
        <?php $_from = $this->_tpl_vars['feed']->get_items(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['csRSSForeach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['csRSSForeach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['i']):
        $this->_foreach['csRSSForeach']['iteration']++;
?>

            <h3><a href="#"><?php echo $this->_tpl_vars['i']->get_title(); ?>
</a></h3>
            <div>
                <p>
                <span style="float: right; font-size:10px;"><?php echo $this->_tpl_vars['i']->get_date(); ?>
</span>
                <?php echo $this->_tpl_vars['i']->get_description(); ?>

                <span style="float:right; font-size:10px;"><a href="<?php echo $this->_tpl_vars['i']->get_link(); ?>
" class="more" target="_blank" >[...mehr]</a></span>
                </p>
            </div>

                        <?php if ($this->_foreach['csRSSForeach']['iteration'] == '5'): ?> <?php break; ?> <?php endif; ?>

        <?php endforeach; endif; unset($_from); ?>
    </div>
    <!-- ## End: RssReader Accordion ## /-->

</div>

<!-- ## End: Rssreader Widget Module Template ## -->