function wrapFish() {
	var catfish = document.getElementById('notification');
	var subelements = [];
	for (var i = 0; i < document.body.childNodes.length; i++) {
 		subelements[i] = document.body.childNodes[i];
	}

	var zip = document.createElement('div');    // Create the outer-most div (zip)
	zip.id = 'zip';                      // call it zip

	for (var i = 0; i < subelements.length; i++) {
	zip.appendChild(subelements[i]); 
	}
	document.body.appendChild(zip); // add the major div
	document.body.appendChild(catfish); // add the catfish after the zip
}

addLoadEvent(function() {
	wrapFish();
});