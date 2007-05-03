/**
 * Copyright 2006 Tim Down.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * log4javascript
 *
 * log4javascript is a logging framework for JavaScript based on log4j
 * for Java. This file contains all core log4javascript code and is the only
 * file required to use log4javascript. If you wish to disable log4javascript
 * in production code, replace log4javascript.js with the stub file
 * log4javascript_stub.js, included in the distribution. Also included in the
 * distribution is log4javascript.js, a compressed but functionally
 * identical version of this file.
 *
 * Author: Tim Down <tim@timdown.co.uk>
 * Version: 1.3.1
 * Last modified: 8/11/2006
 * Website: http://www.timdown.co.uk/log4javascript
 */

/* ------------------------------------------------------------------------- */

// Array-related stuff

// Next three methods are solely for IE5, which is missing them
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

if (!Array.prototype.splice) {
	Array.prototype.splice = function(startIndex, deleteCount) {
		var itemsAfterDeleted = this.slice(startIndex + deleteCount);
		var itemsDeleted = this.slice(startIndex, startIndex + deleteCount);
		this.length = startIndex;
		// Copy the arguments into a proper Array object
		var argumentsArray = [];
		for (var i = 0; i < arguments.length; i++) {
			argumentsArray[i] = arguments[i];
		}
		var itemsToAppend = (argumentsArray.length > 2) ? 
			itemsAfterDeleted = argumentsArray.slice(2).concat(itemsAfterDeleted) : itemsAfterDeleted;
		for (i = 0; i < itemsToAppend.length; i++) {
			this.push(itemsToAppend[i]);
		}
		return itemsDeleted;
	};
}

/* ------------------------------------------------------------------------- */

var log4javascript;
var SimpleDateFormat;

