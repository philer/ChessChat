$(function() {
	chat.init();
});

var chat = {
	
	chatLog :      null,
	chatLogFrame : null,
	chatText :     null,
	chatSubmit :   null,

	
	init : function() {
		chat.chatLog      = $('#chatLog');
		chat.chatLogFrame = $('#chatLogFrame');
		chat.chatText     = $('#chatText');
		chat.chatSubmit   = $('#chatSubmit');
		
		chat.chatSubmit.click(chat.sendMessage);
		
		chat.scrollToBottom();
	},
	
	sendMessage : function(msg) {
		
		if (typeof msg !== 'string') {
			msg = chat.chatText.val();
			chat.chatText
				.val('')
				.focus();
		}
		
		if (msg) {
			msgData = 'controller=Game'
					+ '&gameId=' + '1' // TODO
					+ '&method=post'
					+ '&msg=' + msg;
			
			$.ajax({  
				type: 'POST',
				url: 'index.php/ajax',
				data: msgData,
				dataType: 'json',
				success: [chat.handleReply, chess.handleReply]
		    });
		}
		return false;
	},
	
	handleReply : function(reply) {
		if (reply.msg) {
			chat.chatLog.append(reply.msg);
			chat.scrollToBottom();
		}
	},

	scrollToBottom : function() {
		chat.chatLogFrame.animate(
			{scrollTop: chat.chatLog.height() - chat.chatLogFrame.height()}, 
			200
		);
	}
}
