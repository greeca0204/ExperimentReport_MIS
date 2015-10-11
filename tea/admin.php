<?php session_start ();?>
<?php 
/*名称：教师端系统管理首页
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
if ('tea' != $_SESSION ['user'] ['type']) {
	echo '非教师身份不可登陆<br /><br />';
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
	<div style="width: 1024px;margin-left:auto;margin-right:auto;">
		<!-- 左栏功能列表 -->
	<div style="float: left; width: 220;background-color:rgb(204, 204, 255);">
		<ul style="padding-left:10px;padding-right:10px;">
			<li><div style="font-weight:bold;">个人信息管理</div>
				<ul class="nav_left" >
					<li><a id="update_info" class='menu' href="#">个人信息</a></li>
					<li><a id="update_psw" class='menu' href="#">修改密码</a></li>
				</ul>
			</li>

			<li><div style="font-weight:bold;"><br/>课程管理</div>
				<ul class="nav_left" >
					<li><a id="update_group" class='menu' href="#">课程批次管理</a></li>
					<li><a id="update_item" class='menu' href="#">实验管理</a></li>

				</ul>
			</li>

			<li><div style="font-weight:bold;"><br/>选课管理</div>
				<ul class="nav_left" >
					<li><a class='menu' href="#"><!--  选课信息--></a></li>
					<li><a id='record' class='menu' href="#">审核管理</a></li>
				</ul>
			</li>

			<li><div style="font-weight:bold;"><br/>报告管理</div>
				<ul class="nav_left" >
					<li><a id='report_manage' class='menu' href="#">报告管理</a></li>
					<li><a id='report_correct' class='menu' href="#">报告批改</a></li>
					<li><a id='remark' class='menu' href="#">评语管理</a></li>
				</ul>
			</li>
			<li ><div style="font-weight:bold;"><br/>成绩管理</div>
				<ul class="nav_left" >
					<li><a id='mark_exam' class='menu' href="#">考试成绩</a></li>
					<li><a id='mark_update' class='menu' href="#">成绩更新</a></li>
					<li><a id='mark_all_cor' class='menu' href="#">成绩报表</a></li>
				</ul>
			</li>

			<li><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>

	</div>
	
	<!-- 右边框架 -->
	<div class='sep10'></div>
	<div style="text-align: center;font-size:150%;background-color:;float:left;margin-top:20px;margin-left:50px;">
		<iframe id="iframe_content" width=805 height=600 scrolling=auto
			frameborder=0 src="./index.php"></iframe>
	</div>
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
<!-- 绑定左边导航点击事件 -->
<script type="text/javascript">
var url = [ ];
url['update_info']="./info.php?action=update_info";
url['update_psw']="./info.php?action=update_psw";
url['update_item']="./term_select.php?action=show_item&time="+Math.random();
url['update_group']="./term_select.php?action=show_group&time="+Math.random();
url['remark']="./remark.php?action=default";
url['report_correct']="./report.php?action=default";
url['report_manage']="./report.php?action=report_manage";
url['record']="./select.php?action=default";
url['mark_exam']="./mark.php?action=default";
url['mark_update']="./mark.php?action=mark_update";
url['mark_all_cor']="./mark.php?action=mark_all_cor";
var menuEvent = function menuEvent(e)
{
    //  alert(url[e.target.id]);
	$('#iframe_content').attr('src',url[e.target.id]);    	
}
//绑定点击事件
var start=function() { $(".menu").click( menuEvent );   }
$(start);
</script>
</body>
</html>