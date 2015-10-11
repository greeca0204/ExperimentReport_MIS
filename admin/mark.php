<?php 
/*名称：成绩查看
 * 功能：课程列表，成绩单
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

//$tea_no = "tea";
$today = date ( "Y-m-d" );

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
/**
 * 根据action参数的值，进行不同处理：mark_exam_update 显示某课程平时和考试成绩，record_drop 学生退课记录
 */

switch ($action) {	
		// 打印该门课程的成绩单
	case 'mark_list':
		{
			if (!isset($_REQUEST['cor_no']))
			{
				die('缺少参数:课程号');
			}
			$cor_no = $_REQUEST['cor_no'];
			echo "成绩单<br /><br />";
			
			//获取该课程下各成绩所占比重
			$queryStr = sprintf ( "select  usual_rate,exam_rate,report_rate  from course where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			$cor_usual_rate = $rel['usual_rate'];
			$cor_report_rate = $rel['report_rate'];
			$cor_exam_rate = $rel['exam_rate'];
			
			//获取该课程的实验项目
			$item_list = array();
			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			while($rel = mysql_fetch_array ( $result ))
			{
				$item_list [$rel ['item_no']] = $rel ['exam_rate'];
			}
			
			echo '<table class=table_border>';
			echo '<tr  class="first">';		
			echo '<td>学号</td>';
			echo '<td>姓名</td>';
			foreach($item_list as $item_no=>$exam_rate)
			{
				echo "<td>实验{$item_no}({$exam_rate}%)</td>";		
			}
			echo '<td />';
			echo "<td>报告综合分({$cor_report_rate}%)</td>";
			echo "<td>平时分({$cor_usual_rate}%)</td>";
			echo "<td>考试分({$cor_exam_rate}%)</td>";
			echo '<td>综合分数</td>';
			echo '</tr>';
			
			//获取所有学生分数
			$queryStr = sprintf ( "select *  from course,sel_cor,stu where  sel_cor.cor_no='%s' and course.cor_no=sel_cor.cor_no and stu.stu_no=sel_cor.stu_no ", $cor_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if($rel=mysql_fetch_array($result))
			{
				do{
					echo '<tr class=table_border>';
		//			echo '<td>', "<input type=radio name = id value={$rel['id']}>",'</td>';
					echo '<td>', $rel ["stu_no"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					
					foreach($item_list as $item_no=>$exam_rate)
					{
						$queryStr = sprintf ( "select item_mark  from report where  cor_no='%s' and stu_no='%s' and item_no='%s'", $cor_no,$rel ["stu_no"],$item_no);
						$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
						if($rel2=mysql_fetch_array($result2))
						{
							echo '<td>', $rel2 ["item_mark"], '</td>';		
						}
						else 
						{
							echo '<td>0</td>';
						}			
					}
					
					echo '<td />';
					echo '<td>', $rel ["report_mark"], '</td>';
					echo '<td>', $rel ["usual_mark"], '</td>';
					echo '<td>', $rel ["exam_mark"], '</td>';
					echo '<td>', $rel ["mark"], '</td>';
					echo '</tr>';
				}while($rel = mysql_fetch_array ( $result ));	
			}
		mysql_close();
		};break;
	
	// 显示所有课程
	default :
		{
			
			// 分页中每一页的条目数量
			$page_size = 10;
				
			// 获取页码
			if (isset ( $_GET ['page'] )) {
				$page = intval ( $_GET ['page'] );
			}
			// 设置为第一页
			else {
				$page = 1;
			}
			
			// 获取课程总数
			$queryStr =sprintf("select  count(cor_no)  from course");
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			// echo $rel[0];
			$numrows = $rel [0];
			//总页数
			$pages = intval ( $numrows / $page_size );
			if ($numrows % $page_size) {
				$pages ++;
			}
			
			//前一页和后一页
			$prev=$page-1;
			$next=$page+1;
				
			// 计算记录偏移量
			$offset = $page_size * ($page - 1);
			
			
			echo '课程列表:<br /><br />';
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>学期</td>';
			echo '<td />';
			echo '</tr>';
				
			$queryStr = sprintf ( "select  *  from course  order by term desc limit %s,%s",$offset,$page_size);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
				
			if ( $rel = mysql_fetch_array ( $result ) ) {
				do{
					echo '<tr>';
					//		echo '<td>', "<input type=radio name=cor_no value={$rel['cor_no']}>", '</td>';
					echo '<td>', $rel ["cor_no"], '</td>';
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["term"], '</td>';
					echo "<td><a href='./mark.php?action=mark_list&cor_no={$rel['cor_no']}'>查看成绩单</a></td>";
					echo '</tr>';
				}while($rel = mysql_fetch_array ( $result ) );
			}
			else
			{
					echo '<tr><td>';
					echo '暂时没有课程';
					echo '</tr></td>';
			}	
			echo '</table>';
			echo '<br />';
			//分页导航
			if($page>1)
			{
				echo "<a href=./mark.php?action=default&page=1>首页</a>";
				echo "<a href=./mark.php?action=default&page={$prev}>上一页</a>";
			}
			if ($page < $pages)
			{
			echo "<a href=./mark.php?action=default&page={$next}>下一页</a>";
			echo "<a href=./mark.php?action=default&page={$pages}>尾页</a>";
			}
			echo "共有{$pages}页 ({$page}/{$pages})";		
		}
}

?>