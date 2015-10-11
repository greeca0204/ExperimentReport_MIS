<?php session_start ();?>
<?php
/*名称：管理员端系统管理首页 
 * 作用：显示功能列表，响应用户点击请求
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
if ('admin' != $_SESSION ['user'] ['type']) {
	echo '非管理员身份不可登陆<br /><br />';
	echo "<a href='../index.php'>回到首页</a>";
	die ();
}
?>
<div
		style="background-image: url(../static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
	<div class="sep3"></div>

	<!-- 主导航条 -->
<?php 
require_once  '../nav.php';
?>

	<div class='sep3'></div>
	<div style="width: 1024px; margin-left: auto; margin-right: auto;">
		<!-- 左栏功能列表 -->
		<div
			style="float: left; width: 230; background-color: rgb(204, 204, 255);">
			<ul style="padding-left: 10px; padding-right: 10px;">
				<li><div style="font-weight: bold;">个人信息管理</div>
					<ul class="nav_left">
						<li><a id="update_info" class='menu' href="#">个人信息</a></li>
						<li><a id="update_psw" class='menu' href="#">密码维护</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />学生与教师管理
					</div>
					<ul class="nav_left">
						<li><a id="stu" class='menu' href="#">学生信息维护</a></li>
						<li><a id="tea" class='menu' href="#">教师信息维护</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />课程管理
					</div>
					<ul class="nav_left">
						<li><a id="view_course" class='menu' href="#">查看课程</a></li>
						<li><a id="insert_course" class='menu' href="#">新添课程</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />成绩管理
					</div>
					<ul class="nav_left">
						<li><a id="view_mark" class='menu' href="#">查看成绩</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />帖子管理
					</div>
					<ul class="nav_left">
						<li><a id="post_manage" class='menu' href="#">帖子管理</a></li>
						<li><a id="post_sort" class='menu' href="#">分类管理</a></li>
					</ul></li>

				<li><div style="font-weight: bold;">
						<br />系统管理
					</div>
					<ul class="nav_left">
						<li><a id="submit" class='menu' href="#"> <!--  权限管理-->
						</a></li>
						<li><a id="backup" class='menu' href="#">系统备份</a></li>
						<li><a id="restore" class='menu' href="#">系统还原</a></li>
					</ul></li>
			</ul>
		</div>

		<!-- 右边框架 -->
		<div class='sep10'></div>
		<div
			style="text-align: center; font-size: 150%; background-color:; float: left; margin-top: 20px; margin-left: 50px;">
			<iframe id="iframe_content" width=805 height=600 scrolling=auto
				frameborder=0 src="./index.php" style="text-align: center;"></iframe>
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
url['update_info']="./info.php?action=update_info";
url['update_psw']="./info.php?action=update_psw";
url['view_course']="./course_select.php";
url['insert_course']="./course_add.php";
url['stu']="./class_select.php";
url['tea']="./tea_select.php";
url['view_mark']="./mark.php";
url['post_manage']="./post.php?action=post_manage";
url['post_sort']="./post.php?action=post_sort";
url['backup']="./backup.php?action=backup";
url['restore']="./backup.php?action=restore";

var menuEvent = function menuEvent(e)
 {
     $('#iframe_content').attr('src',url[e.target.id]);       	
}
var start=function() { $(".menu").click( menuEvent );   }
$(start);
</script>
</body>
</html>