<?php
error_reporting(E_ALL & ~E_NOTICE);
require "inc/includes.php";//包含公用引用文件

$connect_id=mysql_connect("$nameost","$dbUser","$dbPass") or die("不能连接到数据库服务器！可能是数据库服务器没有启动，或者用户名密码有误！");
$DBID = @mysql_select_db("$dbData",$connect_id) or die("选择数据库出错，可能是您指定的数据库不存在！");
$action = $_GET["action"];if($action==""){$action="listmd";}
$id 		= $_GET["id"];
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
			alert("请输入书籍编号！");
			document.book.name.focus();
			return false;
		}
	}
	//-->
</script>

<SCRIPT LANGUAGE="JavaScript">
//复选框的全选与取消
function checkAll(str)
{
    var a = document.getElementsByName(str);
    var n = a.length;
    for (var i=0; i<n; i++)
    a[i].checked = window.event.srcElement.checked;
}
function checkItem(str)
{
    var e = window.event.srcElement;
    var all = eval("document.all."+ str);
    if (e.checked)
    {
        var a = document.getElementsByName(e.name);
        all.checked = true;
        for (var i=0; i<a.length; i++)
        {
            if (!a[i].checked)
            {
                all.checked = false; break;
            }
        }
    }
    else
        all.checked = false;
}
</SCRIPT>

</script>
</head>

<body>