(function() {
	function isUndefined(obj) {
		return typeof obj == "undefined";
	}

	// Date-related stuff
	(function() {
		var regex = /('[^']*')|(G+|y+|M+|w+|W+|D+|d+|F+|E+|a+|H+|k+|K+|h+|m+|s+|S+|Z+)|([a-zA-Z]+)|([^a-zA-Z']+)/;
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

		Date.prototype.getDifference = function(date) {
			return this.getTime() - date.getTime();
		};

		Date.prototype.isBefore = function(d) {
			return this.getTime() < d.getTime();
		};

		Date.prototype.getWeekInYear = function(minimalDaysInFirstWeek) {
			if (isUndefined(this.minimalDaysInFirstWeek)) {
				minimalDaysInFirstWeek = DEFAULT_MINIMAL_DAYS_IN_FIRST_WEEK;
			}
			var previousSunday = new Date(this.getTime() - this.getDay() * ONE_DAY);
			previousSunday = new Date(previousSunday.getFullYear(), previousSunday.getMonth(), previousSunday.getDate());
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
			if (isUndefined(this.minimalDaysInFirstWeek)) {
				minimalDaysInFirstWeek = DEFAULT_MINIMAL_DAYS_IN_FIRST_WEEK;
			}
			var previousSunday = new Date(this.getTime() - this.getDay() * ONE_DAY);
			previousSunday = new Date(previousSunday.getFullYear(), previousSunday.getMonth(), previousSunday.getDate());
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

		/* ----------------------------------------------------------------- */

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
			return isUndefined(this.minimalDaysInFirstWeek)	?
				DEFAULT_MINIMAL_DAYS_IN_FIRST_WEEK : this.minimalDaysInFirstWeek;
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

			var searchString = this.formatString;
			while ((result = regex.exec(searchString))) {
				var matchedString = result[0];
				var quotedString = result[1];
				var patternLetters = result[2];
				var otherLetters = result[3];
				var otherCharacters = result[4];

				// If the pattern matched is quoted string, output the text between the quotes
				if (quotedString) {
					if (quotedString == "''") {
						formattedString += "'";
					} else {
						formattedString += quotedString.substring(1, quotedString.length - 1);
					}
				} else if (otherLetters) {
					// Swallow non-pattern letters by doing nothing here
				} else if (otherCharacters) {
					// Simply output other characters
					formattedString += otherCharacters;
				} else if (patternLetters) {
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
							rawData = date.getWeekInYear(this.getMinimalDaysInFirstWeek());
							break;
						case "W":
							rawData = date.getWeekInMonth(this.getMinimalDaysInFirstWeek());
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
							rawData = date.getTimezoneOffset(); // This returns the number of minutes since GMT was this time.
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
				searchString = searchString.substr(result.index + result[0].length);
			}
			return formattedString;
		};
	})();

	/* ------------------------------------------------------------------------- */

	var applicationStartDate = new Date();
	var uniqueId = "log4javascript_" + applicationStartDate.getTime() + "_" +
		Math.floor(Math.random() * 100000000);
	var emptyFunction = function() {};
	var newLine = "\r\n";

	// Create logging object; this will be assigned properties and returned
	log4javascript = {};
	log4javascript.version = "1.3.1";

	// Returns a nicely formatted representation of an error
	function getExceptionStringRep(ex) {
		if (ex) {
			var exStr = "Exception: ";
			if (ex.message) {
				exStr += ex.message;
			} else if (ex.description) {
				exStr += ex.description;
			}
			if (ex.lineNumber) {
				exStr += " on line number " + ex.lineNumber;
			}
			if (ex.fileName) {
				exStr += " in file " + ex.fileName;
			}
			if (showStackTraces && ex.stack) {
				exStr += newLine + "Stack trace:" + newLine + ex.stack;
			}
			return exStr;
		}
		return null;
	}

	function formatObjectExpansion(obj, depth, indentation) {
		var i, output, childDepth, childIndentation, childLines;
		if ((obj instanceof Array) && depth > 0) {
			if (!indentation) {
				indentation = "";
			}
			output = "[" + newLine;
			childDepth = depth - 1;
			childIndentation = indentation + "  ";
			childLines = [];
			for (i = 0; i < obj.length; i++) {
				childLines.push(childIndentation + formatObjectExpansion(obj[i], childDepth, childIndentation));
			}
			output += childLines.join("," + newLine) + newLine + indentation + "]";
			return output;
		} else if (typeof obj == "object" && depth > 0) {
			if (!indentation) {
				indentation = "";
			}
			output = "" + "{" + newLine;
			childDepth = depth - 1;
			childIndentation = indentation + "  ";
			childLines = [];
			for (i in obj) {
				childLines.push(childIndentation + i + ": " + formatObjectExpansion(obj[i], childDepth, childIndentation));
			}
			output += childLines.join("," + newLine) + newLine + indentation + "}";
			return output;
		} else if (typeof obj == "string") {
			return obj;
		} else {
			return obj.toString();
		}
	}

	function escapeNewLines(str) {
		return str.replace(/\r\n|\r|\n/g, "\\r\\n");
	}

	function urlEncode(str) {
		return escape(str).replace(/\+/g, "%2B").replace(/"/g, "%22").replace(/'/g, "%27").replace(/\//g, "%2F");
	}
	
	function bool(obj) {
		return Boolean(obj);
	}

	function array_remove(arr, val) {
		var index = -1;
		for (var i = 0; i < arr.length; i++) {
			if (arr[i] === val) {
				index = i;
				break;
			}
		}
		if (index >= 0) {
			arr.splice(index, 1);
			return true;
		} else {
			return false;
		}
	}

	function extractBooleanFromParam(param, defaultValue) {
		if (isUndefined(param)) {
			return defaultValue;
		} else {
			return bool(param);
		}
	}

	function extractStringFromParam(param, defaultValue) {
		if (isUndefined(param)) {
			return defaultValue;
		} else {
			return String(param);
		}
	}

	function extractIntFromParam(param, defaultValue) {
		if (isUndefined(param)) {
			return defaultValue;
		} else {
			try {
				var value = parseInt(param, 10);
				return isNaN(value) ? defaultValue : value;
			} catch (ex) {
				logLog.warn("Invalid int param " + param, ex);
				return defaultValue;
			}
		}
	}

	function extractFunctionFromParam(param, defaultValue) {
		if (typeof param == "function") {
			return param;
		} else {
			return defaultValue;
		}
	}

	/* --------------------------------------------------------------------- */

	// Simple logging for log4javascript itself

	var logLog = {
		quietMode: false,

		setQuietMode: function(quietMode) {
			this.quietMode = bool(quietMode);
		},

		numberOfErrors: 0,

		alertAllErrors: false,

		setAlertAllErrors: function(alertAllErrors) {
			this.alertAllErrors = alertAllErrors;
		},

		debug: function(message, exception) {
		},

		warn: function(message, exception) {
		},

		error: function(message, exception) {
			if (++this.numberOfErrors == 1 || this.alertAllErrors) {
				if (!this.quietMode) {
					var alertMessage = "log4javascript error: " + message;
					if (exception) {
						alertMessage += newLine + newLine + "Original error: " + getExceptionStringRep(exception);
					}
					alert(alertMessage);
				}
			}
		}
	};
	log4javascript.logLog = logLog;

	/* --------------------------------------------------------------------- */

	var errorListeners = [];

	log4javascript.addErrorListener = function(listener) {
		if (typeof listener == "function") {
			errorListeners.push(listener);
		} else {
			handleError("addErrorListener: listener supplied was not a function");
		}
	};

	log4javascript.removeErrorListener = function(listener) {
		array_remove(errorListeners, listener);
	};

	function handleError(message, exception) {
		logLog.error(message, exception);
		for (var i = 0; i < errorListeners.length; i++) {
			errorListeners[i](message, exception);
		}
	}

	/* --------------------------------------------------------------------- */

	var enabled = (typeof log4javascript_disabled != "undefined") &&
		log4javascript_disabled ? false : true;

	log4javascript.setEnabled = function(enable) {
		enabled = bool(enable);
	};

	log4javascript.isEnabled = function() {
		return enabled;
	};

	// This evaluates the given expression in the current scope, thus allowing
	// scripts to access private variables. Particularly useful for testing
	log4javascript.evalInScope = function(expr) {
		return eval(expr);
	};

	var showStackTraces	= false;

	log4javascript.setShowStackTraces = function(show) {
		showStackTraces = bool(show);
	};

	/* --------------------------------------------------------------------- */

	function Logger(name) {
		this.name = name;
		var appenders = [];
		var loggerLevel = Level.DEBUG;

		// Create methods that use the appenders variable in this scope
		this.addAppender = function(appender) {
			if (appender instanceof log4javascript.Appender) {
				appenders.push(appender);
			} else {
				handleError("Logger.addAppender: appender supplied is not a subclass of Appender");
			}
		};

		this.removeAppender = function(appender) {
			array_remove(appenders, appender);
		};

		this.removeAllAppenders = function(appender) {
			appenders.length = 0;
		};

		this.log = function(level, message, exception) {
			if (level.isGreaterOrEqual(loggerLevel)) {
				var loggingEvent = new LoggingEvent(
					this, new Date(), level, message, exception);
				for (var i = 0; i < appenders.length; i++) {
					appenders[i].doAppend(loggingEvent);
				}
			}
		};

		this.setLevel = function(level) {
			loggerLevel = level;
		};

		this.getLevel = function() {
			return loggerLevel;
		};
	}

	Logger.prototype = {
		trace: function(message, exception) {
			this.log(Level.TRACE, message, exception);
		},

		debug: function(message, exception) {
			this.log(Level.DEBUG, message, exception);
		},

		info: function(message, exception) {
			this.log(Level.INFO, message, exception);
		},

		warn: function(message, exception) {
			this.log(Level.WARN, message, exception);
		},

		error: function(message, exception) {
			this.log(Level.ERROR, message, exception);
		},

		fatal: function(message, exception) {
			this.log(Level.FATAL, message, exception);
		}
	};

	Logger.prototype.trace.isEntryPoint = true;
	Logger.prototype.debug.isEntryPoint = true;
	Logger.prototype.info.isEntryPoint = true;
	Logger.prototype.warn.isEntryPoint = true;
	Logger.prototype.error.isEntryPoint = true;
	Logger.prototype.fatal.isEntryPoint = true;

	/* --------------------------------------------------------------------- */

	// Hashtable of loggers keyed by logger name
	var loggers = {};

	log4javascript.getLogger = function(loggerName) {
		// Use default logger if loggerName is not specified or invalid
		if (!(typeof loggerName == "string")) {
			loggerName = "[anonymous]";
		}

		// Create the logger for this name if it doesn't already exist
		if (!loggers[loggerName]) {
			loggers[loggerName] = new Logger(loggerName);
		}
		return loggers[loggerName];
	};

	var defaultLogger = null;
	log4javascript.getDefaultLogger = function() {
		if (!defaultLogger) {
			defaultLogger = log4javascript.getLogger("[default]");
			var a = new log4javascript.PopUpAppender();
			defaultLogger.addAppender(a);
		}
		return defaultLogger;
	};

	var nullLogger = null;
	log4javascript.getNullLogger = function() {
		if (!nullLogger) {
			nullLogger = log4javascript.getLogger("[null]");
		}
		return nullLogger;
	};

	/* --------------------------------------------------------------------- */

	var Level = function(level, name) {
		this.level = level;
		this.name = name;
	};

	Level.prototype = {
		toString: function() {
			return this.name;
		},
		equals: function(level) {
			return this.level == level.level;
		},
		isGreaterOrEqual: function(level) {
			return this.level >= level.level;
		}
	};

	Level.ALL = new Level(Number.MIN_VALUE, "ALL");
	Level.TRACE = new Level(10000, "TRACE");
	Level.DEBUG = new Level(20000, "DEBUG");
	Level.INFO = new Level(30000, "INFO");
	Level.WARN = new Level(40000, "WARN");
	Level.ERROR = new Level(50000, "ERROR");
	Level.FATAL = new Level(60000, "FATAL");
	Level.OFF = new Level(Number.MAX_VALUE, "OFF");

	log4javascript.Level = Level;

	/* --------------------------------------------------------------------- */

	var LoggingEvent = function(logger, timeStamp, level, message,
			exception) {
		this.logger = logger;
		this.timeStamp = timeStamp;
		this.timeStampInSeconds = Math.floor(timeStamp.getTime() / 1000);
		this.level = level;
		this.message = message;
		this.exception = exception;
	};

	LoggingEvent.prototype.getThrowableStrRep = function() {
		return this.exception ?
			getExceptionStringRep(this.exception) : "";
	};

	log4javascript.LoggingEvent = LoggingEvent;

	/* --------------------------------------------------------------------- */

	// Layout "abstract class"
	var Layout = function() {
	};

	Layout.prototype = {
		defaults: {
			loggerKey: "logger",
			timeStampKey: "timestamp",
			levelKey: "level",
			messageKey: "message",
			exceptionKey: "exception",
			urlKey: "url"
		},
		loggerKey: "logger",
		timeStampKey: "timestamp",
		levelKey: "level",
		messageKey: "message",
		exceptionKey: "exception",
		urlKey: "url",
		batchHeader: "",
		batchFooter: "",
		batchSeparator: "",

		format: function(loggingEvent) {
			handleError("Layout.format: layout supplied has no format() method");
		},

		ignoresThrowable: function() {
			handleError("Layout.ignoresThrowable: layout supplied has no ignoresThrowable() method");
		},

		getContentType: function() {
			return "text/plain";
		},

		allowBatching: function() {
			return true;
		},

		getDataValues: function(loggingEvent) {
			var dataValues = [
				[this.loggerKey, loggingEvent.logger.name],
				[this.timeStampKey, loggingEvent.timeStampInSeconds],
				[this.levelKey, loggingEvent.level.name],
				[this.urlKey, window.location.href],
				[this.messageKey, loggingEvent.message]
			];
			if (loggingEvent.exception) {
				dataValues.push([this.exceptionKey, getExceptionStringRep(loggingEvent.exception)]);
			}
			if (this.hasCustomFields()) {
				for (var i = 0; i < this.customFields.length; i++) {
					dataValues.push([this.customFields[i].name, this.customFields[i].value]);
				}
			}
			return dataValues;
		},

		setKeys: function(loggerKey, timeStampKey, levelKey, messageKey,
				exceptionKey, urlKey) {
			this.loggerKey = extractStringFromParam(loggerKey, this.defaults.loggerKey);
			this.timeStampKey = extractStringFromParam(timeStampKey, this.defaults.timeStampKey);
			this.levelKey = extractStringFromParam(levelKey, this.defaults.levelKey);
			this.messageKey = extractStringFromParam(messageKey, this.defaults.messageKey);
			this.exceptionKey = extractStringFromParam(exceptionKey, this.defaults.exceptionKey);
			this.urlKey = extractStringFromParam(urlKey, this.defaults.urlKey);
		},

		setCustomField: function(name, value) {
			var fieldUpdated = false;
			for (var i = 0; i < this.customFields.length; i++) {
				if (this.customFields[i].name === name) {
					this.customFields[i].value = value;
					fieldUpdated = true;
				}
			}
			if (!fieldUpdated) {
				this.customFields.push({"name": name, "value": value});
			}
		},

		hasCustomFields: function() {
			return (this.customFields.length > 0);
		}
	};

	log4javascript.Layout = Layout;

	/* --------------------------------------------------------------------- */

	// SimpleLayout 
	var SimpleLayout = function() {
		this.customFields = [];
	};

	SimpleLayout.prototype = new Layout();

	SimpleLayout.prototype.format = function(loggingEvent) {
		return loggingEvent.level.name + " - " + loggingEvent.message;
	};

	SimpleLayout.prototype.ignoresThrowable = function(loggingEvent) {
	    return true;
	};

	log4javascript.SimpleLayout = SimpleLayout;

	/* --------------------------------------------------------------------- */

	// NullLayout 
	var NullLayout = function() {
		this.customFields = [];
	};

	NullLayout.prototype = new Layout();

	NullLayout.prototype.format = function(loggingEvent) {
		return loggingEvent.message;
	};

	NullLayout.prototype.ignoresThrowable = function(loggingEvent) {
	    return true;
	};

	log4javascript.NullLayout = NullLayout;

	/* --------------------------------------------------------------------- */

	// XmlLayout 
	var XmlLayout = function() {
		this.customFields = [];
	};

	XmlLayout.prototype = new Layout();

	XmlLayout.prototype.getContentType = function() {
		return "text/xml";
	};

	XmlLayout.prototype.escapeCdata = function(str) {
		return str.replace(/\]\]>/, "]]>]]&gt;<![CDATA[");
	};

	XmlLayout.prototype.format = function(loggingEvent) {
		var str = "<log4javascript:event logger=\"" + loggingEvent.logger.name +
			"\" timestamp=\"" + loggingEvent.timeStampInSeconds +
			"\" level=\"" + loggingEvent.level.name +
			"\">" + newLine + "<log4javascript:message><![CDATA[" +
			this.escapeCdata(loggingEvent.message.toString()) +
			"]]></log4javascript:message>" + newLine;
		if (this.hasCustomFields()) {
			for (var i = 0; i < this.customFields.length; i++) {
				str += "<log4javascript:customfield name=\"" +
					this.customFields[i].name + "\"><![CDATA[" +
					this.customFields[i].value.toString() +
					"]]></log4javascript:customfield>" + newLine;
			}
		}
		if (loggingEvent.exception) {
			str += "<log4javascript:exception><![CDATA[" +
				getExceptionStringRep(loggingEvent.exception) +
				"]]></log4javascript:exception>" + newLine;
		}
		str += "</log4javascript:event>" + newLine + newLine;
		return str;
	};

	XmlLayout.prototype.ignoresThrowable = function(loggingEvent) {
	    return false;
	};

	log4javascript.XmlLayout = XmlLayout;

	/* --------------------------------------------------------------------- */

	// JsonLayout 
	var JsonLayout = function(readable, loggerKey, timeStampKey,
			levelKey, messageKey, exceptionKey, urlKey) {
		this.readable = bool(readable);
		this.batchHeader = this.readable ? "[" + newLine : "[";
		this.batchFooter = this.readable ? "]" + newLine : "]";
		this.batchSeparator = this.readable ? "," + newLine : ",";
		this.setKeys(loggerKey, timeStampKey, levelKey, messageKey,
			exceptionKey, urlKey);
		this.propertySeparator = this.readable ? ", " : ",";
		this.colon = this.readable ? ": " : ":";
		this.customFields = [];
	};

	JsonLayout.prototype = new Layout();

	JsonLayout.prototype.setReadable = function(readable) {
		this.readable = bool(readable);
	};

	JsonLayout.prototype.isReadable = function() {
		return this.readable;
	};

	JsonLayout.prototype.format = function(loggingEvent) {
		var dataValues = this.getDataValues(loggingEvent);
		var str = "{";
		if (this.readable) {
			str += newLine;
		}
		for (var i = 0; i < dataValues.length; i++) {
			if (this.readable) {
				str += "\t";
			}
			// Check the type of the data value to decide whether quotation marks
			// are required
			var valType = typeof dataValues[i][1];
			var val = (valType != "number" && valType != "boolean")	?
				"\"" + escapeNewLines(dataValues[i][1].toString().replace(/\"/g, "\\\"")) + "\"" :
				dataValues[i][1];
			str += "\"" + dataValues[i][0] + "\"" + this.colon + val;
			if (i < dataValues.length - 1) {
				str += this.propertySeparator;
			}
			if (this.readable) {
				str += newLine;
			}
		}
		str += "}";
		if (this.readable) {
			str += newLine;
		}
		return str;
	};

	JsonLayout.prototype.ignoresThrowable = function(loggingEvent) {
	    return false;
	};

	log4javascript.JsonLayout = JsonLayout;

	/* --------------------------------------------------------------------- */

	// HttpPostDataLayout 
	var HttpPostDataLayout = function(loggerKey, timeStampKey,
			levelKey, messageKey, exceptionKey, urlKey) {
		this.setKeys(loggerKey, timeStampKey, levelKey, messageKey,
			exceptionKey, urlKey);
		this.customFields = [];
	};

	HttpPostDataLayout.prototype = new Layout();

	// Disable batching
	HttpPostDataLayout.prototype.allowBatching = function() {
		return false;
	};

	HttpPostDataLayout.prototype.format = function(loggingEvent) {
		var dataValues = this.getDataValues(loggingEvent);
		var queryBits = [];
		for (var i = 0; i < dataValues.length; i++) {
			queryBits.push(urlEncode(dataValues[i][0]) + "=" + urlEncode(dataValues[i][1]));
		}
		return queryBits.join("&");
	};

	HttpPostDataLayout.prototype.ignoresThrowable = function(loggingEvent) {
	    return false;
	};

	log4javascript.HttpPostDataLayout = HttpPostDataLayout;

	/* --------------------------------------------------------------------- */

	// PatternLayout 
	var PatternLayout = function(pattern) {
		if (pattern) {
			this.pattern = pattern;
		} else {
			this.pattern = PatternLayout.DEFAULT_CONVERSION_PATTERN;
		}
		this.customFields = [];
	};

	PatternLayout.TTCC_CONVERSION_PATTERN = "%r %p %c - %m%n";
	PatternLayout.DEFAULT_CONVERSION_PATTERN = "%m%n";
	PatternLayout.ISO8601_DATEFORMAT = "yyyy-MM-dd HH:mm:ss,SSS";
	PatternLayout.DATETIME_DATEFORMAT = "dd MMM yyyy HH:mm:ss,SSS";
	PatternLayout.ABSOLUTETIME_DATEFORMAT = "HH:mm:ss,SSS";

	PatternLayout.prototype = new Layout();

	PatternLayout.prototype.format = function(loggingEvent) {
		var regex = /%(-?[0-9]+)?(\.?[0-9]+)?([cdfmMnpr%])(\{([^\}]+)\})?|([^%]+)/;
		var formattedString = "";
		var result;
		var searchString = this.pattern;

		// Cannot use regex global flag since it doesn't work with exec in IE5
		while ((result = regex.exec(searchString))) {
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
					case "c": // Logger name
						var loggerName = loggingEvent.logger.name;
						if (specifier) {
							var precision = parseInt(specifier, 10);
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
					case "d": // Date
						var dateFormat = PatternLayout.ISO8601_DATEFORMAT;
						if (specifier) {
							dateFormat = specifier;
							// Pick up special cases
							if (dateFormat == "ISO8601") {
								dateFormat = PatternLayout.ISO8601_DATEFORMAT;
							} else if (dateFormat == "ABSOLUTE") {
								dateFormat = PatternLayout.ABSOLUTETIME_DATEFORMAT;
							} else if (dateFormat == "DATE") {
								dateFormat = PatternLayout.DATETIME_DATEFORMAT;
							}
						}
						// Format the date
						replacement = (new SimpleDateFormat(dateFormat)).format(loggingEvent.timeStamp);
						break;
					case "f": // Custom field
						if (this.hasCustomFields()) {
							var fieldIndex = 0;
							if (specifier) {
								fieldIndex = parseInt(specifier, 10);
								if (isNaN(fieldIndex)) {
									handleError("PatternLayout.format: invalid specifier '" +
										specifier + "' for conversion character 'f' - should be a number");
								} else if (fieldIndex === 0) {
									handleError("PatternLayout.format: invalid specifier '" +
										specifier + "' for conversion character 'f' - must be greater than zero");
								} else if (fieldIndex > this.customFields.length) {
									handleError("PatternLayout.format: invalid specifier '" +
										specifier + "' for conversion character 'f' - there aren't that many custom fields");
								} else {
									fieldIndex = fieldIndex - 1;
								}
							}
							replacement = this.customFields[fieldIndex].value;
						}
						break;
					case "m": // Message
						if (specifier) {
							var depth = parseInt(specifier, 10);
							if (isNaN(depth)) {
								handleError("PatternLayout.format: invalid specifier '" +
									specifier + "' for conversion character 'm' - should be a number");
								replacement = loggingEvent.message;
							} else {
								replacement = formatObjectExpansion(loggingEvent.message, depth);
							}
						} else {
							replacement = loggingEvent.message;
						}
						break;
					case "n": // New line
						replacement = newLine;
						break;
					case "p": // Level
						replacement = loggingEvent.level.name;
						break;
					case "r": // Milliseconds since log4javascript startup
						replacement = "" + loggingEvent.timeStamp.getDifference(applicationStartDate);
						break;
					case "%": // Literal % sign
						replacement = "%";
						break;
					default:
						replacement = matchedString;
						break;
				}
				// Format the replacement according to any padding or
				// truncation specified
				var len;

				// First, truncation
				if (truncation) {
					len = parseInt(truncation.substr(1), 10);
					var strLen = replacement.length;
					if (len < strLen) {
						replacement = replacement.substring(strLen - len, strLen);
					}
				}
				// Next, padding
				if (padding) {
					if (padding.charAt(0) == "-") {
						len = parseInt(padding.substr(1), 10);
						// Right pad with spaces
						while (replacement.length < len) {
							replacement += " ";
						}
					} else {
						len = parseInt(padding, 10);
						// Left pad with spaces
						while (replacement.length < len) {
							replacement = " " + replacement;
						}
					}
				}
				formattedString += replacement;
			}
			searchString = searchString.substr(result.index + result[0].length);
		}
		return formattedString;
	};

	PatternLayout.prototype.ignoresThrowable = function(loggingEvent) {
	    return true;
	};

	log4javascript.PatternLayout = PatternLayout;

	/* --------------------------------------------------------------------- */

	// Appender "abstract class"
	var Appender = function() {};

	// Performs threshold checks before delegating actual logging to the
	// subclass's specific append method.
	Appender.prototype = {
		layout: new PatternLayout(),
		threshold: Level.ALL,

		doAppend: function(loggingEvent) {
			if (enabled && loggingEvent.level.level >= this.threshold.level) {
				this.append(loggingEvent);
			}
		},

		append: function(loggingEvent) {},

		setLayout: function(layout) {
			if (layout instanceof Layout) {
				this.layout = layout;
			} else {
				handleError("Appender.setLayout: layout supplied to " +
					this.toString() + " is not a subclass of Layout");
			}
		},

		getLayout: function() {
			return this.layout;
		},

		setThreshold: function(threshold) {
			if (threshold instanceof Level) {
				this.threshold = threshold;
			} else {
				handleError("Appender.setThreshold: threshold supplied to " +
					this.toString() + " is not a subclass of Level");
			}
		},

		getThreshold: function() {
			return this.threshold;
		},

		toString: function() {
			return "[Base Appender]";
		}
	};

	log4javascript.Appender = Appender;

	/* --------------------------------------------------------------------- */

	// AlertAppender
	var AlertAppender = function(layout) {
		if (layout) {
			this.setLayout(layout);
		}
	};

	AlertAppender.prototype = new Appender();

	AlertAppender.prototype.layout = new SimpleLayout();

	AlertAppender.prototype.append = function(loggingEvent) {
		var formattedMessage = this.getLayout().format(loggingEvent);
		if (this.getLayout().ignoresThrowable()) {
			formattedMessage += loggingEvent.getThrowableStrRep();
		}
		alert(formattedMessage);
	};

	AlertAppender.prototype.toString = function() {
		return "[AlertAppender]";
	};

	log4javascript.AlertAppender = AlertAppender;

	/* --------------------------------------------------------------------- */

	// AjaxAppender
	var AjaxAppender = function(url, layout, timed, waitForResponse,
			batchSize, timerInterval, requestSuccessCallback, failCallback) {
		var appender = this;
		var isSupported = true;
		if (!url) {
			handleError("AjaxAppender: URL must be specified in constructor");
			isSupported = false;
		}

		timed = extractBooleanFromParam(timed, this.defaults.timed);
		waitForResponse = extractBooleanFromParam(waitForResponse, this.defaults.waitForResponse);
		batchSize = extractIntFromParam(batchSize, this.defaults.batchSize);
		timerInterval = extractIntFromParam(timerInterval, this.defaults.timerInterval);
		requestSuccessCallback = extractFunctionFromParam(requestSuccessCallback, this.defaults.requestSuccessCallback);
		failCallback = extractFunctionFromParam(failCallback, this.defaults.failCallback);
		var sessionId = null;

		var queuedLoggingEvents = [];
		var queuedRequests = [];
		var sending = false;
		var initialized = false;

		// Configuration methods. The function scope is used to prevent
		// direct alteration to the appender configuration properties.
		function checkCanConfigure(configOptionName) {
			if (initialized) {
				handleError("AjaxAppender: configuration option '" + configOptionName + "' may not be set after the appender has been initialized");
				return false;
			}
			return true;
		}

		this.getSessionId = function() { return sessionId; };
		this.setSessionId = function(sessionIdParam) {
			sessionId = extractStringFromParam(sessionIdParam, null);
			this.layout.setCustomField("sessionid", sessionId);
		};

		this.setLayout = function(layout) {
			if (checkCanConfigure("layout")) {
				this.layout = layout;
				// Set the session id as a custom field on the layout, if not already present
				if (sessionId !== null) {
					this.setSessionId(sessionId);
				}
			}
		};

		if (layout) {
			this.setLayout(layout);
		}

		this.isTimed = function() { return timed; };
		this.setTimed = function(timedParam) {
			if (checkCanConfigure("timed")) {
				timed = bool(timedParam);
			}
		};

		this.getTimerInterval = function() { return timerInterval; };
		this.setTimerInterval = function(timerIntervalParam) {
			if (checkCanConfigure("timerInterval")) {
				timerInterval = extractIntFromParam(timerIntervalParam, timerInterval);
			}
		};

		this.isWaitForResponse = function() { return waitForResponse; };
		this.setWaitForResponse = function(waitForResponseParam) {
			if (checkCanConfigure("waitForResponse")) {
				waitForResponse = bool(waitForResponseParam);
			}
		};

		this.getBatchSize = function() { return batchSize; };
		this.setBatchSize = function(batchSizeParam) {
			if (checkCanConfigure("batchSize")) {
				batchSize = extractIntFromParam(batchSizeParam, batchSize);
			}
		};

		this.setRequestSuccessCallback = function(requestSuccessCallbackParam) {
			requestSuccessCallback = extractFunctionFromParam(requestSuccessCallbackParam, requestSuccessCallback);
		};

		this.setFailCallback = function(failCallbackParam) {
			failCallback = extractFunctionFromParam(failCallbackParam, failCallback);
		};

		// Internal functions
		function sendAll() {
			if (isSupported && enabled) {
				sending = true;
				var currentRequestBatch;
				if (waitForResponse) {
					// Send the first request then use this function as the callback once
					// the response comes back
					if (queuedRequests.length > 0) {
						currentRequestBatch = queuedRequests.shift();
						sendRequest(preparePostData(currentRequestBatch), sendAll);
					} else {
						sending = false;
						if (timed) {
							scheduleSending();
						}
					}
				} else {
					// Rattle off all the requests without waiting to see the response
					while ((currentRequestBatch = queuedRequests.shift())) {
						sendRequest(preparePostData(currentRequestBatch));
					}
					sending = false;
					if (timed) {
						scheduleSending();
					}
				}
			}
		}

		this.sendAll = sendAll;

		function preparePostData(batchedLoggingEvents) {
			// Format the logging events
			var formattedMessages = [];
			var currentLoggingEvent;
			var postData = "";
			while ((currentLoggingEvent = batchedLoggingEvents.shift())) {
				var currentFormattedMessage = appender.getLayout().format(currentLoggingEvent);
				if (appender.getLayout().ignoresThrowable()) {
					currentFormattedMessage += loggingEvent.getThrowableStrRep();
				}
				formattedMessages.push(currentFormattedMessage);
			}
			// Create the post data string
			if (batchedLoggingEvents.length == 1) {
				postData = formattedMessages.join("");
			} else {
				postData = appender.getLayout().batchHeader +
					formattedMessages.join(appender.getLayout().batchSeparator) +
					appender.getLayout().batchFooter;
			}
			return postData;
		}

		function scheduleSending() {
			setTimeout(sendAll, timerInterval);
		}

		function getXmlHttp() {
			var xmlHttp = null;
			if (typeof XMLHttpRequest == "object" || typeof XMLHttpRequest == "function") {
				xmlHttp = new XMLHttpRequest();
			} else {
				try {
					xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e2){
					try {
						xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e3) {
						var msg = "AjaxAppender: could not create XMLHttpRequest object. AjaxAppender disabled";
						handleError(msg);
						isSupported = false;
						if (failCallback) {
							failCallback(msg);
						}
					}
				}
			}
			return xmlHttp;
		}

		function sendRequest(postData, successCallback) {
			try {
				var xmlHttp = getXmlHttp();
				if (isSupported) {
					if (xmlHttp.overrideMimeType) {
						xmlHttp.overrideMimeType(appender.getLayout().getContentType());
					}
					xmlHttp.onreadystatechange = function() {
						if (xmlHttp.readyState == 4) {
							var success = (isUndefined(xmlHttp.status) || xmlHttp.status === 0 ||
								(xmlHttp.status >= 200 && xmlHttp.status < 300));
							if (success) {
								if (requestSuccessCallback) {
									requestSuccessCallback(xmlHttp);
								}
								if (successCallback) {
									successCallback(xmlHttp);
								}
							} else {
								var msg = "AjaxAppender.append: XMLHttpRequest request to URL " +
									url + " returned status code " + xmlHttp.status;
								handleError(msg);
								if (failCallback) {
									failCallback(msg);
								}
							}
							xmlHttp.onreadystatechange = emptyFunction;
							xmlHttp = null;
						}
					};
					xmlHttp.open("POST", url, true);
					try {
						xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					} catch (headerEx) {
						var msg = "AjaxAppender.append: your browser's XMLHttpRequest implementation" +
							" does not support setRequestHeader, therefore cannot post data. AjaxAppender disabled";
						handleError(msg);
						isSupported = false;
						if (failCallback) {
							failCallback(msg);
						}
						return;
					}
					xmlHttp.send(postData);
				}
			} catch (ex) {
				var msg = "AjaxAppender.append: error sending log message to " + url;
				handleError(msg, ex);
				if (failCallback) {
					failCallback(msg + ". Details: " + getExceptionStringRep(ex));
				}
			}
		}

		this.append = function(loggingEvent) {
			if (isSupported) {
				if (!initialized) {
					init();
				}
				queuedLoggingEvents.push(loggingEvent);
				var actualBatchSize = this.getLayout().allowBatching() ? batchSize : 1;

				if (queuedLoggingEvents.length >= actualBatchSize) {
					var currentLoggingEvent;
					var postData = "";
					var batchedLoggingEvents = [];
					while ((currentLoggingEvent = queuedLoggingEvents.shift())) {
						batchedLoggingEvents.push(currentLoggingEvent);
					}
					// Queue this batch of log entries
					queuedRequests.push(batchedLoggingEvents);

					// If using a timer, the queue of requests will be processed by the
					// timer function, so nothing needs to be done here.
					if (!timed) {
						if (!waitForResponse || (waitForResponse && !sending)) {
							sendAll();
						}
					}
				}
			}
		};

		function init() {
			initialized = true;
			// Start timer
			if (timed) {
				scheduleSending();
			}
		}
	};

	AjaxAppender.prototype = new Appender();

	AjaxAppender.prototype.defaults = {
		waitForResponse: false,
		timed: false,
		timerInterval: 1000,
		batchSize: 1,
		requestSuccessCallback: null,
		failCallback: null
	};

	AjaxAppender.prototype.layout = new HttpPostDataLayout();

	AjaxAppender.prototype.toString = function() {
		return "[AjaxAppender]";
	};

	log4javascript.AjaxAppender = AjaxAppender;

	/* --------------------------------------------------------------------- */

	// BaseConsoleAppender
	// Create an anonymous function to protect base console methods

	(function() {
		var getConsoleHtmlLines = function() {
			return [
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
'<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">',
'	<head>',
'		<title>log4javascript</title>',
'		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />',
'		<script type="text/javascript">',
'			//<![CDATA[',
'			var loggingEnabled = true;',
'',
'			function toggleLoggingEnabled() {',
'				setLoggingEnabled($("enableLogging").checked);',
'			}',
'',
'			function setLoggingEnabled(enable) {',
'				loggingEnabled = enable;',
'			}',
'',
'			var newestAtTop = false;',
'',
'			function setNewestAtTop(isNewestAtTop) {',
'				var oldNewestAtTop = newestAtTop;',
'				newestAtTop = Boolean(isNewestAtTop);',
'				if (oldNewestAtTop != newestAtTop) {',
'					// Invert the order of the log entries',
'					var lc = getLogContainer();',
'					var numberOfEntries = lc.childNodes.length;',
'					var node = null;',
'',
'					// Remove all the log container nodes',
'					var logContainerChildNodes = [];',
'					while ((node = lc.firstChild)) {',
'						lc.removeChild(node);',
'						logContainerChildNodes.push(node);',
'					}',
'',
'					// Put them all back in reverse order',
'					while ((node = logContainerChildNodes.pop())) {',
'						lc.appendChild(node);',
'					}',
'',
'					// Reassemble the matches array',
'					if (currentSearch) {',
'						var currentMatch = currentSearch.matches[currentMatchIndex];',
'						var matchIndex = 0;',
'						var matches = [];',
'						var actOnLogEntry = function(logEntry) {',
'							var logEntryMatches = logEntry.getSearchMatches();',
'							for (var i = 0; i < logEntryMatches.length; i++) {',
'								matches[matchIndex] = logEntryMatches[i];',
'								if (currentMatch && logEntryMatches[i].equals(currentMatch)) {',
'									currentMatchIndex = matchIndex;',
'								}',
'								matchIndex++;',
'							}',
'						};',
'						var i;',
'						if (newestAtTop) {',
'							for (i = logEntries.length - 1; i >= 0; i--) {',
'								actOnLogEntry(logEntries[i]);',
'							}',
'						} else {',
'							for (i = 0; i < logEntries.length; i++) {',
'								actOnLogEntry(logEntries[i]);',
'							}',
'						}',
'						currentSearch.matches = matches;',
'						if (currentMatch) {',
'							currentMatch.setCurrent();',
'						}',
'					} else if (scrollToLatest) {',
'						doScrollToLatest();',
'					}',
'				}',
'				$("newestAtTop").checked = isNewestAtTop;',
'			}',
'',
'			function toggleNewestAtTop() {',
'				var isNewestAtTop = $("newestAtTop").checked;',
'				setNewestAtTop(isNewestAtTop);',
'			}',
'',
'			var scrollToLatest = true;',
'',
'			function setScrollToLatest(isScrollToLatest) {',
'				scrollToLatest = isScrollToLatest;',
'				if (scrollToLatest) {',
'					doScrollToLatest();',
'				}',
'				$("scrollToLatest").checked = isScrollToLatest;',
'			}',
'',
'			function toggleScrollToLatest() {',
'				var isScrollToLatest = $("scrollToLatest").checked;',
'				setScrollToLatest(isScrollToLatest);',
'			}',
'',
'			function doScrollToLatest() {',
'				var l = getLogContainer();',
'				if (typeof l.scrollTop != "undefined") {',
'					if (newestAtTop) {',
'						l.scrollTop = 0;',
'					} else {',
'						var latestLogEntry = l.lastChild;',
'						if (latestLogEntry) {',
'							l.scrollTop = l.scrollHeight;',
'						}',
'					}',
'				}',
'			}',
'',
'			var maxMessages = null;',
'',
'			function setMaxMessages(max) {',
'				maxMessages = max;',
'				pruneLogEntries();',
'			}',
'',
'			var logQueuedEventsTimer = null;',
'			var logEntries = [];',
'			var isCssWrapSupported;',
'			var renderDelay = 100;',
'',
'			function log(logLevel, formattedMessage) {',
'				if (loggingEnabled) {',
'					var logEntry = new LogEntry(logLevel, formattedMessage);',
'					logEntries.push(logEntry);',
'					if (loaded) {',
'						if (logQueuedEventsTimer !== null) {',
'							clearTimeout(logQueuedEventsTimer);',
'						}',
'						setTimeout(renderQueuedLogEntries, renderDelay);',
'					}',
'				}',
'			}',
'',
'			function renderQueuedLogEntries() {',
'				logQueuedEventsTimer = null;',
'				var pruned = pruneLogEntries();',
'',
'				// Render any unrendered log entries and apply the current search to them',
'				var initiallyHasMatches = currentSearch ? currentSearch.hasMatches() : false;',
'				for (var i = 0; i < logEntries.length; i++) {',
'					if (!logEntries[i].isRendered) {',
'						logEntries[i].render();',
'						logEntries[i].appendToLog();',
'						if (currentSearch) {',
'							currentSearch.applyTo(logEntries[i]);',
'						}',
'					}',
'				}',
'				if (currentSearch) {',
'					if (pruned) {',
'						if (currentSearch.hasMatches()) {',
'							if (currentMatchIndex === null) {',
'								setCurrentMatchIndex(0);',
'							}',
'							displayMatches();',
'						} else {',
'							displayNoMatches();',
'						}',
'					} else if (!initiallyHasMatches && currentSearch.hasMatches()) {',
'						setCurrentMatchIndex(0);',
'						displayMatches();',
'					}',
'				}',
'				if (scrollToLatest) {',
'					doScrollToLatest();',
'				}',
'			}',
'',
'			function pruneLogEntries() {',
'				if ((maxMessages !== null) && (logEntries.length > maxMessages)) {',
'					var numberToDelete = logEntries.length - maxMessages;',
'					for (var i = 0; i < numberToDelete; i++) {',
'						logEntries[i].remove();',
'					}',
'					logEntries = array_removeFromStart(logEntries, numberToDelete);',
'					if (currentSearch) {',
'						currentSearch.removePrunedMatches();',
'					}',
'					return true;',
'				}',
'				return false;',
'			}',
'',
'			function LogEntry(level, formattedMessage) {',
'				this.level = level;',
'				this.formattedMessage = formattedMessage;',
'				this.isRendered = false;',
'			}',
'',
'			LogEntry.prototype = {',
'				render: function() {',
'					this.mainDiv = document.createElement("div");',
'					this.mainDiv.className = "logentry " + this.level.name;',
'	',
'					// Support for the CSS attribute white-space in IE for Windows is',
'					// non-existent pre version 6 and slightly odd in 6, so instead',
'					// use two different HTML elements',
'					if (isCssWrapSupported) {',
'						this.mainDiv.appendChild(document.createTextNode(this.formattedMessage));',
'					} else {',
'						this.formattedMessage = this.formattedMessage.replace(/\\r\\n/g, "\\r"); // Workaround for IE\'s treatment of white space',
'						this.unwrappedPre = this.mainDiv.appendChild(document.createElement("pre"));',
'						this.unwrappedPre.appendChild(document.createTextNode(this.formattedMessage));',
'						this.unwrappedPre.className = "unwrapped";',
'						this.wrappedSpan = this.mainDiv.appendChild(document.createElement("span"));',
'						this.wrappedSpan.appendChild(document.createTextNode(this.formattedMessage));',
'						this.wrappedSpan.className = "wrapped";',
'					}',
'					this.content = this.formattedMessage;',
'					this.isRendered = true;',
'				},',
'	',
'				appendToLog: function() {',
'					var lc = getLogContainer();',
'					if (newestAtTop && lc.hasChildNodes()) {',
'						lc.insertBefore(this.mainDiv, lc.firstChild);',
'					} else {',
'						getLogContainer().appendChild(this.mainDiv);',
'					}',
'				},',
'	',
'				setContent: function(content) {',
'					if (content != this.content) {',
'						if (getLogContainer().currentStyle) {',
'							if (content === this.formattedMessage) {',
'								this.unwrappedPre.innerHTML = "";',
'								this.unwrappedPre.appendChild(document.createTextNode(this.formattedMessage));',
'								this.wrappedSpan.innerHTML = "";',
'								this.wrappedSpan.appendChild(document.createTextNode(this.formattedMessage));',
'							} else {',
'								content = content.replace(/\\r\\n/g, "\\r"); // Workaround for IE\'s treatment of white space',
'								this.unwrappedPre.innerHTML = content;',
'								this.wrappedSpan.innerHTML = content;',
'							}',
'						} else {',
'							if (content === this.formattedMessage) {',
'								this.mainDiv.innerHTML = "";',
'								this.mainDiv.appendChild(document.createTextNode(this.formattedMessage));',
'							} else {',
'								this.mainDiv.innerHTML = content;',
'							}',
'						}',
'						this.content = content;',
'					}',
'				},',
'',
'				getSearchMatches: function() {',
'					var matches = [];',
'					if (isCssWrapSupported) {',
'						var els = getElementsByClass(this.mainDiv, "searchterm", "span");',
'						for (var i = 0; i < els.length; i++) {',
'							matches[i] = new Match(this.level, els[i]);',
'						}',
'					} else {',
'						var unwrappedEls = getElementsByClass(this.unwrappedPre, "searchterm", "span");',
'						var wrappedEls = getElementsByClass(this.wrappedSpan, "searchterm", "span");',
'						for (i = 0; i < unwrappedEls.length; i++) {',
'							matches[i] = new Match(this.level, null, unwrappedEls[i], wrappedEls[i]);',
'						}',
'					}',
'					return matches;',
'				},',
'',
'				remove: function() {',
'					if (this.isRendered) {',
'						this.mainDiv.parentNode.removeChild(this.mainDiv);',
'						this.mainDiv = null;',
'					}',
'				}',
'			};',
'',
'			function mainPageReloaded() {',
'				var separator = document.createElement("div");',
'				separator.className = "separator";',
'				separator.innerHTML = "&nbsp;";',
'				getLogContainer().appendChild(separator);',
'			}',
'',
'			window.onload = function() {',
'				isCssWrapSupported = (typeof getLogContainer().currentStyle == "undefined");',
'				setLogContainerHeight();',
'				toggleLoggingEnabled();',
'				toggleSearchEnabled();',
'				toggleSearchFilter();',
'				toggleSearchHighlight();',
'				applyFilters();',
'				toggleWrap();',
'				toggleNewestAtTop();',
'				toggleScrollToLatest();',
'				//doSearch();',
'				renderQueuedLogEntries();',
'				loaded = true;',
'				// Workaround to make sure log div starts at the correct size',
'				setTimeout(setLogContainerHeight, 20);',
'',
'				// Remove "Close" button if not in pop-up mode',
'				if (window != top) {',
'					$("closeButton").style.display = "none";',
'				}',
'			};',
'',
'			var loaded = false;',
'',
'			var logLevels = ["TRACE", "DEBUG", "INFO", "WARN", "ERROR", "FATAL"];',
'',
'			function getCheckBox(logLevel) {',
'				return $("switch_" + logLevel);',
'			}',
'',
'			function getLogContainer() {',
'				return $("log");',
'			}',
'',
'			function applyFilters() {',
'				for (var i = 0; i < logLevels.length; i++) {',
'					if (getCheckBox(logLevels[i]).checked) {',
'						addClass(getLogContainer(), logLevels[i]);',
'					} else {',
'						removeClass(getLogContainer(), logLevels[i]);',
'					}',
'				}',
'				updateSearchFromFilters();',
'			}',
'',
'			function toggleAllLevels() {',
'				var turnOn = $("switch_ALL").checked;',
'				for (var i = 0; i < logLevels.length; i++) {',
'					getCheckBox(logLevels[i]).checked = turnOn;',
'					if (turnOn) {',
'						addClass(getLogContainer(), logLevels[i]);',
'					} else {',
'						removeClass(getLogContainer(), logLevels[i]);',
'					}',
'				}',
'			}',
'',
'			function checkAllLevels() {',
'				for (var i = 0; i < logLevels.length; i++) {',
'					if (!getCheckBox(logLevels[i]).checked) {',
'						getCheckBox("ALL").checked = false;',
'						return;',
'					}',
'				}',
'				getCheckBox("ALL").checked = true;',
'			}',
'',
'			function clearLog() {',
'				getLogContainer().innerHTML = "";',
'				logEntries = [];',
'				doSearch();',
'			}',
'',
'			function toggleWrap() {',
'				var enable = $("wrap").checked;',
'				if (enable) {',
'					addClass(getLogContainer(), "wrap");',
'				} else {',
'					removeClass(getLogContainer(), "wrap");',
'				}',
'				refreshCurrentMatch();',
'			}',
'',
'			/* ------------------------------------------------------------------- */',
'',
'			// Search',
'',
'			var searchTimer = null;',
'',
'			function scheduleSearch() {',
'				try {',
'					clearTimeout(searchTimer);',
'				} catch (ex) {',
'					// Do nothing',
'				}',
'				searchTimer = setTimeout(doSearch, 500);',
'			}',
'',
'			function Search(searchTerm, isRegex, searchRegex, isCaseSensitive) {',
'				this.searchTerm = searchTerm;',
'				this.isRegex = isRegex;',
'				this.searchRegex = searchRegex;',
'				this.isCaseSensitive = isCaseSensitive;',
'				this.matches = [];',
'			}',
'',
'			Search.prototype = {',
'				hasMatches: function() {',
'					return this.matches.length > 0;',
'				},',
'',
'				hasVisibleMatches: function() {',
'					if (this.hasMatches()) {',
'						for (var i = 0; i <= this.matches.length; i++) {',
'							if (this.matches[i].isVisible()) {',
'								return true;',
'							}',
'						}',
'					}',
'					return false;',
'				},',
'',
'				match: function(logEntry) {',
'					var entryText = logEntry.formattedMessage;',
'					var matchesSearch = false;',
'					if (this.isRegex) {',
'						matchesSearch = this.searchRegex.test(entryText);',
'					} else if (this.isCaseSensitive) {',
'						matchesSearch = (entryText.indexOf(this.searchTerm) > -1);',
'					} else {',
'						matchesSearch = (entryText.toLowerCase().indexOf(this.searchTerm.toLowerCase()) > -1);',
'					}',
'					return matchesSearch;',
'				},',
'				',
'				getNextVisibleMatchIndex: function() {',
'					for (var i = currentMatchIndex + 1; i < this.matches.length; i++) {',
'						if (this.matches[i].isVisible()) {',
'							return i;',
'						}',
'					}',
'					// Start again from the first match',
'					for (var i = 0; i <= currentMatchIndex; i++) {',
'						if (this.matches[i].isVisible()) {',
'							return i;',
'						}',
'					}',
'					return -1;',
'				},',
'',
'				getPreviousVisibleMatchIndex: function() {',
'					for (var i = currentMatchIndex - 1; i >= 0; i--) {',
'						if (this.matches[i].isVisible()) {',
'							return i;',
'						}',
'					}',
'					// Start again from the last match',
'					for (var i = this.matches.length - 1; i >= currentMatchIndex; i--) {',
'						if (this.matches[i].isVisible()) {',
'							return i;',
'						}',
'					}',
'					return -1;',
'				},',
'',
'				applyTo: function(logEntry) {',
'					var doesMatch = this.match(logEntry);',
'					if (doesMatch) {',
'						replaceClass(logEntry.mainDiv, "searchmatch", "searchnonmatch");',
'						var logEntryContent;',
'						if (this.isRegex) {',
'							var flags = this.isCaseSensitive ? "g" : "gi";',
'							var capturingRegex = new RegExp("(" + this.searchRegex.source + ")", flags);',
'							logEntryContent = logEntry.formattedMessage.replace(capturingRegex, "<span class=\\\"searchterm\\\">$1</span>");',
'						} else {',
'							logEntryContent = "";',
'							var searchTermReplacementStartTag = "<span class=\\\"searchterm\\\">";',
'							var searchTermReplacementEndTag = "</span>";',
'							var searchTermReplacementLength = searchTermReplacementStartTag.length + this.searchTerm.length + searchTermReplacementEndTag.length;',
'							var searchTermLength = this.searchTerm.length;',
'							var startIndex = 0;',
'							var searchIndex;',
'							var searchTermLowerCase = this.searchTerm.toLowerCase();',
'							var logTextLowerCase = logEntry.formattedMessage.toLowerCase();',
'							while ((searchIndex = logTextLowerCase.indexOf(searchTermLowerCase, startIndex)) > -1) {',
'								var searchTermReplacement = searchTermReplacementStartTag + logEntry.formattedMessage.substr(searchIndex, this.searchTerm.length) + searchTermReplacementEndTag;',
'								logEntryContent += logEntry.formattedMessage.substring(startIndex, searchIndex) + searchTermReplacement;',
'								startIndex = searchIndex + searchTermLength;',
'							}',
'							logEntryContent += logEntry.formattedMessage.substr(startIndex);',
'						}',
'						logEntry.setContent(logEntryContent);',
'						var logEntryMatches = logEntry.getSearchMatches();',
'						this.matches = this.matches.concat(logEntryMatches);',
'					} else {',
'						replaceClass(logEntry.mainDiv, "searchnonmatch", "searchmatch");',
'						logEntry.setContent(logEntry.formattedMessage);',
'					}',
'					return doesMatch;',
'				},',
'',
'				removePrunedMatches: function() {',
'					var matchesToRemoveCount = 0;',
'					var currentMatchRemoved = false;',
'					for (var i = 0; i < this.matches.length; i++) {',
'						if (this.matches[i].isOrphan()) {',
'							this.matches[i].remove();',
'							if (i === currentMatchIndex) {',
'								currentMatchRemoved = true;',
'							}',
'							matchesToRemoveCount++;',
'						}',
'					}',
'					if (matchesToRemoveCount > 0) {',
'						array_removeFromStart(this.matches, matchesToRemoveCount);',
'						var newMatchIndex = currentMatchRemoved ? 0 :',
'							currentMatchIndex - matchesToRemoveCount;',
'						if (this.hasMatches()) {',
'							setCurrentMatchIndex(newMatchIndex);',
'						} else {',
'							currentMatchIndex = null;',
'						}',
'					}',
'				}',
'			};',
'',
'			function getPageOffsetTop(el) {',
'				var currentEl = el;',
'				var y = 0;',
'				while (currentEl) {',
'					y += currentEl.offsetTop;',
'					currentEl = currentEl.offsetParent;',
'				}',
'				return y;',
'			}',
'',
'			function scrollIntoView(el) {',
'				getLogContainer().scrollLeft = el.offsetLeft;',
'				getLogContainer().scrollTop = getPageOffsetTop(el) - getToolBarsHeight();',
'			}',
'',
'			function Match(logEntryLevel, spanInMainDiv, spanInUnwrappedPre, spanInWrappedSpan) {',
'				this.logEntryLevel = logEntryLevel;',
'				this.spanInMainDiv = spanInMainDiv;',
'				if (!isCssWrapSupported) {',
'					this.spanInUnwrappedPre = spanInUnwrappedPre;',
'					this.spanInWrappedSpan = spanInWrappedSpan;',
'				}',
'				this.mainSpan = isCssWrapSupported ? spanInMainDiv : spanInUnwrappedPre;',
'			}',
'',
'			Match.prototype = {',
'				equals: function(match) {',
'					return this.mainSpan === match.mainSpan;',
'				},',
'',
'				setCurrent: function() {',
'					if (isCssWrapSupported) {',
'						addClass(this.spanInMainDiv, "currentmatch");',
'						scrollIntoView(this.spanInMainDiv);',
'					} else {',
'						addClass(this.spanInUnwrappedPre, "currentmatch");',
'						addClass(this.spanInWrappedSpan, "currentmatch");',
'						// Scroll the visible one into view',
'						var elementToScroll = $("wrap").checked ? this.spanInWrappedSpan : this.spanInUnwrappedPre;',
'						scrollIntoView(elementToScroll);',
'					}',
'				},',
'',
'				setNotCurrent: function() {',
'					if (isCssWrapSupported) {',
'						removeClass(this.spanInMainDiv, "currentmatch");',
'					} else {',
'						removeClass(this.spanInUnwrappedPre, "currentmatch");',
'						removeClass(this.spanInWrappedSpan, "currentmatch");',
'					}',
'				},',
'',
'				isOrphan: function() {',
'					return isOrphan(this.mainSpan);',
'				},',
'',
'				isVisible: function() {',
'					return getCheckBox(this.logEntryLevel).checked;',
'				},',
'',
'				remove:  function() {',
'					if (isCssWrapSupported) {',
'						this.spanInMainDiv = null;',
'					} else {',
'						this.spanInUnwrappedPre = null;',
'						this.spanInWrappedSpan = null;',
'					}',
'				}',
'			};',
'',
'			var currentSearch = null;',
'			var currentMatchIndex = null;',
'',
'			function doSearch() {',
'				var searchBox = $("searchBox");',
'				var searchTerm = searchBox.value;',
'				var isRegex = $("searchRegex").checked;',
'				var isCaseSensitive = $("searchCaseSensitive").checked;',
'				var i;',
'',
'				if (searchTerm === "") {',
'					$("searchReset").disabled = true;',
'					$("searchNav").style.display = "none";',
'					removeClass(document.body, "searching");',
'					removeClass(searchBox, "hasmatches");',
'					removeClass(searchBox, "nomatches");',
'					for (i = 0; i < logEntries.length; i++) {',
'						removeClass(logEntries[i].mainDiv, "searchmatch");',
'						removeClass(logEntries[i].mainDiv, "searchnonmatch");',
'						logEntries[i].setContent(logEntries[i].formattedMessage);',
'					}',
'					currentSearch = null;',
'					setLogContainerHeight();',
'				} else {',
'					$("searchReset").disabled = false;',
'					$("searchNav").style.display = "block";',
'					var searchRegex;',
'					var regexValid;',
'					if (isRegex) {',
'						try {',
'							searchRegex = isCaseSensitive ? new RegExp(searchTerm, "g") : new RegExp(searchTerm, "gi");',
'							regexValid = true;',
'							replaceClass(searchBox, "validregex", "invalidregex");',
'							searchBox.title = "Valid regex";',
'						} catch (ex) {',
'							regexValid = false;',
'							replaceClass(searchBox, "invalidregex", "validregex");',
'							searchBox.title = "Invalid regex: " + (ex.message ? ex.message : (ex.description ? ex.description : "unknown error"));',
'							return;',
'						}',
'					} else {',
'						searchBox.title = "";',
'						removeClass(searchBox, "validregex");',
'						removeClass(searchBox, "invalidregex");',
'					}',
'					addClass(document.body, "searching");',
'					currentSearch = new Search(searchTerm, isRegex, searchRegex, isCaseSensitive);',
'					for (i = 0; i < logEntries.length; i++) {',
'						currentSearch.applyTo(logEntries[i]);',
'					}',
'					setLogContainerHeight();',
'',
'					// Highlight the first search match',
'					if (currentSearch.hasMatches()) {',
'						setCurrentMatchIndex(0);',
'						displayMatches();',
'					} else {',
'						displayNoMatches();',
'					}',
'				}',
'			}',
'			',
'			function updateSearchFromFilters() {',
'				if (currentSearch && currentSearch.hasMatches()) {',
'					var currentMatch = currentSearch.matches[currentMatchIndex];',
'					if (currentMatch.isVisible()) {',
'						displayMatches();',
'						setCurrentMatchIndex(currentMatchIndex);',
'					} else {',
'						currentMatch.setNotCurrent();',
'						// Find the next visible match, if one exists',
'						var nextVisibleMatchIndex = currentSearch.getNextVisibleMatchIndex();',
'						if (nextVisibleMatchIndex > -1) {',
'							setCurrentMatchIndex(nextVisibleMatchIndex);',
'							displayMatches();',
'						} else {',
'							displayNoMatches();',
'						}',
'					}',
'				}',
'			}',
'',
'			function refreshCurrentMatch() {',
'				if (currentSearch && currentSearch.hasMatches()) {',
'					setCurrentMatchIndex(currentMatchIndex);',
'				}',
'			}',
'',
'			function displayMatches() {',
'				replaceClass($("searchBox"), "hasmatches", "nomatches");',
'				$("searchBox").title = "" + currentSearch.matches.length + " matches found";',
'				$("searchNav").style.display = "block";',
'				setLogContainerHeight();',
'			}',
'',
'			function displayNoMatches() {',
'				replaceClass($("searchBox"), "nomatches", "hasmatches");',
'				$("searchBox").title = "No matches found";',
'				$("searchNav").style.display = "none";',
'				setLogContainerHeight();',
'			}',
'',
'			function toggleSearchEnabled(enable) {',
'				enable = (typeof enable == "undefined") ? !$("searchDisable").checked : enable;',
'				$("searchBox").disabled = !enable;',
'				$("searchReset").disabled = !enable;',
'				$("searchRegex").disabled = !enable;',
'				$("searchNext").disabled = !enable;',
'				$("searchPrevious").disabled = !enable;',
'				$("searchCaseSensitive").disabled = !enable;',
'				$("searchNav").style.display = (enable && ($("searchBox").value !== "")) ?',
'					"block" : "none";',
'				if (enable) {',
'					removeClass($("search"), "greyedout");',
'					addClass(document.body, "searching");',
'					if ($("searchHighlight").checked) {',
'						addClass(getLogContainer(), "searchhighlight");',
'					} else {',
'						removeClass(getLogContainer(), "searchhighlight");',
'					}',
'					if ($("searchFilter").checked) {',
'						addClass(getLogContainer(), "searchfilter");',
'					} else {',
'						removeClass(getLogContainer(), "searchfilter");',
'					}',
'					$("searchDisable").checked = !enable;',
'				} else {',
'					addClass($("search"), "greyedout");',
'					removeClass(document.body, "searching");',
'					removeClass(getLogContainer(), "searchhighlight");',
'					removeClass(getLogContainer(), "searchfilter");',
'				}',
'				setLogContainerHeight();',
'			}',
'',
'			function toggleSearchFilter() {',
'				var enable = $("searchFilter").checked;',
'				if (enable) {',
'					addClass(getLogContainer(), "searchfilter");',
'				} else {',
'					removeClass(getLogContainer(), "searchfilter");',
'				}',
'				refreshCurrentMatch();',
'			}',
'',
'			function toggleSearchHighlight() {',
'				var enable = $("searchHighlight").checked;',
'				if (enable) {',
'					addClass(getLogContainer(), "searchhighlight");',
'				} else {',
'					removeClass(getLogContainer(), "searchhighlight");',
'				}',
'			}',
'',
'			function clearSearch() {',
'				$("searchBox").value = "";',
'				doSearch();',
'			}',
'',
'			function searchNext() {',
'				try {',
'				if (currentSearch !== null && currentMatchIndex !== null) {',
'					currentSearch.matches[currentMatchIndex].setNotCurrent();',
'					var nextMatchIndex = currentSearch.getNextVisibleMatchIndex();',
'					if (nextMatchIndex > currentMatchIndex || confirm("Reached the end of the page. Start from the top?")) {',
'						setCurrentMatchIndex(nextMatchIndex);',
'					}',
'				}',
'				} catch (err) {',
'					alert("currentMatchIndex is " + currentMatchIndex);',
'				}',
'			}',
'',
'			function searchPrevious() {',
'				if (currentSearch !== null && currentMatchIndex !== null) {',
'					currentSearch.matches[currentMatchIndex].setNotCurrent();',
'					var previousMatchIndex = currentSearch.getPreviousVisibleMatchIndex();',
'					if (previousMatchIndex < currentMatchIndex || confirm("Reached the start of the page. Continue from the bottom?")) {',
'						setCurrentMatchIndex(previousMatchIndex);',
'					}',
'				}',
'			}',
'',
'			function setCurrentMatchIndex(index) {',
'				currentMatchIndex = index;',
'				currentSearch.matches[currentMatchIndex].setCurrent();',
'			}',
'',
'			/* ------------------------------------------------------------------------- */',
'',
'			// CSS Utilities',
'',
'			function addClass(el, cssClass) {',
'				if (!hasClass(el, cssClass)) {',
'					if (el.className) {',
'						el.className += " " + cssClass;',
'					} else {',
'						el.className = cssClass;',
'					}',
'				}',
'			}',
'',
'			function hasClass(el, cssClass) {',
'				if (el.className) {',
'					var classNames = el.className.split(" ");',
'					return array_contains(classNames, cssClass);',
'				}',
'				return false;',
'			}',
'',
'			function removeClass(el, cssClass) {',
'				if (hasClass(el, cssClass)) {',
'					// Rebuild the className property',
'					var existingClasses = el.className.split(" ");',
'					var newClasses = [];',
'					for (var i = 0; i < existingClasses.length; i++) {',
'						if (existingClasses[i] != cssClass) {',
'							newClasses[newClasses.length] = existingClasses[i];',
'						}',
'					}',
'					el.className = newClasses.join(" ");',
'				}',
'			}',
'',
'			function replaceClass(el, newCssClass, oldCssClass) {',
'				removeClass(el, oldCssClass);',
'				addClass(el, newCssClass);',
'			}',
'',
'			/* ------------------------------------------------------------------------- */',
'',
'			// Other utility functions',
'',
'			function getElementsByClass(el, cssClass, tagName) {',
'				var elements = el.getElementsByTagName(tagName);',
'				var matches = [];',
'				for (var i = 0; i < elements.length; i++) {',
'					if (hasClass(elements[i], cssClass)) {',
'						matches.push(elements[i]);',
'					}',
'				}',
'				return matches;',
'			}',
'',
'			// Syntax borrowed from Prototype library',
'			function $(id) {',
'				return document.getElementById(id);',
'			}',
'',
'			function isOrphan(node) {',
'				var currentNode = node;',
'				while (currentNode) {',
'					if (currentNode == document.body) {',
'						return false;',
'					}',
'					currentNode = currentNode.parentNode;',
'				}',
'				return true;',
'			}',
'',
'			function getWindowWidth() {',
'				if (window.innerWidth) {',
'					return window.innerWidth;',
'				} else if (document.documentElement && document.documentElement.clientWidth) {',
'					return document.documentElement.clientWidth;',
'				} else if (document.body) {',
'					return document.body.clientWidth;',
'				}',
'				return 0;',
'			}',
'',
'			function getWindowHeight() {',
'				if (window.innerHeight) {',
'					return window.innerHeight;',
'				} else if (document.documentElement && document.documentElement.clientHeight) {',
'					return document.documentElement.clientHeight;',
'				} else if (document.body) {',
'					return document.body.clientHeight;',
'				}',
'				return 0;',
'			}',
'',
'			function getToolBarsHeight() {',
'				return $("switches").offsetHeight;',
'			}',
'',
'			function setLogContainerHeight() {',
'				var windowHeight = getWindowHeight();',
'				$("body").style.height = getWindowHeight() + "px";',
'				getLogContainer().style.height = "" +',
'					(windowHeight - getToolBarsHeight()) + "px";',
'			}',
'			window.onresize = setLogContainerHeight;',
'',
'			if (!Array.prototype.push) {',
'				Array.prototype.push = function() {',
'			        for (var i = 0; i < arguments.length; i++){',
'			            this[this.length] = arguments[i];',
'			        }',
'			        return this.length;',
'				};',
'			}',
'',
'			if (!Array.prototype.pop) {',
'				Array.prototype.pop = function() {',
'					if (this.length > 0) {',
'						var val = this[this.length - 1];',
'						this.length = this.length - 1;',
'						return val;',
'					}',
'				};',
'			}',
'',
'			if (!Array.prototype.shift) {',
'				Array.prototype.shift = function() {',
'					if (this.length > 0) {',
'						var firstItem = this[0];',
'						for (var i = 0; i < this.length - 1; i++) {',
'							this[i] = this[i + 1];',
'						}',
'						this.length = this.length - 1;',
'						return firstItem;',
'					}',
'				};',
'			}',
'',
'			function array_removeFromStart(array, numberToRemove) {',
'				if (Array.prototype.splice) {',
'					array.splice(0, numberToRemove);',
'				} else {',
'					for (var i = numberToRemove; i < array.length; i++) {',
'						array[i - numberToRemove] = array[i];',
'					}',
'					array.length = array.length - numberToRemove;',
'				}',
'				return array;',
'			}',
'',
'			function array_contains(arr, val) {',
'				for (var i = 0; i < arr.length; i++) {',
'					if (arr[i] == val) {',
'						return true;',
'					}',
'				}',
'				return false;',
'			}',
'			//]]>',
'		</script>',
'		<style type="text/css">',
'			body {',
'				background-color: white;',
'				color: black;',
'				padding: 0px;',
'				margin: 0px;',
'				font-family: tahoma, verdana, arial, helvetica, sans-serif;',
'				overflow: hidden;',
'			}',
'',
'			div#switchesContainer input {',
'				margin-bottom: 0px;',
'			}',
'',
'			div#switches div.toolbar {',
'				border-top: solid #ffffff 1px;',
'				border-bottom: solid #aca899 1px;',
'				background-color: #f1efe7;',
'				padding: 3px 5px;',
'				font-size: 68.75%;',
'			}',
'',
'			div#switches div.toolbar, div#search input {',
'				font-family: tahoma, verdana, arial, helvetica, sans-serif;',
'			}',
'',
'			div#switches input.button {',
'				padding: 0px 5px;',
'				font-size: 100%;',
'			}',
'',
'			div#switches input#clearButton {',
'				margin-left: 20px;',
'			}',
'',
'			div#levels label {',
'				font-weight: bold;',
'			}',
'',
'			div#levels label, div#options label {',
'				margin-right: 5px;',
'			}',
'',
'			div#levels label#wrapLabel {',
'				font-weight: normal;',
'			}',
'',
'			div#search {',
'				padding: 5px 0px;',
'			}',
'',
'			div#search label {',
'				margin-right: 10px;',
'			}',
'',
'			div#search label.searchboxlabel {',
'				margin-right: 0px;',
'			}',
'',
'			div#search input {',
'				font-size: 100%;',
'			}',
'',
'			div#search input.validregex {',
'				color: green;',
'			}',
'',
'			div#search input.invalidregex {',
'				color: red;',
'			}',
'',
'			div#search input.nomatches {',
'				color: white;',
'				background-color: #ff6666;',
'			}',
'',
'			div#search input.nomatches {',
'				color: white;',
'				background-color: #ff6666;',
'			}',
'',
'			*.greyedout {',
'				color: gray;',
'			}',
'',
'			*.greyedout *.alwaysenabled {',
'				color: black;',
'			}',
'',
'			div#log {',
'				font-family: Courier New, Courier;',
'				font-size: 75%;',
'				width: 100%;',
'				overflow: auto;',
'			}',
'',
'			*.logentry {',
'				overflow: visible;',
'				display: none;',
'				white-space: pre;',
'			}',
'',
'			*.logentry pre.unwrapped {',
'				display: inline;',
'			}',
'',
'			*.logentry span.wrapped {',
'				display: none;',
'			}',
'',
'			body.searching *.logentry span.currentmatch {',
'				color: white !important;',
'				background-color: green !important;',
'			}',
'',
'			body.searching div.searchhighlight *.logentry span.searchterm {',
'				/*font-weight: bold;*/',
'				color: black;',
'				background-color: yellow;',
'			}',
'',
'			div.wrap *.logentry {',
'				white-space: normal !important;',
'				border-width: 0px 0px 1px 0px;',
'				border-color: #dddddd;',
'				border-style: dotted;',
'			}',
'',
'			div.wrap *.logentry pre.unwrapped {',
'				display: none;',
'			}',
'',
'			div.wrap *.logentry span.wrapped {',
'				display: inline;',
'			}',
'',
'			div.searchfilter *.searchnonmatch {',
'				display: none !important;',
'			}',
'',
'			div#log *.TRACE, label#label_TRACE {',
'				color: #666666;',
'			}',
'',
'			div#log *.DEBUG, label#label_DEBUG {',
'				color: green;',
'			}',
'',
'			div#log *.INFO, label#label_INFO {',
'				color: #000099;',
'			}',
'',
'			div#log *.WARN, label#label_WARN {',
'				color: #999900;',
'			}',
'',
'			div#log *.ERROR, label#label_ERROR {',
'				color: red;',
'			}',
'',
'			div#log *.FATAL, label#label_FATAL {',
'				color: #660066;',
'			}',
'',
'			div.TRACE#log *.TRACE,',
'			div.DEBUG#log *.DEBUG,',
'			div.INFO#log *.INFO,',
'			div.WARN#log *.WARN,',
'			div.ERROR#log *.ERROR,',
'			div.FATAL#log *.FATAL {',
'				display: block;',
'			}',
'',
'			div#log div.separator {',
'				background-color: #cccccc;',
'				margin: 5px 0px;',
'				line-height: 1px;',
'			}',
'		</style>',
'	</head>',
'',
'	<body id="body">',
'		<div id="switchesContainer">',
'			<div id="switches">',
'				<div id="levels" class="toolbar">',
'					Filters:',
'					<input type="checkbox" id="switch_TRACE" onclick="applyFilters(); checkAllLevels()" checked="checked" title="Show/hide trace messages" /><label for="switch_TRACE" id="label_TRACE">trace</label>',
'					<input type="checkbox" id="switch_DEBUG" onclick="applyFilters(); checkAllLevels()" checked="checked" title="Show/hide debug messages" /><label for="switch_DEBUG" id="label_DEBUG">debug</label>',
'					<input type="checkbox" id="switch_INFO" onclick="applyFilters(); checkAllLevels()" checked="checked" title="Show/hide info messages" /><label for="switch_INFO" id="label_INFO">info</label>',
'					<input type="checkbox" id="switch_WARN" onclick="applyFilters(); checkAllLevels()" checked="checked" title="Show/hide warn messages" /><label for="switch_WARN" id="label_WARN">warn</label>',
'					<input type="checkbox" id="switch_ERROR" onclick="applyFilters(); checkAllLevels()" checked="checked" title="Show/hide error messages" /><label for="switch_ERROR" id="label_ERROR">error</label>',
'					<input type="checkbox" id="switch_FATAL" onclick="applyFilters(); checkAllLevels()" checked="checked" title="Show/hide fatal messages" /><label for="switch_FATAL" id="label_FATAL">fatal</label>',
'					<input type="checkbox" id="switch_ALL" onclick="toggleAllLevels(); applyFilters()" checked="checked" title="Show/hide all messages" /><label for="switch_ALL" id="label_ALL">all</label>',
'				</div>',
'				<div id="search" class="toolbar">',
'					<label for="searchBox" class="searchboxlabel">Search:</label> <input type="text" id="searchBox" onclick="toggleSearchEnabled(true)" onkeyup="scheduleSearch()" size="20" />',
'					<input type="button" id="searchReset" disabled="disabled" value="Reset" onclick="clearSearch()" class="button" title="Reset the search" />',
'					<input type="checkbox" id="searchRegex" onclick="doSearch()" title="If checked, search is treated as a regular expression" /><label for="searchRegex">Regex</label>',
'					<input type="checkbox" id="searchCaseSensitive" onclick="doSearch()" title="If checked, search is case sensitive" /><label for="searchCaseSensitive">Match case</label>',
'					<input type="checkbox" id="searchDisable" onclick="toggleSearchEnabled()" title="Enable/disable search" /><label for="searchDisable" class="alwaysenabled">Disable</label>',
'					<div id="searchNav">',
'						<input type="button" id="searchNext" disabled="disabled" value="Next" onclick="searchNext()" class="button" title="Go to the next matching log entry" />',
'						<input type="button" id="searchPrevious" disabled="disabled" value="Previous" onclick="searchPrevious()" class="button" title="Go to the previous matching log entry" />',
'						<input type="checkbox" id="searchFilter" onclick="toggleSearchFilter()" title="If checked, non-matching log entries are filtered out" /><label for="searchFilter">Filter</label>',
'						<input type="checkbox" id="searchHighlight" onclick="toggleSearchHighlight()" title="Highlight matched search terms" /><label for="searchHighlight" class="alwaysenabled">Highlight all</label>',
'					</div>',
'				</div>',
'				<div id="options" class="toolbar">',
'					Options:',
'					<input type="checkbox" id="enableLogging" onclick="toggleLoggingEnabled()" checked="checked" title="Enable/disable logging" /><label for="enableLogging" id="wrapLabel">Log</label>',
'					<input type="checkbox" id="wrap" onclick="toggleWrap()" title="Enable / disable word wrap" /><label for="wrap" id="wrapLabel">Wrap</label>',
'					<input type="checkbox" id="newestAtTop" onclick="toggleNewestAtTop()" title="If checked, causes newest messages to appear at the top" /><label for="newestAtTop" id="newestAtTopLabel">Newest at the top</label>',
'					<input type="checkbox" id="scrollToLatest" onclick="toggleScrollToLatest()" checked="checked" title="If checked, window automatically scrolls to a new message when it is added" /><label for="scrollToLatest" id="scrollToLatestLabel">Scroll to latest</label>',
'					<input type="button" id="clearButton" value="Clear" onclick="clearLog()" class="button" title="Clear all log messages"  />',
'					<input type="button" id="closeButton" value="Close" onclick="window.close()" class="button" title="Close the window" />',
'				</div>',
'			</div>',
'',
'		</div>',
'		<div id="log" class="TRACE DEBUG INFO WARN ERROR FATAL"></div>',
'	</body>',
'</html>'
];
		};

		function ConsoleAppender() {}

		var consoleAppenderIdCounter = 1;

		ConsoleAppender.prototype = new Appender();

		ConsoleAppender.prototype.create = function(inPage, containerElement,
				layout, lazyInit, focusConsoleWindow, useOldPopUp,
				complainAboutPopUpBlocking, newestMessageAtTop,
				scrollToLatestMessage, initiallyMinimized, width, height,
				reopenWhenClosed, maxMessages) {
			var appender = this;

			// Common properties
			if (layout) {
				this.setLayout(layout);
			} else {
				this.setLayout(this.defaults.layout);
			}
			var initialized = false;
			var consoleWindowLoaded = false;
			var queuedLoggingEvents = [];
			var isSupported = true;
			var consoleAppenderId = consoleAppenderIdCounter++;

			// Params
			lazyInit = extractBooleanFromParam(lazyInit, true);
			newestMessageAtTop = extractBooleanFromParam(newestMessageAtTop, this.defaults.newestMessageAtTop);
			scrollToLatestMessage = extractBooleanFromParam(scrollToLatestMessage, this.defaults.scrollToLatestMessage);
			width = width ? width : this.defaults.width;
			height = height ? height : this.defaults.height;
			maxMessages = maxMessages ? maxMessages : this.defaults.maxMessages;

			// Functions whose implementations vary between subclasses
			var init, safeToAppend, getConsoleWindow;

			// Configuration methods. The function scope is used to prevent
			// direct alteration to the appender configuration properties.
			var appenderName = inPage ? "InPageAppender" : "PopUpAppender";
			var checkCanConfigure = function(configOptionName) {
				if (initialized) {
					handleError(appenderName + ": configuration option '" + configOptionName + "' may not be set after the appender has been initialized");
					return false;
				}
				return true;
			};

			this.isNewestMessageAtTop = function() { return newestMessageAtTop; };
			this.setNewestMessageAtTop = function(newestMessageAtTopParam) {
				newestMessageAtTop = bool(newestMessageAtTopParam);
				if (consoleWindowLoaded && isSupported) {
					getConsoleWindow().setNewestAtTop(newestMessageAtTop);
				}
			};

			this.isScrollToLatestMessage = function() { return scrollToLatestMessage; };
			this.setScrollToLatestMessage = function(scrollToLatestMessageParam) {
				scrollToLatestMessage = bool(scrollToLatestMessageParam);
				if (consoleWindowLoaded && isSupported) {
					getConsoleWindow().setScrollToLatest(scrollToLatestMessage);
				}
			};

			this.getWidth = function() { return width; };
			this.setWidth = function(widthParam) {
				if (checkCanConfigure("width")) {
					width = extractStringFromParam(widthParam, width);
				}
			};

			this.getHeight = function() { return height; };
			this.setHeight = function(heightParam) {
				if (checkCanConfigure("height")) {
					height = extractStringFromParam(heightParam, height);
				}
			};

			this.getMaxMessages = function() { return maxMessages; };
			this.setMaxMessages = function(maxMessagesParam) {
				maxMessages = extractIntFromParam(maxMessagesParam, maxMessages);
				if (consoleWindowLoaded && isSupported) {
					getConsoleWindow().setMaxMessages(maxMessages);
				}
			};

			// Common methods
			this.append = function(loggingEvent) {
				if (isSupported) {
					queuedLoggingEvents.push(loggingEvent);
					// Force a check of whether the window is closed
					var isSafeToAppend = safeToAppend();
					if (!initialized || (consoleClosed && reopenWhenClosed)) {
						init();
					}
					if (safeToAppend()) {
						appendQueuedLoggingEvents();
					}
				}
			};

			var appendQueuedLoggingEvents = function(loggingEvent) {
				while (queuedLoggingEvents.length > 0) {
					var currentLoggingEvent = queuedLoggingEvents.shift();
					var formattedMessage = appender.getLayout().format(currentLoggingEvent);
					if (appender.getLayout().ignoresThrowable()) {
						formattedMessage += currentLoggingEvent.getThrowableStrRep();
					}
					getConsoleWindow().log(currentLoggingEvent.level, formattedMessage);
				}
				if (focusConsoleWindow) {
					getConsoleWindow().focus();
				}
			};

			var writeHtml = function(doc) {
				var lines = getConsoleHtmlLines();
				doc.open();
				for (var i = 0; i < lines.length; i++) {
					doc.writeln(lines[i]);
				}
				doc.close();
			};

			var consoleClosed = false;

			var pollConsoleWindow = function(windowTest, successCallback, errorMessage) {
				function pollConsoleWindowLoaded() {
					try {
						// Test if the console has been closed while polling
						if (consoleClosed) {
							clearInterval(poll);
						}
						if (windowTest(getConsoleWindow())) {
							clearInterval(poll);
							successCallback();
						}
					} catch (ex) {
						clearInterval(poll);
						isSupported = false;
						handleError(errorMessage, ex);
					}
				}

				// Poll the pop-up since the onload event is not reliable
				var poll = setInterval(pollConsoleWindowLoaded, 100);
			};

			// Define methods and properties that vary between subclasses
			if (inPage) {
				// InPageAppender

				// Extract params
				if (!containerElement || !containerElement.appendChild) {
					isSupported = false;
					handleError("InPageAppender.init: a container DOM element must be supplied for the console window");
					return;
				}
				initiallyMinimized = extractBooleanFromParam(initiallyMinimized, appender.defaults.initiallyMinimized);

				// Configuration methods. The function scope is used to prevent
				// direct alteration to the appender configuration properties.
				this.isInitiallyMinimized = function() { return initiallyMinimized; };
				this.setInitiallyMinimized = function(initiallyMinimizedParam) {
					if (checkCanConfigure("initiallyMinimized")) {
						initiallyMinimized = bool(initiallyMinimizedParam);
					}
				};

				// Define useful variables
				var minimized = false;
				var iframeContainerDiv;
				var iframeId = uniqueId + "_InPageAppender_" + consoleAppenderId;

				this.hide = function() {
					iframeContainerDiv.style.display = "none";
					minimized = true;
				};

				this.show = function() {
					iframeContainerDiv.style.display = "block";
					minimized = false;
				};

				this.isVisible = function() {
					return !minimized;
				};

				this.close = function() {
					if (!consoleClosed) {
						iframeContainerDiv.parentNode.removeChild(iframeContainerDiv);
						consoleClosed = true;
					}
				};

				// Create init, getConsoleWindow and safeToAppend functions
				init = function() {
					var initErrorMessage = "InPageAppender.init: unable to create console iframe"; 
					function finalInit() {
						try {
							getConsoleWindow().setNewestAtTop(newestMessageAtTop);
							getConsoleWindow().setScrollToLatest(scrollToLatestMessage);
							getConsoleWindow().setMaxMessages(maxMessages);
							consoleWindowLoaded = true;
							appendQueuedLoggingEvents();
							if (initiallyMinimized) {
								appender.hide();
							}
						} catch (ex) {
							isSupported = false;
							handleError(initErrorMessage, ex);
						}
					}

					function writeToDocument() {
						try {
							var windowTest = function(win) { return bool(win.loaded); };
							writeHtml(getConsoleWindow().document);
							if (windowTest(getConsoleWindow())) {
								finalInit();
							} else {
								pollConsoleWindow(windowTest, finalInit, initErrorMessage);
							}
						} catch (ex) {
							isSupported = false;
							handleError(initErrorMessage, ex);
						}
					}

					minimized = initiallyMinimized;
					iframeContainerDiv = containerElement.appendChild(document.createElement("div"));

					iframeContainerDiv.style.width = width;
					iframeContainerDiv.style.height = height;
					iframeContainerDiv.style.border = "solid gray 1px";

					// Adding an iframe using the DOM would be preferable, but it doesn't work
					// in IE5 on Windows, or in Konqueror prior to version 3.5 - in Konqueror
					// it creates the iframe fine but I haven't been able to find a way to obtain
					// the iframe's window object
					var iframeHtml = "<iframe id='" + iframeId + "' name='" + iframeId +
						"' width='100%' height='100%' frameborder='0'" +
						"scrolling='no'></iframe>";
					iframeContainerDiv.innerHTML = iframeHtml;
					consoleClosed = false;

					// Write the console HTML to the iframe
					var iframeDocumentExistsTest = function(win) { return bool(win) && bool(win.document); };
					if (iframeDocumentExistsTest(getConsoleWindow())) {
						writeToDocument();
					} else {
						pollConsoleWindow(iframeDocumentExistsTest, writeToDocument, initErrorMessage);
					}

					initialized = true;
				};

				getConsoleWindow = function() {
					var iframe = window.frames[iframeId];
					if (iframe) {
						return iframe;
					}
				};

				safeToAppend = function() {
					if (isSupported && !consoleClosed) {
						if (!consoleWindowLoaded && getConsoleWindow() && getConsoleWindow().loaded) {
							consoleWindowLoaded = true;
						}
						return consoleWindowLoaded;
					}
					return false;
				};
			} else {
				// PopUpAppender

				// Extract params
				useOldPopUp = extractBooleanFromParam(useOldPopUp, appender.defaults.useOldPopUp);
				complainAboutPopUpBlocking = extractBooleanFromParam(complainAboutPopUpBlocking, appender.defaults.complainAboutPopUpBlocking);
				reopenWhenClosed = extractBooleanFromParam(reopenWhenClosed, this.defaults.reopenWhenClosed);

				// Configuration methods. The function scope is used to prevent
				// direct alteration to the appender configuration properties.
				this.isUseOldPopUp = function() { return useOldPopUp; };
				this.setUseOldPopUp = function(useOldPopUpParam) {
					if (checkCanConfigure("useOldPopUp")) {
						useOldPopUp = bool(useOldPopUpParam);
					}
				};

				this.isComplainAboutPopUpBlocking = function() { return complainAboutPopUpBlocking; };
				this.setComplainAboutPopUpBlocking = function(complainAboutPopUpBlockingParam) {
					if (checkCanConfigure("complainAboutPopUpBlocking")) {
						complainAboutPopUpBlocking = bool(complainAboutPopUpBlockingParam);
					}
				};

				this.isFocusPopUp = function() { return focusConsoleWindow; };
				this.setFocusPopUp = function(focusPopUpParam) {
					// This property can be safely altered after logging has started
					focusConsoleWindow = bool(focusPopUpParam);
				};

				this.isReopenWhenClosed = function() { return reopenWhenClosed; };
				this.setReopenWhenClosed = function(reopenWhenClosedParam) {
					// This property can be safely altered after logging has started
					reopenWhenClosed = bool(reopenWhenClosedParam);
				};

				this.close = function() {
					try {
						popUp.close();
					} catch (e) {
						// Do nothing
					}
					consoleClosed = true;
				};

				// Define useful variables
				var popUp;

				// Create init, getConsoleWindow and safeToAppend functions
				init = function() {
					var windowProperties = "width=" + width + ",height=" + height + ",status,resizable";
					var windowName = "PopUp_" + location.host.replace(/[^a-z0-9]/gi, "_") + "_" + consoleAppenderId;
					if (!useOldPopUp) {
						// Ensure a previous window isn't used by using a unique name
						windowName = windowName + "_" + uniqueId;
					}

					function finalInit() {
						consoleWindowLoaded = true;
						getConsoleWindow().setNewestAtTop(newestMessageAtTop);
						getConsoleWindow().setScrollToLatest(scrollToLatestMessage);
						getConsoleWindow().setMaxMessages(maxMessages);
						appendQueuedLoggingEvents();
					}

					try {
						popUp = window.open("", windowName, windowProperties);
						consoleClosed = false;
						if (popUp) {
							if (useOldPopUp && popUp.loaded) {
								popUp.mainPageReloaded();
								finalInit();
							} else {
								writeHtml(popUp.document);
								// Check if the pop-up window object is available
								var popUpLoadedTest = function(win) { return bool(win) && win.loaded; };
								if (popUp.loaded) {
									finalInit();
								} else {
									pollConsoleWindow(popUpLoadedTest, finalInit, "PopUpAppender.init: unable to create console window");
								}
							}
						} else {
							isSupported = false;
							logLog.warn("PopUpAppender.init: pop-ups blocked, please unblock to use PopUpAppender");
							if (complainAboutPopUpBlocking) {
								handleError("log4javascript: pop-up windows appear to be blocked. Please unblock them to use pop-up logging.");
							}
						}
					} catch (ex) {
						handleError("PopUpAppender.init: error creating pop-up", ex);
					}
					initialized = true;
				};

				getConsoleWindow = function() {
					return popUp;
				};

				safeToAppend = function() {
					if (isSupported && !isUndefined(popUp) && !consoleClosed) {
						if (popUp.closed || 
								(consoleWindowLoaded && isUndefined(popUp.closed))) { // Extra check for Opera
							consoleClosed = true;
							logLog.debug("PopUpAppender: pop-up closed");
							return false;
						}
						if (!consoleWindowLoaded && popUp.loaded) {
							consoleWindowLoaded = true;
						}
					}
					return isSupported && consoleWindowLoaded && !consoleClosed;
				};
			}

			if (enabled && !lazyInit) {
				init();
			}

			// Expose getConsoleWindow so that automated tests can check the DOM
			this.getConsoleWindow = getConsoleWindow;
		};

		/* ----------------------------------------------------------------- */

		var PopUpAppender = function(lazyInit, layout, focusPopUp,
				useOldPopUp, complainAboutPopUpBlocking, newestMessageAtTop,
				scrollToLatestMessage, reopenWhenClosed, width, height,
				maxMessages) {

			var focusConsoleWindow = extractBooleanFromParam(focusPopUp, this.defaults.focusPopUp);

			this.create(false, null, layout, lazyInit, focusConsoleWindow,
				useOldPopUp, complainAboutPopUpBlocking,
				newestMessageAtTop, scrollToLatestMessage, null, width, height,
				reopenWhenClosed, maxMessages);
		};

		PopUpAppender.prototype = new ConsoleAppender();

		PopUpAppender.prototype.defaults = {
			layout: new PatternLayout("%d{HH:mm:ss} %-5p - %m{1}%n"),
			focusPopUp: false,
			lazyInit: true,
			useOldPopUp: true,
			complainAboutPopUpBlocking: true,
			newestMessageAtTop: false,
			scrollToLatestMessage: true,
			width: "600",
			height: "400",
			reopenWhenClosed: false,
			maxMessages: null
		};

		PopUpAppender.prototype.toString = function() {
			return "[PopUpAppender]";
		};

		log4javascript.PopUpAppender = PopUpAppender;

		/* ----------------------------------------------------------------- */

		var InPageAppender = function(containerElement, lazyInit,
				layout, initiallyMinimized, newestMessageAtTop,
				scrollToLatestMessage, width, height, maxMessages) {

			this.create(true, containerElement, layout, lazyInit, false,
				null, null, newestMessageAtTop, scrollToLatestMessage,
				initiallyMinimized, width, height, null, maxMessages);
		};

		InPageAppender.prototype = new ConsoleAppender();

		InPageAppender.prototype.defaults = {
			layout: new PatternLayout("%d{HH:mm:ss} %-5p - %m{1}%n"),
			initiallyMinimized: false,
			lazyInit: true,
			newestMessageAtTop: false,
			scrollToLatestMessage: true,
			width: "100%",
			height: "250px",
			maxMessages: null
		};

		InPageAppender.prototype.toString = function() {
			return "[InPageAppender]";
		};

		log4javascript.InPageAppender = InPageAppender;

		// Next line for backwards compatibility
		log4javascript.InlineAppender = InPageAppender;
	})();

	/* --------------------------------------------------------------------- */

	// BrowserConsoleAppender (only works in Opera and Safari and Firefox with
	// FireBug extension)
	var BrowserConsoleAppender = function(layout) {
		if (layout) {
			this.setLayout(layout);
		}
	};

	BrowserConsoleAppender.prototype = new log4javascript.Appender();
	BrowserConsoleAppender.prototype.layout = new NullLayout();
	BrowserConsoleAppender.prototype.threshold = Level.DEBUG;

	BrowserConsoleAppender.prototype.append = function(loggingEvent) {
		var appender = this;

		var getFormattedMessage = function() {
			var layout = appender.getLayout();
			var formattedMessage = layout.format(loggingEvent);
			if (layout.ignoresThrowable() && loggingEvent.exception) {
				formattedMessage += loggingEvent.getThrowableStrRep();
			}
			return formattedMessage;
		};

		if ((typeof opera != "undefined") && opera.postError) { // Opera
			opera.postError(getFormattedMessage());
		} else if (window.console && window.console.log) { // Safari and FireBug
			var formattedMesage = getFormattedMessage();
			// Log to FireBug using its logging methods or revert to the console.log
			// method in Safari
			if (window.console.debug && Level.DEBUG.isGreaterOrEqual(loggingEvent.level)) {
				window.console.debug(formattedMesage);
			} else if (window.console.info && Level.INFO.equals(loggingEvent.level)) {
				window.console.info(formattedMesage);
			} else if (window.console.warn && Level.WARN.equals(loggingEvent.level)) {
				window.console.warn(formattedMesage);
			} else if (window.console.error && loggingEvent.level.isGreaterOrEqual(Level.ERROR)) {
				window.console.error(formattedMesage);
			} else {
				window.console.log(formattedMesage);
			}
		}
	};

	BrowserConsoleAppender.prototype.toString = function() {
		return "[BrowserConsoleAppender]";
	};

	log4javascript.BrowserConsoleAppender = BrowserConsoleAppender;
})();