<?php 
/*名称：教师列表
 * 功能：显示教师列表
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>

<?php
include '../config.php';
include '../is_login_admin.php';

// 分页中每一页的条目数量
$page_size = 10;

// 获取页码
$page = isset ( $_REQUEST ['page'] )?intval($_REQUEST ['page']):1;

// 获取班级总数
$queryStr = "select  count(distinct tea_no)  from tea";
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
$rel = mysql_fetch_array ( $result );
// echo $rel[0];
$numrows = $rel [0];

// 计算总页数
$pages = intval ( $numrows / $page_size );
if ($numrows % $page_size) {
	$pages ++;
}

//前一页和后一页
$prev=$page-1;
$next=$page+1;

// 计算记录偏移量
$offset = $page_size * ($page - 1);
$queryStr = "select  *  from tea order by department asc,tea_no asc limit $offset,$page_size";
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
mysql_close ();

echo "<form method='post' action='./tea.php'>";
echo '<table class=table_border>';
echo '<tr class="first">';
echo '<td />';
echo '<td>教师编号</td>';
echo '<td>姓名</td>';
echo '<td>电子邮件</td>';
echo '<td>手机号码</td>';
echo '<td>院别</td>';
echo '</tr>';
if ($rel = mysql_fetch_array ( $result )) {
	do {
		echo '<tr>';
		echo '<td>', "<input type=checkbox name = tea_no[] value={$rel['tea_no']}>", '</td>';
		echo '<td>', $rel ["tea_no"], '</td>';
		echo '<td>', $rel ["name"], '</td>';
		echo '<td>', $rel ["mail"], '</td>';
		echo '<td>', $rel ["mobile"], '</td>';
		echo '<td>', $rel ["department"], '</td>';
		echo '</tr>';
	} while ( $rel = mysql_fetch_array ( $result ) );
} 

else {
	echo '<tr>';
	echo "还没有教师，请先添加教师";
	echo '</tr>';
}
echo '</table>';

//分页导航
if($page>1)
{
	echo "<a href=./tea_select.php?page=1>首页</a>";
	echo "<a href=./tea_select.php?page={$prev}>上一页</a>";
}
if ($page<$pages)
{
	echo "<a href=./tea_select.php?page={$next}>下一页</a>";
	echo "<a href=./tea_select.php?page={$pages}>尾页</a>";
}
echo "共有{$pages}页 ({$page}/{$pages})";
echo '<table>';
echo '<br />';
echo '<tr>';
echo '</table>';

//功能导航
echo '<table>';
echo '<tr>';
echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
echo '<td>', '<input type=button class=btn  value=增加>', '</input>', '</td>';
echo '<td>', '<input type=button class=btn  value=修改密码>', '</input>', '</td>';
echo '<td>', '<input type=button class=btn  value=查看>', '</input>', '</td>';
echo '<td>', '<input type=button class=btn  value=删除>', '</input>', '</td>';
echo '</tr></table>';
echo '</form>';

echo '<br />';
//echo '注意:删除请谨慎';
?>

<!-- 绑定导航条点击事件 -->

<div id='table'></div>
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
          var frm1=$('form');
          
        switch(e.target.value)
          {
          case '查看':
              $('#action').attr('value',"select");break;
          case '修改密码':
              $('#action').attr('value',"change_psw");break;
          case '增加':
              $('#action').attr('value',"add");break;
          case '删除':
          {
              var tips = window.confirm("确定要删除?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"delete");
          };break;
          default:
        	  break;
          }
    //    alert($('#action').attr('value'));
       
         frm1.submit();     	
       }
      var start=function() { $(".btn").click( submit_action );   }
      $(start);
</script>