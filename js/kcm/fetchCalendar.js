function loadCal(cal)
{
	var base = window.location.protocol + "//" + window.location.host;
	// Display a loading icon in our display element
	$('#calendaring').html("<div style='text-align:center;width:auto;'><br /><br />" +
			"<h1 style='color:blue;'>Loading...</h1>" +
			"<img src='" + base + "/images/system/ajax.gif' style='width:auto;height:auto;margin:0 auto;'>" +
			"<br /><br /></div>");
			
	// Request the JSON and process it
	$.ajax({
		type:'GET',
		url: cal,
		success:function(results) {
			// Display the thumbnails on the page
			$('#calendaring').html(results.calendar);
            //alert(results.calendar);
		},
		dataType:'json'
	});
	//alert("<img src='" + base + "/images/system/ajax.gif' style='width:auto;height:auto;margin:0 auto;'>");
}