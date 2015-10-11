<?php session_start ();?>
<?php 
/*名称：ajax判断验证码后台
 * 
 */
?>
<?php
//判断验证码
$authcode = strtolower ( $_SESSION ['authcode'] );
$code = isset($_REQUEST['code'])?$_REQUEST['code']:NULL;
$code = strtolower($code);
if ($authcode == $code)
{
	echo "T";
}
else 
{
	echo "F";
}
//echo $authcode;
?>