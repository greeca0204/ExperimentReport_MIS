<?php session_start ();?>

<?php 
/*名称：管理员是否登陆
 * 
 */
?>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<?php
//判断是否登陆
if (!isset($_SESSION['user']) || NULL == $_SESSION['user'])
{
	echo '你还没有登陆，请先登陆<br /><br />';
	echo "<a href='../login.php' target=_black>立即登陆</a>";
	die();
}

if( 'admin' != $_SESSION['user']['type'])
{
	echo '非管理员身份不可登陆<br /><br />';
	echo "<a href='../index.php' target=_black>回到首页</a>";
	die();
}
?>