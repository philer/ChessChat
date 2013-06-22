var playerName = "Phil";
//var playerID = 1;

$(function() {
	
	var today = new Date();
	
	$("#chatSubmit").click(function() {
		
		var msg = $("#chatText").val();
		
		if (msg) {
			
			var msgTime = today.getHours()+":"+today.getMinutes();
			
			/*msgData = "msgTime="+msgTime
					+ "&playerName="+playerName
					+ "&msg="+msg;*/
			msgData = "controller=Game"
					+ "&gameId=" + "1" // TODO
					+ "&method=post"
					+ "&msg=" + msg;
			
			$.ajax({  
				type: "POST",  
				//url: "lib/ajaxAnswer.php",  
				url: "index.php/ajax",
				data: msgData,  
				success: function(msgTpl) {  
					$("#chatLog").append(msgTpl);
					/*msg += ajaxAnswer;
					var msgHTML = "<p class=\"msgOwn\">"
								+ "	<span class=\"msgTime\">"+msgTime+"</span>"
								+ "	<span class=\"msgAuthor\">"+playerName+"</span>"
								+ "	<span class=\"msgText\">"+msg+"</span>"
								+ "</p>";
					$("#chatLog").append(msgHTML);*/
					$("#chatText").val("");
					scrollChatToBottom();
		    	}
		    });
		}
		return false;
	});
});

$(function() {
	$( ".chesspiece" ).draggable({
		containment: "table#chessboardTable"
		, cursor: 'move'
		//, snap: "table#chessboardTable td.square"
		, stack: '.chesspiece'
		//, revert: true
	});
	$( "table#chessboardTable td.square" ).droppable({
		accept: ".chesspiece",
		drop: function( event, ui ) {
			//$("#chatText").val("asdf");
			var chesspiece = ui.draggable;
			
			//chesspiece.draggable( 'disable' );
			//$(this).droppable( 'disable' );
			//chesspiece.position(" { of: $(this), my: 'left top', at: 'left top' } ");
			chesspiece.draggable( 'option', 'revert', false );

			alert( 'The chesspiece #'
				+ chesspiece.attr('id')
				+ ' was dropped onto the square #'
				+ $(this).attr('id') );
		}
	});
});

//TODO
function scrollChatToBottom() {
	$('#chatLog').animate({ 
		scrollTop: $('#chatLog > div').height()-$('#chatLog').height()}, 
		200, "swing"
	);
}
