var playerName = "Phil";
//var playerID = 1;

$(function() {
	
	var today = new Date();
	
	$("#chatSubmit").click(function() {
		
		var msg = $("#chatText").val();
		
		if (msg) {
			
			var msgTime = today.getHours()+":"+today.getMinutes();
			
			msgData = "msgTime="+msgTime
					+ "&playerName="+playerName
					+ "&msg="+msg;
			
			$.ajax({  
				type: "POST",  
				url: "lib/ajaxAnswer.php",  
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
		    	}
		    });
		}
		return false;
	});
});
