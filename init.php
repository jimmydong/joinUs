<?php 
print <<< end_of_head
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
	<style>
		html,body,div,p{
			font-size: 1.2rem;
		}
	</style>
	</head>
  <body>
end_of_head;
include('include.inc.php');

Util::init();
echo "init OK。 <a href='/login.php'>join now!</a>";


