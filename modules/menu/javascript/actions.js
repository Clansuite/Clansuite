function treeTooltipOn() { document.getElementById("tree-tooltip").innerHTML = treeTooltips[treeElements.indexOf(this.id)]; }
function treeTooltipOff() { document.getElementById("tree-tooltip").innerHTML = ""; }

var treeElements = ["tree-moveUp", "tree-moveDown", "tree-moveLeft", "tree-moveRight", "tree-insert", "tree-info", "tree-remove"];
var treeTooltips = ["Move Up", "Move Down", "Move Left", "Move Right", "Insert", "Info", "Delete"];

for (var i = 0; i < treeElements.length; i++) {
    if ( document.getElementById(treeElements[i]) )
    {
        document.getElementById(treeElements[i]).onmouseover = treeTooltipOn;
        document.getElementById(treeElements[i]).onmouseout = treeTooltipOff;
    }
}

function treeMoveUp() {
    if (tree.mayMoveUp()) {
        tree.moveUp();
    }
}
function treeMoveDown() {
    if (tree.mayMoveDown()) {
        tree.moveDown();
    }
}
function treeMoveLeft() {
    if (tree.mayMoveLeft()) {
        tree.moveLeft();
    }
}
function treeMoveRight() {
    if (tree.mayMoveRight()) {
        tree.moveRight();
    }
}
function treeInsert() {
    treeHideInfo();
    document.getElementById("tree-insert-form").style.display = "block";
    document.getElementById("tree-insert-where-div").style.display = (tree.active ? "" : "none");
    if (tree.active) {
        var where = document.getElementById("tree-insert-where");
        if (tree.mayInsertInside()) {
            if (!where.options[2] && !where.options[3]) {
                where.options[2] = new Option("Inside at start", "inside_start");
                where.options[3] = new Option("Inside at end", "inside_end");
            }
        } else if (where.options[2] && where.options[3]) {
            where.options[2] = null;
            where.options[3] = null;
            where.options.length = 2;
        }
    }
}
function treeHideInsert() {
    var name = document.getElementById("tree-insert-name");
    var href = document.getElementById("tree-insert-href");
    var title = document.getElementById("tree-insert-title");
    var target = document.getElementById("tree-insert-target");
    var permission = document.getElementById("tree-insert-permission");
    name.value = "";
    href.value = "";
    title.value = "";
    target.value = "";
    permission.value = "";
    document.getElementById("tree-insert-form").style.display = "none";
}
function treeInfo() {
    treeHideInsert();
    var name = document.getElementById("tree-info-name");
    var href = document.getElementById("tree-info-href");
    var title = document.getElementById("tree-info-title");
    var target = document.getElementById("tree-info-target");
    var update_icon = document.getElementById('update_icon');
    var permission = document.getElementById("tree-info-permission");
    name.value = "";
    href.value = "";
    title.value = "";
    target.value = "";
    permission.value = "";
    document.getElementById("tree-info-form").style.display = "block";
    if (tree.active) {
        var node = tree.getActiveNode();
        for (i = 0; i < document.getElementById('tree-info-custom_icon').length; i++)
        {
            if( document.getElementById('tree-info-custom_icon').options[i].text == node.custom_icon )
            {
                document.getElementById('tree-info-custom_icon').options[i].selected = 'true';
            }
        }
        name.value = node.text;
        href.value = node.href;
        title.value = node.title;
        target.value = node.target;
        permission.value = node.permission;
        update_icon.src = tree.custom_icon_path + node.custom_icon;
    }
}
function treeInfoUpdate() {
    var name = document.getElementById("tree-info-name");
    var href = document.getElementById("tree-info-href");
    var title = document.getElementById("tree-info-title");
    var target = document.getElementById("tree-info-target");
    var custom_icon = document.getElementById('tree-info-custom_icon').options[document.getElementById('tree-info-custom_icon').options.selectedIndex];
    var permission = document.getElementById("tree-info-permission");
    name.value = name.value.trim();
    href.value = href.value.trim();
    if (!name.value) {
        return false;
    }
    if (tree.active) {
        var node = tree.getActiveNode();
        node.text = name.value;
        node.href = href.value;
        node.title = title.value;
        node.target = target.value;
        node.custom_icon = custom_icon.text;
        node.permission = permission.value;
        tree.updateHtml();
    }
}
function treeHideInfo() {
    var name = document.getElementById("tree-info-name");
    var href = document.getElementById("tree-info-href");
    var title = document.getElementById("tree-info-title");
    var target = document.getElementById("tree-info-target");
    var permission = document.getElementById("tree-info-permission");
    name.value = "";
    href.value = "";
    title.value = "";
    target.value = "";
    permission.value = "";
    document.getElementById("tree-info-form").style.display = "none";
}

