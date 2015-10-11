<?php 
/*名称：报告管理前台
 * 功能：显示实验项目列表，未批改报告列表，批改报告，显示所有课程，显示批次，某批次学生列表，某学生报告列表，
 * 更新报告成绩，显示未关闭课程，
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
//$tea_no = "tea";
$today = date ( "Y-m-d" );

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
/**
 * 根据action参数的值，进行不同处理：select选择课号查看实验内容，add添加实验内容，update更新实验内容
 * edit_time编辑实验报告提交时间
 */

switch ($action) {
	// 显示某个课程的实验项目列表
	case 'view_item_list' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '请先选择一个实验项目' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			echo '<table class=table_border>';
			echo '<tr class="first">';
			// echo '<td />';
			echo '<td>实验编号</td>';
			echo '<td>实验名称</td>';
			echo '<td>成绩百分比</td>';
			echo '<td />';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					// echo '<td>', "<input type=radio name = id
					// value={$rel['id']}>",'</td>';
					echo '<td>', $rel ["item_no"], '</td>';
					echo '<td>', $rel ["item_name"], '</td>';
					echo '<td>', $rel ["exam_rate"], '</td>';
					echo "<td><a href='./report.php?action=view_item_report&cor_no={$cor_no}&item_no={$rel['item_no']}'>查看未批改报告</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo "该课程暂时还没有实验内容";
				echo '</tr></td>';
			}
			echo '<br />';
			echo '</table>';
		}
		;
		break;
	
	// 显示某个实验项目的已提交、但还没有批改的报告列表
	case 'view_item_report' :
		{
			if (! isset ( $_REQUEST ['item_no'] )) {
				die ( '缺少参数:实验项目编号' );
			}
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:实验课程编号' );
			}
			$item_no = $_REQUEST ['item_no'];
			$cor_no = $_REQUEST ['cor_no'];
			
			echo "实验课程:{$cor_no}<br/>";
			echo "实验编号:{$item_no}<br/><br/>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>学生学号</td>';
			echo '<td>姓名</td>';
			echo '<td>提交时间</td>';
			echo '<td />';
			echo '</tr>';
			
			// $item_no=$_GET ['item_no'];
			$queryStr = sprintf ( "select  *  from report,stu where  report.cor_no='%s' and report.item_no='%s' and report.status='1' and report.stu_no=stu.stu_no", $cor_no, $item_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					// echo '<td>', "<input type=radio name = id
					// value={$rel['id']}>",'</td>';
					echo '<td>', $rel ["stu_no"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '<td>', $rel ["date"], '</td>';
					echo "<td><a href='./report.php?action=correct_report&stu_no={$rel['stu_no']}&cor_no={$cor_no}&item_no={$item_no}'>批改报告</a></td>";
					
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
	
	// 批改报告，前台
	case 'correct_report' :
		{
			echo "<form  method='post' action='./report_deal.php?action=correct_report'>";
			if (! isset ( $_REQUEST ['item_no'] )) {
				die ( '缺少参数:实验项目编号' );
			}
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:实验课程编号' );
			}
			if (! isset ( $_REQUEST ['stu_no'] )) {
				die ( '缺少参数:学生学号' );
			}
			$item_no = $_REQUEST ['item_no'];
			$cor_no = $_REQUEST ['cor_no'];
			$stu_no = $_REQUEST ['stu_no'];
			
			$queryStr = sprintf ( "select  *  from report,stu where  report.cor_no='%s' and report.item_no='%s' and report.status='1' and report.stu_no=stu.stu_no", $cor_no, $item_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				echo "<form  method='post' action='./report_deal.php?action=correct_report'>";
				echo '<table>';
				echo '<tr>';
				echo '<td>课程编号:</td>';
				echo "<td><input  value={$rel['cor_no']} disabled></input></td>";
				// echo '</tr>';
				// echo '<tr>';
				echo '<td>实验编号:</td>';
				echo "<td><input  value={$rel['item_no']} disabled></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>学生学号:</td>';
				echo "<td><input value={$rel['stu_no']} disabled></input></td>";
				// echo '</tr>';
				
				// echo '<tr>';
				echo '<td>学生姓名:</td>';
				echo "<td><input value={$rel['name']} disabled></input></td>";
				echo '</tr>';
				echo '</table>';
				
				echo '报告内容:<br /><br />';
				echo '<pre>';
				echo "<textarea style='width:700;height:400;' disabled>";
				
				echo htmlspecialchars(stripslashes ($rel['body']));
				
				echo '</textarea>';
				echo '</pre>';
				
				echo '<table>';
				echo '<tr>';
				echo '<td>评语</td>';
				echo '<td><select name=remark>';
				echo "<option value='无'>无</option>";
				$queryStr = sprintf ( "select *  from remark where tea_no='%s' order by no asc", $tea_no );
				$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				while ( $rel2 = mysql_fetch_array ( $result2 ) ) {
					echo "<option value='{$rel2['body']}'>{$rel2['body']}</option>";
				}
				echo '</select>';
				echo '</td></tr>';
				
				echo '<tr>';
				echo '<td>分数:</td>';
				echo "<td><input name=item_mark></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo "<td><input type='hidden' name='id' value={$rel['id']}> </input></td>";
				echo "<td><input type='hidden' name='cor_no' value={$cor_no}> </input></td>";
				echo "<td><input type='hidden' name='item_no' value={$item_no}> </input></td>";
				echo "<td><input type='hidden' name='stu_no' value={$stu_no}> </input></td>";
				echo '</tr>';
				echo '<tr>';
				echo "<td><input class=button type='submit' value='提交'></input></td>";
				echo '</tr>';
				echo '</table>';
				echo '</form>';
			}
			mysql_close ();
		}
		;
		break;
	
	// 报告管理,显示所有课程
	case 'report_manage' :
		{
			echo '报告管理';
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>学期</td>';
			echo '<td />';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from course where tea_no='%s'", $tea_no, $today );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					// echo '<td>', "<input type=radio name=cor_no
					// value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["term"], '</td>';
					echo "<td><a href='./report.php?action=view_course_group&cor_no={$rel['cor_no']}'>查看批次</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo '暂时没有您的课程';
				echo '</tr></td>';
			}
			echo '</table>';
		}
		;
		break;
	
	// 显示某门课程的批次
	case 'view_course_group' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:实验课程' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			echo '<table  class=table_border>';
			echo '<tr class="first">';
			echo '<td>实验批次</td>';
			echo '<td>开始时间(周)</td>';
			echo '<td>结束时间(周)</td>';
			echo '<td>上课时间(星期)</td>';
			echo '<td>上课时间(第几大节)</td>';
			echo '<td>容量</td>';
			echo '<td />';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from groups where  groups.cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					// echo '<td>', "<input type=radio name = id
					// value={$rel['id']}>", '</td>';
					echo '<td>', $rel ["groups"], '</td>';
					echo '<td>第', $rel ["week_start"], '周</td>';
					echo '<td>第', $rel ["week_end"], '周</td>';
					
					$count_str = strlen ( $rel ["week_nums"] );
					$str = NULL;
					for($i = 0; $i < $count_str; $i ++) {
						$str = $str . '星期' . substr ( $rel ['week_nums'], $i, 1 ) . ';';
					}
					
					echo '<td>', $str, '</td>';
					echo '<td>', $rel ["lesson_seq"], '</td>';
					echo '<td>', $rel ["num"], '</td>';
					echo "<td><a href='./report.php?action=view_group_stu&cor_no={$rel['cor_no']}&group={$rel['groups']}'>查看学生</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo "该课程暂时还没有添加批次";
				echo '</tr></td>';
			}
		}
		;
		break;
	
	// 显示某课程某个批次的学生列表
	case 'view_group_stu' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:课程号' );
			}
			if (! isset ( $_REQUEST ['group'] )) {
				die ( '缺少参数:批次号' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			$group = $_REQUEST ['group'];
			
			echo "课程编号:{$cor_no},批次:{$group} 的学生列表<br /><br />";
			echo '<table  class=table_border>';
			echo '<tr class="first">';
			// echo '<td />';
			echo '<td>学生学号</td>';
			echo '<td>学生姓名</td>';
			echo '<td />';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from sel_cor,stu where cor_no='%s' and groups='%s' and status='1' and sel_cor.stu_no=stu.stu_no", $cor_no, $group );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', $rel ["stu_no"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo "<td><a href='./report.php?action=view_stu_item&stu_no={$rel['stu_no']}&group={$group}&cor_no={$cor_no}'>查看报告</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo '<空>';
				echo '</td></tr>';
			}
		}
		;
		break;
	
		//显示学生某个课程的实验报告列表
	case 'view_stu_item' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:课程号' );
			}
			if (! isset ( $_REQUEST ['stu_no'] )) {
				die ( '缺少参数:学生号' );
			}
			$cor_no = $_REQUEST ['cor_no'];
	//		$group = $_REQUEST ['group'];
			$stu_no = $_REQUEST ['stu_no'];
			
			echo "学号:{$stu_no}的实验报告<br />";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>实验编号</td>';
			echo '<td>实验名称</td>';
			echo '<td>提交日期</td>';
			echo '<td>分数</td>';
			echo '<td />';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from report,item where report.stu_no='%s' and report.cor_no='%s' and status in (1,2) and report.item_no=item.item_no and  item.cor_no='%s' order by item.item_no", $stu_no, $cor_no,$cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', $rel ["item_no"], '</td>';
					echo '<td>', $rel ["item_name"], '</td>';
					echo '<td>', $rel ["date"], '</td>';
					echo '<td>', $rel ["item_mark"], '</td>';
					echo "<td><a href='./report.php?action=update_report&id={$rel['id']}&cor_no={$cor_no}&stu_no={$stu_no}&item_no={$rel['item_no']}'>查看、更新报告</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			}
			mysql_close ();
		}
		;
		break;
	
	// 更新报告,前台
	case 'update_report' :
		{
			echo "<form  method='post' action='./report_deal.php?action=correct_report'>";
			//参数检查，接收
			if (! isset ( $_REQUEST ['item_no'] )) {
				die ( '缺少参数:实验项目编号' );
			}
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:实验课程编号' );
			}
			if (! isset ( $_REQUEST ['stu_no'] )) {
				die ( '缺少参数:学生学号' );
			}
			$item_no = $_REQUEST ['item_no'];
			$cor_no = $_REQUEST ['cor_no'];
			$stu_no = $_REQUEST ['stu_no'];
			
			//查询，打印报告
			$queryStr = sprintf ( "select  *  from report,stu where  report.cor_no='%s' and report.item_no='%s' and report.status in (1,2) and report.stu_no=stu.stu_no", $cor_no, $item_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				echo "<form  method='post' action='./report_deal.php?action=correct_report'>";
				echo '<table>';
				echo '<tr>';
				echo '<td>课程编号:</td>';
				echo "<td><input  value={$rel['cor_no']} disabled></input></td>";
				// echo '</tr>';
				// echo '<tr>';
				echo '<td>实验编号:</td>';
				echo "<td><input  value={$rel['item_no']} disabled></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>学生学号:</td>';
				echo "<td><input value={$rel['stu_no']} disabled></input></td>";
				// echo '</tr>';
				
				// echo '<tr>';
				echo '<td>学生姓名:</td>';
				echo "<td><input value={$rel['name']} disabled></input></td>";
				echo '</tr>';
				echo '</table>';
				
				echo '报告内容:<br /><br />';
				echo '<pre>';
				echo "<textarea style='width:700;height:400;' disabled>";
				
				echo htmlspecialchars(stripslashes ($rel['body']));
				
				echo '</textarea>';
				echo '</pre>';
				
				echo '<table>';
				echo '<tr>';
				echo '<td>评语</td>';
				echo '<td><select name=remark>';
				echo "<option value='无'>{$rel['remark']}</option>";
				$queryStr = sprintf ( "select *  from remark where tea_no='%s' order by no asc", $tea_no );
				$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				while ( $rel2 = mysql_fetch_array ( $result2 ) ) {
					echo "<option value='{$rel2['body']}'>{$rel2['body']}</option>";
				}
				echo '</select>';
				echo '</td></tr>';
				
				echo '<tr>';
				echo '<td>分数:</td>';
				echo "<td><input name=item_mark value={$rel['item_mark']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo "<td><input type='hidden' name='id' value={$rel['id']}> </input></td>";
				echo "<td><input type='hidden' name='cor_no' value={$cor_no}> </input></td>";
				echo "<td><input type='hidden' name='item_no' value={$item_no}> </input></td>";
				echo "<td><input type='hidden' name='stu_no' value={$stu_no}> </input></td>";
				echo '</tr>';
				
				//判断课程是否已经关闭
				$queryStr = sprintf ( "select close_time  from course where cor_no='%s'", $cor_no );
				$result3 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				$rel3 = mysql_fetch_array ( $result3 );
				if ($today > $rel3['close_time'])
				{
					echo '<tr><td>';
					echo '课程已关闭，无法再更改!';
					echo '</tr></td>';
					echo '</table>';
				    echo '</form>';
				    mysql_close();
				    die();			
				}
				echo '<tr>';
				echo "<td><input class=button type='submit' value='更新'></input></td>";
				echo '</tr>';
				echo '</table>';
				echo '</form>';
			}
			mysql_close ();
		}
		;
		break;
	
	// 报告批改，默认显示未关闭课程
	default :
		{
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>学期</td>';
			echo '<td />';
			echo '</tr>';
			
			//查询打印未关闭课程
			$queryStr = sprintf ( "select  *  from course where tea_no='%s' and close_time>='%s'", $tea_no, $today );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					// echo '<td>', "<input type=radio name=cor_no
					// value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["term"], '</td>';
					echo "<td><a href='./report.php?action=view_item_list&cor_no={$rel['cor_no']}'>进入</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr><td>';
				echo '暂时没有您的课程';
				echo '</tr></td>';
			}
			echo '</table>';
		}
}
?>