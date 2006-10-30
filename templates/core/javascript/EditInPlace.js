/*
 * Edit In Place
 * http://josephscott.org/code/js/eip/
 * Version 0.3.3
 * License http://josephscott.org/code/js/eip/license.txt
 */

// Fake a EditInPlace.* name space.
var EditInPlace = function() { };

// Default settings for editable ids.
EditInPlace.defaults = {
	// Default options that you can over write.
	id:						false,
	save_url:				false,
	type:					'text',
	select_text:			false,	// doesn't work in Safari
	size:					false,  // will be calculated at run time
	max_size:				100,
	rows:					false,  // will be calculated at run time
	cols:					50,
	options:				false,
	escape_function:		escape,	// or encodeURIComponent
	on_blur:				'',
	mouseover_class:		'eip_mouseover',
	editfield_class:		'eip_mouseover',
	savebutton_text:		'SAVE',
	savebutton_class:		'eip_savebutton',
	cancelbutton_text:		'CANCEL',
	cancelbutton_class:		'eip_cancelbutton',
	saving_text:			'Saving...',
	saving_class:			'eip_saving',
	empty_text:				'Click To Edit',
	empty_class:			'eip_empty',
	edit_title:				'Click To Edit',
	click:					'click',  // or dblclick 
	savefailed_text:		'Failed to save changes.',
	ajax_data:				false,

	// Private options that are managed for you.
	// PLEASE DO NOT TOUCH THESE.
	is_empty:				false,
	orig_text:				'',
	mouseover_observer:		false,
	mouseout_observer:		false,
	mouseclick_observer:	false
};

// Place to keep individual option sets.
EditInPlace.options = { };

// Make an id editable.
EditInPlace.makeEditable = function(args) {
	var id = args['id'];
	var id_opt = EditInPlace._getOptionsReference(id);

	// Start the option set with the defaults.
	for(var i in EditInPlace.defaults) {
		id_opt[i] = EditInPlace.defaults[i];
	}

	// Over write defaults with provided arguments.
	for(var i in args) {
		id_opt[i] = args[i];
	}

	// Store the current (original) value of the editable id.
	id_opt['orig_text'] = $(id).innerHTML;

	// Check for empty values.
	if($(id).innerHTML == '') {
		id_opt['is_empty'] = true;
		$(id).innerHTML = id_opt['empty_text'];
		Element.addClassName(id, id_opt['empty_class']);
	}

	// Build event observers.
	id_opt['mouseover_observer'] = EditInPlace._mouseOver.bindAsEventListener();
	id_opt['mouseout_observer'] = EditInPlace._mouseOut.bindAsEventListener();
	id_opt['mouseclick_observer'] = EditInPlace._mouseClick.bindAsEventListener();

	// Watch for events.
	Event.observe(id, 'mouseover', id_opt['mouseover_observer']);
	Event.observe(id, 'mouseout', id_opt['mouseout_observer']);
	Event.observe(id, id_opt['click'], id_opt['mouseclick_observer']);

	$(id).title = id_opt['edit_title'];

	// Return a reference the option set
	// just in case someone is interested.
	return(id_opt);
};

// **************  Private Functions ***************** 

// Get a reference to the option set for a specific editable id.
EditInPlace._getOptionsReference = function(id) {
	// If an option set doesn't exist for the id
	// then create an empty one.
	if(!EditInPlace.options[id]) {
		EditInPlace.options[id] = { };
	}

	return(EditInPlace.options[id]);
};

// Process mouseover events.
EditInPlace._mouseOver = function(event) {
	var id = Event.element(event).id;
	var id_opt = EditInPlace._getOptionsReference(id);

	Element.addClassName(id, id_opt['mouseover_class']);
};

// Process mouseout events.
EditInPlace._mouseOut = function(event) {
	var id = Event.element(event).id;
	var id_opt = EditInPlace._getOptionsReference(id);

	Element.removeClassName(id, id_opt['mouseover_class']);
};