/* only event - blur */
function treeInsertExecute() {
    var where = document.getElementById("tree-insert-where");
    var type = document.getElementById("tree-insert-type");
    var name = document.getElementById("tree-insert-name");
    var href = document.getElementById("tree-insert-href");
    var title = document.getElementById("tree-insert-title");
    var target = document.getElementById("tree-insert-target");
    var custom_icon = document.getElementById('tree-insert-custom_icon').options[document.getElementById('tree-insert-custom_icon').options.selectedIndex];
    var permission = document.getElementById("tree-insert-permission");
    name.value = name.value.trim();
    href.value = href.value.trim();
    if (!name.value) {
        return false;
    }
    var o = {"href": href.value, "title": title.value, "target": target.value, "custom_icon": custom_icon.text, "permission": permission.value};
    if (tree.active) {
        switch (where.value) {
            case "before":
                tree.insertBefore("tree-"+(++tree.count), name.value, type.value, o);
                break;
            case "after":
                tree.insertAfter("tree-"+(++tree.count), name.value, type.value, o);
                break;
            case "inside_start":
                tree.insertInsideAtStart("tree-"+(++tree.count), name.value, type.value, o);
                break;
            case "inside_end":
                tree.insertInsideAtEnd("tree-"+(++tree.count), name.value, type.value, o);
                break;
        }
    } else {
        tree.insert("tree-"+(++tree.count), name.value, type.value, o);
    }
    name.value = "";
    href.value = "";
    title.value = "";
    target.value = "";
    permission.value = "";
    this.blur();
}
function treeRemove() {
    if (tree.mayRemove()) {
        if (confirm("Delete current node ?")) {
            tree.remove();
            if (document.getElementById("tree-insert-form").style.display == "block") {
                treeInsert();
            }
            if (document.getElementById("tree-info-form").style.display == "block") {
                treeInfo();
            }
        }
    }
}


document.getElementById("tree-moveUp").onclick    = treeMoveUp;
document.getElementById("tree-moveDown").onclick  = treeMoveDown;
document.getElementById("tree-moveLeft").onclick  = treeMoveLeft;
document.getElementById("tree-moveRight").onclick = treeMoveRight;

if (document.all && !/opera/i.test(navigator.userAgent)) {
    document.getElementById("tree-moveUp").ondblclick    = treeMoveUp;
    document.getElementById("tree-moveDown").ondblclick  = treeMoveDown;
    document.getElementById("tree-moveLeft").ondblclick  = treeMoveLeft;
    document.getElementById("tree-moveRight").ondblclick = treeMoveRight;
}

document.getElementById("tree-insert").onclick = treeInsert;
document.getElementById("tree-info").onclick = treeInfo;
document.getElementById("tree-remove").onclick = treeRemove;

document.getElementById("tree-insert-button").onclick = treeInsertExecute;
document.getElementById("tree-insert-cancel").onclick = treeHideInsert;

document.getElementById("tree-info-button").onclick = treeInfoUpdate;
document.getElementById("tree-info-cancel").onclick = treeHideInfo;

tree.textClickListener.add(function() { if (document.getElementById("tree-insert-form").style.display == "block") { treeInsert(); } });
tree.textClickListener.add(function() { if (document.getElementById("tree-info-form").style.display == "block") { treeInfo(); } });

/* Finds the index of the first occurence of item in the array, or -1 if not found */
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(item) {
        for (var i = 0; i < this.length; ++i) {
            if (this[i] === item) { return i; }
        }
        return -1;
    };
}

// ---------
// ! PLUGINS
// ---------

function treePluginImportHtml() {
    document.getElementById("tree-plugin").style.display = "block";
    document.getElementById("tree-plugin-header").innerHTML = "Import from Html";
    document.getElementById("tree-plugin-button-import-html").style.display = "block";
}
function treePluginImportHtmlExecute() {
    var html = document.getElementById("tree-plugin-textarea");
    tree.importFromHtml(html.value);
}
function treePluginGenerateMenu() {
    var content = tree.generateMenu();
    document.getElementById("tree-plugin-content").innerHTML = content;
    document.getElementById("tree-plugin").style.display = "block";
}
function treePluginHide() {
    document.getElementById("tree-plugin").style.display = "none";
    document.getElementById("tree-plugin-header").innerHTML = "";
    document.getElementById("tree-plugin-textarea").value = "";
    document.getElementById("tree-plugin-button-import-html").style.display = "none";
}