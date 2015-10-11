<?php
/* 名称：选课审核前台 
 * 功能：显示某课程未审核名单，已退课记录，显示未关闭课程
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_tea.php';
// $tea_no = "tea";
$today = date ( "Y-m-d" );

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;
/**
 * 名称：选课审核前台
 * 根据action参数的值，进行不同处理：record_un 显示未审核课程，record_drop 学生退课记录
 */

switch ($action) {
	// 显示某个课程未审核名单
	case 'record_un' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '请先选择一门课程' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			echo '未审核名单:<br />';
			echo "<form  method='post' action='./select_deal.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>学生学号</td>';
			echo '<td>姓名</td>';
			echo '<td>批次</td>';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from sel_cor,stu where  cor_no='%s' and sel_cor.status='0' and sel_cor.stu_no=stu.stu_no", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', "<input type=checkbox name=id[] value={$rel['id']}>", '</td>';
					echo '<td>', $rel ["stu_no"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '<td>', $rel ["groups"], '</td>';
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
				echo '</table>';
				echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
				echo '<td>', '<input type=button class=btn  value=通过>', '</input>', '</td>';
				echo '<td>', '<input type=button class=btn  value=拒绝>', '</input>', '</td>';
			} else {
				echo '<tr><td>';
				echo "该课程无未审核学生";
				echo '</tr></td>';
				echo '</table>';
			}
			
			echo '</tr>';
			echo '</form>';
		}
		;
		break;
	
	// 显示已经退课的学生
	case 'record_drop' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '请先选择一门课程' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			echo "实验课程:{$cor_no}的退课记录<br/><br/>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>学生学号</td>';
			echo '<td>姓名</td>';
			echo '</tr>';
			
			// $item_no=$_GET ['item_no'];
			$queryStr = sprintf ( "select  *  from sel_cor,stu where cor_no='%s' and sel_cor.status='2' and sel_cor.stu_no=stu.stu_no", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', $rel ["stu_no"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo '<无>';
				echo '</td></tr>';
			}
		}
		;
		break;
	
	// 审核记录，默认显示未关闭课程
	default :
		{
			echo "<form  method='post' action='./select.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>学期</td>';
			echo '</tr>';
			
			// 查询打印未关闭课程
			$queryStr = sprintf ( "select  *  from course where tea_no='%s' and close_time>='%s'", $tea_no, $today );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', "<input type=radio name=cor_no value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["term"], '</td>';
					// echo "<td><a
					// href='./report.php?action=view_item_list&cor_no={$rel['cor_no']}'>进入</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo '暂时没有您的课程';
				echo '</tr></td>';
			}
			echo '</table>';
			echo '<br /><br />';
			echo '<input type=hidden name=action id=action value="" />';
			echo '<td>', '<input type=button class=btn value=未审核>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn value=已退课>', '</input>', '</td>';
			echo '</tr>';
			echo '</form>';
		}
}
?>
<!-- 绑定导航条点击事件 -->

<div id='table'></div>
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
          var frm1=$('form');
          
        switch(e.target.value)
          {
          case '未审核':
              $('#action').attr('value',"record_un");break;
          case '已退课':
              $('#action').attr('value',"record_drop");break;
          case '通过':
              $('#action').attr('value',"verify");break;
          case '拒绝':
              $('#action').attr('value',"unverify");break;
          default:
        	  break;
          }
       
         frm1.submit();     	
       }
      var start=function() { $(".btn").click( submit_action );   }
      $(start);	
</script>