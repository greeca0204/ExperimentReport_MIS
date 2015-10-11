<?php 
/*名称：批次管理后台
 * 功能：添加批次，更新批次，删除批次
 */
?>
<?php
include '../config.php';
include '../is_login_tea.php';

//$tea_no = "tea";
$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;

/** 根据action参数的值，进行不同处理：add添加实验内容，update更新实验内容,delete删除处理
 *    edit_time编辑实验报告提交时间
 *
 */

switch($action)
{
	//添加批次，对数据库的插入更新
	case 'add':
		{
			$cor_no = trim($_REQUEST['cor_no']);
			$groups = trim($_REQUEST['groups']);
			$week_start = trim($_REQUEST['week_start']);
			$week_end = trim($_REQUEST['week_end']);
			$week_nums = trim($_REQUEST['week_nums']);
			$lesson_seq = trim($_REQUEST['lesson_seq']);
			$num = trim($_REQUEST['num']);
			if ($cor_no == NULL || $groups ==NULL || $week_start ==NULL || $week_end ==NULL  ||$week_nums==NULL || $lesson_seq==NULL || $num ==NULL)
			{
				die('请完整输入');
			}
			
			$queryStr=sprintf("insert into groups values(NULL,'%s','%s','%s','%s','%s','%s','%s')",$groups,$cor_no,$week_start,$week_end,$week_nums,$lesson_seq,$num);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
			
			if($result!=NULL && 1==mysql_affected_rows())
			{
				echo '添加成功';
			}
			else 
			{
				echo '添加失败';
			}
			mysql_close ();
		};break;
		
		//更新批次信息，对数据库表的更新
	case 'update':
		{
			$id = trim($_REQUEST['id']);
			$cor_no = trim($_REQUEST['cor_no']);
			$groups = trim($_REQUEST['groups']);
			$week_start = trim($_REQUEST['week_start']);
			$week_end = trim($_REQUEST['week_end']);
			$week_nums = trim($_REQUEST['week_nums']);
			$lesson_seq = trim($_REQUEST['lesson_seq']);
			$num= trim($_REQUEST['num']);
	
			$queryStr = sprintf ( "update groups set  groups='%s',cor_no='%s' ,week_start='%s',week_end='%s',week_nums='%s',lesson_seq='%s',num='%s' where  id='%s'", $groups,$cor_no,$week_start,$week_end,$week_nums,$lesson_seq,$num,$id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;

			if($result!=NULL && 1==mysql_affected_rows())
			{
				echo '修改成功';
			}
			else
			{
				echo '修改失败';
			}
			mysql_close ();
			
		};break;
		
		//删除批次，更新数据库
	case 'delete':
		{
			if (!isset($_REQUEST['id']))
			{
				die('请先选择批次目');
			}
			$id = $_REQUEST ['id'];
			$queryStr = sprintf("delete from groups where id='%s'",$id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
			if($result!=NULL && 1==mysql_affected_rows())
			{
				echo '删除成功';
			}
			else 
			{
				echo '删除失败了';
			}
			mysql_close ();
		};break;
		
	default:
		break;
}
?>