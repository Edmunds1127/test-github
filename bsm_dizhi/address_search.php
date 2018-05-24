<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
require "inc/includes.php";//包含公用引用文件

$connect_id=mysql_connect("$dbHost","$dbUser","$dbPass") or die("不能连接到数据库服务器！可能是数据库服务器没有启动，或者用户名密码有误！");
$DBID = @mysql_select_db("$dbData",$connect_id) or die("选择数据库出错，可能是您指定的数据库不存在！");
$action=$_GET["action"];

if($action=="search"){
	$field=$_POST['field'];
	$s_key=$_POST['s_key'];
	switch ($field){
	  case "按姓名"    : $s_field="xingming";  break;
		case "按单位"    : $s_field="danwei";  break;
	}
	$_SESSION["query"]="select * from dizhi where $s_field like '%$s_key%' order by id desc";
	//echo $_SESSION["query"];exit;
	echo "<meta http-equiv=refresh content=0;url=address_search.php?action=dosearch>";
exit;
}
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
function CheckForm()
	{
		if(document.address.s_key.value=="")
		{
			alert("请输入查询条件！");
			document.address.s_key.focus();
			return false;
		}
	}
function confirmdel(durl,sid)
{
	/*durl:url by dujie 2009-03-15*/
	if (confirm("真的要删除吗?"))
	window.location.href=durl+sid
}
</SCRIPT>

</head>
<body>

<!--快速查询begin-->
<div style='margin-top:30px;padding:5px 0 5px 0;width:500px;border:1px solid #069;text-align:center;'>
	<form style='margin:0;' action='address_search.php?action=search' method='post' name='address' onSubmit='return CheckForm();'>
		<ul style='padding-left:100px;width:100%;text-align:left;valign:middle;height:20px;line-height:20px;'>
			<li style='width:100%;'>查询联系人：
				<select name='field'><option>按姓名</option><option>按单位</option></select>
				<input class='input_border' id='searchkey' name='s_key' type='text' size='20' value=>
				<input class='bg_input' accesskey='s' type='submit' name='submit' value=' 搜索 ' >
				<input class='bg_input' accesskey='r' type='reset' name='' value=' 重置 ' >
			</li>
	  </ul>
	</form>
</div>
 
<!--分组信息begin-->
<div style='margin-top:10px;width:500px;background:#fff;text-align:left;'>
	<ul><li style='float:left;'>分组：</li><li style='float:left;'>[<a href=address_search.php?action=list&class=-1>所有名单</a>]</li>&nbsp;<? while (list($key,$value) = each($leibie_arr)) { echo "<li style='float:left;'>[<a href=address_search.php?action=list&leibie=$key target=_self>".$value."</a>]&nbsp;</li>";} ?></ul>
</div>

<?php
if($action=="dosearch"){	$query=$_SESSION["query"];}

if($action=="list")
{
	$leibie=$_GET["leibie"];
	if($leibie>-1){$query='select * from dizhi where leibie='.$leibie.' order by danwei desc';}else{$query='select * from dizhi order by danwei desc';}
}

//echo $query; exit;
$pagestr=Make_Page($query,$connect_id);
$result	=mysql_query($query,$connect_id);
if(!$result||mysql_num_rows($result)<1){msgbox(500,150,'没有符合条件的记录！即将返回上一步……','address_search.php?action=list');exit;}

//begin to list
$col_array=array("500px","20%","60%","20%");
echo "<div class='list' style='width:$col_array[0];'>";
echo "<ul class='list_heading'><li style='width:$col_array[1];text-align:center;'>ID</li><li style='width:$col_array[2];text-align:center;'>姓名</li><li style='width:$col_array[4];text-align:center;'>操作</li></ul>";
while($row=mysql_fetch_array($result)){
	$id			= $row['id'];
	$youbian=	$row['youbian'];
	$xingming=	$row['xingming'];
	$chengwei=$row['chengwei'];
	$dizhi	=	$row['dizhi'];
	$danwei	=	$row['danwei'];
	echo "<ul class='list_ul'>";
	echo "<li style='width:$col_array[1];text-align:center;'>$id</li>";
	echo "<li style='width:$col_array[2];'>$xingming</li>";
	echo "<li style='width:$col_array[4];'><a href=address.php?action=edit&id=$id>编辑</a>&nbsp;|&nbsp;<a href='javascript:confirmdel(\"address.php?action=delete&id=\",\"$id\")'>删除</a></li>";
	echo "</ul>";

}
echo "</div>";
echo "<div style='margin-top:5px;text-align:center'>$pagestr</div>";//分页
?>
</body>
</html>