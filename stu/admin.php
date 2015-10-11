<?php session_start ();?>
<?php
/*
 * 名称：学生端系统管理首页 功能：显示功能列表，响应用户点击请求
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<body>
<?php
// 判断用户身份
if (! isset ( $_SESSION ['user'] ) || NULL == $_SESSION ['user']) {
	echo '你还没有登陆，请先登陆<br /><br />';
	echo "<a href='../login.php'>立即登陆</a>";
	die ();
}
if ('stu' != $_SESSION ['user'] ['type']) {
	echo '非学生身份不可登陆<br /><br />';
	echo "<a href='../index.php'>回到首页</a>";
	die ();
}
?>
<div
		style="background-image: url(../static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
	<!-- 导航条 -->
	<div class="sep3"></div>
<?php
require_once '../nav.php';
?>

	<div class='sep10'></div>
	<div style="width: 1024px; margin-left: auto; margin-right: auto;">
		<!-- 左栏功能列表 -->
		<div
			style="float: left; width: 220; background-color: rgb(204, 204, 255);">
			<ul style="padding-left: 10px; padding-right: 10px;">
				<li><div style="font-weight: bold;">
						<br />个人信息管理
					</div>
					<ul class="nav_left">
						<li><a id="update_info" class='menu' href="#">个人信息</a></li>
						<li><a id="update_psw" class='menu' href="#">密码修改</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />选课管理
					</div>
					<ul class="nav_left">
						<li><a id="course_select" class='menu' href="#">选择课程</a></li>
						<li><a id="course_selected" class='menu' href="#">已选课程</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />报告管理
					</div>
					<ul class="nav_left">
						<li><a id="view_date" class='menu' href="#">提交截止时间</a></li>
						<li><a id="report_show_course" class='menu' href="#">填写实验报告</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />成绩查看
					</div>
					<ul class="nav_left">
						<li><a id="mark" class='menu' href="#">查看成绩</a></li>
					</ul></li>
			</ul>
		</div>

		<div class="sep3"></div>
		<div style="text-align: center;">
			<div class='sep10'></div>
			<iframe id="iframe_content" width=805 height=600 scrolling=auto
				frameborder=0 src="./index.php" style=""></iframe>
		</div>
	</div>
	<div style="clear: both; text-align: center;">
		<hr />
	© 2013
	By 梁德斌
		<?php
		if (isset ( $_SESSION ['user'] ) && NULL != $_SESSION ['user']) {
			echo "<div style='float:right;'>";
			echo "你的身份是:{$_SESSION['user']['type']}&nbspID为:{$_SESSION['user']['id']}";
			echo "</div>";
		}
		?>
	</div>
	<!-- 绑定左边导航点击事件 -->
	<script type="text/javascript">
var url = [ ];
url['pass']="../signup.html";
url['update_info']="./info.php?action=update_info";
url['update_psw']="./info.php?action=update_psw";
url['course_select']="./course_select_grade.php?action=course_select";
url['course_selected']="./course.php?action=view_selected";
url['report_show_course']="./report.php?action=default";
url['mark']="./mark.php";
var menuEvent = function menuEvent(e)
{
	$('#iframe_content').attr('src',url[e.target.id]);	
}
var start=function() { $(".menu").click( menuEvent );   }
$(start); 
</script>
</body>
</html>