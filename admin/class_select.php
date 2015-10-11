<?php
/* 名称：班级列表 
 * 作用：显示班级列表
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
$page_size = 5;

// 获取页码
$page = isset ( $_REQUEST ['page'] ) ? intval ( $_REQUEST ['page'] ) : 1;

// 获取班级总数
$queryStr = "select  count(distinct class_no)  from stu";
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
$rel = mysql_fetch_array ( $result );
// echo $rel[0];
$numrows = $rel [0];

// 计算总页数
$pages = intval ( $numrows / $page_size );
if ($numrows % $page_size) {
	$pages ++;
}

// 前一页和后一页
$prev = $page - 1;
$next = $page + 1;

// 计算记录偏移量
$offset = $page_size * ($page - 1);
$queryStr = "select  distinct class_no from stu order by class_no desc limit $offset,$page_size";
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
mysql_close ();

echo "<form method='post' action='./class.php'>";
echo '<table>';
echo '<tr>';
echo '<td />';
echo '<td>班级号</td>';
// echo '<td>入学年份</td>';
echo '</tr>';
if ($rel = mysql_fetch_array ( $result )) {
	do {
		echo '<tr>';
		echo '<td>', "<input type=checkbox name = class_no[] value={$rel['class_no']}>", '</td>';
		echo '<td>', $rel ["class_no"], '</td>';
		// echo '<td>', $rel ["grade"], '</td>';
		echo '</tr>';
	} while ( $rel = mysql_fetch_array ( $result ) );
} 

else {
	echo '<tr>';
	echo "还没有班级，请先建立班级";
	echo '</tr>';
}
echo '</table>';

// 分页导航
if ($page > 1) {
	echo "<a href=./class_select.php?page=1>首页</a>";
	echo "<a href=./class_select.php?page={$prev}>上一页</a>";
}
if ($page < $pages) {
	echo "<a href=./class_select.php?page={$next}>下一页</a>";
	echo "<a href=./class_select.php?page={$pages}>尾页</a>";
}
echo "共有{$pages}页 ({$page}/{$pages})";
echo '<table>';
echo '<br />';
echo '<tr>';

// 功能导航
echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
echo '<td>', '<input type=button class=btn  value=增加 style="width:65px;">', '</input>', '</td>';
echo '<td>', '<input type=button class=btn  value=查看 style="width:65px;">', '</input>', '</td>';
echo '<td>', '<input type=button class=btn  value=删除 style="width:65px;">', '</input>', '</td>';
echo '</tr></table>';
echo '</form>';

echo '<br />';
echo '注意:删除请谨慎，删除班级后该班学生也会被删除';
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
              $('#action').attr('value',"select_class");break;
          case '增加':
              $('#action').attr('value',"add_class");break;
          case '删除':
          {
              var tips = window.confirm("提交之后无法更改，你确定要提交?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"delete_class");
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