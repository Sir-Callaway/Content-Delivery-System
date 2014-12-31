function soulsend() {
	$("#soulsend").click(function(){
	var valid = "";
	var isr = ' is required.';
	var text = $("#soultext").val();
	var name = $("#soulname").val();
	var mail = $("#soulmail").val();
	var institution = $("#soulinstitution").val();
	var number = $("#soulnumber").val();
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
	$("#soulresponse").fadeIn("slow");
	$("#soulresponse").html("Error:"+valid);
	}
	else {
	var souldatastr = $("#reservationform").serialize();
	$("#soulresponse").css("display", "inline-block");
	$("#soulresponse").html("Sending message...<img style='height: 1em;' src='/images/system/ajax.gif' /> ");
	$("#soulresponse").fadeIn("slow");
	setTimeout("soulsender('"+souldatastr+"')",2000);
	}
	return false;
	});
}

function soulsender(souldatastr){
$.ajax({
type: "POST",
url: "/soul_itinerary/send",
data: souldatastr,
cache: false,
success: function(html){
$("#soulresponse").fadeIn("slow");
$("#soulresponse").html(html);
setTimeout('$("#soulresponse").fadeOut("slow")',2000);
}
});
}