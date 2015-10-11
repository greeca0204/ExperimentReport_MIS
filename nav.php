<?php
/*
 * 名称：主页导航 
 * 功能：页面中上方导航条
 */
?>
<?php 
//获取当前域名
function getPageURL()
{
	$pageURL = 'http';
	if(isset( $_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
	{
		$pageURL .= "s";
	}
	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . '/';
	}
	else
	{
		$pageURL .= $_SERVER["SERVER_NAME"] . '/';
	}
	return $pageURL;
}
$domain = getPageURL();
?>
<!--主导航条-->
<div class="nav">
	<ul class="nav_mid" >
		<li><a href="<?php echo $domain ,'index.php'?>" class="font_white">首页</a></li>
		<li><a href="<?php echo $domain ,'bbs.php'?>" class="font_white">讨论区</a></li>
		<li><a href="<?php echo $domain ,'report.php'?>" class="font_white">实验报告管理</a></li>
		<li><a href="<?php echo $domain ,'about_center.php'?>" class="font_white">中心概括</a></li>
			<?php
			//根据登陆状态显示登陆或退出按钮
	        //session_start ();
			if (! isset ( $_SESSION ['user'] ) || NULL == $_SESSION ['user']) {
				echo "<li><a href=$domain" . "login.php " . " class='font_white'>登陆</a></li>";
			} else {
				echo "<li><a href=$domain" . "login.php?action=logout " . " class='font_white'>退出</a></li>";
			}
			?>
		</ul>
	</div>