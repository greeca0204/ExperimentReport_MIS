<?php session_start ();?>
<?php 
/*名称：首页
 * 功能：显示导航，中心介绍，友情链接，通知，教学资源，网络服务
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="./static/css/global.css"></link>
<script type="text/javascript" src="./static/jquery/jquery-1.8.3.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

</head>
<body>
<?php
include './config.php';
?>
<div
		style="background-image: url(./static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
<!--主导航条-->
	<div class="sep3"></div>
<?php 
require_once  './nav.php';
?>

	<div
		style="clear: left; width: 1024px; margin-left: auto; margin-right: auto;">

		<!--中心介绍-->

		<div style="float: left; width: 250px; height: 330px;">
			<div style="text-align: center; background-color: rgb(204, 204, 255)">
				<span><strong>中心介绍</strong></span>
			</div>
			<p class="white" style="margin-top: 1px;">   
				桂林电子科技大学的计算机学科始建于1980年，是全国高校中较早开办计算机及应用专业的学校之一。2000年学校在体制改革中，为了加强计算机基础实验课程建设，通过对全校各系相关专业的计算机实验室进行重新整合成立了校级计算机基础实验教学中心。中心现有基础课程实
				验室8个，并设有一个软件设计训练基地、一个大学生创新基地、一个软件开发基...</p>
		</div>

		<!--轮播图-->
		<div
			style="clear: right; float: left; width: 500px; height: 330px; margin-left: 5px; overflow: auto;">
			<div
				style="text-align: center; background-color: rgb(204, 204, 255); overflow: auto;">
				<span style="float: left; margin-left: 60px;"><strong>相关图片</strong></span>
			</div>

			<div
				style="float: left; min-width: 460px; width: 460px; margin: 0px;">
				<div id="banner" style="margin: 0px;">
					<div id="banner_list">
						<div id="banner_list" style="margin: 0px;">
							<a href="#" target="_blank"><img src="./static/image/p1.png"
								title="" alt="" /></a> <a href="#" target="_blank"><img
								src="./static/image/p2.png" title="" alt="" /></a> <a href="#"
								target="_blank"><img src="./static/image/p3.png" title="" alt="" /></a>
							<a href="#" target="_blank"><img src="./static/image/p4.png"
								title="" alt="" /></a>
						</div>

						<ul class="nav_mid"
							style="z-index: 1002; filter: Alpha(Opacity =   80); opacity: 0.8; margin: 0px;">
							<li class="on">1</li>
							<li>2</li>
							<li>3</li>
							<li>4</li>
						</ul>


					</div>
				</div>
			</div>
		</div>

		<!-- 通知 -->
		<div style="float: left; width: 264px; margin-left: 5px;">
			<div
				style="text-align: center; background-color: rgb(204, 204, 255); overflow: auto;">
				<span style="float: left; margin-left: 60px;"><strong>通知</strong></span>
			</div>

			<marquee direction=up scrollAmount=2>
			<ul
				style="list-style: none; font-size: 18px; margin: 0px; padding: 0px; ">
			<?php
			/*
			 * $queryStr = sprintf ( "select
			 * post_id,sort,title,author,post_time,last_reply_time,last_floor,name
			 * from topic,sort where visible='0' and topic.sort=sort.id order by
			 * last_reply_time desc limit 0,10" ); $result = mysql_query (
			 * $queryStr, $conn ) or die ( "查询失败:" . mysql_error () ); if ($rel
			 * = mysql_fetch_array ( $result )) { do { echo "<li><a
			 * href=./bbs.php?topic={$rel['post_id']}>{$rel['title']}</a></li>";
			 * } while ( $rel = mysql_fetch_array ( $result ) ); } mysql_close
			 * ();
			 */
			?>
	                 <li><a
					href=http://jsjzx.guet.edu.cn/shownews.aspx?nid=280><span style="font-size: 15px">第六次程序设计大赛培训时间安排表</span></a></li>
				<li ><a href=http://jsjzx.guet.edu.cn/shownews.aspx?nid=279><span style="font-size: 15px">2013-2014-1排课资料</span></a></li>
				<li><a href=http://jsjzx.guet.edu.cn/shownews.aspx?nid=278><span style="font-size: 15px">商学院12级六专业学生调课通知</span></a></li>
				<li><a href=http://jsjzx.guet.edu.cn/shownews.aspx?nid=277><span style="font-size: 15px">第六届程序设计大赛报名通知</span></a></li>
				<li><a href=http://jsjzx.guet.edu.cn/shownews.aspx?nid=276><span style="font-size: 15px">有小孩的教职工：请看六一节通知</span></a></li>
				<li><a href=http://jsjzx.guet.edu.cn/shownews.aspx?nid=275><span style="font-size: 15px">关于组织教职工参保、续保广西职工重大</span></a></li>

			</ul>
			</marquee>
		</div>


	</div>
	<!-- 第一行的div结束 -->


