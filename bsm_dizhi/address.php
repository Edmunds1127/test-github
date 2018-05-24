<?php
error_reporting(E_ALL & ~E_NOTICE);
require "inc/includes.php";//包含公用引用文件
/**********************************************************************
mysql> desc dizhi;
+----------+-----------+------+-----+---------+----------------+
| Field    | Type      | Null | Key | Default | Extra          |
+----------+-----------+------+-----+---------+----------------+
| id       | int(11)   | NO   | PRI | NULL    | auto_increment |
| leibie   | int(11)   | YES  |     | NULL    |                |类别
| youbian  | char(10)  | YES  |     | NULL    |                |邮编
| xingming | char(50)  | YES  |     | NULL    |                |姓名
| chengwei | char(20)  | YES  |     | NULL    |                |称谓
| dizhi    | char(150) | YES  |     | NULL    |                |地址
| danwei   | char(50)  | YES  |     | NULL    |                |单位
+----------+-----------+------+-----+---------+----------------+
*************************************************************************/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv='Pragma' content='no-cache'>
<meta http-equiv='Cache-Control' content='no-cache'>
<meta http-equiv='Expires' content='-1'>
<link href='css/default_style.css' rel='stylesheet' type='text/css' />

<SCRIPT language=javascript>
<!--
function CheckForm()
	{
		if(document.address.xingming.value=="")
		{
			alert("请输入姓名！");
			document.address.xingming.focus();
			return false;
		}
	}
//-->
</SCRIPT>

<script charset="utf-8" src="kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
<script>
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="dizhi"]', {
			resizeType : 1,
			allowPreviewEmoticons : false,
			allowImageUpload : false,
			items : ['source','removeformat']
		});
	});
</script>
</head>

<body>

<?php
$connect_id=mysql_connect("$dbHost","$dbUser","$dbPass") or die("不能连接到数据库服务器！可能是数据库服务器没有启动，或者用户名密码有误！");
$DBID      = @mysql_select_db("$dbData",$connect_id) or die("选择数据库出错，可能是您指定的数据库不存在！");

$action= $_GET["action"];
$id    = $_GET["id"];
$type  = $_GET["type"];

if ($action == "insert") {
	$leibie   = $_POST["leibie"];
	$youbian  = $_POST["youbian"];
	$xingming = $_POST["xingming"];
		$query="select xingming from dizhi where xingming='$xingming'";//判断联系人是否已存在
		$result=mysql_query($query);if( $result && mysql_num_rows($result)>0 ){ msgbox(500,150,'<font color=red><b>该联系人已存在！</b></font>','#'); exit; }
	$chengwei	=$_POST["chengwei"];
	$dizhi = $_POST["dizhi"];
	$dizhi = str_replace("<p>","",$dizhi);$dizhi = str_replace("</p>","<br />",$dizhi);//将段落标记p替换为换行标记br
	$danwei   = $_POST["danwei"];

	$query_str = "insert into dizhi (leibie,youbian,xingming,chengwei,danwei,dizhi) values('$leibie','$youbian','$xingming','$chengwei','$danwei','$dizhi')";
	$result=mysql_query($query_str) or die("更新数据库失败1");
		msgbox(500,150,'添加联系人成功！即将返回所有联系人列表……','address_search.php?action=list&leibie=-1');
	exit;
}

if ($action == "editok") {
	$leibie   = $_POST["leibie"];
	$youbian  = $_POST["youbian"];
	$xingming = $_POST["xingming"];
	$chengwei	=$_POST["chengwei"];
	$dizhi = $_POST["dizhi"];
	$dizhi = str_replace("<p>","",$dizhi);$dizhi = str_replace("</p>","<br />",$dizhi);//将段落标记p替换为换行标记br
	$danwei   = $_POST["danwei"];

 $query_str = "update dizhi set leibie='$leibie',youbian='$youbian',xingming='$xingming',chengwei='$chengwei',danwei='$danwei',dizhi='$dizhi' where id=$id";
 $result=mysql_query($query_str) or die("更新数据库失败2");
	msgbox(500,150,'更新联系人资料成功！即将返回所有联系人列表……','address_search.php?action=list&leibie=-1');
exit;
}

