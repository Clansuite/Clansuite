// Forms, some help functions.
// Author: Cezary Tomczak [www.gosu.pl]

if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s*|\s*$/g, "");
    };
}

// submit button must have name "submit" to get this working
window.onload = function() {
    document.forms[0].onsubmit = function() {
        if (!document.forms[0].submit.disabled) {
            document.forms[0].submit.disabled = true;
            return true;
        }
        return false;
    };
};

function isEmail(s) {
    return (/^\w+@\w+\.[\w.]+$/.test(s) && s.charAt(s.length-1) != ".");
}
function checkLength(s, min, max) {
    if (typeof min == "number") {
        if (s.length < min) { return false; }
    }
    if (typeof max == "number") {
        if (s.length > max) { return false; }
    }
    return true;
}