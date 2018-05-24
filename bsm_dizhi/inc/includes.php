<?php
/*[数据库配置]*/
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbData = "bsm_dizhi";
/*[基础数据配置]*/
$sitename=array("武汉大学简帛研究中心","address");
$version ="V1.0";
$leibie_arr=array('大陆','港台','欧美','日韩');

/*[有关函数]*/
/******************************************/
/*提示框*/
function msgbox($wid,$hei,$inf,$url)
{
	echo "<div style='width:100%;text-align:center;'><div style='margin-top:150px;width:".$wid."px;height:".$hei."px;line-height:".$hei."px;background:#fff;border:1px solid #069;text-align:center;'>";
	echo "$inf";
	echo "<meta http-equiv=refresh content=2;url=".$url.">";
	echo "</div></div>";
}

/********************************************/
/*截取长字符串中的指定数目字符*/
function cutstr($str,$int)
{
	if (strlen($str) > $int){		$str=substr($str,0,$int)."..";	}
	return $str;
}

/*****************************************************************/
//checkempty()的功能：检验一个字段是否为空值，如果为空，则该字段不显示
/*$addt是附加信息，用于显示发送Email按钮，2009-03-11*/
function checkempty($title,$val)
{
	if($val<>""){	$str="<span style='width:70px;text-align:right;'>".$title."：</span><span style='width:80%;'>".$val."</span>";	}else{	$str="";	}
	return $str;
}

/****************************************以下为分页函数
函数名称：Make_Page
函数功能：对数据进行分页显示,该函数可完全自动加上分页时所带的参数,该函数应在最后查询前调用,如下:
函数参数：&$QuerySql :分页数据的SQL语句,如"select * from users" ,传的是地址方式
          $connect_id :MYSQL连接句柄
          $array    :可选的每页显示数,一维数组如"array(5,10,20)"
返 回 值：分面显示的链接字符串,将其输出到网页即可,
          在调用该函数後,$QuerySql的内容将会改变为以下形式:
          如"select * from users limit 0,10"
************************************************************************/
function Make_Page(&$QuerySql,$connect_id)
{     
  //--------------设置每页显示的数目
    $page_rows =20;

  //---------------设置分页的GET变量---------------------
  //--这两处参数是程序自动加上的,顺序为"$Page_Rows=90&ActionPage=12"
    $Aget='ActionPage'; //当前页参数
    $AP_str="&{$Aget}=";   

  //---------------得到记录总数-------------------------
    $res=@mysql_query($QuerySql,$connect_id);
		$RecordNum=@mysql_num_rows($res); 
    @mysql_free_result($res);

  //---------------得到当前显示的页数---------
    $page = $_GET[$Aget]=='' ? 1 : $_GET[$Aget];
       
  //---------------得到设置後的总页数-----------------------
    if($RecordNum > 0 )
      {
        if($RecordNum < $page_rows )     $page_count = 1;  
        else if($RecordNum %$page_rows ) $page_count = (int)($RecordNum/$page_rows) + 1;     
        else                             $page_count = $RecordNum / $page_rows;
       }else{
        $page_count = 0;
     }

  //---------------处理分页时的参数-------------------------
    $getval='?';
    $str=$_SERVER["QUERY_STRING"]; //得到URL'?'后的字符串
    if($str != "")
       {
         $tmp_str=strstr($str,$Aget); //得到分页参数字符串
         if( $tmp_str != '' ) //加了分页参数
       			{
              $getval .= str_replace($tmp_str,'',$str); //去除分面参数
            }else $getval .= $str;
       }
    
    if( $getval[strlen($getval)-1] == '&' ){ //去掉最后的字符'&'
         $getval=substr($getval,0,strlen($getval)-1);
    }

  //-------------改变SQL语句--------------------------------
    $QuerySql .= " limit ".($page-1)*$page_rows .", $page_rows"; 

  //-------------生成可选的每页显示数---------------------------
    $page_string="共 <span style='color:#FF0000'>".$RecordNum."</span>条记录";
    $page_string.=",第<span style='color:#FF0000'>$page/ ".$page_count."</span>页 ";

  //-------------生成翻页链接-------------------------------
    $value=$getval.$AP_str;
    if($getval == '?') $value=str_replace('?&','?',$value);
    if( $page == 1 )
       $page_string .= '[首页] [上一页] ';
    else {
       $page_string .= '[<a href="'.$value.'1" title="首页">首页</a>] ';
       $page_string .= '[<a href="'.$value.($page-1).'" title="上一页">上一页</a>] ';
    }
       
    if( ($page == $page_count) || ($page_count == 0) ) //--显示最后一页
      {
        $page_string .= '[下一页] [尾页] ';
     }else{
        $page_string .= '[<a href="'.$value.($page+1).'" title="下一页">下一页</a>] ';
        $page_string .= '[<a href="'.$value.$page_count.'" title="尾页">尾页</a>] ';
     }

  //-----------生成可选分页数---------------------------------
    $page_string.="转到<select name=\"topage\" size=\"1\" onchange=\"javascript:location.reload(this.value);\"> \n\r";
    for($i=1; $i<=$page_count; $i++)
      {
        if($i == $page)
          $page_string.='<option value="'.$value.$i.'" selected="selected">'."第{$i}页</option>\n";
        else $page_string.='<option value="'.$value.$i.'">'."第{$i}页</option>\n";
      }

    $page_string .= '</select>';
    return $page_string;
}
?>