function quickmessage() {
	$("#footsend").click(function(){
	var valid = "";
	var isr = ' is required.';
	var name = $("#name").val();
	var mail = $("#mail").val();
	var text = $("#text").val();
	if (name.length<1) {
	valid += '<br />Name'+isr;
	}
	if (!mail.match(/^([a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$)/i)) {
	valid += '<br />A valid Email'+isr;
	}
	if (text.length<1) {
	valid += '<br />Text'+isr;
	}
	if (valid!="") {
	$("#response").fadeIn("slow");
	$("#response").html("Error:"+valid);
	}
	else {
	var datastr = $("#contactform").serialize();
	$("#response").css("display", "inline-block");
	$("#response").html("Sending message...<img style='height: 1em;' src='/images/system/ajax.gif' /> ");
	$("#response").fadeIn("slow");
	setTimeout("send('"+datastr+"')",2000);
	}
	return false;
	});
}

function send(datastr){
$.ajax({
type: "POST",
url: "/quickmessage/send",
data: datastr,
cache: false,
success: function(html){
$("#response").fadeIn("slow");
$("#response").html(html);
setTimeout('$("#response").fadeOut("slow")',5000);
}
});
}