// Process mouseclick events.
EditInPlace._mouseClick = function(event) {
	var id = Event.element(event).id;
	var id_opt = EditInPlace._getOptionsReference(id);
	var form = EditInPlace._formField(id) + EditInPlace._formButtons(id);

	// Hide the original id and show the edit form and set the focus.
	Element.hide(id);
	new Insertion.After(id, form);
	Field.focus(id + '_edit');

	// Watch for button clicks.
	Event.observe(id + '_save', 'click', EditInPlace._saveClick);
	Event.observe(id + '_cancel', 'click', EditInPlace._cancelClick);
	Event.observe(id + '_edit', 'blur', function(event) {
		switch(id_opt['on_blur']) {
			case 'cancel':
				EditInPlace._cancelClick(false, id);
				break;
			case 'save':
				EditInPlace._saveClick(false, id);
				break;
			default:
				break;
		}
	});
};

// Process save button clicks.
EditInPlace._saveClick = function(event, id) {
	if(!id) {
		id = Event.element(event).id.replace(/_save$/, '');
	}
	var id_opt = EditInPlace._getOptionsReference(id);

	// Package data for the AJAX request.
	var new_text = id_opt['escape_function']($F(id + '_edit'));
	var params = 'id=' + id + '&content=' + new_text;
	if(id_opt['type'] == 'select') {
		params += '&option_name=' + $(id + '_option_' + new_text).innerHTML;
	}

	// If additional data was provided to be sent, add it on.
	if(id_opt['ajax_data']) {
		for(var i in id_opt['ajax_data']) {
			var url_data = id_opt['escape_function'](id_opt['ajax_data'][i]);
			params += '&' + i + '=' + url_data;
		}
	}

	// Build saving message.
	var saving = '<span class="' 
		+ id_opt['saving_class'] + '">' + id_opt['saving_text'] + '</span>\n';

	// Turn off event watching while saving changes.
	Event.stopObserving(id, 'mouseover', id_opt['mouseover_observer']);
	Event.stopObserving(id, 'mouseout', id_opt['mouseout_observer']);
	Event.stopObserving(id, id_opt['click'], id_opt['mouseclick_observer']);

	// Remove the form editor and display the saving message.
	Element.remove(id + '_editor');
	$(id).innerHTML = saving;
	Element.show(id);

	// Send the changes via AJAX.
	var ajax_req = new Ajax.Request(
		id_opt['save_url'],
		{
			method: 'post',
			postBody: params,
			onSuccess: function(t) { EditInPlace._saveComplete(id, t); },
			onFailure: function(t) { EditInPlace._saveFailed(id, t); }
		}
	);
};

// Proces cancel button clicks.
EditInPlace._cancelClick = function(event, id) {
	if(!id) {
		id = Event.element(event).id.replace(/_cancel$/, '');
	}
	var id_opt = EditInPlace._getOptionsReference(id);

	// Remove the edit form and mouseover class.
	Element.remove(id + '_editor');
	Element.removeClassName(id, id_opt['editfield_class']);

	// Display empty edit text if the id was empty.
	if($(id).innerHTML == '') {
		id_opt['id_empty'] = true;
		$(id).innerHTML = id_opt['empty_text'];
		Element.addClassName(id, id_opt['empty_class']);
	}

	// Show the original id again.
	Element.show(id);
};

// Complete the successful AJAX request.
EditInPlace._saveComplete = function(id, t) {
	var id_opt = EditInPlace._getOptionsReference(id);

	// Check to see if the user deleted all of the text.
	if(t.responseText == '') {
		id_opt['is_empty'] = true;
		$(id).innerHTML = id_opt['empty_text'];
		Element.addClassName(id, id_opt['empty_class']);
	}
	else {
		id_opt['is_empty'] = false;
		Element.removeClassName(id, id_opt['empty_class']);
		$(id).innerHTML = t.responseText;
	}

	// Save the new text as the original text.
	id_opt['orig_text'] = t.responseText;

	// Turn on event watching again.
	Event.observe(id, 'mouseover', id_opt['mouseover_observer']);
	Event.observe(id, 'mouseout', id_opt['mouseout_observer']);
	Event.observe(id, id_opt['click'], id_opt['mouseclick_observer']);
};

