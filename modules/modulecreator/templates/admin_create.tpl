<div class="td_header_small">Frontend <span style="color: #cc0000;">/modules/{$m.module_name}/{$m.module_name}.class.php</span></div>
<div style="height: 200px; overflow: auto;" class="cell2">
    {$frontend}
</div>
<div class="td_header_small">Backend <span style="color: #cc0000;">/modules/{$m.module_name}/{$m.module_name}.admin.php</span></div>
<div style="height: 200px; overflow: auto;" class="cell2">
    {$backend}
</div>
<div class="td_header_small">Configuration <span style="color: #cc0000;">/modules/{$m.module_name}/{$m.module_name}.config.php</span></div>
<div style="height: 200px; overflow: auto;" class="cell2">
    {$config}
</div>
{$m|@var_dump}