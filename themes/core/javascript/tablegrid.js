Ajax.Responders.register({
	onCreate: function() {
		if($('notification') && Ajax.activeRequestCount > 0)
			Effect.Appear('notification',{duration: 0.65, queue: 'end'});
	},
	onComplete: function() {
		if($('notification') && Ajax.activeRequestCount == 0)
			Effect.Fade('notification',{duration: 0.65, queue: 'end'});
	}
});

TableGrid = Class.create();
TableGrid.prototype = {
	initialize: function(table, cols, url, edit_class, input_class ) {
    	this.table = table;
    	this.url = url;
    	if( !edit_class )
    	   this.edit_class = 'editcell';
    	else
            this.edit_class = edit_class;

    	if( !input_class )
    	   this.input_class = 'ajax_input_class';
    	else
            this.input_class = input_class;

   		this.tbody = $(table).getElementsByTagName('tbody')[0];

   		this.editing = false;
		this.saving = false;
		this.movenext = false;
		this.numcol = cols;

		//Event.observe($('addrow'), "click", this.addRow.bindAsEventListener(this));
		this.mouseoverListener = this.enterHover.bindAsEventListener(this);
    	this.mouseoutListener = this.leaveHover.bindAsEventListener(this);
    	this.onclickListener = this.enterEditMode.bindAsEventListener(this);

		if(this.tbody.getElementsByTagName('tr').length > 0) { this.addCellEvent(); }
	  },

	addCellEvent: function() {
	    //  table#id td.class
	    $$('table#' + this.table + ' td.' + this.edit_class).each(function(item) {
			Event.observe(item, 'mouseover', this.mouseoverListener);
			Event.observe(item, 'mouseout', this.mouseoutListener);
			Event.observe(item, 'click', this.onclickListener);
		}.bind(this));
	},

	enterHover: function(event) {                   //#fcd661
    	Event.element(event).style.backgroundColor = '#ffc';
    },

    leaveHover: function(event) {
    	Event.element(event).style.backgroundColor = '#ffffff';
    },

    onBlur: function(event) {
 		if(this.editing)
 		{
			this.saveData();
			Event.stop(event);
			return;
 		}
    },

    enterEditMode: function(event) {
    	if (this.saving) return;
    	if (this.editing) return;

    	this.editing = true;
    	this.cell = Event.element(event);
    	this.old_padding = this.cell.style.padding;
    	this.createEditField();
    },

    createEditField: function() {
        if( this.table == 'profile' && this.cell.id == 'birthday' )
        {
    		this.cell.style.backgroundColor = '#ffffff';
    		this.cell.style.padding = '0px';

    		Event.stopObserving(this.cell, 'mouseover', this.mouseoverListener);
    		Event.stopObserving(this.cell, 'mouseout', this.mouseoutListener);
    		Event.stopObserving(this.cell, 'click', this.onclickListener);

    		var text = this.cell.innerHTML.split(".");
    		var obj = this;
    		this.oldValue = text;

    	    var textField = document.createElement("input");
            textField.obj = this;
    	    textField.type = 'text';
    	    textField.setAttribute('class', this.input_class);
    	    textField.name = 'value';
    	    textField.setAttribute('autocomplete', 'off');
    	    textField.id = 'value';
    	    textField.maxLength = '2';
    	    textField.size = '2';
    	    textField.value = text[0].replace(/^[\s]+/,'').replace(/&lt;/,'<').replace(/&gt;/,'>');
    	    textField.onclick = this.enterInput.bind(this);
    	    //textField.onblur = this.onBlur.bind(this);

    	    var textField2 = document.createElement("input");
            textField2.obj = this;
    	    textField2.type = 'text';
    	    textField2.setAttribute('class', this.input_class);
    	    textField2.name = 'value';
    	    textField2.setAttribute('autocomplete', 'off');
    	    textField2.id = 'value';
    	    textField2.maxLength = '2';
    	    textField2.size = '2';
    	    textField2.value = text[1].replace(/^[\s]+/,'').replace(/&lt;/,'<').replace(/&gt;/,'>');
    	    textField2.onclick = this.enterInput.bind(this);
    	    //textField2.onblur = this.onBlur.bind(this);

    	    var textField3 = document.createElement("input");
            textField3.obj = this;
    	    textField3.type = 'text';
    	    textField3.setAttribute('class', this.input_class);
    	    textField3.name = 'value';
    	    textField3.setAttribute('autocomplete', 'off');
    	    textField3.id = 'value';
    	    textField3.maxLength = '4';
    	    textField3.size = '4';
    	    textField3.value = text[2].replace(/^[\s]+/,'').replace(/&lt;/,'<').replace(/&gt;/,'>');
    	    textField3.onclick = this.enterInput.bind(this);
    	    textField3.onblur = this.onBlur.bind(this);

    	    this.editField = textField;
    	    this.editField2 = textField2;
    	    this.editField3 = textField3;
    	    Event.observe(this.editField, "keypress", this.onKeyPress.bindAsEventListener(this));
    	    Event.observe(this.editField2, "keypress", this.onKeyPress.bindAsEventListener(this));
    	    Event.observe(this.editField3, "keypress", this.onKeyPress.bindAsEventListener(this));

    	    this.cell.innerHTML = '';
    	    this.cell.appendChild(this.editField);
    	    this.cell.appendChild(this.editField2);
    	    this.cell.appendChild(this.editField3);

    	    this.editField.focus();
        }
        else if (this.table == 'profile' && this.cell.id == 'gender')
        {
    		this.cell.style.backgroundColor = '#ffffff';
    		this.cell.style.padding = '0px';

    		Event.stopObserving(this.cell, 'mouseover', this.mouseoverListener);
    		Event.stopObserving(this.cell, 'mouseout', this.mouseoutListener);
    		Event.stopObserving(this.cell, 'click', this.onclickListener);

    		var text = this.cell.innerHTML;
    		var obj = this;
    		this.oldValue = text;

    	    var textField = document.getElementById("gender_container").cloneNode(true);
            textField.obj = this;
    	    textField.setAttribute('class', this.input_class);
    	    textField.name = 'value';
    	    textField.setAttribute('autocomplete', 'off');
    	    textField.id = 'value';
    	    textField.onclick = this.enterInput.bind(this);
    	    textField.onblur = this.onBlur.bind(this);

    	    this.editField = textField;
    	    Event.observe(this.editField, "keypress", this.onKeyPress.bindAsEventListener(this));

    	    this.cell.innerHTML = '';
    	    this.cell.appendChild(this.editField);

    	    this.editField.focus();
        }
        else if (this.table == 'profile' && this.cell.id == 'country')
        {
    		this.cell.style.backgroundColor = '#ffffff';
    		this.cell.style.padding = '0px';

    		Event.stopObserving(this.cell, 'mouseover', this.mouseoverListener);
    		Event.stopObserving(this.cell, 'mouseout', this.mouseoutListener);
    		Event.stopObserving(this.cell, 'click', this.onclickListener);

    		var text = this.cell.innerHTML;
    		var obj = this;
    		this.oldValue = text;

    	    var textField = document.getElementById("country_container").cloneNode(true);
            textField.obj = this;
    	    textField.setAttribute('class', this.input_class);
    	    textField.name = 'value';
    	    textField.setAttribute('autocomplete', 'off');
    	    textField.id = 'value';
    	    textField.onclick = this.enterInput.bind(this);
    	    textField.onblur = this.onBlur.bind(this);

    	    this.editField = textField;
    	    Event.observe(this.editField, "keypress", this.onKeyPress.bindAsEventListener(this));

    	    this.cell.innerHTML = '';
    	    this.cell.appendChild(this.editField);

    	    this.editField.focus();
        }
        else if (this.table == 'profile' && this.cell.id == 'custom_text')
        {
    		this.cell.style.backgroundColor = '#ffffff';
    		this.cell.style.padding = '0px';

    		Event.stopObserving(this.cell, 'mouseover', this.mouseoverListener);
    		Event.stopObserving(this.cell, 'mouseout', this.mouseoutListener);
    		Event.stopObserving(this.cell, 'click', this.onclickListener);

    		var text = this.cell.innerHTML;
    		var obj = this;
    		this.oldValue = text;

    	    var textField = document.createElement("textarea");
            textField.obj = this;
    	    //textField.type = 'textarea';
    	    textField.setAttribute('class', 'input_textarea');
    	    textField.name = 'value';
    	    textField.setAttribute('autocomplete', 'off');
    	    textField.id = 'value';
    	    textField.rows = '15';
    	    textField.cols = '40';
    	    textField.value = text.replace(/^[\s]+/,'').replace(/&lt;/,'<').replace(/&gt;/,'>');
    	    textField.onclick = this.enterInput.bind(this);
    	    textField.onblur = this.onBlur.bind(this);

    	    this.editField = textField;
    	    Event.observe(this.editField, "keypress", this.onKeyPress.bindAsEventListener(this));

    	    this.cell.innerHTML = '';
    	    this.cell.appendChild(this.editField);

    	    this.editField.focus();
        }
        else
        {
    		this.cell.style.backgroundColor = '#ffffff';
    		this.cell.style.padding = '0px';

    		Event.stopObserving(this.cell, 'mouseover', this.mouseoverListener);
    		Event.stopObserving(this.cell, 'mouseout', this.mouseoutListener);
    		Event.stopObserving(this.cell, 'click', this.onclickListener);

    		var text = this.cell.innerHTML;
    		var obj = this;
    		this.oldValue = text;

    	    var textField = document.createElement("input");
            textField.obj = this;
    	    textField.type = 'text';
    	    textField.setAttribute('class', this.input_class);
    	    textField.name = 'value';
    	    textField.setAttribute('autocomplete', 'off');
    	    textField.id = 'value';
    	    textField.maxLength = '255';
    	    textField.size = '10';
    	    textField.value = text.replace(/^[\s]+/,'').replace(/&lt;/,'<').replace(/&gt;/,'>');
    	    textField.onclick = this.enterInput.bind(this);
    	    textField.onblur = this.onBlur.bind(this);

    	    this.editField = textField;
    	    Event.observe(this.editField, "keypress", this.onKeyPress.bindAsEventListener(this));

    	    this.cell.innerHTML = '';
    	    this.cell.appendChild(this.editField);

    	    this.editField.focus();
    	}
	},

    onKeyPress: function(event) {
     	if(this.editing) {
			switch(event.keyCode) {
				case Event.KEY_TAB:
					this.movenext = true;
					this.saveData();
					Event.stop(event);
					return;
				case Event.KEY_RETURN:
					this.saveData();
					Event.stop(event);
					return;
				case Event.KEY_ESC:
					this.cancelEdit();
					Event.stop(event);
					return;
     		}
     	}
	},

  	enterInput: function(event) {
		this.editing = true;
		this.movenext = false;
  	},

	addRow: function(event) {
		if (this.saving) return;
    	if (this.editing) return;
    	var numrows = this.tbody.getElementsByTagName('tr').length + 1;

		var new_row = document.createElement('tr');
		for(var i=1; i <= this.numcol; i++) {
			new_cell = document.createElement('td');
			new_text = document.createTextNode(' ');
			new_cell.id = this.table + '_' + numrows + '-' + i;
			Event.observe(new_cell, 'mouseover', this.mouseoverListener);
			Event.observe(new_cell, 'mouseout', this.mouseoutListener);
			Event.observe(new_cell, 'click', this.onclickListener);
			new_cell.appendChild(new_text);
			new_row.appendChild(new_cell);
        }
        this.tbody.appendChild(new_row);
  	},

	cancelEdit: function() {
		this.removeInput();
		this.cell.innerHTML = this.oldValue;
		this.saving = false;
		this.oldValue = null;
		this.cell = null;
	},

	removeInput: function() {
		this.editing = false;
    	if(this.editField) {
      		Element.remove(this.editField);
      		this.editField = null;
    	}
    	this.cell.style.padding = this.old_padding;

    	Event.observe(this.cell, 'mouseover', this.mouseoverListener);
		Event.observe(this.cell, 'mouseout', this.mouseoutListener);
		Event.observe(this.cell, 'click', this.onclickListener);
  	},

	saveData: function() {
    	this.saving = true;

        if( this.table == 'profile' && this.cell.id == 'birthday' )
        {
           var pars = '&table=' + escape(encodeURIComponent(this.table)) + '&value=' + escape(encodeURIComponent(this.editField.value)) + '.' + escape(encodeURIComponent(this.editField2.value)) + '.' + escape(encodeURIComponent(this.editField3.value)) + '&cell=' + escape(encodeURIComponent(this.cell.id));
        }
        else
        {
    	   var pars = '&table=' + escape(encodeURIComponent(this.table)) + '&value=' + escape(encodeURIComponent(this.editField.value)) + '&cell=' + escape(encodeURIComponent(this.cell.id));
    	}


    	this.removeInput();

		new Ajax.Updater(
		  { success: this.cell,
            failure: null },
		  this.url,
		  {
		  	method: 'post',
		    parameters: pars,
		    asynchronous: true,
		    onComplete: this.onComplete.bind(this),
		    onFailure: this.onFailure.bind(this)
		  });
	},

	nextCell: function (curcell) {
		var rowcol = curcell.id.sub(this.table + '_', '').split('-');
		var row = parseInt(rowcol[0]);
		var col = parseInt(rowcol[1]);

		if (col != this.numcol) {
			col++;
		} else {
			col = 1;
			row++;
			this.addRow();
		}

		return this.table + '_' + row + '-' + col;
	},

    onComplete: function (transport) {
		this.saving = false;

		if (this.movenext) {
			this.movenext = false;
    		this.cell = $(this.nextCell(this.cell));
    		this.editing = true;
    		this.createEditField();
		}
	},

	onFailure: function (transport) {
		this.cancelEdit();

		alert('Sorry. There was an error, updating database!');
	}
}
