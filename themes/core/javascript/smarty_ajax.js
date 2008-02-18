var SmartyAjax = {

  call: function(url, method, params, params_func, callback) {
    if (params_func) {
      if (params.length != 0) params += "&";
      params += $H(params_func()).toQueryString();
    }
    var myAjax = new Ajax.Request(
      url,
      {
        method: method,
        parameters: params,
        onComplete: callback
      });
  },

  update: function(update_id, url, method, params, callback) {
    var myAjax = new Ajax.Updater(
      update_id,
      url,
      {
        method: method,
        parameters: params,
        onComplete: function () {
            eval(callback)
        }
      });
  },

  submit: function(form, params, callback) {
  	var myAjax = new Ajax.Request(
  		form.action,
  		{
  			method: form.method,
  			parameters: Form.serialize(form.id),
        onComplete: callback || this.onSubmit
  		});
  },

  onSubmit: function(originalRequest) {
    var results = originalRequest.responseText.split(";");

    if (results[0] == "true") {
      SmartyAjax.Messages.set(results[1], SmartyAjax.Messages.MT_WARNING)
    } else {
      SmartyAjax.Messages.clear();
      SmartyAjax.Messages.setType(SmartyAjax.Messages.MT_ERROR);
      for (var i = 1; i < results.length; i++) {
        SmartyAjax.Messages.add(results[i]);
      }
    }
  }
}


SmartyAjax.GlobalHandlers = {
	onCreate: function() {
		SmartyAjax.Process.show();
	}

};


Ajax.Responders.register(SmartyAjax.GlobalHandlers);


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



SmartyAjax.Messages = {
  MT_WARNING: 0,
  MT_ERROR: 1,

  S_MT_WARNING: "Please note:",
  S_MT_ERROR: "Please fix following errors:",

  initialize: function() {
    this.messages = $("messages");
    this.messagesTitle = $("messages-title");
  },

  clear: function() {
    if (!this.messagesList) {
      this.messagesList = $("messages-list");
    	if (!this.messagesList) return;
    }
  	this.messagesList.innerHTML = "";
  	this.messagesList.style.display = "none";
  },

  add: function(message) {
    if (!this.messagesList) {
      this.messagesList = $("messages-list");
    	if (!this.messagesList) return;
    }
  	var messageLI = document.createElement("LI");
  	messageLI.innerHTML = message;
  	this.messagesList.appendChild(messageLI);
  	this.messagesList.style.display = "block";
  	Element.scrollTo("messages");
  },

  set: function(message, type) {
    this.clear();
    this.setType(type);
    this.add(message);
  },

  setType: function(type) {
    if (!this.messages) this.messages = $("messages");
  	if (!this.messagesTitle) this.messagesTitle = $("messages-title");
  	switch (type) {
  	  case this.MT_ERROR:
  	    if (this.messages) this.messages.className = "data-error";
  	    if (this.messagesTitle) this.messagesTitle = this.S_MT_ERROR;
  	    break;
  	  case this.MT_WARNING:
  	  default:
  	    if (this.messages) this.messages.className = "data-warning";
  	    if (this.messagesTitle) this.messagesTitle = this.S_MT_WARNING;
  	}
  }
}

/*
SmartyAjax.Process = {
  S_PROCESS: '<img src="/templates/core/images/ajax/2.gif" /> Wait while processing your request... ',

  show: function() {
    if (!this.process) {
      this.process = $("ajax-process");
      if (!this.process) return;
    }
    var top = window.pageYOffset ? window.pageYOffset : document.documentElement ? document.documentElement.scrollTop : document.body.scrollTop;
    this.process.style.top = top + "px";
    this.process.innerHTML = this.S_PROCESS;
    this.process.style.display = "block";
  },

  hide: function() {
    if (!this.process) {
      this.process = $("ajax-process");
      if (!this.process) return;
    }
    this.process.style.display = "none";
  }
}

*/
