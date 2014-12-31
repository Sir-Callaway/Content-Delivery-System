function loadGen(base, containerID, controller)
{
	//alert(base);
	// Display a loading icon in our display element
	$('#'+containerID).html("<div style='text-align:center;width:auto;'><br /><br />" +
			"<h1 style='color:blue;'>Loading...</h1>" +
			"<img src='" + base + "images/system/ajax.gif' style='width:auto;height:auto;margin:0 auto;'>" +
			"<br /><br /></div>");
			
	// Request the JSON and process it
	$.ajax({
		type:'GET',
		url: base + "/components/"+controller+"/0/3",
		success:function(results) {
			// Display the thumbnails on the page
			$('#'+containerID).html(results.components);
		},
		dataType:'json'
	});
	//alert("<img src='" + base + "/images/system/ajax.gif' style='width:auto;height:auto;margin:0 auto;'>");
}