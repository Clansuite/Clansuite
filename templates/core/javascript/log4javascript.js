/**
 * log4javascript
 *
 * log4javascript is a logging framework for JavaScript based on log4j
 * for Java.
 *
 * Author: Tim Down
 * Version: 0.9
 * Last modified: 26/3/2006
 */
 

var log4javascript = (function() {
	var applicationStartDate = new Date();

	// Create logging object; this will be assigned properties and returned
	var log4javascript = new Object();
	log4javascript.version = "0.9";

	// Hashtable of loggers keyed by logger name
	var loggers = new Object();
	
	// Log levels
	log4javascript.Level = function(level, name) {
		this.level = level;
		this.name = name;
	};
	
	log4javascript.Level.prototype.toString = function() {
		return this.name;
	};
	
	log4javascript.Level.DEBUG = new log4javascript.Level(1, "DEBUG");
	log4javascript.Level.INFO = new log4javascript.Level(2, "INFO");
	log4javascript.Level.WARN = new log4javascript.Level(3, "WARN");
	log4javascript.Level.ERROR = new log4javascript.Level(4, "ERROR");
	log4javascript.Level.FATAL = new log4javascript.Level(5, "FATAL");
	log4javascript.Level.NONE = new log4javascript.Level(6, "NONE");

	log4javascript.getLogger = function(loggerName) {
		// Use default logger if loggerName is not specified or invalid
		if (!(typeof loggerName == "string")) {
			loggerName = "[root]";
		}
		
		// Create the logger for this name if it doesn't already exist
		if (!loggers[loggerName]) {
			var appenders = new Array();
			var log = function(logger, level, message, exception) {
				if (level.level >= logger.level.level) {
					var loggingEvent = new log4javascript.LoggingEvent(logger, new Date(), level, message, exception);
					appenders.log4javascript_foreach(
						function(appender) {
							appender.doAppend(loggingEvent);
						}
					);
				}
			};
			// Create the public API for the logger
			loggers[loggerName] = {
				name		: loggerName,
				level		: log4javascript.Level.DEBUG,
				addAppender : function(appender) {
					if (!appender instanceof log4javascript.Appender) {
						throw new Error("appender supplied is not a subclass of Appender");
					}
					appenders.push(appender);
				},
				debug		: function(message, exception) {
					log(this, log4javascript.Level.DEBUG, message, exception)
				},
				info		: function(message, exception) {
					log(this, log4javascript.Level.INFO, message, exception)
				},
				warn		: function(message, exception) {
					log(this, log4javascript.Level.WARN, message, exception)
				},
				error		: function(message, exception) {
					log(this, log4javascript.Level.ERROR, message, exception)
				},
				fatal		: function(message, exception) {
					log(this, log4javascript.Level.FATAL, message, exception)
				},
				setLevel	: function(level) {
					this.level = level;
				}
			};
		}
		return loggers[loggerName];
	};
	
	log4javascript.getDefaultLogger = function() {
		var log = log4javascript.getLogger();
		var a = new log4javascript.PopUpAppender(true, true, true, true);
		log.addAppender(a);
		return log;
	};

	/* --------------------------------------------------------------------- */

	log4javascript.LoggingEvent = function(logger, timestamp, level, message, exception) {
		this.logger = logger;
		this.timestamp = timestamp;
		this.level = level;
		this.message = message;
		this.exception = exception;
	};
	
	/* --------------------------------------------------------------------- */

	// Layout "abstract class"
	log4javascript.Layout = function() {
	};

	log4javascript.Layout.prototype.format = function(loggingEvent) {
		throw new Error("layout supplied has no format() method");
	};

	log4javascript.Layout.prototype.getContentType = function() {
		return "text/plain";
	};

	/* --------------------------------------------------------------------- */

	// SimpleLayout 
	log4javascript.SimpleLayout = function() {
	};
	
	log4javascript.SimpleLayout.prototype = new log4javascript.Layout();

	log4javascript.SimpleLayout.prototype.format = function(loggingEvent) {
		return loggingEvent.level.name + " - " + loggingEvent.message;
	};

	/* --------------------------------------------------------------------- */

	// PatternLayout 
	log4javascript.PatternLayout = function(pattern) {
		if (pattern) {
			this.pattern = pattern;
		} else {
			this.pattern = log4javascript.PatternLayout.DEFAULT_CONVERSION_PATTERN;
		}
	};

	log4javascript.PatternLayout.TTCC_CONVERSION_PATTERN = "%r %p %c - %m";
	log4javascript.PatternLayout.DEFAULT_CONVERSION_PATTERN = "%m%n";
	log4javascript.PatternLayout.ISO8601_DATEFORMAT = "yyyy-MM-dd HH:mm:ss,SSS";
	log4javascript.PatternLayout.DATETIME_DATEFORMAT = "dd MMM YYYY HH:mm:ss,SSS";
	log4javascript.PatternLayout.ABSOLUTETIME_DATEFORMAT = "HH:mm:ss,SSS";

	log4javascript.PatternLayout.prototype = new log4javascript.Layout();

	log4javascript.PatternLayout.prototype.format = function(loggingEvent) {
		var regex = /%(-?[0-9]+)?(\.?[0-9]+)?([cdmnpr%])(\{([^\}]+)\})?|([^%]+)/g;
		var formattedString = "";
		var result;
		
		while ((result = regex.exec(this.pattern))) {
			var matchedString = result[0];
			var padding = result[1];
			var truncation = result[2];
			var conversionCharacter = result[3];
			var specifier = result[5];
			var text = result[6];
			
			// Check if the pattern matched was just normal text
			if (text) {
				formattedString += "" + text;
			} else {
				// Create a raw replacement string based on the conversion
				// character and specifier
				var replacement = "";
				switch(conversionCharacter) {
					case "c":
						var loggerName = loggingEvent.logger.name;
						if (specifier) {
							var precision = parseInt(specifier);
							var loggerNameBits = loggingEvent.logger.name.split(".");
							if (precision >= loggerNameBits.length) {
								replacement = loggerName;
							} else {
								replacement = loggerNameBits.slice(loggerNameBits.length - precision).join(".");
							}
						} else {
							replacement = loggerName;
						}
						break;
					case "d":
						var dateFormat = log4javascript.PatternLayout.ISO8601_DATEFORMAT;
						if (specifier) {
							var dateFormat = specifier;
							// Pick up special cases
							if (dateFormat == "ISO8601") {
								dateFormat = log4javascript.PatternLayout.ISO8601_DATEFORMAT;
							} else if (dateFormat == "ABSOLUTE") {
								dateFormat = log4javascript.PatternLayout.ABSOLUTETIME_DATEFORMAT;
							} else if (dateFormat == "DATE") {
								dateFormat = log4javascript.PatternLayout.DATETIME_DATEFORMAT;
							}
						}
						// Format the date
						replacement = (new SimpleDateFormat(dateFormat)).format(loggingEvent.timestamp);
						break;
					case "m":
						replacement = loggingEvent.message;
						break;
					case "n":
						replacement = "\n";
						break;
					case "p":
						replacement = loggingEvent.level.name;
						break;
					case "r":
						replacement = "" + loggingEvent.timestamp.getDifference(applicationStartDate);
						break;
					case "%":
						replacement = "%";
						break;
					default:
						replacement = matchedString;
						break;
				}
				// Format the replacement according to any padding or
				// truncation specified

				// First, truncation
				if (truncation) {
					var len = parseInt(truncation.substr(1));
					replacement = replacement.substring(0, len);
				}
				// Next, padding
				if (padding) {
					if (padding.charAt(0) == "-") {
						var len = parseInt(padding.substr(1));
						// Right pad with spaces
						while (replacement.length < len) {
							replacement += " ";
						}
					} else {
						var len = parseInt(padding);
						// Left pad with spaces
						while (replacement.length < len) {
							replacement = " " + replacement;
						}
					}
				}
				formattedString += replacement;
			}
		}
		return formattedString;
	};

	/* --------------------------------------------------------------------- */

	var defaultLayout = new log4javascript.PatternLayout();

	// Appender "abstract class"
	log4javascript.Appender = function() {
		this.layout = defaultLayout;
	};

	log4javascript.Appender.prototype.doAppend = function(loggingEvent) {
		throw new Error("appender supplied does not have a doAppend() method");
	};

	log4javascript.Appender.prototype.setLayout = function(layout) {
		if (!layout instanceof log4javascript.Layout) {
			throw new Error("layout supplied is not a subclass of Layout");
		}
		this.layout = layout;
	};

	log4javascript.Appender.prototype.getLayout = function() {
		return this.layout;
	};
	
	/* --------------------------------------------------------------------- */

	// AlertAppender
	log4javascript.AlertAppender = function() {
		this.setLayout(log4javascript.AlertAppender.defaultLayout);
	};
	
	log4javascript.AlertAppender.prototype = new log4javascript.Appender();
	
	log4javascript.AlertAppender.defaultLayout = new log4javascript.SimpleLayout();

	log4javascript.AlertAppender.prototype.init = function() {};

	log4javascript.AlertAppender.prototype.doAppend = function(loggingEvent) {
		var formattedMessage = this.layout.format(loggingEvent);
		alert(formattedMessage);
	};

	/* --------------------------------------------------------------------- */

	var consoleWindowUrl = "console.html";

	// BaseConsoleAppender
	log4javascript.BaseConsoleAppender = function() {};
	
	log4javascript.BaseConsoleAppender.prototype = new log4javascript.Appender();
	
	log4javascript.BaseConsoleAppender.prototype.init_console = function() {
		this.initialized = false;
		this.consoleWindow = null;
		this.consoleWindowLoaded = false;
		this.queuedLoggingEvents = new Array();
	};

	log4javascript.BaseConsoleAppender.prototype.pollConsoleWindow = function() {
		var appender = this;

		function pollConsoleWindowLoaded() {
			if (appender.consoleWindowLoaded) {
				clearInterval(poll);
			} else if (appender.consoleWindow && appender.consoleWindow.loaded) {
				clearInterval(poll);
				finalInit();
			}
		}
		
		function finalInit() {
			appender.consoleWindowLoaded = true;
			appender.appendQueuedLoggingEvents();
		}
		
		// Poll the pop-up since the onload event is not reliable
		var poll = setInterval(pollConsoleWindowLoaded, 100);
	};

	log4javascript.BaseConsoleAppender.prototype.doAppend = function(loggingEvent) {
		if (!this.initialized) {
			this.init();
		}
		this.queuedLoggingEvents.push(loggingEvent);
		this.appendQueuedLoggingEvents();
	};

	log4javascript.BaseConsoleAppender.prototype.appendQueuedLoggingEvents = function(loggingEvent) {
		if (this.safeToAppend()) {
			while (this.queuedLoggingEvents.length > 0) {
				var currentLoggingEvent = this.queuedLoggingEvents.shift();
				var formattedMessage = this.layout.format(currentLoggingEvent);
				this.consoleWindow.log(currentLoggingEvent.level, formattedMessage);
			}
			if (this.focusConsoleWindow) {
				this.consoleWindow.focus();
			}
		}
	};

	/* --------------------------------------------------------------------- */

	var appenderId = 0;

	// PopUpAppender
	log4javascript.PopUpAppender = function(focusPopUp, lazyInit, keepLogWhenPageReloads,
			complainAboutPopUpBlocking, width, height) {
		this.focusConsoleWindow = focusPopUp ? true : false;
		this.lazyInit = lazyInit ? true : false;
		this.keepLogWhenPageReloads = keepLogWhenPageReloads ? true : false;
		this.complainAboutPopUpBlocking = complainAboutPopUpBlocking ? true : false;
		this.width = width ? width : this.defaults.width;
		this.height = height ? height : this.defaults.height;
		this.popUpClosed = false;
		this.setLayout(this.defaults.layout);
		this.init_console();
		this.appenderId = appenderId++;
		if (!this.lazyInit) {
			this.init();
		}
	};
	
	log4javascript.PopUpAppender.prototype = new log4javascript.BaseConsoleAppender();

	log4javascript.PopUpAppender.prototype.defaults = {
		layout: new log4javascript.PatternLayout("%d{HH:mm:ss} %-5p - %m"),
		width: "600",
		height: "400"
	};
	
	log4javascript.PopUpAppender.prototype.init = function() {
		var appender = this;
		var windowProperties = "width=" + this.width + ",height=" + this.height + ",status,resizable";
		var windowName = "log4javascriptPopUp" + this.appenderId;
		
		if (this.keepLogWhenPageReloads) {
			this.consoleWindow = window.open("", windowName, windowProperties);
			if (this.consoleWindow && !(new RegExp(consoleWindowUrl).test(this.consoleWindow.location.href))) {
				this.consoleWindow.location.href = consoleWindowUrl;
			} else if (this.consoleWindow) {
				this.consoleWindow.mainPageReloaded();
			}
		} else {
			this.consoleWindow = window.open(consoleWindowUrl, windowName, windowProperties);
		}
		
		if (this.consoleWindow) {
			this.popUpsBlocked = false;
			this.pollConsoleWindow();
		} else {
			this.popUpsBlocked = true;
			if (this.complainAboutPopUpBlocking) {
				alert("Pop-up windows appear to be blocked. Please unblock them to use pop-up logging");
			}
		}
		this.initialized = true;
	};


	log4javascript.PopUpAppender.prototype.safeToAppend = function() {
		if (!this.popUpsBlocked && !this.popUpClosed) {
			if (this.consoleWindow.closed) {
				this.popUpClosed = true;
				return false;
			}
			if (!this.consoleWindowLoaded && this.consoleWindow.loaded) {
				this.consoleWindowLoaded = true;
			}
		}
		return !this.popUpsBlocked && this.consoleWindowLoaded && !this.popUpClosed;
	};

	/* --------------------------------------------------------------------- */

	// InlineAppender
	log4javascript.InlineAppender = function(containerElement, startMinimized, lazyInit, width, height) {
		this.containerElement = containerElement;
		this.startMinimized = startMinimized ? true : false;
		this.lazyInit = lazyInit ? true : false;
		this.width = width ? width : this.defaults.width;
		this.height = height ? height : this.defaults.height;
		this.setLayout(this.defaults.layout);
		this.focusConsoleWindow = false;
		this.init_console();
		if (!this.lazyInit) {
			this.init();
		}
	};
	
	log4javascript.InlineAppender.prototype = new log4javascript.BaseConsoleAppender();

	log4javascript.InlineAppender.prototype.defaults = {
		layout: new log4javascript.PatternLayout("%d{HH:mm:ss} %-5p - %m"),
		width: "100%",
		height: "250px"
	}
	
	log4javascript.InlineAppender.prototype.init = function() {
		var appender = this;
		
		var hide = function() {
			appender.switcherButton.value = "Show log";
			appender.iframe.style.display = "none";
			appender.minimized = true;
		}
		
		var show = function() {
			appender.switcherButton.value = "Hide log";
			appender.iframe.style.display = "block";
			if (appender.safeToAppend()) {
				appender.consoleWindow.scrollToLatestEntry();
			}
			appender.minimized = false;
		}
		
		this.minimized = this.startMinimized;

		this.switcherDiv = this.containerElement.appendChild(document.createElement("div"));

		this.switcherButton = document.createElement("input");
		this.switcherButton.type = "button";
		this.switcherButton.onclick = function() {
			if (appender.minimized) {
				show();
			} else {
				hide();
			}
		};
		this.switcherDiv.appendChild(this.switcherButton);
		
		this.iframe = this.containerElement.appendChild(document.createElement("iframe"));
		this.iframe.width = this.width;
		this.iframe.height = this.height;
		this.iframe.src = consoleWindowUrl;
		this.iframe.frameBorder = 0;
		this.iframe.style.border = "solid gray 1px";
		
		this.consoleWindow = this.iframe.contentWindow;

		if (this.startMinimized) {
			hide();
		} else {
			show();
		}
		
		this.pollConsoleWindow();
		this.initialized = true;
	};

	log4javascript.InlineAppender.prototype.safeToAppend = function() {
		if (!this.consoleWindowLoaded && this.consoleWindow.loaded) {
			this.consoleWindowLoaded = true;
		}
		return this.consoleWindowLoaded;
	};

	return log4javascript;
})();

