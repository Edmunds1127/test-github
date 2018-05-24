<?php
error_reporting(E_ALL & ~E_NOTICE);
require "inc/includes.php";//包含公用引用文件
/*
mysql> desc book;
+-------+-------------+------+-----+---------+----------------+
| Field | Type        | Null | Key | Default | Extra          |
+-------+-------------+------+-----+---------+----------------+
| id    | int(11)     | NO   | PRI | NULL    | auto_increment |
| name  | varchar(50) | YES  |     | NULL    |                |书名
| cbsj  | date        | YES  |     | NULL    |                |出版时间
| cbs   | varchar(50) | YES  |     | NULL    |                |出版社
| zsmd  | text        | YES  |     | NULL    |                |赠书名单（数组转换为字符串保存于数据库中）
+-------+-------------+------+-----+---------+----------------+
*/
$connect_id = mysql_connect("$nameost","$dbUser","$dbPass") or die("不能连接到数据库服务器！可能是数据库服务器没有启动，或者用户名密码有误！");
$DBID       = @mysql_select_db("$dbData",$connect_id) or die("选择数据库出错，可能是您指定的数据库不存在！");
$action     = $_GET["action"];
$id         = $_GET["id"];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv='Pragma' content='no-cache'>
<meta http-equiv='Cache-Control' content='no-cache'>
<meta http-equiv='Expires' content='-1'>
<link href='css/default_style.css' rel='stylesheet' type='text/css' />
<script language=javascript>
<!--
function chkform()
	{
		if(document.book.name.value=="")
		{
			alert("请输入书籍名称！");
			document.book.name.focus();
			return false;
		}
	}
function confirmdel(durl,sid)
{
	/*durl:url by dujie 2009-03-15*/
	if (confirm("真的要删除吗?"))
	window.location.href=durl+sid
}
//-->
</script>
</head>

<body>
		<div style='margin-top:20px;width:600px;text-align:left;border-bottom:1px solid #069;'>
		<ul><li style='width:570px;;padding-left:100px;text-align:left;float:right;text-align:right;'><a href='book.php?action=list'>所有书籍</a>&nbsp;|&nbsp;<a href='book.php'>添加新书籍</a></li></ul>
	</div>
<?php
if ($action == "insert") {
	$name = $_POST["name"];
	$name = str_replace("《","",$name);$name = str_replace("》","",$name);//将书名号替换掉
	$cbsj = $_POST["cbsj"];
	$cbs = $_POST["cbs"];

	$query_str = "insert into book(name,cbs,cbsj) values('$name','$cbs','$cbsj')";
	//echo $query_str;exit;
	$result=mysql_query($query_str) or die("更新数据库失败A");
	msgbox(500,150,'添加书籍成功！即将返回所有书籍列表……','book.php?action=list');
	exit;
}

if ($action == "editok") {
	$name = $_POST["name"];
	$name = str_replace("《","",$name);$name = str_replace("》","",$name);//将书名号替换掉
	$cbsj = $_POST["cbsj"];
	$cbs = $_POST["cbs"];

 $query_str = "update book set name='$name',cbsj='$cbsj',cbs='$cbs' where id=$id";
 $result=mysql_query($query_str) or die("更新数据库失败1");
	msgbox(500,150,'更新书籍成功！即将返回所有书籍列表……','book.php?action=list');
exit;
}

if ($action == "delete") {
	if ($id<0) die("错误的调用(1)");
	$query_str = "delete from book where id=$id";
	$result=mysql_query($query_str) or die("更新数据库失败3");
	msgbox(500,150,'删除书籍成功！即将返回所有书籍列表……','book.php?action=list&class=-1');

	exit;
}

if ($action == "list") {
	$query  = "select * from book order by id desc";
	$pagestr= Make_Page($query,$connect_id);
	$result = mysql_query($query,$connect_id);
	if(!$result || mysql_num_rows($result)==0){	msgbox(600,150,'没有找到有关记录，即将返回上一步','javascript:history.go(-1);');exit;}
	$col_array=array("600px","10%","30%","20%","40%");
	echo "<div class='list' style='width:$col_array[0];'>";
	echo "<ul class='list_heading'><li style='width:$col_array[1];'>ID</li><li style='width:$col_array[2];'>书籍名称</li><li style='width:$col_array[3];'>出版社</li><li style='width:$col_array[4];'>操作</li></ul>";
	while($row=mysql_fetch_array($result)){
		$id  = $row["id"];
		$name= "《".$row["name"]."》";
		$cbs = $row["cbs"];
	echo "<ul class='list_ul'>";
	echo "<li style='width:$col_array[1];text-align:center;'>$id</li>";
	echo "<li style='width:$col_array[2];'>$name</li>";
	echo "<li style='width:$col_array[3];'>$cbs</li>";
	echo "<li style='width:$col_array[4];'><a href='bookmd.php?&id=$id'>赠书名单</a>&nbsp;|&nbsp;<a href=user.php?action=edit&id=$id>编辑</a>&nbsp;|&nbsp;<a href='javascript:confirmdel(\"book.php?action=delete&id=\",\"$id\")'>删除</a></li>";
	echo "</ul>";
	}
	echo "</div>";
	exit;
}

$tmp_action="";

if ($action == "edit" ) {
 if ($id<0) die("错误调用(3)");
 $result = mysql_query("select * from book where id=$id") ;
 $total = mysql_num_rows($result);
 if ($total <> 1) {
 msgbox(500,150,'该书籍不存在！即将返回所有书籍列表……','book.php?action=list');
 exit;
 }
 $row  = mysql_fetch_array ($result);
 $cbs  = $row["cbs"];
 $name =$row["name"];
 $cbsj =$row["cbsj"];
 $bar  = "编辑书籍";
 $tmp_action = "book.php?action=editok&id=$id";
}else{
 $bar  = "添加新书籍";
 $tmp_action = "book.php?action=insert";
}
?>

<div style='width:600px;margin:0 auto;padding-top:10%;text-align:center;'>
<?php
//main table to be filled
	$list_col_array_l=array("600px","15%","55%","28%");
	echo "<form style='margin:0;' action=$tmp_action method='post' name='book' onSubmit='return chkform();' onReset='return confirm('您确认要重新填写此表单吗？');'>";
	echo "<div class='list' style='width:$list_col_array_l[0];'>";
	echo "<ul class='theading'><li style='width:100%'>$bar</li></ul>";
	echo "<ul class='list_ul'><li style='width:$list_col_array_l[1];text-align:right;'>书籍名称：</li><li style='width:$list_col_array_l[2];'><input style='width:280px;' name='name' type='text' value= $name ></li><li style='width:$list_col_array_l[3]'>书籍名称不用《》</li></ul>";
	echo "<ul class='list_ul'><li style='width:$list_col_array_l[1];text-align:right;'>出版时间：</li><li style='width:$list_col_array_l[2];'><input style='width:280px;' name='cbsj' type='text' value= $cbsj ></li><li style='width:$list_col_array_l[3]'>日期格式。如2013-01-24</li></ul>";
	echo "<ul class='list_ul'><li style='width:$list_col_array_l[1];text-align:right;'>出版社：  </li><li style='width:$list_col_array_l[2];'><input style='width:280px;' name='cbs'  type='text' value= $cbs></li><li style='width:$list_col_array_l[3]'></li></ul>";
	echo "<ul class='list_ul'><li style='width:100%;text-align:center;'><input class='bg_input' accesskey='s' type='submit' name='submit' value=' 提交 ' > <input class='bg_input' accesskey='r' type='reset' name='' value=' 重置 ' ></li></ul>";
	echo "</div></form>";
?>
</div>
</body></html>