// Initialize MochaUI when the DOM is ready
window.addEvent('domready', function(){									 
	MochaUI.Desktop = new MochaUI.Desktop();									 
	MochaUI.Dock = new MochaUI.Dock();	
	MochaUI.Modal = new MochaUI.Modal();
});


// This runs when a person leaves your page.
window.addEvent('unload', function(){
	if (MochaUI) MochaUI.garbageCleanUp();
});