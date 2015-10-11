<?php 
/*名称：学生端课程管理后台
 * 功能：选课，退课
 */
?>
<?php
include '../config.php';
include '../is_login_stu.php';

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
switch($action)
{
	//选课
	case 'course_select':
		{
			if (!isset($_REQUEST['cor_no'] ))
			{
				die("选课失败:课程号出错");
			}
			if (!isset($_REQUEST['groups'] ))
			{
				die("请先选择批次");
			}
			$cor_no = trim($_REQUEST['cor_no']);
	//		$stu_no = trim($_REQUEST['stu_no']);
			$groups = trim($_REQUEST['groups']);
			
			//若是退课再选
			$queryStr = sprintf("select count(stu_no) from sel_cor where stu_no='%s' and cor_no='%s'  and status='2'",$stu_no,$cor_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "选择失败:" . mysql_error () );
			$rel = mysql_fetch_array($result);
			if ($rel[0] !=0)
			{
				$queryStr = sprintf("update sel_cor set status=0,groups='%s' where stu_no='%s' and cor_no='%s' ",$groups,$stu_no,$cor_no);
				$result = mysql_query ( $queryStr, $conn ) or die ( "添加失败:" . mysql_error () );
				if($result==TRUE && 1==mysql_affected_rows())
				{
					echo '选课成功';
				}
				else
				{
					echo '选课失败';
				}
				mysql_close();
				die();
			}
			//若是第一次选课
			$queryStr = sprintf("insert into sel_cor values(NULL,'%s','%s','%s',0,NULL,NULL,NULL,NULL)",$stu_no,$cor_no,$groups);
			$result = mysql_query ( $queryStr, $conn ) or die ( "添加失败:" . mysql_error () );
			 
			//添加成功
			if($result==TRUE && 1==mysql_affected_rows())
			{
				echo '选课成功';
			}
			//添加失败
			else
			{
				echo '选课失败';
			}
			mysql_close()	;
		    };break;

	//退课的后台处理
	case 'course_unselect':
		{
			if (!isset($_REQUEST['cor_no'] ))
			{
				die("请先选择课程");
			}
			
			$cor_no = $_REQUEST['cor_no'];
	//		$stu_no = $_REQUEST['stu_no'];
	
			//删除选课信息
			$queryStr = sprintf("update sel_cor set status=2 where stu_no='%s' and cor_no='%s'",$stu_no,$cor_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
			if($result=TRUE &&  1==mysql_affected_rows())
			{
				echo '退课成功';
			}
			else
			{
				echo '退课失败';
			}			
		}break;
		
	default:
		break;   
}
?>