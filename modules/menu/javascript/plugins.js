function DynamicTreePlugins() {
    this.importFromHtml = function(html) {
        // dirty hack for ie (automatic conversion to absolute paths problem), see also DynamicTreeBuilder.parse()
        html = html.replace(/href=["']([^"']*)["']/g, 'href="dynamictree://dynamictree/$1"');
        document.getElementById(this.id).innerHTML = html;
        this.reset();
    };
    this.generateMenu = function() {
        var ret = "";
        for (var p in this.allNodes) {
            if (!this.allNodes[p]) { continue; }
            var node = this.allNodes[p];
            var doc = node.isDoc ? "item" : "folder"
            var target = node.target == '' ? "_self" : node.target;
            var title = node.title == '' ? node.text : node.title;
            ret += '<input type="hidden" name="container['+node.id+'][parent]" value="'+node.parentNode.id+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][title]" value="'+title+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][type]" value="'+doc+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][text]" value="'+node.text+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][href]" value="'+node.href+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][target]" value="'+target+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][sortorder]" value="'+node.getIndex()+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][icon]" value="'+node.custom_icon+'">\n';
            ret += '<input type="hidden" name="container['+node.id+'][permission]" value="'+node.permission+'">\n';
        }
        return ret;
    };
}

/* Repeat string n times */
if (!String.prototype.repeat) {
    String.prototype.repeat = function(n) {
        var ret = "";
        for (var i = 0; i < n; ++i) {
            ret += this;
        }
        return ret;
    };
}