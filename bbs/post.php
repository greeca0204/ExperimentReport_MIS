<?php

session_start ();
header ( "Content-type: text/html; charset=utf-8" );
?>
 
 <?php
	/* 名称：发帖前台 
	 * 功能：帖子输入界面
	 */
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>

<div
	style="background-image: url(../static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
<?php
include '../config.php';
$now = date ( "Y-m-d G:i:s" );
// $stu = 'stu2';

// 发帖

echo "<div id=topMain>";
// 判断用户身份
if (! isset ( $_SESSION ['user'] ) || NULL == $_SESSION ['user']) {
	echo '你还没有登陆，请先登陆<br /><br />';
	echo "<a href='../login.php'>立即登陆</a>";
	die ();
}
$user = $_SESSION ['user'] ['id'];
echo "<a href=../bbs.php style=''>回首页</a>";
echo '<br /><br />';
echo "<form method='post' action='./post_deal.php?action=new_post'>";
echo "标题";
echo "<input name=title></input>";
echo "&nbsp分类<select name=sort>";

// 获取帖子分类
$queryStr = sprintf ( "select  *  from sort" );
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
mysql_close ();
while ( $rel = mysql_fetch_array ( $result ) ) {
	echo "<option value='{$rel['id']}'>{$rel['name']}</option>";
}
echo "</select>";

echo '<br />';
echo '帖子内容:';
echo '<br />';
echo "<textarea name=body cols=87 rows=30 style='width:720;height:500;'></textarea>";
echo '<br /><br />';
echo "<input class=button type=submit value=发帖></input>";
echo '</form>';

echo '</div>';
?>

<div style="clear: both; text-align: center;">
	<hr />
	© 2013 By 梁德斌
</div>