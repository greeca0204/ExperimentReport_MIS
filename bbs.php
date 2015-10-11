<?php
/* 名称：论坛主页模块
 * 功能：显示帖子内容和回复，显示帖子列表
 * 
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="./static/css/global.css"></link>
<script type="text/javascript" src="./static/jquery/jquery-1.8.3.js"></script>
</head>

<?php
session_start ();
include './config.php';
$now = date ( "Y-m-d G:i:s" );
?>
<div style="background-image:url(./static/image/topback.jpg);width:1024px;height:80px;margin-left: auto; margin-right: auto;"></div>

<!--主导航条-->
<div class="sep3"></div>
<?php 
require_once  './nav.php';
?>

<div id='topMain'>
<?php

// 若有主题的id，则显示主题和回复列表，并显示回复框

if (isset ( $_REQUEST ['topic'] )) {
	echo "<span style='height:30px;'>";
	echo "<a href=./bbs.php style=''>首页</a>";
	echo "</span>";
	$post_id = $_REQUEST ['topic'];
	
	// 获取帖子
	$queryStr = sprintf ( "select  *  from topic,sort where post_id='%s' and topic.sort=sort.id", $post_id );
	$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
	if ($rel = mysql_fetch_array ( $result )) {
		
		echo "<span>>{$rel['name']}</span><br />";
		echo "<span class=post_title>{$rel['title']}</span>";
		echo "<br /><span style='font-size:16px;'>By {$rel['author']}</span>";
		echo "<span style='margin-left:50px;'>发表时间:{$rel['post_time']}<span>";
		
		echo '<hr />';
		echo '<div class=sep10></div>';
		echo '<pre style="width:720px;">';
		echo htmlspecialchars(stripslashes($rel ['body']));
		echo '</pre>';
		echo '<hr />';
		
		// 分页中每一页的条目数量
		$page_size = 10;
		
		// 获取页码
		$reply_page = isset ( $_REQUEST ['reply_page'] )?intval($_REQUEST ['reply_page']):1;
		
		// 获取回帖总数
		$queryStr = sprintf ( "select  count(reply_id)  from reply where post_id='%s'", $post_id );
		$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
		$rel2 = mysql_fetch_array ( $result2 );
		$numrows = $rel2 [0];
		// 总页数
		$reply_pages = intval ( $numrows / $page_size );
		if ($numrows % $page_size) {
			$reply_pages ++;
		}
		// 前一页和后一页
		$prev = $reply_page - 1;
		$next = $reply_page + 1;
		// 计算记录偏移量
		$offset = $page_size * ($reply_page - 1);
		
		$queryStr = sprintf ( "select  *  from reply where post_id='%s'  order by floor asc limit %s,%s", $post_id, $offset, $page_size );
		$result3 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
		
		// 若有回复贴
		if ($rel3 = mysql_fetch_array ( $result3 )) {
			do {
				echo '<div class=bg_color></div>';
				echo "<div class=author>";
				echo "<span >By {$rel3['author']}&nbsp&nbsp&nbsp&nbsp</span>";
				echo "<span >发表时间:{$rel3['reply_time']}&nbsp&nbsp&nbsp&nbsp<span>";
				echo "<span style='float:right;'>{$rel3['floor']}楼<span>";
				// echo '<div></div>';
				echo "</div>";
				
				echo "<pre>";
				echo htmlspecialchars(stripslashes($rel3['body']));
				echo "</pre>";
				
			} while ( $rel3 = mysql_fetch_array ( $result3 ) );
			
			// 分页导航
			echo '<br />';
			if ($reply_page > 1) {
				echo "<a href=./bbs.php?topic={$post_id}&reply_page=1>首页</a>";
				echo "<a href=./bbs.php?topic={$post_id}&reply_page={$prev}>上一页</a>";
			}
			if ($reply_page < $reply_pages) {
				echo "<a href=./bbs.php?topic={$post_id}&reply_page={$next}>下一页</a>";
				echo "<a href=./bbs.php?topic={$post_id}&reply_page={$reply_pages}>尾页</a>";
			}
			echo "共有{$reply_pages}页 ({$reply_page}/{$reply_pages})";
		} else {
			// echo '没回复';
		}
		
		// 显示回复框
		echo '<div class=bg_color></div>';
		echo '<br />回复楼主<br />';
		echo "<form method='get' action='./bbs/post_deal.php'>";
		echo "<textarea name=reply_body cols=87 rows=5 style='width:720;height:80;'></textarea>";
		
		echo '<br />';
		echo "<input type=hidden name=action value=reply_post></input>";
		echo "<input type=hidden name=post_id value={$post_id}></input>";
		echo "<input class=button type=submit value=回帖></input>";
		echo '</form>';
	} 	// 帖子不存在
	else {
		echo '<br /><br />帖子不存在';
	}
} 

// 默认显示主题列表
else {
	echo "<span style='float:right;'><a href=./bbs/post.php?action=new_post>发帖</a></span>";
	echo "<div class=bg_color></div>";
	// 分页中每一页的条目数量
	$page_size = 15;
	
	// 获取页码
	$page = isset ( $_REQUEST ['page'] )?intval($_REQUEST ['page']):1;
	
	// 获取主题总数
	$queryStr = sprintf ( "select  count(post_id)  from topic" );
	$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
	$rel = mysql_fetch_array ( $result );
	$numrows = $rel [0];
	
	// 总页数
	$pages = intval ( $numrows / $page_size );
	if ($numrows % $page_size) {
		$pages ++;
	}
	
	// 前一页和后一页
	$prev = $page - 1;
	$next = $page + 1;
	
	// 计算记录偏移量
	$offset = $page_size * ($page - 1);
	
	$queryStr = sprintf ( "select  post_id,sort,title,author,post_time,last_reply_time,last_floor,name  from topic,sort where visible='0' and topic.sort=sort.id  order by last_reply_time desc limit %s,%s", $offset, $page_size );
	$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
	mysql_close ();
	if ($rel = mysql_fetch_array ( $result )) {
		echo '<br />';
		do {
			echo "<div class=bbs_topic_list>";
			//打印分类
			echo "<span class=bbs_topic_list_sort >[{$rel['name']}]</span>";
			
			//打印标题
			echo "<span class='title' >&nbsp<a href=./bbs.php?topic={$rel['post_id']}>";

			echo mb_substr($rel['title'], 0,18,'utf-8');
            echo "</a></span>";
            
			echo "<span style='float:right;font-size:15px;margin-right:10px'>最后回复:";

            $last_reply =  strtotime($now)-strtotime($rel['last_reply_time']) ;
        //  echo time($now);
      
            if ($last_reply<60)
            {
            	echo $last_reply;
            	echo '秒前';
            }
            else if ($last_reply<3600) 
            {
            	echo floor($last_reply/60);
            	echo '分钟前';
            }
            else if ($last_reply<86400)
            {
            	echo floor($last_reply/3600);
            	echo '小时前';
            }
            else
           {
            	echo floor($last_reply/86400);
            	echo '天前';
            }

            echo "</span>";
			echo "<span style='float:right;font-size:15px;margin-right:10px'>楼主:{$rel['author']}</span>";
			// echo "<span
			// style='float:right;font-size:15px;margin-right:10px'>{$rel['author']}reply</span>";
			echo '</div>';
		} while ( $rel = mysql_fetch_array ( $result ) );
	} else {
		echo '暂时还没有帖子';
	}
	
	// 分页导航
	echo '<br />';
	echo "<div style='height:30px;'>";
	if ($page > 1) {
		echo "<a href=./bbs.php?page=1>首页</a>";
		echo "<a href=./bbs.php?page={$prev}>上一页</a>";
		echo '</span>';
	}
	if ($page < $pages) {
		echo "<a href=./bbs.php?page={$next}>下一页</a>";
		echo "<a href=./bbs.php?page={$pages}>尾页</a>";
		echo '</span>';
	}
	echo "共有{$pages}页 ({$page}/{$pages})";
	echo "</div>";
}
?>
</div>

<div style="clear: both; text-align: center;">
	<hr />
	© 2013 By 梁德斌
	<?php
	if (isset ( $_SESSION ['user'] ) && NULL != $_SESSION ['user']) {
		echo "<div style='float:right;'>";
		echo "你的身份是:{$_SESSION['user']['type']}&nbspID为:{$_SESSION['user']['id']}";
		echo "</div>";
	}
	?>
</div>
</html>