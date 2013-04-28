var playerName = "Phil";
$(function() {
	var today = new Date();

	$("#chatSubmit").click(function() {
		var msg = $("#chatText").val();
		if (msg) {
			var hh = today.getHours();
			var mm = today.getMinutes();
			var msgHTML = "<p class=\"msgOwn\">"
						+ "	<span class=\"msgTime\">"+hh+":"+mm+"</span>"
						+ "	<span class=\"msgAuthor\">"+playerName+"</span>"
						+ "	<span class=\"msgText\">"+msg+"</span>"
						+ "</p>";
			$("#chatLog").append(msgHTML)
			$("#chatText").val("");
		}
		return false;
	});
});
