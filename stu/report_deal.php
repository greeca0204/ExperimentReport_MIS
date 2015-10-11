<?php 
/*名称：报告管理后台
 * 功能：更新实验报告
 */
?>
<?php
include '../config.php';
include '../is_login_stu.php';

//$stu_no = 'stu2';
$today = date("Y-m-d");
$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;

switch ($action) 
{
	// 保存实验报告
	case 'save' :
		{
			$status = 0;
		}
		break;
	
	// 提交实验报告
	case 'submit' :
		{
			$status = 1;
		}
		break;
	default :
		die("提交状态不正确");
}

$body = $_REQUEST ['body'];
$cor_no = $_REQUEST ['cor_no'];
$item_no = $_REQUEST ['item_no'];
$body = addslashes ($body);

//若已经过了报告截止提交时间
$queryStr = sprintf ("select report_time,close_time from course where cor_no='%s'",$cor_no);
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
$rel = mysql_fetch_array ( $result );
if($today > $rel['report_time'] || $today > $rel['close_time'])
{
	die('<br />更新失败:已经超过报告截止提交时间<br  />');
}

// 若已经填写过报告
$queryStr = sprintf ( "select  *  from report where stu_no='%s' and cor_no='%s' and item_no='%s' ", $stu_no, $cor_no, $item_no );
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
if ($rel = mysql_fetch_array ( $result ))
 {
 	if ($rel['status'] !=0)
 	{
 		die("更新失败:你以前已经提交了");
 	}
	$queryStr = sprintf ( "update report set  date='%s',body='%s',status='%s' where cor_no='%s' and stu_no='%s' and item_no='%s'",$today,$body,$status,$cor_no,$stu_no,$item_no);
	$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
	if($result= TRUE && 1==mysql_affected_rows())
	{
		echo '操作成功';
	}
	else
	{
		echo '操作失败';	
	}
} 

// 若还没有填写过报告
else 
{
	$queryStr = sprintf ( "insert into report values(NULL,'%s','%s','%s','%s','%s',NULL,NULL,'%s')",$cor_no,$stu_no,$item_no,$today,$body,$status);
	$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
	if($result= TRUE && 1==mysql_affected_rows())
	{
		echo '操作成功';
	}
	else
	{
		echo '操作失败';
	}
}
?>