Ajax.InPlaceEditorPlusFader = Class.create();
Object.extend(Ajax.InPlaceEditorPlusFader.prototype, Ajax.InPlaceEditor.prototype);
Object.extend(Ajax.InPlaceEditorPlusFader.prototype, {
  enterEditMode: function(evt) {
    if (this.saving) return;
    if (this.editing) return;
    this.editing = true;
    this.onEnterEditMode();
    if (this.options.externalControl) {
      Element.hide(this.options.externalControl);
    }
    this.createForm();
    //Element.hide(this.element);
    //new Effect.Fade(this.element);
    new Effect.Appear(this.editField);
    new Effect.Scale(this.editField, 100, {scaleContent: false, scaleFrom: 10, scaleMode: { originalHeight: 200, originalWidth: 400 }});
    this.element.parentNode.appendChild(this.form, this.element);
    //if (!this.options.loadTextURL) Field.scrollFreeActivate(this.editField);
    // stop the event to avoid a page refresh in Safari
    if (evt) {
      Event.stop(evt);
    }
    return false;
  },
  createEditField: function() {
    var text;
    if(this.options.loadTextURL) {
      text = this.options.loadingText;
    } else {
      text = this.getText();
    }

    var obj = this;

    if (this.options.rows == 1 && !this.hasHTMLLineBreaks(text)) {
      this.options.textarea = false;
      var textField = document.createElement("input");
      textField.obj = this;
      textField.type = "text";
      textField.name = this.options.paramName;
      textField.value = text;
      textField.style.backgroundColor = this.options.highlightcolor;
      textField.className = 'editor_field';
      textArea.style.height = 1;
      textArea.style.width = 1;
      textArea.style.display = 'none';
      var size = this.options.size || this.options.cols || 0;
      if (size != 0) textField.size = size;
      if (this.options.submitOnBlur)
        textField.onblur = this.onSubmit.bind(this);
      this.editField = textField;
    } else {
      this.options.textarea = true;
      var textArea = document.createElement("textarea");
      textArea.obj = this;
      textArea.name = this.options.paramName;
      textArea.value = this.convertHTMLLineBreaks(text);
      textArea.style.height = 1;
      textArea.style.width = 1;
      textArea.style.display = 'none';
      textArea.className = 'editor_field';
      if (this.options.submitOnBlur)
        textArea.onblur = this.onSubmit.bind(this);
      this.editField = textArea;
    }

    if(this.options.loadTextURL) {
      this.loadExternalText();
    }
    this.form.appendChild(this.editField);
  },
  createForm: function() {
    this.form = document.createElement("form");
    this.form.id = this.options.formId;
    Element.addClassName(this.form, this.options.formClassName)
    this.form.onsubmit = this.onSubmit.bind(this);

    this.createEditField();

    if (this.options.textarea) {
      var br = document.createElement("br");
      this.form.appendChild(br);
    }

    if (this.options.textBeforeControls)
      this.form.appendChild(document.createTextNode(this.options.textBeforeControls));

    if (this.options.okButton) {
      var okButton = document.createElement("input");
      okButton.type = "submit";
      okButton.value = this.options.okText;
      okButton.className = 'editor_ok_button';
      okButton.style.display = 'none';
      okButton.style.width = 1;
      okButton.style.height = 1;
      this.form.appendChild(okButton);
      new Effect.Appear(okButton);
      new Effect.Scale(okButton, 100, { scaleFrom: 0 });
    }

    if (this.options.okLink) {
      var okLink = document.createElement("a");
      okLink.href = "#";
      okLink.appendChild(document.createTextNode(this.options.okText));
      okLink.onclick = this.onSubmit.bind(this);
      okLink.className = 'editor_ok_link';
      this.form.appendChild(okLink);
    }

    if (this.options.textBetweenControls &&
      (this.options.okLink || this.options.okButton) &&
      (this.options.cancelLink || this.options.cancelButton))
      this.form.appendChild(document.createTextNode(this.options.textBetweenControls));

    if (this.options.cancelButton) {
      var cancelButton = document.createElement("input");
      cancelButton.type = "submit";
      cancelButton.value = this.options.cancelText;
      cancelButton.onclick = this.onclickCancel.bind(this);
      cancelButton.className = 'editor_cancel_button';
      cancelButton.style.width = 1;
      cancelButton.style.height = 1;
      cancelButton.style.display = 'none';
      this.form.appendChild(cancelButton);
      new Effect.Appear(cancelButton);
      new Effect.Scale(cancelButton, 100, { scaleFrom: 0 });
    }

    if (this.options.cancelLink) {
      var cancelLink = document.createElement("a");
      cancelLink.href = "#";
      cancelLink.appendChild(document.createTextNode(this.options.cancelText));
      cancelLink.onclick = this.onclickCancel.bind(this);
      cancelLink.className = 'editor_cancel editor_cancel_link';
      this.form.appendChild(cancelLink);
    }

    if (this.options.textAfterControls)
      this.form.appendChild(document.createTextNode(this.options.textAfterControls));
  },
  leaveEditMode: function() {
    Element.removeClassName(this.element, this.options.savingClassName);
    this.removeForm();
    this.leaveHover();
    this.element.style.backgroundColor = this.originalBackground;
    //new Effect.Appear(this.element);
    Element.show(this.element);
    if (this.options.externalControl) {
      Element.show(this.options.externalControl);
    }
    this.editing = false;
    this.saving = false;
    this.oldInnerHTML = null;
    this.onLeaveEditMode();
  },
  removeForm: function() {
    if(this.form) {
      //if (this.form.parentNode) //Element.remove(this.form);
      new Effect.Scale(this.editField, 10, {scaleContent: false, scaleFrom: 100, scaleMode: { originalHeight: 200, originalWidth: 400 }});
      new Effect.Fade(this.editField);
      new Effect.Fade(this.form);
      //this.form = null;
    }
  },
  onLoading: function() {
    this.saving = true;
    this.removeForm();
    //this.leaveHover();
  },
  showSaving: function() {},
});