var $jQ = jQuery.noConflict();

$jQ(function() {
	/**
	 * Tabs
	 **/
	var match = 0;
	$jQ('#vertical-tabs').prepend('<ul id="wrapper"></ul>');
	$jQ('#vertical-tabs > fieldset').each(function(i) {
		var header = $jQ(this).children('legend').html();
		var index = 'tab-' + i;
		$jQ('#vertical-tabs #wrapper').append($jQ('<li><a href="#' + index + '">' + header + '</a></li>'));
		$jQ(this).attr('id', index);
		if (window.location.hash.substr(1) == index) {
			match = i;
		}
	});
	$jQ('#vertical-tabs > fieldset > legend').remove();
	$jQ('#vertical-tabs > fieldset:not(:eq(' + match + '))').hide();
	
	$jQ('#vertical-tabs #wrapper li a').click(function() {
		$jQ('#vertical-tabs #wrapper li a').removeClass('selected');
		$jQ(this).addClass('selected');
		$jQ('#vertical-tabs > fieldset:visible').hide();
		$jQ('#vertical-tabs fieldset').filter(this.hash).show();
		return false;
	}).filter(':eq(' + match + ')').click();
})