$(function() {
	$( '.chesspiece' ).draggable({
		containment: 'table#chessboardTable',
		stack: '.chesspiece',
		snap: '.square > div',
		snapMode: 'inner',
		snapTolerance: '10'
		//, revert: true
	});
	$( 'table#chessboardTable .square' ).droppable({
		accept: '.chesspiece',
		drop: function( event, ui ) {
			// var chesspiece = ui.draggable;
			// chesspiece.position(' { of: $(this), my: 'left top', at: 'left top' } ');

			ui.draggable.draggable( 'option', 'revert', false );
			
			from = ui.draggable.attr('id');
			to = $(this).attr('id');
			sendMessage('#'+from+' was dropped on #'+to);
			sendMessage(from.substr( from.indexOf('-')+1 )
				+ '-' + to.substr( to.indexOf('-')+1 )
				);
		}
	});
});
