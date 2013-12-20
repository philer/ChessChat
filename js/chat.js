var chat = {
    
    chatLog      : null,
    chatLogFrame : null,
    chatText     : null,
    chatSubmit   : null,
    
    lastMsgId   : 0,
    
    init : function() {
        chat.chatLog      = $('#chatLog');
        chat.chatLogFrame = $('#chatLogFrame');
        chat.chatText     = $('#chatText');
        chat.chatSubmit   = $('#chatSubmit');
        
        chat.chatSubmit.click(chat.sendMessage);
        
        chat.scrollToBottom();
        
        chat.lastMsgId = chatData.lastMsgId;
    },
    
    sendMessage : function(msg) {
        if (typeof msg !== 'string') {
            msg = chat.chatText.val();
            chat.chatText.val('');
        }
        if (msg) {
            // if (msg == 'debug') { } else
            core.post(
                'ChatController',
                'msg',
                'gameId=' + gameData.id + '&msg=' + msg,
                [chat.handleReply, chess.handleReply]
                );
        }
        chat.chatText.focus();
        return false;
    },
    
    handleReply : function(reply) {
        if (reply.msg) {
            chat.chatLog.append(reply.msg);
            chat.scrollToBottom();
        }
        if (reply.lastMsgId) {
            chat.lastMsgId = reply.lastMsgId;
        }
    },

    scrollToBottom : function() {
        chat.chatLogFrame.animate(
            {scrollTop: chat.chatLog.height() - chat.chatLogFrame.height()}, 
            200
        );
    }
};
