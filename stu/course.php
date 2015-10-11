<?php 
/*名称：学生端课程管理前台
 * 功能：查看批次，查看已选课程，退课，显示实验内容和批次，显示可选课程
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>

<?php
include '../config.php';
include '../is_login_stu.php';
$today = date("Y-m-d");
//$stu_no = "stu2";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
switch($action)
{
	
	//查看课程的批次
	case 'course_view_group':
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '请先选择一门课程' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			echo "<form method='post' action='./course_deal.php'>";
			echo '<table  class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
				
			echo '<td>实验批次</td>';
			echo '<td>开始时间(周)</td>';
			echo '<td>结束时间(周)</td>';
			echo '<td>周几</td>';
			echo '<td>第几大节</td>';
			echo '<td>容量</td>';
			echo '</tr>';
				
			$queryStr = sprintf ( "select  *  from groups where  groups.cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', "<input type=radio name = groups value={$rel['groups']}>", '</td>';
					echo '<td>', $rel ["groups"], '</td>';
					echo '<td>第', $rel ["week_start"], '周</td>';
					echo '<td>第', $rel ["week_end"], '周</td>';
					echo '<td>', $rel ["week_nums"], '</td>';
					echo '<td>', $rel ["lesson_seq"], '</td>';
					echo '<td>', $rel ["num"], '</td>';
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
				
				echo '<tr>';
			//	echo '<td>', "<input type=hidden name=stu_no value={$stu_no}>", '<td>';
				echo '<td>', "<input type=hidden name=cor_no value={$cor_no}>", '<td>';
				echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
				echo '</tr>';
				echo '</table>';
				//		echo '<td>', '<input type=button class=btn  value=增加 style="width:65px;background-image:url(../static/image/but_1.png)">', '</input>', '</td>';
				//		echo '<td>', '<input type=button class=btn  value=更新 style="width:65px;background-image:url(../static/image/but_1.png)">', '</input>', '</td>';
				echo '<input type=button class=btn  value=选课>', '</input>';
				
			} else {
				echo '<tr><td>';
				echo "该课程暂时还没有添加批次";
				echo '</td></tr>';
				echo '</table>';
			}
	
			echo '</form>';	
		};break;

		//查看已选课程
	case 'view_selected':
		{
			echo "<form method='post' action='./course.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>课程批次</td>';
			echo '<td>实验教师</td>';
			echo '<td>报告截止提交时间</td>';
			echo '</tr>';
			
			$queryStr = sprintf("select * from course,sel_cor,tea where sel_cor.stu_no='%s' and sel_cor.cor_no=course.cor_no and tea.tea_no=course.tea_no and sel_cor.status  in (0,1)",$stu_no);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			
			//存在已选课程
			if($rel = mysql_fetch_array($result))
			{
				do
				{
					echo '<tr>';
					echo '<td>', "<input type=radio name = cor_no value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["groups"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '<td>', $rel ["close_time"], '</td>';
			//		echo '<td>', "<a>查看内容</a>", '</td>';
					echo '</tr>';
				}
				while ($rel = mysql_fetch_array($result));
			
				//功能导航,退课和查看实验内容
				echo '<table>';
				echo '<br />';
				echo '<tr><td>';
			
		//		echo '<td>', "<input name=stu_no value={$stu_no} type=hidden></input>", '</td>';
				echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
				echo '<td>', '<input type=button class=btn  value=退课 >', '</input>', '</td>';
				echo '<td>', '<input type=button class=btn  value=查看实验 >', '</input>', '</td>';
				echo '</tr></table>';
				echo '</form>';
			}
			else
			{
				echo '暂无已选实验课程';
			}	
		};break;
		
		//退课，前台，将跳到course_deal.php处理
	case 'course_unselect':
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( "请先选择一门课程" );
			}
			$cor_no = $_REQUEST ['cor_no'];
			$url = "./course_deal.php?action=course_unselect&cor_no={$cor_no}&stu_no={$stu_no}";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";	
		};break;

		//显示某课程的实验内容列表和批次
	case 'view_course_item_list':
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( "请先选择一门课程" );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			//显示实验批次信息
			
			$queryStr = sprintf ( "select groups  from sel_cor where stu_no='%s' and cor_no='%s'", $stu_no,$cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			$groups = $rel['groups'];
			
			$queryStr = sprintf ( "select *  from groups where cor_no='%s' and groups='%s'", $cor_no,$groups );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			if($rel = mysql_fetch_array ( $result ))
			{
				echo '<table class=table_border>';
				echo '<tr class="first">';
				echo '<td>实验批次</td>';
				echo '<td>开始时间(周)</td>';
				echo '<td>结束时间(周)</td>';
				echo '<td>上课时间(星期)</td>';
				echo '<td>上课时间(第几大节)</td>';
				echo '<td>容量(人)</td>';
				echo '</tr>';
				
				echo '<tr>';
	//			echo '<td>', "<input type=radio name = id value={$rel['id']}>",'</td>';
				echo '<td>', $rel ["groups"], '</td>';
				echo '<td>第', $rel ["week_start"], '周</td>';
				echo '<td>第', $rel ["week_end"], '周</td>';
				
				//判断星期几上课
				$count_str = strlen($rel ["week_nums"]);
				$str = NULL;
				for ($i = 0;$i < $count_str; $i++)
				{
				$str = $str . '星期' . substr($rel['week_nums'], $i,1) . ';';
				}
				
				echo '<td>', $str, '</td>';
				echo '<td>', $rel ["lesson_seq"], '</td>';
				echo '<td>', $rel ["num"], '</td>';
				echo '</tr>';
				
				echo '</table>';
			}

			//显示实验内容列表
			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			
			echo '<br /><table class=table_border>';
			echo '<tr class="first">';
	//		echo '<td />';
			echo '<td>实验编号</td>';
			echo '<td>实验名称</td>';
			echo '<td>成绩百分比</td>';
			echo '<td />';
			echo '</tr>';
				
			if ($rel = mysql_fetch_array ( $result )) {	
				do {
					echo '<tr>';
					echo "<td>{$rel['item_no']}</td>";
					echo '<td>', $rel ["item_name"], '</td>';
					echo '<td>', $rel ["exam_rate"], '</td>';
					echo "<td><a href=./course.php?action=view_course_item&cor_no={$cor_no}&id={$rel['id']}>查看内容</a></td>";
					// echo "<td><a
					// href='./update_item.php?action=update&cor_no=$cor_no&
					// id={$rel['id']}'>修改</a></td>";
					// echo "<td><a
					// href='./update_item_deal.php?action=delete&id={$rel['id']}'>删除</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr>';
				echo '<td>';
				echo "没实验内容";
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';		
		};break;
		
		//显示实验内容
	case 'view_course_item':
		{
			if (!isset($_REQUEST['id']))
			{
				die('请先选择实验项目');
			}
			if (!isset($_REQUEST['cor_no']))
			{
				die('请先选择实验项目');
			}
			
			$id = $_REQUEST['id'];
			$cor_no = $_REQUEST['cor_no'];
			
			$queryStr = sprintf ( "select  *  from item where cor_no='%s' and id='%s' ",$cor_no, $id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close();
			
			if($rel=mysql_fetch_array($result))
			{
				echo "实验编号:{$rel['item_no']}";
				echo '<br />';
				echo "实验名称:{$rel['item_name']}";
				echo '<br />';
				echo "所占成绩百分比:{$rel['exam_rate']}";
				echo '<br />';
				echo "实验内容:<br/><br/>";
				echo "<pre>";
				echo "<textarea style='width:700;height:400;' disabled>";
				echo htmlspecialchars(stripslashes ($rel['body']));
				echo '</textarea>';
				echo '</pre>';		
			}		
		};break;
		
		//默认显示可选课程
	default:
		{
			$grade = $_REQUEST['grade'];

			//测试
		//	$today = '0000-0000-00';
			
			echo "<form method='post' action='./course.php'>";
			echo '<table   class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>实验教师</td>';
			echo '</tr>';
			
			$queryStr =sprintf("select * from course,tea where course.close_time>='%s' and course.select_time>='%s' and course.grade='%s' and tea.tea_no=course.tea_no and course.cor_no not in (select cor_no from sel_cor where stu_no='%s' and status in(0,1))",$today,$today,$grade,$stu_no);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			
			//有课程可选
			if($rel = mysql_fetch_array($result))
			{
				do
				{
					echo '<tr>';
					echo '<td>', "<input type=radio name = cor_no value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '</tr>';
				}
				while ($rel = mysql_fetch_array($result));
			
				//功能导航
				echo '<table>';
				echo '<br />';
				echo '<tr><td>';
			
		//		echo '<td>', "<input name=stu_no value={$stu_no} type=hidden></input>", '</td>';
		//		echo '<td>', "<input name=cor_no value={$cor_no} type=hidden></input>", '</td>';
				echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
				//	echo '<td>', '<input type=button class=btn  value=选课 style="width:65px;background-image:url(../static/image/but_1.png)">', '</input>', '</td>';
				echo '<td>', '<input type=button class=btn  value=查看批次>', '</input>', '</td>';
				echo '</tr>';	
			}
			else
			{
				echo '<tr><td>';
				echo '无可选实验课程';
				echo '</td></tr>';
			}
			echo '</table>';
			echo '</form>';
		};break;
}
?>

<!-- 绑定导航条点击事件 -->
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
         var frm1 = $('form');  
        switch(e.target.value)
          {
    //      case '选课':
     //         $('#action').attr('value',"course_select");break;
        case '查看批次':
              $('#action').attr('value',"course_view_group");break;
          case '查看实验':
              $('#action').attr('value',"view_course_item_list");break;
          case '选课':
              $('#action').attr('value',"course_select");break;
          case '退课':
          {
              var tips = window.confirm("你确定要退课?");
              if(tips == false)
              {
                  return;
              }
              $('#action').attr('value',"course_unselect");
          }break;        
          
          default:
        	  break;
          }
        frm1.submit();     	
       }
      var start = function() { $(".btn").click( submit_action );   }
      $(start);
</script>