<?php
if($action=="updatebook"){
	$md=$_POST['md'];
	print_r($md);
	if (count($md)>0){
		$md=implode("+",$md);
		$query_str = "update book set zsmd='$md' where id=$id";
	  $result=mysql_query($query_str) or die("更新数据库失败1");
	}
	echo "<meta http-equiv=refresh content=0;url='?action=listmd&id=$id'>";
	exit;
}

	$query  = "select * from book where id=$id";
	$result = mysql_query($query,$connect_id);
	if(!$result || mysql_num_rows($result)==0){	msgbox(700,150,'没有符合条件的记录！即将返回上一步……','book.php?action=list');exit;}
	$row = mysql_fetch_array($result);
		$id  = $row["id"];
		$name= $row["name"];
		$cbs = $row["cbs"];
		$cbsj= $row["cbsj"];
		$zsmd= $row["zsmd"];
		if(!empty($zsmd)){ $zsmd=explode("+",$zsmd); }
		//print_r($zsmd);exit;
	$col_array_s=array("900px","25%","50%","25%");
	echo "<div class='list' style='margin-top:30px;width:$col_array_s[0];'>";
	echo "<ul class='list_heading'><li style='width:100%;'>书籍详情</li></ul>";
	echo "<ul class='list_ul'><li style='width:$col_array_s[1];text-align:right;padding-right:10px;'>书籍名称：</li><li style='width:$col_array_s[2];'>《 $name 》</li><li style='width:$col_array_s[3];'></li></ul>";
	echo "<ul class='list_ul'><li style='width:$col_array_s[1];text-align:right;padding-right:10px;'>出版时间：</li><li style='width:$col_array_s[2];'>$cbsj</li><li style='width:$col_array_s[3];'></li></ul>";
	echo "<ul class='list_ul'><li style='width:$col_array_s[1];text-align:right;padding-right:10px;'>出版社：  </li><li style='width:$col_array_s[2];'>$cbs</li><li style='width:$col_array_s[3];'></li></ul>";
	echo "<ul class='list_ul'><li style='width:$col_array_s[1];text-align:right;padding-right:10px;'>赠书名单：</li><li style='width:75%;'>";
	//显示赠书名单
	if($action=="listmd")
	{
		if(!empty($zsmd))
		{
			echo "<a href=?action=editmd&id=$id>编辑赠书名单</a>&nbsp;|&nbsp;<a href='print.php?id=$id' target=_blank>打印赠书名单</a><br />";
			//开始逐一显示赠书名单
			foreach($zsmd as $lxrid)
			{
				$query  = "select id,xingming from dizhi where id=$lxrid";
				$result = mysql_query($query,$connect_id);
				if(!$result || mysql_num_rows($result)<>1)//如果联系人在数据表中被删除，则此处显示“已删除联系人”
				{
					$xingming="<font color=red>(已删除联系人)</font>";
				}else{
					$row    = mysql_fetch_array($result);	//正常显示
					$xingming= $row["xingming"];
				}
				echo "<span style='width:200px;float:left;'>".$xingming."</span>";
			}
		}else{
			echo "<a href=?action=editmd&id=$id>添加赠书名单</a>";
		}
		exit;
	}

	//编辑赠书名单
	if($action=="editmd"){
		echo "<div style='width:100%;'>";
		echo "<form style='margin:0;' action='bookmd.php?action=updatebook&id=$id' method='post' name='bookmd' onSubmit='return chkform();' onReset='return confirm('您确认要重新填写此表单吗？');'>";
		echo "<ul><input type=checkbox name=All onclick=\"checkAll('md[]')\">全选</ul>";

		//显示大陆联系人
		$query  = "select id,xingming from dizhi where leibie=0 order by xingming desc";
		$result = mysql_query($query,$connect_id);
		mysql_query("set names utf-8");
		echo "<ul>（一）大陆联系人：</ul><ul>";
		while($row=mysql_fetch_array($result)){
			$id			= $row['id'];
			$xingming=	$row['xingming'];
			echo "<li style='width:200px;float:left;'><input name='md[]' type='checkbox' value='$id' ";
				if(!empty($zsmd))//更改赠书名单时用
				{
					foreach($zsmd as $mdid){	if($mdid==$id){echo "checked";}}
				}
				echo "/>".$xingming."</li>";
		}
		echo "</ul>";

		//显示港台联系人
		$query  = "select id,xingming from dizhi where leibie=1 order by xingming";
		$result = mysql_query($query,$connect_id);
		mysql_query("set names utf-8");
		echo "<ul>（二）港台联系人：</ul><ul>";
		while($row=mysql_fetch_array($result)){
			$id			= $row['id'];
			$xingming=	$row['xingming'];
			echo "<li style='width:200px;float:left;'><input name='md[]' type='checkbox' value='$id' ";
				if(!empty($zsmd))//更改赠书名单时用
				{
					foreach($zsmd as $mdid){	if($mdid==$id){echo "checked";}}
				}
				echo "/>".$xingming."</li>";
		}
		echo "</ul>";
		//显示欧美联系人
		$query  = "select id,xingming from dizhi where leibie=2 order by xingming";
		$result = mysql_query($query,$connect_id);
		mysql_query("set names utf-8");
		echo "<ul>（二）欧美联系人：</ul><ul>";
		while($row=mysql_fetch_array($result)){
			$id			= $row['id'];
			$xingming=$row['xingming'];
			echo "<li style='width:200px;float:left;'><input name='md[]' type='checkbox' value='$id' ";
				if(!empty($zsmd))//更改赠书名单时用
				{
					foreach($zsmd as $mdid){	if($mdid==$id){echo "checked";}}
				}
				echo "/>".$xingming."</li>";
		}
		echo "</ul>";
		//显示日韩联系人
		$query  = "select id,xingming from dizhi where leibie=3 order by xingming";
		$result = mysql_query($query,$connect_id);
		mysql_query("set names utf-8");
		echo "<ul>（二）日韩联系人：</ul><ul>";
		while($row=mysql_fetch_array($result)){
			$id			= $row['id'];
			$xingming=	$row['xingming'];
			echo "<li style='width:200px;float:left;'><input name='md[]' type='checkbox' value='$id' ";
				if(!empty($zsmd))//更改赠书名单时用
				{
					foreach($zsmd as $mdid){	if($mdid==$id){echo "checked";}}
				}
				echo "/>".$xingming."</li>";
		}
		echo "</ul>";

		echo "<ul><input class='bg_input' accesskey='s' type='submit' name='submit' value=' 提交 ' > <input class='bg_input' accesskey='r' type='reset' name='' value=' 重置 ' ></ul>";
		echo "</div>";
	exit;
	}
	echo "</li><li style='width:$col_array_s[3];'></li></ul>";
	echo "</div>";
?>
</div>
</body></html>