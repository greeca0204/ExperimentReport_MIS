<?php
/* 名称：生成验证码
 * 
 */
?>
<?php
$im = imagecreate(120,30);
$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$yan=NULL;
for($i=0;$i<4;$i++)
{
  $ch=substr($string,rand(0,61),1);
  $yan=$yan . $ch;	
}
session_start();
$_SESSION['authcode']=$yan;

$bg = imagecolorallocate($im, 220, 220, 220);
$black = imagecolorallocate($im, 0, 0, 0);

// prints a black "P" in the top left corner
imagestring($im, 30, 20,10, $yan, $black);

header('Content-type: image/png');
header('charset: utf-8');
imagepng($im);
?> 