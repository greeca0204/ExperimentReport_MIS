<?php session_start ();?>
<?php
/*
 * 名称：登陆模块 功能： 根据 url 或表单中的action参数进行相应处理 signup：注册 ,暂无此功能 login：
 * 接收登陆界面的表单，进行登陆处理 logout:退出处理 default:显示用户登陆界面
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="./static/css/global.css" />
<script type="text/javascript" src="./static/jquery/jquery-1.8.3.js"></script>
</head>
<div
	style="background-image: url(./static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
<?php
include './config.php';

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;

switch ($action) {
	case 'signup' :
		{
			$user = trim ( $_POST ['user'] );
			$pass1 = trim ( $_POST ['pass1'] );
			$pass1 = trim ( $_POST ['pass1'] );
			$email = trim ( $_POST ['mail'] );
			echo $user, '<br />';
			echo 'signup';
		}
		;
		break;
	
	// 登陆，用户名判断
	case 'login' :
		{
			// session_start ();
			$authcode = strtolower ( $_SESSION ['authcode'] );
			
			$img_code = strtolower ( trim ( $_REQUEST ['img_code'] ) );
			
			// 若验证码不对
			if ($authcode != $img_code) {
				echo "<div class='center' style='width:720px;'>";
				echo '验证码不正确，2秒后自动返回登陆页面<br /><br/>';
				echo "<a href='./login.php'>立即跳转</a>";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2;URL='./login.php' \">";
				echo "<div>";
				die ();
			}
			
			if (! isset ( $_REQUEST ['user'] ) || empty ( $_REQUEST ['user'] )) {
				echo "<div class='center' style='width:720px;'>";
				echo "<div>";
				die ( '请输入用户' );
			}
			if (! isset ( $_REQUEST ['psw'] ) || empty ( $_REQUEST ['psw'] )) {
				echo "<div class='center' style='width:720px;'>";
				echo "<div>";
				die ( '请输入密码' );
			}
			$user = trim ( $_REQUEST ['user'] );
			$user = addslashes ( $user );
			$psw = trim ( $_REQUEST ['psw'] );
			$identify = $_REQUEST ['identify'];
			$psw = md5 ( base64_encode ( $psw ) );
			
			// 根据用户身份确定数据库用户表
			switch ($identify) {
				case 'stu' :
					$table = 'stu';
					$table_field = 'stu_no';
					break;
				case 'tea' :
					$table = 'tea';
					$table_field = 'tea_no';
					break;
				case 'admin' :
					$table = 'admin';
					$table_field = 'user';
					break;
			}
			
			$queryStr = sprintf ( "select * from %s where %s='%s' and psw='%s'", $table, $table_field, $user, $psw );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ( $conn );
			if ($rel = mysql_fetch_array ( $result )) {
				$a = array ();
				$a ['type'] = $identify;
				$a ['id'] = $user;
				$_SESSION ['user'] = $a;
				
				$url_return = "./index.php";
				if (isset ( $_REQUEST ['url_return'] ) && NULL != $_REQUEST ['url_return']) {
					$url_return = $_REQUEST ['url_return'];
				}
				
				// 释放验证码
				unset ( $_SESSION ['authcode'] );
				echo "<div class='center' style='width:720px;'>";
				echo '登陆正确,2秒后跳转回原页面<br /><br/>';
				echo "<a href='{$url_return}'>立即跳转</a>";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2;URL='{$url_return}' \">";
				echo "</div>";
			} else {
				echo "<div class='center' style='width:720px;'>";
				echo '密码不正确，2秒后自动返回登陆页面<br /><br/>';
				echo "<a href='./login.php'>立即跳转</a>";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2;URL='./login.php' \">";
				echo "</div>";
			}
		}
		;
		break;
	
	// 退出处理
	case 'logout' :
		{
			unset ( $_SESSION ['user'] );
			echo "<div class='center' style='width:720px;'>";
			echo '你已经退出登陆了,2秒后自动返回首页<br /><br/>';
			echo "<a href='./index.php'>立即跳转</a>";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2;URL='./index.php' \">";
			echo "</div>";
		}
		;
		break;
	
	// 默认为登陆界面
	default :
		{
			// session_start();
			$url_return = "./index.php";
			if (isset ( $_SERVER ["HTTP_REFERER"] ) && NULL != $_SERVER ["HTTP_REFERER"]) {
				$url_return = $_SERVER ["HTTP_REFERER"];
			}
			if (isset ( $_SESSION ['user'] ) && NULL != $_SESSION ['user']) {
				echo "<div class='center' style='width:720px;'>";
				echo '你已经登陆过了,2秒后自动跳转回原页面<br />';
				echo "<a href='{$url_return}'>立即跳转</a>";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2;URL='{$url_return}' \">";
				echo "<div>";
				die ();
			}
			echo "<div class='center' style='width:720px;'>";
			echo '<a href=./index.php>回到首页</a><br/><br />';
			echo "<form action='./login.php' method='post'>";
			echo '<table>';
			echo '<tr>';
			echo "<td>用户名</td>";
			echo "<td><input name=user></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo "<td>密码</td>";
			echo "<td><input type='password' name=psw></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo "<td>验证码</td>";
			echo "<td><input  name=img_code id=img_code></input></td>";
			echo "<td><img id='imgCode' src='./authcode.php'></td>";
			echo "<td><a id='fresh' href='#'>刷新</a></td>";
			echo "<td><span id=tip></span></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>选择身份</td>';
			echo "<td><select name='identify'><option value='stu'>学生</option><option value='tea'>教师</option><option value='admin'>管理员</option></select></td>";
			echo '</tr>';
			
			echo "<tr><td><input type='hidden' name=action value='login' ></td></tr>";
			echo "<tr><td><input type='hidden' name=url_return value='{$url_return}' ></td></tr>";
			echo '<tr>';
			echo "<td><input type='submit' value='登录' class='button'></td>";
			echo "<td><input type='reset' value='重置' class='button'></td>";
			echo '</tr>';
			echo '</table>';
			echo '</form>';
			echo "</div>";
		}
		;
		break;
}
?>

<!-- 点击刷新验证码 -->
<script type="text/javascript">
//刷新事件
var fresh=$("#fresh");
fresh.click(function (){
$('#imgCode').attr('src','./authcode.php?time='+Math.random());
$('#tip').text('');
$('#img_code').val('');
});

//ajax判断验证码
$('#img_code').bind("blur",
	function(){
	//若验证码输入框不为空
	if($('#img_code').val() != '')
	{
	       $.ajax({
			type:"post",
			url:"./check_img_code.php?time="+Math.random(),
			data:"code="+$('#img_code').val(),
			success:function(data){
				if(data  =="T")
				{
					$('#tip').css("color","blue");
					$('#tip').text('验证码正确');
				}
				else
				{
					$('#tip').css("color","red");
					$('#tip').text('验证码错误');
				}
			}
	        });
    }
    //若验证码输入框为空
	else
	{
		$('#tip').text('');
	}
}
);
</script>