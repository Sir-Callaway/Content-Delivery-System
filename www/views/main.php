<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="width=device-width; initial-scale=0" name="viewport">
    <meta name="description" content="{pageTitle}" />
    <meta name="keywords" content="{description}" />
    <title>{pageTitle} | KCM</title>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="icon" type="image/x-icon" href="/favicon.ico"  media="screen,projection" charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/css/kcm/conserv.css"  media="screen,projection" charset="utf-8">      
    <link rel="icon" type="image/ico" href="/images/system/favicon.ico" media="all">
    <style>@import url(http://fonts.googleapis.com/css?family=Dosis);</style>
    {inHeadFileSources}
  </head>
  <body id="<?php if(trim($controller)=='') echo 'home';?>">
  {header}
  {body}
  {footer}

    <script type="text/javascript" src="/js/kcm/conserv.js"></script>
  {inBodyFileSources}
  </body>  
</html>