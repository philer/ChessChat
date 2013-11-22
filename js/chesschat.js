///// chesschat core class /////
var cc = {
	
	init : function() {
		$('#mainMenu a[href$="Game/new"]').click(function() {
			overlay.showTpl('gameForm');
			return false;
		});
		$('#mainMenu a[href$="login"]').click(function() {
			overlay.showTpl('loginForm');
			return false;
		});
		$('#mainMenu a[href$="register"]').click(function() {
			overlay.showTpl('registerForm');
			return false;
		});
	},
	
	post : function(controller, action, data, listeners) {
		$.ajax({  
			type: 'POST',
			url: 'index.php/ajax',
			data: 'controller=' + controller
				+ '&action=' + action
				+ (data === undefined ? '' : '&' + data),
			dataType: 'json',
			success: listeners
		});
	}
	
}

////// OVERLAY //////
var overlay = {
	
	overlay     : null,
	container   : null,
	content     : null,
	closeButton : null,
	
	init : function() {
		overlay.overlay     = $('#overlay');
		overlay.container   = $('#overlay .overlayContainer');
		overlay.content     = $('#overlay .overlayContent');
		overlay.closeButton = $('#overlay a.close');
		
		// close via button
		overlay.closeButton.click(overlay.hide);
		// close via esc
		$(document).keypress(function(e) {
			if (e.keyCode == 27) overlay.hide();
		});
		// close via click next to overlay window
		overlay.overlay.click(overlay.hide);
		overlay.container.click(function(e) {e.stopPropagation()});
		
	},
	
	showTpl : function(tplName) {
		cc.post(
			'TemplateEngine',
			'fetch',
			'tpl=' + tplName,
			function(e) {
				overlay.show(e.tpl);
			});
	},
	
	show : function(content) {
		overlay.content.html(content);
		overlay.overlay.show();
		overlay.container.css('max-height',
				$('#overlay .overlayContent section').height()
				+ 100
			);
	},
	
	hide : function() {
		overlay.overlay.hide();
	},
	
}

$(function() {
	cc.init();
	overlay.init();
});
