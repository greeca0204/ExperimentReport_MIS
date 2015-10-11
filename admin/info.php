<?php 
/*名称：管理员端个人信息管理前台
 * 作用：修改个人信息界面
 */
?>
<head>
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_admin.php';

//$user='test';
$user = $_SESSION['user']['id'];
$queryStr = sprintf("select *  from admin where user='%s'",$user);
$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
mysql_close();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;

/**  根据参数 action 的值显示不同的前台页面：update_info 更新个人信息，
 * update_psw 更新密码
 * 
 */
switch($action)
{
	case 'update_info':
		{
			if ($rel = mysql_fetch_array($result))
			{
			echo "<form method='post' action='./info_deal.php?action=update_info'>";
			echo '<table>';

			echo '<tr>';
			echo '<td>姓名</td>';
			echo '<td>', "<input name = name value={$rel['name']}></input>",'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>邮件</td>';
			echo '<td>', "<input type=email name = mail value={$rel['mail']}></input>",'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>手机</td>';
			echo '<td>', "<input  name = mobile value={$rel['mobile']}></input>",'</td>';
			echo '</tr>';	
			
			echo '<tr>';
			echo '<td>', "<input type=hidden name = user value={$user}></input>",'</td>';
			echo '</tr>';

			echo '<tr>';
			echo "<td><input class=button type='submit' value='提交'></input></td>";
			echo "<td><input class=button type='reset' value='重置'></input></td>";
			echo '</tr>';
			echo '</table>';
			echo '</form>';
			}
			else 
			{
				echo "数据库查询失败";
			}	
		};break;
	case 'update_psw':
		{
			echo "<form method='post' action='./info_deal.php?action=update_psw'>";
			echo '<table>';
			
			echo '<tr>';
			echo '<td>原密码</td>';
			echo '<td>', "<input type=password name = old_psw ></input>",'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>新密码</td>';
			echo '<td>', "<input type=password name = new_psw1></input>",'</td>';
			echo '</tr>';	
			
			echo '<tr>';
			echo '<td>再输入一次</td>';
			echo '<td>', "<input type=password name = new_psw2></input>",'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>', "<input type=hidden name = user value={$user}></input>",'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo "<td><input class=button type='submit' value='提交'></input></td>";
			echo "<td><input  class=button type='reset' value='重置'></input></td>";
			echo '</tr>';
			echo '</table>';
			echo '</form>';	
		}break;
	default:
		echo "未知传值";
		break;
}



?>