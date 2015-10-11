<?php 
/*名称：年级选择
 * 功能：选择年级
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_stu.php';

//$stu_no='stu2';
echo "<form method='post' action='./course.php?action=default'>";
$queryStr = "select  distinct grade  from stu where stu_no='{$stu_no}'";
$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
$rel = mysql_fetch_array($result);
echo "年级<input name='grade'  value={$rel[0]}>";
echo '</input>';

//		echo "<input type='hidden'  name=stu_no value={$stu_no}></input>";
echo "<input class=button type='submit' value='查看'></input>";
echo "</form>";
?>