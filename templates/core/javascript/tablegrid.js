TableGrid = Class.create();
TableGrid.prototype = {
	initialize: function(table, cols, url ) {
    	this.table = table;
    	
   		this.url = url;
   		
   		
   		this.tbody = $(table).getElementsByTagName('tbody')[0];
   		this.indicator = $('indicator');
   		this.editing = false;
		this.saving = false;
		this.movenext = false;
		this.numcol = cols;
				
		Event.observe($('addrow'), "click", this.addRow.bindAsEventListener(this));
		this.mouseoverListener = this.enterHover.bindAsEventListener(this);
    	this.mouseoutListener = this.leaveHover.bindAsEventListener(this);
    	this.onclickListener = this.enterEditMode.bindAsEventListener(this);
	
		if(this.tbody.getElementsByTagName('tr').length > 0) this.addCellEvent();
	},	
	
	addCellEvent: function() {	
    	$$('table#' + this.table + ' td').each(function(item) {
			Event.observe(item, 'mouseover', this.mouseoverListener);
			Event.observe(item, 'mouseout', this.mouseoutListener);
			Event.observe(item, 'click', this.onclickListener);
		}.bind(this));
	},	
	
	enterHover: function(event) {
    	Event.element(event).style.backgroundColor = '#fcd661';
    },
    
    leaveHover: function(event) {
    	Event.element(event).style.backgroundColor = '#ffffff';
    },
    
    enterEditMode: function(event) {
    	if (this.saving) return;
    	if (this.editing) return;
    	
    	this.editing = true;
    	this.cell = Event.element(event);
    	this.createEditField();
    },
    
    createEditField: function() {
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
	    textField.name = 'value';
	    textField.setAttribute('autocomplete', 'off');
	    textField.id = 'value';
	    textField.maxLength = '20';
	    textField.size = '10';
	    textField.value = text.replace(/^ /, '');
	    textField.style.backgroundColor = '#fbecc0';
		//textField.onblur = this.onBlur.bind(this);
	    textField.onclick = this.enterInput.bind(this);
	    
	    this.editField = textField;
	    Event.observe(this.editField, "keypress", this.onKeyPress.bindAsEventListener(this));
	    
	    this.cell.innerHTML = '';
	    this.cell.appendChild(this.editField);
	    
	    this.editField.focus();
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
    	this.cell.style.padding = '2px';
    	
    	Event.observe(this.cell, 'mouseover', this.mouseoverListener);
		Event.observe(this.cell, 'mouseout', this.mouseoutListener);
		Event.observe(this.cell, 'click', this.onclickListener);
  	},
  	
	startIndicator: function() {
    	Element.show(this.indicator);
	},

  	stopIndicator: function() {
    	Element.hide(this.indicator);
  	},
  	
  	saveData: function() {
    	this.saving = true;
   	
    	var pars = '&table=' + this.table + '&value=' + this.editField.value + '&cell=' + this.cell.id;
    	
    	this.startIndicator();
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
		this.stopIndicator();
		if (this.movenext) {
			this.movenext = false;
    		this.cell = $(this.nextCell(this.cell));
    		this.editing = true;
    		this.createEditField();
		}
	},
	
	onFailure: function (transport) {
		this.cancelEdit();
		this.stopIndicator();
		alert('Sorry. There was an error, updating database!');
	}
}
