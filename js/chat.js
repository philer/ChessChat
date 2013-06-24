var playerName = 'Phil';
//var playerID = 1;

// DOM vars
var chatLog;
var chatLogFrame;
var chatText;
var chatSubmit;

// init
$(function() {
	chatLog      = $('#chatLog');
	chatLogFrame = $('#chatLogFrame');
	chatText     = $('#chatText');
	chatSubmit   = $('#chatSubmit');
	
	chatSubmit.click(sendMessage);
	
	scrollChatToBottom();
});

// functions

function sendMessage(msg) {
	if (typeof msg !== 'string') {
		msg = chatText.val();
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
			success: showMessage
	    });
	}
	return false;
}

function showMessage(msgTpl) {  
	chatLog.append(msgTpl);
	chatText.val('')
	        .focus();
	scrollChatToBottom();
}

function scrollChatToBottom() {
	chatLogFrame.animate(
		{scrollTop: chatLog.height() - chatLogFrame.height()}, 
		200
	);
}
