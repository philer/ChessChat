$(function() {
	chess.init();
});

var chess = {
	
	chesspieces : null,
	squares :     null,

	
	init : function() {
		
		chess.chesspieces = $('table#chessboardTable .chesspiece.' + ownColor);
		chess.squares =     $('table#chessboardTable .square');
		
		chess.chesspieces.draggable({
			containment :   'table#chessboardTable',
			stack :         '.chesspiece',
			snap :          '.square > div',
			snapMode :      'inner',
			snapTolerance : '10'
			//, revert: true
		});
		
		chess.squares.droppable({
			accept : chess.chesspieces,
			drop :   chess.handleMove
		});
	},
	
	handleMove : function(event, ui) {
		// ui.draggable.draggable( 'option', 'revert', false );
		
		from = ui.draggable.attr('id');
		to = $(this).attr('id');
		
		// chat.sendMessage('#' + from + ' was dropped on #' + to);
		chat.sendMessage(
			from.substr( from.indexOf('-')+1 )
			+ '-'
			+ to.substr( to.indexOf('-')+1 )
		);
	},
	
	handleReply : function(reply) {
		if (reply.move) {
			chess.executeMove(reply.move);
		}
	},
	
	// TODO
	// animateMove : function(move) {}
	
	executeMove : function(move) {
		// expecting move to look like "E3-E4"
		from = move.substr(0,2);
		to =   move.substr(3,2);
		
		chesspiece = $('#chesspiece-' + from);
		newsquare =  $('#square-' + to + ' > div');
		
		// TODO add to prison
		newsquare.empty();
		chesspiece.appendTo(newsquare);
		chesspiece.css({top: 0, left: 0});
		chesspiece.attr('id', 'chesspiece-' + to);
	}
}
	