<!-- 第二行的div开始 -->
	<div
		style="clear: left; width: 1024px; margin-left: auto; margin-right: auto;">
		<!--友情链接-->
		<div style="clear: left; float: left; width: 250px; height: 250px;">
			<div style="text-align: center; background-color: rgb(204, 204, 255)">
				<span><strong>友情链接</strong></span>
			</div>
			<ul
				style="list-style: none; font-size: 18px; margin: 0px; padding: 0px;">
				<li><a href="http://www.guet.edu.cn/">桂林电子科技大学</a></li>
				<li><a href="http://w5.guet.edu.cn/jxsjb/">桂电教学实践部</a></li>
				<li><a href="http://szhxy.guet.edu.cn/qxgl/">桂电数字化校园</a></li>
				<li><a href="http://lib.gliet.edu.cn/">桂林电子科技大学图书馆</a></li>
				<li><a href="http://202.117.58.254/ctec/">西安交大计算机实验中心</a></li>
			</ul>
		</div>


		<!--教学资源-->
		<div
			style="float: left; width: 250px; height: 250px; margin-left: 5px;">
			<div style="text-align: center; background-color: rgb(204, 204, 255)">
				<span><strong>教学资源</strong></span>
			</div>
			<ul
				style="list-style: none; font-size: 18px; margin: 0px; padding: 0px;">
				<li><a href="http://metc.guet.edu.cn/">现教中心 </a></li>
				<li><a href="http://www.guet.edu.cn/net-fuwu/ftp.asp">桂电 FTP </a></li>
				<li><a href="http://w3.guet.edu.cn/cisco">培训认证 </a></li>
				<li><a href="http://w3.guet.edu.cn/zhglc/guizhongyiqi/index.htm">设备共享 </a></li>
				<li><a href="http://202.117.58.254/ctec/">桂电blackboar教学平台</a></li>
				<li><a href="http://202.117.58.254/ctec/">程序设计训练基地</a></li>
				<li><a href="http://202.117.58.254/ctec/">大学生创新实践基地</a></li>
			</ul>
		</div>

		<div
			style="float: left; width: 250px; height: 250px; margin-left: 0px;">
			<div style="text-align: center; background-color: rgb(204, 204, 255)">
				<div>&nbsp</div>
			</div>
			<ul
				style="list-style: none; font-size: 18px; margin: 0px; padding: 0px;">
				<li><a href="http://www.guet.edu.cn/">桂电教育在线</a></li>
				<li><a href="http://w5.guet.edu.cn/jxsjb/">计算机中心教学资源网站</a></li>
				<li><a href="http://szhxy.guet.edu.cn/qxgl/">数据库基础教学网站</a></li>
				<li><a href="http://lib.gliet.edu.cn/">网页设计学习网站</a></li>
				<li><a href="http://202.117.58.254/ctec/">在线教学过程登记表</a></li>
				<li><a href="http://202.117.58.254/ctec/">校级程序设计大赛(校内）</a></li>
			</ul>
		</div>

		<!--网络服务-->
		<div
			style="float: left; width: 250px; height: 250px; margin-left: 5px;">
			<div style="text-align: center; background-color: rgb(204, 204, 255)">
				<span><strong>网络服务</strong></span>
			</div>
			<ul
				style="list-style: none; font-size: 18px; margin: 0px; padding: 0px;">
				<li><a href="http://www.guet.edu.cn/">实验教学信息管理系统</a></li>
				<li><a href="http://w5.guet.edu.cn/jxsjb/">教师网络化办公系统</a></li>
				<li><a href="http://szhxy.guet.edu.cn/qxgl/">实验预约系统</a></li>
				<li><a href="http://lib.gliet.edu.cn/">计算机中心教学BBS</a></li>
				<li><a href="http://202.117.58.254/ctec/">计算机等级考试样卷库</a></li>
				<li><a href="http://202.117.58.254/ctec/">学习互助中心</a></li>
				<li><a href="http://202.117.58.254/ctec/">程序在线智能测评系统</a></li>
				<li><a href="http://202.117.58.254/ctec/">Android开发团队</a></li>
			</ul>
		</div>
		
	<!-- 第二行的div结束 -->
	
	</div>
	<div></div>


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

	<!-- 图片轮播效果 -->
	<script type="text/javascript">
	
    	  var t = n =0, count;
    	  $(document).ready(function(){ 
    	 count=$("#banner_list a").length;
    	  $("#banner_list a:not(:first-child)").hide();
    	  $("#banner_info").html($("#banner_list a:first-child").find("img").attr('alt'));
    	  $("#banner_info").click(function(){window.open($("#banner_list a:first-child").attr('href'), "_blank")});
    	  $("#banner li").click(function() {
    	 var i = $(this).text() -1;//获取Li元素内的值，即1，2，3，4
    	  n = i;
    	 if (i >= count) return;
    	  $("#banner_info").html($("#banner_list a").eq(i).find("img").attr('alt'));
    	  $("#banner_info").unbind().click(function(){window.open($("#banner_list a").eq(i).attr('href'), "_blank")})
    	  $("#banner_list a").filter(":visible").fadeOut(500).parent().children().eq(i).fadeIn(1000);
    	  document.getElementById("banner").style.background="";
    	  $(this).toggleClass("on");
    	  $(this).siblings().removeAttr("class");
    	  });
    	  t = setInterval("showAuto()", 2000);
    	  $("#banner").hover(function(){clearInterval(t)}, function(){t = setInterval("showAuto()", 2000);});
    	  })

    	 function showAuto()
    	  {
    	  n = n >=(count -1) ?0 : ++n;
    	  $("#banner li").eq(n).trigger('click');
    	  }
    	         	

 //    $(start);
</script>

</body>
</html>
