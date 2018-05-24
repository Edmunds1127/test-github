<?php
error_reporting(E_ALL & ~E_NOTICE);
require "inc/includes.php";//包含公用引用文件

$connect_id=mysql_connect("$nameost","$dbUser","$dbPass") or die("不能连接到数据库服务器！可能是数据库服务器没有启动，或者用户名密码有误！");
$DBID = @mysql_select_db("$dbData",$connect_id) or die("选择数据库出错，可能是您指定的数据库不存在！");
$id 		= $_GET["id"];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content='text/html; charset=gb2312' http-equiv='Content-Type'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv='Pragma' content='no-cache'>
<meta http-equiv='Cache-Control' content='no-cache'>
<meta http-equiv='Expires' content='-1'>
</head>

<body>

<div style='width:800px;margin:0 auto;'>
<?php
	$query  = "select * from book where id=$id";
	$result = mysql_query($query,$connect_id);
	if(!$result || mysql_num_rows($result)==0){	msgbox(700,150,'没有符合条件的记录！即将返回上一步……','book.php?action=list');exit;}
	$row = mysql_fetch_array($result);
		$id  = $row["id"];
		$zsmd=$row["zsmd"];
		$zsmd=explode("+",$zsmd);
		if(!empty($zsmd))
		{
			foreach($zsmd as $lxrid)
			{
			$query  = "select * from dizhi where id=$lxrid";
			$result = mysql_query($query,$connect_id);if(!$result){exit;}
			$row    = mysql_fetch_array($result);
			$leibie=$row["leibie"];
			$youbian=$row["youbian"];
			$xingming= $row["xingming"];
			$chengwei=$row["chengwei"];
			$dizhi=$row["dizhi"];
			$danwei=$row["danwei"];
				if($leibie==0)//大陆样式
				{
					echo "<div style='width:800px;height:300px;border-bottom:1px dashed #EFEFEF;text-align:left;'>";
					echo "<div style='padding:20px;font:22px;line-height:30px;'><span style='word-spacing: 1em;'>$youbian</span><br />$dizhi<br />$danwei</div>";
					echo "<div style='width:100%;text-align:center;font:26px;font-family:黑体'>".$xingming."  ".$chengwei."（收）</div>";
					echo "<div style='margin:50px 20px 20px 500px;font:22px;line-height:30px;text-align:center;'>武汉大学简帛研究中心<br />430072</div>";
					echo "</div>";
				}

				if($leibie>0)//港台、歐美、日韓样式
				{
					echo "<div style='width:800px;height:300px;border-bottom:1px dashed #EFEFEF;text-align:left;'>";
					echo "<div style='padding:20px;font:22px;text-align:left;'>武漢大學簡帛研究中心<br />湖北 武漢 430072";
						if($leibie>1){echo "<br/>中國";}//欧美、日韩的地址显示“中国”字样
					echo "</div>";
					echo "<div style='margin-left:360px;width:100%;text-align:left;font:22px;'>";
						if($leibie==2){ echo $chengwei.$xingming; }else{ echo $xingming."  ".$chengwei; }//欧美人士称谓在前；港台、日韩人士称谓在后
					echo "<br />".$dizhi."</div>";
					echo "</div>";
				}
			}
		}
		exit;
?>
</div>
</body>
</html>