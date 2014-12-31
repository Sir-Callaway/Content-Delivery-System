<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))

ob_start("ob_gzhandler");

else

ob_start();

header('Content-type: text/javascript');
header('Expires: Fri, 21 Dec 2012 00:00:00 GMT');


$files = array(
			'fetchCalendar.js',
			'fetchComponent.js',
			'responsive-enhance.js',
			'jquery.min.js',
			'jquery.ui.core.js',
			'jquery.ui.widget.js',		
			'jquery.ui.tabs.js',
			'jquery.hoverIntent.minified.js',
			'slides.min.jquery.js',
			'quickmessage.js',
			'soulSend.js',
			'printContent.js',
			'document.ready.js'
        );

for ($file = 0; $file < sizeOf($files); $file++)
echo file_get_contents($files[$file]) ."\n";

?>