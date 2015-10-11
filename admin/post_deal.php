<?php 
/*名称：帖子管理后台
 * 功能：更新帖子，删帖，删回复，添加分类，修改分类
 */
?>
<head>
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_admin.php';

$now = date ( "Y-m-d G:i:s" );
//$stu = 'stu2';

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
echo '<div id=topMain>';
/**
 * 根据action参数的值，进行不同的后台处理：post_update更新帖子，post_delete删除帖子
 * reply_delete删除回复，sort_add增加分类，sort_update分类更新
 */
switch ($action) {
	// 更新帖子内容和标题
	case 'post_update' :
		{
			if (! isset ( $_REQUEST ['post_id'] )) {
				die ( "请先选择帖子" );
			}
			
			if (! isset ( $_REQUEST ['title'] ) || NULL == $_REQUEST ['title']) {
				die ( "请输入帖子标题" );
			}
			
			if (! isset ( $_REQUEST ['body'] ) || NULL == $_REQUEST ['body']) {
				die ( "请输入帖子内容" );
			}
			$post_id = $_REQUEST ['post_id'];
			$title = $_REQUEST ['title'];
			$body = $_REQUEST ['body'];
			
			$queryStr = sprintf ( "update topic set title='%s',body='%s' where post_id='%s'", $title, $body, $post_id );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
			// 更新帖子成功
			if ($result != FALSE && 1 == mysql_affected_rows ()) {
				echo '更新成功';
			} else {
				echo '更新失败了';
			}
			mysql_close ();
		}
		;
		break;
	
	// 删帖
	case 'post_delete' :
		{
			
			$ids = $_REQUEST ['post_id'];
			// print_r($ids);
			$count_sucess = 0;
			$count_fail = 0;
			
			for($i = 0; $i < count ( $ids ); $i ++) {
				$queryStr = sprintf ( "delete from topic where post_id='%s'", $ids [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "删除失败: " . mysql_error () );
				// 删除主题成功
				if ($result != FALSE && 0 != mysql_affected_rows ()) {
					$count_sucess ++;
					$queryStr = sprintf ( "delete from reply where post_id='%s'", $ids [$i] );
					$result = mysql_query ( $queryStr, $conn ) or die ( "删除回复失败: " . mysql_error () );
				} 				// 删除失败
				else {
					$count_fail ++;
				}
			}
			echo "{$count_sucess }个主题成功删除<br />";
			echo "{$count_fail }个主题删除失败<br />";
			mysql_close ();
		}
		;
		break;
	
	// 删回复贴
	case 'reply_delete' :
		{
			$post_id = $_REQUEST['post_id'];
			$ids = $_REQUEST ['reply_id'];
			$count_sucess = 0;
			$count_fail = 0;
			
			for($i = 0; $i < count ( $ids ); $i ++) {
				$queryStr = sprintf ( "delete from reply where reply_id='%s' and post_id='%s'", $ids [$i],$post_id );
				$result = mysql_query ( $queryStr, $conn ) or die ( "删除失败: " . mysql_error () );
				// 删除回复成功
				if ($result != FALSE && 0 != mysql_affected_rows ()) {
					$count_sucess ++;
				} 	
				// 删除失败
				else {
					$count_fail ++;
				}
			}
			echo "{$count_sucess }个回复成功删除<br />";
			echo "{$count_fail }个回复删除失败<br />";
			mysql_close ();
		}
		;
		break;
		
		//增加分类，后台
	case 'sort_add':
		{
			if (!isset($_REQUEST['name'] ) || NULL == $_REQUEST['name'] )
			{
				die("请填写分类名");
			}
			$name = $_REQUEST['name'] ;
			
			//向数据库添加分类
			$queryStr = sprintf("insert into sort values(NULL,'%s')",$name);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if($result==TRUE && 0!=mysql_affected_rows())
			{
				echo "成功添加了分类名:{$name}";
			}
			else
			{
				echo '添加分类名失败';
			}
			
		};break;
		
		//修改分类，后台
	case 'sort_update':
		{
			if (!isset($_REQUEST['id'] ))
			{
				die("缺少分类编号");
			}
			$id = $_REQUEST['id'] ;
			
			if (!isset($_REQUEST['name'] ) || NULL == $_REQUEST['name'] )
			{
				die("请填写分类名");
			}
			$id = $_REQUEST['id'] ;
			$name = $_REQUEST['name'] ;
			//更新分类名
			$queryStr = sprintf("update sort set name='%s' where id='%s'",$name,$id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () ) ;
			if($result!=FALSE && 1 ==mysql_affected_rows())
			{
				echo '更新分类名成功';
			}
			else
			{
				echo '更新分类名失败';
			}
			
		};break;
	
	default :
		break;
}
echo '</div>';
?>