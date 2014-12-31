<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))

ob_start("ob_gzhandler");

else

ob_start();

header('Content-type: text/javascript');
header('Expires: Fri, 21 Dec 2012 00:00:00 GMT');


$files = array(
			'jquery.ui.core.js',
			'jquery.ui.widget.js',		
			'jquery.ui.tabs.js'
        );

for ($file = 0; $file < sizeOf($files); $file++)
echo file_get_contents($files[$file]) ."\n";

?>