// Complete the failed AJAX request.
EditInPlace._saveFailed = function(id, t) {
	var id_opt = EditInPlace.getOptionsReference(id);

	// Restore the original text, remove the editfield class
	// and alert the user that the save failed.
	$(id).innerHTML = id_opt['orig_text'];
	Element.removeClassName(id, id_opt['editfield_class']);
	alert(id_opt['savefailed_text']);

	// Turn on event watching again.
	Event.observe(id, 'mouseover', id_opt['mouseover_observer']);
	Event.observe(id, 'mouseout', id_opt['mouseout_observer']);
	Event.observe(id, id_opt['click'], id_opt['mouseclick_observer']);
};

// Build the form field to edit.
EditInPlace._formField = function(id) {
	var id_opt = EditInPlace._getOptionsReference(id);

	// If the original text value was empty then
	// make it empty again before we allow editing.
	if(id_opt['is_empty'] == true) {
		Element.removeClassName(id, id_opt['empty_class']);
		$(id).innerHTML = '';
	}

	// Every form is wrapped in a span.
	var field = '<span id="' + id + '_editor">\n';

	// Text input edit.
	if(id_opt['type'] == 'text') {
		var size = id_opt['orig_text'].length + 15;
		// Don't let the size get bigger than the maximum.
		if(size > id_opt['max_size']) {
			size = id_opt['max_size'];
		}

		// Use a specific size if one was provided.
		size = (id_opt['size'] ? id_opt['size'] : size);

		field += '<input id="' + id + '_edit" class="'
			+ id_opt['editfield_class'] + '" name="' + id
			+ '_edit" type="text" size="' + size + '" value="'
			+ id_opt['orig_text'] + '"';

		if(id_opt['select_text']) {
			field += 'onfocus="this.select();"';
		}

		field += ' /><br />\n';
	}
	// Textarea edit.
	else if(id_opt['type'] == 'textarea') {
		// Calculate the number of rows to use.
		var rows = (id_opt['orig_text'].length / id_opt['cols']) + 4;

		// Use a specific rows is fone was provided.
		rows = (id_opt['rows'] ? id_opt['rows'] : rows);

		field += '<textarea id="' + id + '_edit" class="'
			+ id_opt['editfield_class'] + '" name="' + id + '_edit" rows="'
			+ rows + '"cols="' + id_opt['cols'] + '"'

		if(id_opt['select_text']) {
			field += 'onfocus="this.select();"';
		}

		field += '>' + id_opt['orig_text'] + '</textarea><br />\n';
	}
	// Select edit.
	else if(id_opt['type'] == 'select') {
		field += '<select id="' + id + '_edit" class="'
			+ id_opt['editfield_class'] + '" name="' + id + '_edit">\n';

		for(var i in id_opt['options']) {
			field += '<option id="' + id + '_option_' + i + '" name="' + id
				+ '_option_' + i + '" value="' + i + '"';

			if(id_opt['options'][i] == $(id).innerHTML) {
				field += ' selected="selected"';
			}

			field += '>' + id_opt['options'][i] + '</option>\n';
		}

		field += '</select>\n';
	}

	return(field);
};

// Build form buttons.
EditInPlace._formButtons = function(id) {
	var id_opt = EditInPlace._getOptionsReference(id);

	return(
		'<span><input id="' + id + '_save" class="' + id_opt['savebutton_class']
		+ '" type="button" value="' + id_opt['savebutton_text']
		+ '" /> OR <input id="' + id + '_cancel" class="'
		+ id_opt['cancelbutton_class'] + '" type="button" value="'
		+ id_opt['cancelbutton_text'] + '" /></span></span>'
	);
};
