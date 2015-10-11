<?php session_start ();?>
<?php 
/*名称：实验报告管理系统首页
 * 功能：判断用户登陆类型，跳转到用户类型的系统首页
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="./static/css/global.css"></link>
<script type="text/javascript" src="./static/jquery/jquery-1.8.3.js"></script>
<div style="background-image: url(./static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
<?php
//判断用户身份
if (!isset($_SESSION['user']) || NULL == $_SESSION['user'])
{
	echo "<div class='center' style='width:720px;'>";
	echo '请重新登陆,2秒后自动跳到登陆页面<br /><br />';
	echo "<a href='./login.php'>立即登陆</a>";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2;URL='./login.php' \">";
	echo "</div>";
	die();
}
switch($_SESSION['user']['type'])
{
	case 'stu':
		{
		        echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL='./stu/admin.php' \">";
		};break;
		
		case 'tea':
			{
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL='./tea/admin.php' \">";
			};break;
			
		case 'admin':
			{
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL='./admin/admin.php' \">";
			};break;	
			
		default:break;	
}
?>