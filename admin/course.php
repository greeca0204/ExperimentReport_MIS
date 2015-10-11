<?php 
/*名称：课程管理前台
 * 功能：更新课程信息，查看实验内容，查看批次，删除课程，所有课程列表
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css">
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_admin.php';

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
switch ($action) {
	
	// 更新课程信息，前台
	case 'update_cor' :
		{
			// 获取、判断所选课程
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '请先选择一门课程' );
			}
			$cor_nums = $_REQUEST ['cor_no'];
			if (count ( $cor_nums ) > 1) {
				die ( "请只选择一门课程" );
			}
			$cor_no = $cor_nums [0];
			
			// 查询数据库获取课程信息
			$queryStr = sprintf ( "select  *  from course where  cor_no='%s' ", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			
			echo '修改课程';
			echo "<form method='post' action='./course_deal.php?action=update_cor'>";
			echo '<table>';
			if ($rel = mysql_fetch_array ( $result )) {
				echo '<tr>';
				echo '<td>', '课程编号', '</td>';
				echo "<td><input name='cor_no' value={$rel['cor_no']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '课程名称', '</td>';
				echo "<td><input name='cor_name' value={$rel['cor_name']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '学年', '</td>';
				echo "<td><input name='term' value={$rel['term']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '教师', '</td>';
				echo "<td><input name='tea_no' value={$rel['tea_no']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '选课最后期限', '</td>';
				echo "<td><input name='select_time' value={$rel['select_time']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '关闭时间', '</td>';
				echo "<td><input name='close_time' value={$rel['close_time']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '平时所长百分比', '</td>';
				echo "<td><input name='usual_rate' value={$rel['usual_rate']}>%</input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '实验报告所长百分比', '</td>';
				echo "<td><input name='report_rate' value={$rel['report_rate']}>%</input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', '考试成绩所长百分比', '</td>';
				echo "<td><input name='exam_rate' value={$rel['exam_rate']}>%</input></td>";
				echo '</tr>';
			} else {
				echo '无此课程';
			}
			echo '<tr>';
			echo '<td>', "<input type=hidden name=id  value={$rel['id']} type=hidden></input>", '</td>';
			echo "<td><input class=button type='submit' value='提交'></input></td>";
			echo '</tr>';
			echo '</table>';
			echo '</form>';
		}
		;
		break;
	
	// 查看实验内容
	case 'view_cor_item' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( "请先选择一门课程" );
			}
			$cor_nums = $_REQUEST ['cor_no'];
			if (count ( $cor_nums ) > 1) {
				die ( "请只选择一门课程" );
			}
			$cor_no = $cor_nums [0];
			
			// echo $cor_no;
			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>实验编号</td>';
			echo '<td>实验名称</td>';
			echo '<td>成绩百分比</td>';
			echo '</tr>';
			
			if ($rel = mysql_fetch_array ( $result )) {
				
				do {
					echo '<tr>';
					echo '<td>', $rel ["item_no"], '</td>';
					echo '<td>', $rel ["item_name"], '</td>';
					echo '<td>', $rel ["exam_rate"], '</td>';
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
		}
		;
		break;
	
		//查看批次
	case 'view_group' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '请先选择一门课程' );
			}
			$cor_nums = $_REQUEST ['cor_no'];
			if (count ( $cor_nums ) > 1) {
				die ( "请只选择一门课程" );
			}
			
			$cor_no = $cor_nums[0];
			
			// echo '课程名称：',$_POST['cor_name'];
	//		echo "<form method='post' action='./group.php?tea_no={$tea_no}'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
	//		echo '<td />';
			
			echo '<td>实验批次</td>';
			echo '<td>开始时间(周)</td>';
			echo '<td>结束时间(周)</td>';
			echo '<td>上课时间(星期)</td>';
			echo '<td>上课时间(第几大节)</td>';
			echo '<td>容量</td>';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from groups where  groups.cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
		//			echo '<td>', "<input type=radio name = id value={$rel['id']}>", '</td>';
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
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo "该课程暂时还没有添加批次";
				echo '</tr></td>';
			}
			echo '<br />';
			echo '<table><tr>';
	//		echo '<td>', "<input type=hidden name=cor_no value={$tea_no}>", '<td>';
	//		echo '<td>', "<input type=hidden name=cor_no value={$cor_no}>", '<td>';
	//		echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
	//		echo '<td>', '<input type=button class=btn  value=增加 style="width:65px;background-image:url(../static/image/but_1.png)">', '</input>', '</td>';
	//		echo '<td>', '<input type=button class=btn  value=更新 style="width:65px;background-image:url(../static/image/but_1.png)">', '</input>', '</td>';
	//		echo '<td>', '<input type=button class=btn  value=删除 style="width:65px;background-image:url(../static/image/but_1.png)">', '</input>', '</td>';
			echo '</tr></table>';
			echo '</table>';
			echo '</form>';
		}
		;
		break;
	
	// 删除课程，前台
	case 'delete_cor' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( "请先选择课程" );
			}
			$cor_nums = $_REQUEST ['cor_no'];
			$url = "./course_deal.php?action=delete_cor";
			for($i = 0; $i < count ( $cor_nums ); $i ++) {
				$url = $url . "&cor_no[{$i}]=" . $cor_nums [$i];
			}
			echo $url;
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";
		}
		;
		break;
	
	// 默认显示所有课程
	default :
		{
			if (! isset ( $_REQUEST ['tea_no'] ) || ! isset ( $_REQUEST ['term'] )) {
				die ( "提交不正确" );
			}
			$tea_no = $_REQUEST ['tea_no'];
			$term = $_REQUEST ['term'];
			
			// 分页中每一页的条目数量
			$page_size = 10;
			
			// 获取页码
			$page = isset ( $_REQUEST ['page'] )?intval($_REQUEST ['page']):1;

			// 获取搜索条件，学期与教师
			$Str = " where course.tea_no=tea.tea_no";
			// 若选择了教师
			if ('all' != $tea_no) {
				$Str = $Str . sprintf ( " and course.tea_no='%s'", $tea_no );
			}
			
			// 若选择了学期
			if ('all' != $term) {
				$Str = $Str . sprintf ( " and term='%s' ", $term );
			}
			
			// 获取总条目
			$queryStr = "select count(cor_no) from course,tea " . $Str;
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			$numrows = $rel [0];
			
			$pages = intval ( $numrows / $page_size );
			if ($numrows % $page_size) {
				$pages ++;
			}
			
			// 前一页和后一页
			$prev = $page - 1;
			$next = $page + 1;
			
			// 计算记录偏移量
			$offset = $page_size * ($page - 1);
			
			$queryStr = "select * from course,tea " . $Str . " order by term desc limit {$offset},{$page_size}";
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			echo "<form method='post' action='./course.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>课程号</td>';
			echo '<td>课程名称</td>';
			echo '<td>学期</td>';
			echo '<td>教师</td>';
			echo '</tr>';
			
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', "<input type=checkbox name = cor_no[] value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["term"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					// echo "<td><a
					// href='./course_view_item.php?cor_no={$rel['cor_no']}'>查看实验内容</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} 			

			// 无课程
			else {
				echo '<tr><td>';
				echo "无课程，请先添加课程";
				echo '</td></tr>';
			}
			echo '</table>';
			
			// 分页导航
			if ($page > 1) {
				echo "<a href=./course.php?tea_no={$tea_no}&term={$term}&page=1>首页</a>";
				echo "<a href=./course.php?tea_no={$tea_no}&term={$term}&page={$prev}>上一页</a>";
			}
			if ($page < $pages) {
				echo "<a href=./course.php?tea_no={$tea_no}&term={$term}&page={$next}>下一页</a>";
				echo "<a href=./course.php?tea_no={$tea_no}&term={$term}&page={$pages}>尾页</a>";
			}
			echo "共有{$pages}页 ({$page}/{$pages})";
			
			// 功能导航
			echo '<table>';
			echo '<br />';
			echo '<tr>';
			
			// echo '<td>', "<input name=class_no value={$class_no}
			// type=hidden></input>", '</td>';
			echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
			echo '<td>', '<input type=button class=btn  value=查看批次>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=查看实验>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=修改>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=删除>', '</input>', '</td>';
			
			echo '</tr></table>';
			echo '</form>';
		}
		;
		break;
}
?>




<!-- 绑定导航条点击事件 -->
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
          var frm1 = $('form');  
        switch(e.target.value)
          {
          case '删除':
          {
              var tips = window.confirm("确定要删除课程?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"delete_cor");
          }break;
          case '修改':
              $('#action').attr('value',"update_cor");break;
          case '查看实验':
              $('#action').attr('value',"view_cor_item");break;
          case '查看批次':
              $('#action').attr('value',"view_group");break;
          default:
        	  break;
          }
         frm1.submit();     	
       }
      var start = function() { $(".btn").click( submit_action );   }
      $(start);
</script>