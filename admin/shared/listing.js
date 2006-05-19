// Colorizing table rows
// Author: Cezary Tomczak [www.gosu.pl]

if (window.attachEvent) {
    window.attachEvent("onload", function(e) { listing(); });
} else if (window.addEventListener) {
    window.addEventListener("load", function(e) { listing(); }, false);
}

function listing() {
    var tables = document.getElementsByTagName("table");
    for (var i = 0; i < tables.length; ++i) {
        if (/listing/.test(tables[i].className)) {
            var trs = tables[i].getElementsByTagName("tr");
            for (var j = 0; j < trs.length; ++j) {
                for (var k = 0; k < trs[j].childNodes.length; ++k) {
                    var td = trs[j].childNodes[k];
                    if (td.nodeType != 1 || td.tagName == "TH") { continue; }
                    td.className += (td.className ? " " : "") + "block"+(j % 2 == 0 ? 1 : 2);
                }
            }
        }
    }
}