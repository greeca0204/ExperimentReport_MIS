<?php 
/*名称：个人信息管理后台
 * 功能：修改个人信息，修改密码
 */
?>
<?php
include '../config.php';
include '../is_login_tea.php';

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
//echo $action;

/**  根据参数 action 的值进行不同的后台处理：update_info 更新个人信息，
 * update_psw 更新密码
 * 
 */
switch($action)
{
	case 'update_info':
		{
		//	$tea_no = trim($_REQUEST['tea_no']);
			$name = trim($_REQUEST['name']);
			$mail = trim($_REQUEST['mail']);
			$mobile = trim($_REQUEST['mobile']);
			$department = trim($_REQUEST['department']);
			$skill = trim($_REQUEST['skill']);
			
			$queryStr = sprintf("update  tea set name='%s',mail='%s',mobile='%s',department='%s',skill='%s' where tea_no='%s'",$name,$mail,$mobile,$department,$skill,$tea_no);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			if($result == TRUE && 1==mysql_affected_rows())
			{
				echo "修改成功";
			}
			else
			{
				echo '修改失败';
			}
			mysql_close();
			
		};break;
	case 'update_psw':
		{
	//		$tea_no = trim($_REQUEST['tea_no']);
			$old_psw = strtolower(trim($_REQUEST['old_psw']));
			$new_psw1 = strtolower(trim($_REQUEST['new_psw1']));
			$new_psw2= strtolower(trim($_REQUEST['new_psw2']));		
			if($new_psw1 != $new_psw2)
			{
				die("修改失败：两次输入密码不一致");
			}
			if($old_psw === $new_psw1)
			{
				die("修改失败：原密码与新密码一样");
			}
			$old_psw = md5(base64_encode($old_psw));
			$new_psw1 = md5(base64_encode($new_psw1));
			
			$queryStr = sprintf("update  tea set psw='%s' where tea_no='%s' and psw='%s'",$new_psw1,$tea_no,$old_psw);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			
			if($result!=NULL && 1==mysql_affected_rows())
			{
				echo "修改成功";
			}
			else
			{
				echo "修改失败";
			}
			mysql_close();
						
		}break;
	default:
		echo "未知传值";
		break;
}
?>