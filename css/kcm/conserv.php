<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))

ob_start("ob_gzhandler");

else

ob_start();

header('Content-type: text/css');
header('Expires: Fri, 21 Dec 2012 00:00:00 GMT');


$files = array(
  'reset.css',
  'default.css',
  'header.css',
  'site.css',
  'footer.css',
  'horizontal_nav.css',
  'navigation.css',
  //'style.css',
  //'elements.css',
  'buttons.css'
        );

for ($file = 0; $file < sizeOf($files); $file++)
echo file_get_contents($files[$file]) ."\n";

?>