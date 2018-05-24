<?php
require "inc/includes.php";
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv='Pragma' content='no-cache'>
<meta http-equiv='Cache-Control' content='no-cache'>
<meta http-equiv='Expires' content='-1'>
<link href="css/default_style.css" rel="stylesheet" type="text/css" />
<style type='text/css'>
.menudiv{	margin:5px auto;	padding:1px;	width:90%;	border:1px solid #069;	background:#fff;	text-align:left;	}
.menudiv ul{	height:22px;	line-height:22px;	}
.msub{	padding:3px;	text-align:left;	font-family: Tahoma;	font-size: 12px;	}
</style>

</head>

<body>

<div style='margin:0;padding-top:5%;text-align:center;'>

<div style='width:90%;height:30px;background:url(Images/<? echo $sitename[1]; ?>.png) no-repeat;margin-bottom:10px;'></div>

<div class='menudiv'>
	<ul class='tHeading' style="text-align:center;"><li>地址本管理</li></ul>
	<ul class='msub' id=submenu1>
		<li><a href="address.php" target=mainframe>添加联系人</a></li>
		<li><a href="address_search.php?action=list" target=mainframe>查询联系人地址</a></li>
		<li><a href="book.php?action=list" target=mainframe>书籍管理与赠书名单</a></li>
	</ul>
</div>

<div style='margin-top:30px;width:170px;text-align:center;'><a href='http://www.bsm.org.cn' target=_blank>武汉大学简帛研究中心</a></div>
</div>
</body>
</html>