if ($action == "delete") {
	if ($id<0) die("错误的调用(a)");
	$query_str = "delete from dizhi where id=$id";
	$result=mysql_query($query_str) or die("更新数据库失败3");
	msgbox(500,150,'删除联系人成功！即将返回所有联系人列表……','address_search.php?action=list&leibie=-1');
	exit;
}

$tmp_action="";

if ($action == "edit" ) {
 if ($id<0) die("错误调用(b)");
 $result = mysql_query("select * from dizhi where id=$id") ;
 $total  = mysql_num_rows($result);
 if ($total <> 1) {
 	msgbox(500,150,'该联系人不存在！即将返回所有联系人列表……','address_search.php?action=list&leibie=-1');
 exit;
 }
 $row  = mysql_fetch_array ($result);
 $leibie   = $row["leibie"];
 $youbian  = $row["youbian"];
 $xingming = $row["xingming"];
 $chengwei =$row["chengwei"];
 $danwei   = $row["danwei"];
 $dizhi = $row["dizhi"];

 $bar  = "编辑联系人";
 $tmp_action = "address.php?action=editok&id=$id";
}else{
 $leibie   = 0;
 $youbian  = "";
 $xingming = "";
 $chengwei ="";
 $danwei   = "";
 $dizhi = "";
 $bar  = "添加联系人";
 $tmp_action = "address.php?action=insert";
}

//table to be filled
$col_array=array("600px","25%","75%");
echo "<div class='list' style='width:$col_array[0];margin:20px auto;'>";
echo "<form style='margin:0;' action=$tmp_action method='post' name='address' onSubmit='return CheckForm();' onReset='return confirm('您确认要重新填写此表单吗？');'>";
echo "<ul class='theading'><li style='width:100%'>$bar</li></ul>";
echo "<ul class='list_ul'><li style='width:$col_array[1];text-align:right;'>姓名：</li><li style='width:$col_array[2];'><input style='width:300px;' name='xingming' type='text' value= $xingming ><font color=red>必填</font></li></ul>";
echo "<ul class='list_ul'><li style='width:$col_array[1];text-align:right;'>称谓：</li><li style='width:$col_array[2];'><input style='width:300px;' name='chengwei' type='text' value= $chengwei ></li></ul>";
echo "<ul class='list_ul'><li style='width:$col_array[1];text-align:right;'>通信地址：</li><li style='width:$col_array[2];'><textarea name='dizhi' style='width:400px;height:150px;visibility:hidden;'>$dizhi</textarea></li></ul>";
echo "<ul class='list_ul'><li style='width:$col_array[1];text-align:right;'>类别：</li><li style='width:$col_array[2];'><select name='leibie'>";
				while (list($key,$value) = each($leibie_arr)) {
					if ($key == $leibie) {	echo "<option value=$key SELECTED>$value</option>\n";} else {echo "<option value=$key>$value</option>\n";}
				}
echo "</select>(影响信封格式)</li></ul>";echo "<ul class='list_ul'><li style='width:$col_array[1];text-align:right;'>单位：</li><li style='width:$col_array[2];'><input style='width:300px;' name='danwei' type='text' value= $danwei >大陆联系人填写</li></ul>";
echo "<ul class='list_ul'><li style='width:$col_array[1];text-align:right;'>邮编：</li><li style='width:$col_array[2];'><input style='width:300px;' name='youbian' type='text' value= $youbian ></li>大陆联系人填写</ul>";


echo "<ul class='list_ul'><li style='width:100%;text-align:center;'><input class='bg_input' accesskey='s' type='submit' name='submit' value=' 提交 ' > <input class='bg_input' accesskey='r' type='reset' name='' value=' 重置 ' ></li></ul>";
echo "</form></div>";
?>

</body></html>