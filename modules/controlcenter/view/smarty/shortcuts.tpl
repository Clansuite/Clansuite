
{* jQuery used for Vertical Tabs *}
{move_to target="pre_head_close"}
<script type="text/javascript">
	jQuery(function() {
		jQuery('#vertical-tabs').tabs({ fx: { height: 'toggle', opacity: 'toggle' } });
	});
</script>
{/move_to}

<!-- Tab Table for the Shortcuts -->
<table cellspacing="0" cellpadding="0" width="100%">
	<tbody>
		<tr style="height: 15px;">
			<td style="min-width: 120px; background-color: #ACD943; text-align: bottom;" width="33%" valign="bottom">
				<b>&nbsp;&raquo; Shortcuts</b>
			</td>
			<td nowrap="nowrap" style="background-image:url('{$www_root}modules/controlcenter/images/green-triangle.gif'); background-repeat: no-repeat; width: 100%;" />
		</tr>
		<tr>
			<td height="3" bgcolor="#ACD943" colspan="2"/></td>
		</tr>
		<tr>
			<td bgcolor="#dde9cf" valign="top" colspan="2">
				<div id="vertical-tabs">
					<table cellspacing="1" border="0" width="100%">
						<tbody>
						<tr bgcolor="#ffffff">

							<td width="125" style="vertical-align:top;">
								<table cellpadding="0" cellspacing="0" width="100%" style="padding: 5px;">
									<tbody>
										<tr>
											<td>
												{* Start:Shortcuts menu*}
												<!-- the left sidebar with five categories -->
												<ul>
													 <li><a href="#fragment-1"><span><img alt="Modules Shortcut Icon" src="{$www_root_themes_core}images/symbols/modules.png" /><br />Modules</span></a></li>
													 <li><a href="#fragment-2"><span><img alt="Admin Shortcut Icon" src="{$www_root_themes_core}images/symbols/settings.png" /><br />Administration</span></a></li>
													 <li><a href="#fragment-3"><span><img alt="Settings Shortcut Icon" src="{$www_root_themes_core}images/symbols/system.png" /><br />System</span></a></li>
													 <li><a href="#fragment-4"><span><img alt="User Shortcut Icon" src="{$www_root_themes_core}images/symbols/groups.png" /><br />Benutzer</span></a></li>
													 <li><a href="#fragment-5"><span><img alt="Layout Shortcut Icon" src="{$www_root_themes_core}images/symbols/templates.png" /><br />Layout</span></a></li>
												</ul>
											</td>
										</tr>
									</body>
								</table>
							</td>

							<td width="auto" style="vertical-align:top;">
								<table cellspacing="6" width="100%">
									<tbody>
										<tr>
											<td>
												{* Start:Shortcuts Content*}
												<div id="fragment-1"> I

													{* Table for Administration Symbols/Shortcuts *}
													<table cellspacing="10" cellpadding="5" {* Tabelle zentrieren: style="margin:0 auto" *}>

														{* commented out: moving to nested sets
														{foreach key=row item=image from=$shortcuts}
															<tr class="tr_row2">

															{foreach key=col item=data from=$image}
																 <td align="center" style="width:65px; padding: 10px;">
																	  <a href="{$data.href}">
																			<img alt="Shortcut Icon" src="{$www_root_themes_core}images/symbols/{$data.file_name}" />
																			<br />
																			<span style="margin-top: 10px; display: block">{t}{$data.title}{/t}</span>
																	  </a>
																 </td>
															{/foreach}

															</tr>
														{/foreach}
														*}
													</table>
												</div>

												<div id="fragment-2"> II </div>
												<div id="fragment-3"> III </div>
												<div id="fragment-4"> IV </div>
												<div id="fragment-5"> V </div>

												<!-- Clear -->
												<div style="clear:both"></div>

											</td>
										</tr>
									</body>
								</table>
							</td>
						</tr>
					</tbody>
				</table>

				<!-- Close Vertical Tabs -->
				</div>
				{* End:Shortcuts Content *}

				<input class="ButtonOrange" type="button" value="Edit" />                    
			</td>

		</tr>
	</tbody>
</table>