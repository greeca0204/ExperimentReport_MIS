<?php
/* 名称：学期选择 
 * 功能：在课程列表之前，先进行学期选择
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_tea.php';
// $tea_no = "tea";

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;

// 根据action 设置表单的action值
switch ($action) {
	
	// 跳转到 显示实验内容 的页面
	case 'show_item' :
		{
			$tips = '实验内容管理';
			$url = "./item.php?tea_no={$tea_no}";
		}
		;
		break;
	
	case 'show_group' :
		{
			$tips = '实验批次管理';
			$url = "./group.php?tea_no={$tea_no}";
		}
		;
		break;
	
	default :
		break;
}

echo $tips;
echo '<br />';
echo "<form method='post' action={$url}>";
echo "学期<select name='term'>";
echo "<option value='all'>所有</option>";
$queryStr = sprintf ( "select  distinct term  from course where tea_no='%s' order by term desc", $tea_no );
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
while ( $rel = mysql_fetch_array ( $result ) ) {
	echo "<option value='{$rel['term']}'>{$rel['term']}</option>";
}

echo '</select>';
echo "<input class=button type='submit' value='提交'></input>";
echo '</form>';

mysql_close ();
?>