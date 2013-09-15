$(function() {
	overlay.init();
});

////// OVERLAY //////
var overlay = {
	
	overlay     : null,
	title       : null,
	content     : null,
	closeButton : null,
	
	init : function() {
		overlay.overlay     = $('#overlay');
		overlay.title       = $('#overlay header h3');
		overlay.content     = $('#overlay .overlayContent');
		overlay.closeButton = $('#overlay header a.close');
		
		// close via button
		overlay.closeButton.click(overlay.hide);
		// close via esc
		$(document).keypress(function(e) {
			if (e.keyCode == 27) overlay.hide();
		});
		// close via click next to overlay window
		overlay.overlay.click(overlay.hide);
		$('#overlay .overlayContainer').click(function() {return false;});
		
	},
	
	show : function(title, content) {
		overlay.title.html(title);
		overlay.content.html(content);
		overlay.overlay.show();
	},
	
	hide : function() {
		overlay.overlay.hide();
	},
	
}
