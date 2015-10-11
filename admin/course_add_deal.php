<?php
/* 名称：添加课程后台 
 * 作用：添加课程，更新课程数据库
 */
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css">
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<body>

<?php
include '../config.php';
include '../is_login_admin.php';

if (! ($_REQUEST ['cor_no'] && $_REQUEST ['cor_name'] && $_REQUEST ['term'] && $_REQUEST ['tea_no'] && $_REQUEST ['usual_rate'] && $_REQUEST ['report_rate'] && $_REQUEST ['exam_rate'])) {
	die ( "请完整输入" );
}

list ( $year, $month, $day ) = explode ( '-', $_REQUEST ['select_time'] );

if (! checkdate ( $month, $day, $year )) {
	die ( '输入的选课截止时间不是合法日期' );
}

list ( $year, $month, $day ) = explode ( '-', $_REQUEST ['close_time'] );
if (! checkdate ( $month, $day, $year )) {
	die ( '输入的课程关闭时间不是合法日期' );
}

if ($_REQUEST ['select_time'] > $_REQUEST ['close_time']) {
	die ( '添加失败:选课截止时间不能大于关闭时间' );
}

if (100 != ($_REQUEST ['usual_rate'] + $_REQUEST ['report_rate'] + $_REQUEST ['exam_rate'])) {
	die ( '添加失败:平时成绩、考试成绩、报告成绩总和不等于100' );
}

$queryStr = sprintf ( "insert into course values(NULL,'%s','%s','%s','%s','%s','%s','%s','%s',NULL,'%s','%s')", $_REQUEST ['cor_no'], $_REQUEST ['term'], $_REQUEST ['tea_no'], $_REQUEST ['cor_name'], $_REQUEST ['usual_rate'], $_REQUEST ['report_rate'], $_REQUEST ['exam_rate'], $_REQUEST ['select_time'], $_REQUEST ['close_time'], $_REQUEST ['grade'] );
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );

if ($result == TRUE && 1 == mysql_affected_rows ())
	echo "添加成功 <br>";
?>
</body>
</html>