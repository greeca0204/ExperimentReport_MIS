<?php
/* 名称：课程管理后台 
 * 功能：更新课程，删除课程
 */
?>

<?php
include '../config.php';
include '../is_login_admin.php';

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;
// echo $action;

/**
 * 根据参数 action 的值进行不同的后台处理：update_cor 更新课程，
 * delete_cor 删除课程
 */
switch ($action) {
	
	// 更新课程，后台处理
	case 'update_cor' :
		{
			$id = trim ( $_REQUEST ['id'] );
			$cor_no = trim ( $_REQUEST ['cor_no'] );
			$cor_name = trim ( $_REQUEST ['cor_name'] );
			$term = trim ( $_REQUEST ['term'] );
			$tea_no = trim ( $_REQUEST ['tea_no'] );
			$select_time = trim ( $_REQUEST ['select_time'] );
			$close_time = trim ( $_REQUEST ['close_time'] );
			$usual_rate = trim ( $_REQUEST ['usual_rate'] );
			$report_rate = trim ( $_REQUEST ['report_rate'] );
			$exam_rate = trim ( $_REQUEST ['exam_rate'] );
			
			if (100 != ($usual_rate + $exam_rate + $report_rate)) {
				die ( '更新失败:平时成绩、考试成绩、报告成绩总和不等于100' );
			}
			
			$queryStr = sprintf ( "update  course set cor_no='%s',term='%s',tea_no='%s',cor_name='%s',usual_rate='%s',report_rate='%s',
exam_rate='%s',select_time='%s',report_time='%s',close_time='%s' 
where id='%s'", $cor_no, $term, $tea_no, $cor_name, $usual_rate, $report_rate, $exam_rate, $select_time, $report_rate, $close_time, $id );
			
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			// 更新课程成功
			if ($result != NULL && 1 == mysql_affected_rows ()) {
				echo "修改课程成功";
			} else {
				echo '修改课程失败';
			}
			mysql_close ();
		}
		;
		break;
	
	// 删除课程，后台
	case 'delete_cor' :
		{
			$cor_nums = $_REQUEST ['cor_no'];
			$count_sucess = 0;
			$count_fail = 0;
			
			echo '<table><tr>';
			echo '<td>删除课程</td>';
			echo '<td>状态(成功/失败)</td>';
			echo '</tr>';
			
			for($i = 0; $i < count ( $cor_nums ); $i ++) {
				$queryStr = sprintf ( "delete from course where cor_no='%s'", $cor_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				
				// 删除成功
				if ($result != NULL && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $cor_nums [$i];
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				

				// 删除失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $cor_nums [$i];
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }门课程成功删除<br />";
			echo "{$count_fail }门课程删除失败<br />";
			
			mysql_close ();
		}
		;
		break;
	
	default :
		break;
}
?>