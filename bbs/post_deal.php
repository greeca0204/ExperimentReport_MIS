 <?php 
 /*名称：发帖后台
  * 功能：发布帖子，发表回复
  */
 ?>
<?php session_start ();
header("Content-type: text/html; charset=utf-8"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<div style="background-image:url(../static/image/topback.jpg);width:1024px;height:80px;margin-left: auto; margin-right: auto;"></div>
<?php
include '../config.php';
$now = date ( "Y-m-d G:i:s" );
//$stu = 'stu2';

//判断用户身份
if (!isset($_SESSION['user']) || NULL == $_SESSION['user'])
{
	echo 'session失效，请重新登陆<br /><br />';
	echo "<a href='../login.php'>立即登陆</a>";
	die();
}
$user = $_SESSION['user']['id'];


$action=NULL;
if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}
echo '<div id=topMain>';
/** 根据action参数的值，进行不同的后台处理：new_post发表新帖子，reply_post回复帖子
 *
 */
switch ($action)
 {
 	case 'new_post':
 		{
 		//	echo '发帖';
 		if (!isset($_REQUEST ['sort']) || NULL == $_REQUEST ['sort'] )
 		{
 			die('请先选择帖子分类');
 		}
 		if (!isset($_REQUEST ['title']) || NULL == $_REQUEST ['title'])
 		{
 			die('请先填写标题');
 		}
 		if (!isset($_REQUEST ['body']) || NULL == $_REQUEST ['body'])
 		{
 			die('帖子内容呢?');
 		}
 		$sort = trim($_REQUEST ['sort']);
 		$title = trim($_REQUEST ['title']);
 		$body = trim($_REQUEST ['body']);
 		
 		//帖子字符转义
 		$title = addslashes ($title );
 		$body = addslashes ($body );
 		//把帖子插入到数据库
 		$queryStr=sprintf("insert into topic values(NULL,'%s','%s','%s','%s','%s','0','%s','0','0','0')",$sort,$title,$user,$body,$now,$now);
 		$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
 		if($result !=FALSE && 1==mysql_affected_rows())
 		{
 			echo '发表成功';
 			
 		}
 		else
 		{
 			echo '发表失败';
 		}
 		echo "<br /><br /><a href=../bbs.php style=''>回首页</a>";
 		mysql_close();
 			
 		};break;
 		
 		//回帖
 	case 'reply_post':
 		{
 			if (!isset($_REQUEST ['post_id']) )
 			{
 				die('回帖失败:缺少post_id');
 			}
 			if (!isset($_REQUEST ['reply_body']) || NULL == $_REQUEST ['reply_body'] )
 			{
 				die('回帖失败:你的回复为空');
 			}
 			$post_id = $_REQUEST ['post_id'];
 			$reply_body = $_REQUEST ['reply_body'];
 			$reply_body = addslashes ($reply_body );
 		//	echo $post_id;
 		//	echo $reply_body;
 		
 			// 获取帖子楼层
 			$queryStr = sprintf ( "select  last_floor  from topic where post_id='%s'",$post_id);
 			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
 			$rel = mysql_fetch_array ( $result );
 			$last_floor = $rel['last_floor'] + 1;
 			
 			//回复贴插入数据库
 			$queryStr=sprintf("insert into reply values(NULL,'%s','%s','%s','%s','%s')",$user,$reply_body,$now,$last_floor,$post_id);
 			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
 			if($result !=FALSE && 1==mysql_affected_rows())
 			{
 				
 				$queryStr=sprintf("update topic set last_floor='%s',last_reply_time='%s' where post_id='%s'",$last_floor,$now,$post_id);
 				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
 				if($result !=FALSE && 1==mysql_affected_rows())
 				{
 					echo '回复成功';	
 				}
 			}
 			else
 			{
 				echo '回复失败';
 			}
 			echo "<br /><br /><a href=../bbs.php style=''>回首页</a>";
 			echo '&nbsp&nbsp&nbsp';
 			echo "<a href=../bbs.php?topic={$post_id} style=''>回主题</a>";
 			mysql_close();
 			
 			
 		};break;
 		
 	default:	break;
}
echo '</div>';
?>