<?php 
/*名称：报告管理后台
 * 功能：批改报告
 * 
 */
?>
<?php
include '../config.php';
include '../is_login_tea.php';
//$tea_no = "tea";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;

/** 根据action参数的值，进行不同处理：correct_report批改报告
 *
 */

switch($action)
{
	//批改成绩
	case 'correct_report':
		{
			if (!isset($_REQUEST['item_no']))
			{
				die('缺少参数:实验项目编号');
			}
			if (!isset($_REQUEST['cor_no']))
			{
				die('缺少参数:实验课程编号');
			}
			if (!isset($_REQUEST['stu_no']))
			{
				die('缺少参数:学生学号');
			}		
			if (!isset($_REQUEST['item_mark']) || $_REQUEST['item_mark'] == NULL)
			{
				die('请先给分数');
			}
	//		$item_no = $_REQUEST['item_no'];
	//		$cor_no = $_REQUEST['cor_no'];
	//		$stu_no = $_REQUEST['stu_no'];
        	$item_mark = $_REQUEST['item_mark'];
			$remark = $_REQUEST['remark'];
			$id = $_REQUEST['id'];
		//	echo $id;
	
			if ($item_mark < 0 || $item_mark > 100)
			{
				die("批改失败:分数应在0到100之间");
			}
			
			$queryStr = sprintf ("update report set  item_mark='%s',remark='%s',status='2' where id='%s'", $item_mark,$remark,$id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;

			if($result!=NULL && 1==mysql_affected_rows())
			{
				echo '批改成功';
			}
			else
			{
				echo '批改失败';
			}
			mysql_close ();
			
		};break;
		
	default:
		echo '提交不成功';	
}
?>