var core = {
	
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
};