/* ------------------------------------------------------------------------- */

// Array-related stuff

Array.prototype.log4javascript_foreach = function(action) {
	for (var i = 0; i < this.length; i++) {
		action(this[i]);
	}
};

if (!Array.prototype.push) {
	Array.prototype.push = function() {
        for (var i = 0; i < arguments.length; i++){
            this[this.length] = arguments[i];
        }
        return this.length;
	};
}

if (!Array.prototype.shift) {
	Array.prototype.shift = function() {
		if (this.length > 0) {
			var firstItem = this[0];
			for (var i = 0; i < this.length - 1; i++) {
				this[i] = this[i + 1];
			}
			this.length = this.length - 1;
			return firstItem;
		}
	};
}

/* ------------------------------------------------------------------------- */

// Date-related stuff
Date.prototype.getDifference = function(date) {
	return (this.getTime() - date.getTime());
};

var SimpleDateFormat = (function() {
	var regex = /('[^']*')|(G+|y+|M+|w+|W+|D+|d+|F+|E+|a+|H+|k+|K+|h+|m+|s+|S+|Z+)|([a-zA-Z]+)|([^a-zA-Z']+)/g;
	var monthNames = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"];
	var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	var TEXT2 = 0, TEXT3 = 1, NUMBER = 2, YEAR = 3, MONTH = 4, TIMEZONE = 5;
	var types = {
		G : TEXT2,
		y : YEAR,
		Y : YEAR,
		M : MONTH,
		w : NUMBER,
		W : NUMBER,
		D : NUMBER,
		d : NUMBER,
		F : NUMBER,
		E : TEXT3,
		a : TEXT2,
		H : NUMBER,
		k : NUMBER,
		K : NUMBER,
		h : NUMBER,
		m : NUMBER,
		s : NUMBER,
		S : NUMBER,
		Z : TIMEZONE
	};
	var ONE_DAY = 24 * 60 * 60 * 1000;
	var ONE_WEEK = 7 * ONE_DAY;
	var DEFAULT_MINIMAL_DAYS_IN_FIRST_WEEK = 1;
	
	Date.prototype.isBefore = function(d) {
		return this.getTime() < d.getTime();
	};
	
	Date.prototype.getWeekInYear = function(minimalDaysInFirstWeek) {
		var previousSunday = new Date(this.getTime() - this.getDay() * ONE_DAY);
		var startOfYear = new Date(this.getFullYear(), 0, 1);
		var numberOfSundays = previousSunday.isBefore(startOfYear) ? 
			0 : 1 + Math.floor((previousSunday.getTime() - startOfYear.getTime()) / ONE_WEEK);
		var numberOfDaysInFirstWeek =  7 - startOfYear.getDay();
		var weekInYear = numberOfSundays;
		if (numberOfDaysInFirstWeek >= minimalDaysInFirstWeek) {
			weekInYear++;
		}
		return weekInYear;
	};

	Date.prototype.getWeekInMonth = function(minimalDaysInFirstWeek) {
		var previousSunday = new Date(this.getTime() - this.getDay() * ONE_DAY);
		var startOfMonth = new Date(this.getFullYear(), this.getMonth(), 1);
		var numberOfSundays = previousSunday.isBefore(startOfMonth) ? 
			0 : 1 + Math.floor((previousSunday.getTime() - startOfMonth.getTime()) / ONE_WEEK);
		var numberOfDaysInFirstWeek =  7 - startOfMonth.getDay();
		var weekInMonth = numberOfSundays;
		if (numberOfDaysInFirstWeek >= minimalDaysInFirstWeek) {
			weekInMonth++;
		}
		return weekInMonth;
	};

	Date.prototype.getDayInYear = function() {
		var startOfYear = new Date(this.getFullYear(), 0, 1);
		return 1 + Math.floor((this.getTime() - startOfYear.getTime()) / ONE_DAY);
	};

	/* --------------------------------------------------------------------- */

	SimpleDateFormat = function(formatString) {
		this.formatString = formatString;
	};
	
	/**
	 * Sets the minimum number of days in a week in order for that week to
	 * be considered as belonging to a particular month or year
	 */
	SimpleDateFormat.prototype.setMinimalDaysInFirstWeek = function(days) {
		this.minimalDaysInFirstWeek = days;
	};
	
	SimpleDateFormat.prototype.getMinimalDaysInFirstWeek = function(days) {
		return (typeof this.minimalDaysInFirstWeek == "undefined")
				? DEFAULT_MINIMAL_DAYS_IN_FIRST_WEEK : this.minimalDaysInFirstWeek;
	};
	
	SimpleDateFormat.prototype.format = function(date) {
		var formattedString = "";
		var result;

		var padWithZeroes = function(str, len) {
			while (str.length < len) {
				str = "0" + str;
			}
			return str;
		};

		var formatText = function(data, numberOfLetters, minLength) {
			return (numberOfLetters >= 4) ? data : data.substr(0, Math.max(minLength, numberOfLetters));
		};

		var formatNumber = function(data, numberOfLetters) {
			var dataString = "" + data;
			// Pad with 0s as necessary
			return padWithZeroes(dataString, numberOfLetters);
		};

		while ((result = regex.exec(this.formatString))) {
			var matchedString = result[0];
			var quotedString = result[1];
			var patternLetters = result[2];
			var otherLetters = result[3];
			var otherCharacters = result[4];

			// If the pattern matched is quoted string, output the text between the quotes
			if (quotedString) { // Quoted string
				if (quotedString == "''") {
					formattedString += "'";
				} else {
					formattedString += quotedString.substring(1, quotedString.length - 1);
				}
			} else if (otherLetters) { // Other letters
				// Swallow non-pattern letters by doing nothing here
			} else if (otherCharacters) { // Other characters
				// Simply output other characters
				formattedString += otherCharacters;
			} else if (patternLetters) { // Pattern letters
				// Replace pattern letters
				var patternLetter = patternLetters.charAt(0);
				var numberOfLetters = patternLetters.length;
				var rawData = "";
				switch (patternLetter) {
					case "G":
						rawData = "AD";
						break;
					case "y":
						rawData = date.getFullYear();
						break;
					case "M":
						rawData = date.getMonth();
						break;
					case "w":
						rawData = date.getWeekInYear(this.minimalDaysInFirstWeek);
						break;
					case "W":
						rawData = date.getWeekInMonth(this.minimalDaysInFirstWeek);
						break;
					case "D":
						rawData = date.getDayInYear();
						break;
					case "d":
						rawData = date.getDate();
						break;
					case "F":
						rawData = 1 + Math.floor((date.getDate() - 1) / 7);
						break;
					case "E":
						rawData = dayNames[date.getDay()];
						break;
					case "a":
						rawData = (date.getHours() >= 12) ? "PM" : "AM";
						break;
					case "H":
						rawData = date.getHours();
						break;
					case "k":
						rawData = 1 + date.getHours();
						break;
					case "K":
						rawData = date.getHours() % 12;
						break;
					case "h":
						rawData = 1 + (date.getHours() % 12);
						break;
					case "m":
						rawData = date.getMinutes();
						break;
					case "s":
						rawData = date.getSeconds();
						break;
					case "S":
						rawData = date.getMilliseconds();
						break;
					case "Z":
						rawData = date.getTimezoneOffset(); // This is returns the number of minutes since GMT was this time.
						break;
				}
				// Format the raw data depending on the type
				switch (types[patternLetter]) {
					case TEXT2:
						formattedString += formatText(rawData, numberOfLetters, 2);
						break;
					case TEXT3:
						formattedString += formatText(rawData, numberOfLetters, 3);
						break;
					case NUMBER:
						formattedString += formatNumber(rawData, numberOfLetters);
						break;
					case YEAR:
						if (numberOfLetters <= 2) {
							// Output a 2-digit year
							var dataString = "" + rawData;
							formattedString += dataString.substr(2, 2);
						} else {
							formattedString += formatNumber(rawData, numberOfLetters);
						}
						break;
					case MONTH:
						if (numberOfLetters >= 3) {
							formattedString += formatText(monthNames[rawData], numberOfLetters, numberOfLetters);
						} else {
							// NB. Months returned by getMonth are zero-based
							formattedString += formatNumber(rawData + 1, numberOfLetters);
						}
						break;
					case TIMEZONE:
						var isPositive = (rawData > 0);
						// The following line looks like a mistake but isn't
						// because of the way getTimezoneOffset measures.
						var prefix = isPositive ? "-" : "+";
						var absData = Math.abs(rawData);

						// Hours
						var hours = "" + Math.floor(absData / 60);
						hours = padWithZeroes(hours, 2);
						// Minutes
						var minutes = "" + (absData % 60);
						minutes = padWithZeroes(minutes, 2);
						
						formattedString += prefix + hours + minutes;
						break;
				}
			}
		}
		return formattedString;
	};
	return SimpleDateFormat;
})();
