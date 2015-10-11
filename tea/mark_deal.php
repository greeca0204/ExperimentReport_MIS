<?php 
/*名称：成绩管理后台
 * 功能：修改平时成绩和考试成绩，更新学生综合成绩
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
 * 根据action参数的值，进行不同处理：exam_update 更新平时成绩和考试成绩
 */

switch ($action) {
	// 更新平时成绩和考试成绩
	case 'exam_update' :
		{
			$ids = $_REQUEST ['id'];
			$usual_mark = $_REQUEST ['usual_mark'];
			$exam_mark = $_REQUEST ['exam_mark'];
			
			// 判断分数的合理性
			for($i = 0; $i < count ( $ids ); $i ++) {
				if ($usual_mark [$i] > 100 || $usual_mark [$i] < 0 || $exam_mark [$i] > 100 || $exam_mark [$i] < 0) {
					die ( '分数应在0到100之间' );
				}
			}
			// 统计成功和失败次数
			$count_sucess = 0;
			$count_fail = 0;
			echo '修改平时、考试成绩<br />';
			for($i = 0; $i < count ( $ids ); $i ++) {
				$queryStr = sprintf ( "update sel_cor set usual_mark='%s',exam_mark='%s' where id='%s'", $usual_mark [$i], $exam_mark [$i], $ids [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				// 删除成功
				if ($result == TRUE && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
				} else {
					$count_fail ++;
				}
			}
			echo '<br />';
			echo "{$count_sucess }个学生修改成绩成功<br />";
			echo "{$count_fail }个学生成绩没修改<br />";
		}
		;
		break;
	
	// 更新实验报告的综合成绩
	case 'mark_update' :
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( '缺少参数:课程号码' );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			// 保存成功更新成绩的学生个数
			$count_stu = 0;
			
			//获取该课程下各成绩所占比重
			$queryStr = sprintf ( "select  usual_rate,exam_rate,report_rate  from course where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			$cor_usual_rate = $rel['usual_rate']/100;
			$cor_report_rate = $rel['report_rate']/100;
			$cor_exam_rate = $rel['exam_rate']/100;
	
			// 判断该课程下的实验项目所占百分比
			$sum = 0;
			$exam_rate = array ();
			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					// 获取每个实验项目成绩所占百分比
					$exam_rate [$rel ['item_no']] = $rel ['exam_rate'];
					$sum = $sum + $rel ['exam_rate'];
				} while ( $rel = mysql_fetch_array ( $result ) );
			}
			if (100 != $sum) {
				// print_r($exam_rate);
				mysql_close ();
				echo "课号为:{$cor_no} 的课程下各实验项目的成绩所占百分比总和不等于100<br /><br />";
				die ( '请修改该课程下的实验项目成绩所占百分比' );
			}
			
			//检测是否还有未批改的实验报告
			$queryStr = sprintf ( "select  count(*)  from report where  cor_no='%s' and status='1'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			if ($rel[0] != 0)
			{
				die('更新失败:该门课程还有报告还未批改，请先批改');
			}
			
			// 获取选了本课程的学生名单
			$queryStr = sprintf ( "select  stu_no  from sel_cor where  cor_no='%s'", $cor_no );
			$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			if ($rel2 = mysql_fetch_array ( $result2 )) {
				// 对学生的每项实验成绩进行计算，并更新
				do {
					// 计算学生的实验报告综合成绩
					// print_r($exam_rate);
					// echo '<br />';
					// echo $rel2['stu_no'];
					// echo '<br />';
					$temp_mark = 0;
					$queryStr = sprintf ( "select item_no,item_mark  from report where  cor_no='%s' and stu_no='%s'", $cor_no, $rel2 ['stu_no'] );
					$result3 = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
					if ($rel3 = mysql_fetch_array ( $result3 )) {
						do {
						//	echo $rel3 ['item_no'];
						//	echo '<br />';
							$temp_item_mark = $rel3 ['item_mark'];
							if ($temp_item_mark == NULL) {
								$temp_item_mark = 0;
							}
							//计算实验报告综合成绩
							$temp_mark = $temp_mark + $exam_rate [$rel3 ['item_no']] * $temp_item_mark / 100;
						} while ( $rel3 = mysql_fetch_array ( $result3 ) );
					}
					// 更新每个学生的实验报告综合成绩
					$queryStr = sprintf ( "update sel_cor set report_mark='%s' where  cor_no='%s' and stu_no='%s'", $temp_mark, $cor_no, $rel2 ['stu_no'] );
					$result4 = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
					$queryStr = sprintf ( "update sel_cor set mark=usual_mark*%s +exam_mark*%s + report_mark*%s where  cor_no='%s' and stu_no='%s'", $cor_usual_rate,$cor_exam_rate,$cor_report_rate, $cor_no, $rel2 ['stu_no'] );
					$result5 = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );				
					if ($result5 == TRUE && 1 == mysql_affected_rows ()) {
						$count_stu ++;
					}
				} while ( $rel2 = mysql_fetch_array ( $result2 ) );
			}
			echo "成功更新了{$count_stu}个学生的实验报告综合成绩";
			mysql_close ();
		}
		;
		break;
	
	default :
		break;
}
?>