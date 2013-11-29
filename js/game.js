////// CHAT //////
var chat = {
	
	chatLog      : null,
	chatLogFrame : null,
	chatText     : null,
	chatSubmit   : null,
	
	lastId   : 0,
	
	init : function() {
		chat.chatLog      = $('#chatLog');
		chat.chatLogFrame = $('#chatLogFrame');
		chat.chatText     = $('#chatText');
		chat.chatSubmit   = $('#chatSubmit');
		
		chat.chatSubmit.click(chat.sendMessage);
		
		chat.scrollToBottom();
		
		chat.lastId = chatData.lastId;
		setInterval(chat.getUpdate, chatData.updateInterval);
	},
	
	getUpdate : function() {
		cc.post(
			'ChatController',
			'getUpdate',
			'gameId=' + gameData.id + '&lastId=' + chat.lastId,
			chat.handleReply
			);
	},
	
	sendMessage : function(msg) {
		if (typeof msg !== 'string') {
			msg = chat.chatText.val();
			chat.chatText.val('');
		}
		if (msg) {
			cc.post(
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
		if (reply.lastId) {
			chat.lastId = reply.lastId;
		}
	},

	scrollToBottom : function() {
		chat.chatLogFrame.animate(
			{scrollTop: chat.chatLog.height() - chat.chatLogFrame.height()}, 
			200
		);
	}
};

////// CHESS //////
var chess = {
	
	chesspieces : null,
	squares     : null,
	whitePrison : null,
	blackPrison : null,
	statusField : null,
	selected    : null,
	
	init : function() {
		
		chess.squares     = $('td.square');
		chess.whitePrison = $('#whitePrison');
		chess.blackPrison = $('#blackPrison');
		chess.statusField = $('#status');
		
		if (gameData.ownColor) {
			chess.chesspieces = $('table#chessboardTable span.chesspiece.' + gameData.ownColor);
			chess.chesspieces.click(chess.select);
			chess.squares.click(chess.moveTo);
			
			chess.chesspieces.draggable({
				containment   : 'table#chessboardTable',
				stack         : 'table#chessboardTable span.chesspiece',
				snap          : 'td.square > div',
				snapMode      : 'inner',
				snapTolerance : '10',
				revert        : 'invalid',
				start         : function(event, ui) {
				                	if (chess.selected !== null) {
				                		chess.selected.removeClass('selected');
									}
									chess.selected = $(this).addClass('selected noClick');
				                }
			});
			
			chess.squares.droppable({
				accept : chess.chesspieces,
				drop   : chess.handleMove
			});
		}
	},
	
	/**
	 * Marks a chesspiece as 'selected'
	 */
	select : function(event) {
		if (chess.selected !== null) {
			chess.selected.removeClass('selected');
			if (chess.selected.getField() === $(this).getField()) {
				chess.selected = null;
				return;
			}
		}
		chess.selected = $(this).addClass('selected');
	},
	
	/**
	 * Moves a selected chesspiece to the appropriate spot
	 */
	moveTo : function(event) {
		if (chess.selected !== null
			&& chess.selected.getField() !== $(this).getField() ) {
			
			moveStr = chess.selected.getField()
				    + '-'
				    + $(this).getField();
			cc.post(
				'ChatController',
				'move', 
				'gameId=' + gameData.id + '&move=' + moveStr,
				[chat.handleReply, chess.handleReply]
				);
			
			chess.selected.removeClass('selected');
			chess.selected = null;
		}
	},
	
	/**
	 * Handles move via drag & drop
	 */
	handleMove : function(event, ui) {
		// ui.draggable.draggable( 'option', 'revert', false );
		// var moveStr = ui.draggable.getField() + '-' + $(this).getField();
		if (ui.draggable.getField() !== $(this).getField()) {
			cc.post(
				'ChatController',
				'move', 
				'gameId='
					+ gameData.id
					+ '&move='
					+ ui.draggable.getField()
					+ '-'
					+ $(this).getField(),
				[chat.handleReply, chess.handleReply]
				);
		} else {
			chess.resetMove(ui.draggable.getField());
		}
		ui.draggable.removeClass('selected');
	},
	
	/**
	 * Reacts to an ajax response
	 * @param  {json} reply
	 */
	handleReply : function(reply) {
		if (reply.move) {
			if (reply.move.valid) {
				chess.executeMove(reply.move)
			} else {
				// alert(reply.move.invalidReason);
				chess.resetMove(reply.move.from);
			}
		}
		if (reply.status) {
			chess.statusField.html(reply.status);
		}
	},
	
	/**
	 * Actually moves chesspiece to new html parent.
	 * @param  {string} move "E3-"E4"
	 */
	executeMove : function(move) {
		// var from = move.substr(0,2);
		// var to   = move.substr(3,2);
		
		var chesspiece = $('#chesspiece-' + move.from);
		var newsquare  = $('#square-' + move.to + ' > div');
		
		var prisoner = newsquare.contents();
		if (typeof prisoner.html() !== 'undefined') {
			if (prisoner.hasClass('white')) {
				chess.whitePrison.append('<li>' + prisoner.html() + '</li>');
			} else {
				chess.blackPrison.append('<li>' + prisoner.html() + '</li>');
			}
		}
		newsquare.empty();
		chesspiece.appendTo(newsquare)
		          .css({top: '0px', left: '0px'})
		          .attr('id', 'chesspiece-' + move.to);
	},
	
	/**
	 * Returns a chesspiece to its original position, e.g.
	 * when a move was invalid.
	 * This only works if executeMove has not been called yet.
	 * @param  {string} move "E3-E4"
	 */
	resetMove : function(from) {
		// from = move.substr(0,2);
		$('#chesspiece-' + from).css({top: '0px', left: '0px'});
	},
	
	/**
	 * Layout helper
	 */
	setBoardSize : function() {
		var gh = $('#game').height() - $("#game header").height() - $("#game footer").height();
		var gw = $("#game").innerWidth();
		var a = Math.min(gh,gw-100);
		
		$("#chessboardTable").height(.9*a)
		                     .width(.9*a);
		
		$("#chessboard").css("margin-top", (gh-0.9*a )/2)
		                .css("font-size",0.07*a+"px");
	}
};

/// chess lib ////
jQuery.fn.getField = function() {
	id = $(this[0]).attr('id');
	return id.substr( id.indexOf('-')+1 );
};

$(function() {
	
	chess.setBoardSize();
	$(window).resize(chess.setBoardSize);
	
	chat.init();
	if (game !== 'undefined') {
		chess.init();
	}
	
	$('#resign').click(function(){
		overlay.show('Resign', 'resign is not implemented');
		return chat.sendMessage('/resign');
	});
	$('#offerDraw').click(function(){
		overlay.show('Draw', 'draw is not implemented');
		return chat.sendMessage('/offerDraw');
	});
});
