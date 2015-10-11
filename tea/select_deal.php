<?php 
/*名称：选课审核后台
 * 功能：同意选课，不同意选课
 */
?>
<?php
include '../config.php';
include '../is_login_tea.php';
//$tea_no = "tea";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
/** 根据action参数的值，进行不同处理：verify 通过审核，unverify 不通过审核
 *
 */

switch($action)
{
	//通过审核
	case 'verify':
		{
			if (!isset($_REQUEST['id']))
			{
				die('缺少参数:选课id');
			}		
			
			$ids = $_REQUEST['id'];
		//	echo count($ids);

			$count_sucess = 0;
			$count_fail = 0 ;
			echo '选课审核<br />';
			for($i = 0 ; $i < count($ids) ; $i++)
			{
				$queryStr = sprintf ("update sel_cor set  status='1' where id='%s'",$ids[$i]);
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
				
				//审核成功
				if($result == TRUE && 1 ==mysql_affected_rows())
				{
					$count_sucess ++ ;
				}
				else 
				{
					$count_fail++;
				}
			}
			echo '<br />';
			echo "{$count_sucess }个学生审核成功<br />";
			echo "{$count_fail }个学生审核失败<br />";
			mysql_close ();	
		};break;
		
		//审核不通过
		case 'unverify':
			{
				if (!isset($_REQUEST['id']))
				{
					die('缺少参数:选课id');
				}
					
				$ids = $_REQUEST['id'];
				$count_sucess = 0;
				$count_fail = 0 ;
				echo '选课审核<br />';
				for($i = 0 ; $i < count($ids) ; $i++)
				{
				$queryStr = sprintf ("delete from sel_cor where id='%s'",$ids[$i]);
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
		
				//审核成功
				if($result == TRUE && 1 ==mysql_affected_rows())
				{
				$count_sucess ++ ;
				}
				else
				{
				$count_fail++;
				}
				}
					echo '<br />';
					echo "{$count_sucess }个学生拒绝成功<br />";
					echo "{$count_fail }个学生拒绝失败<br />";
					mysql_close ();				
			};break;
	default:
		echo '提交不成功';	
}
?>