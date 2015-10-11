<?php 
/*名称：成绩查看
 * 功能：查看成绩单
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
$today = date("Y-m-d");
//echo $today;
//$stu_no = "stu2";

echo '我的实验成绩:<br /><br />';
echo '<table class=table_border>';
echo '<tr class="first">';
echo '<td>课号</td>';
echo '<td>课程名称</td>';
echo '<td>课程批次</td>';
echo '<td>实验教师</td>';
echo '<td>实验报告分</td>';
echo '<td>平时分</td>';
echo '<td>考试分</td>';
echo '<td>综合成绩</td>';
echo '</tr>';

$queryStr = sprintf("select * from course,sel_cor,tea where sel_cor.stu_no='%s' and sel_cor.cor_no=course.cor_no and tea.tea_no=course.tea_no and sel_cor.status='1'",$stu_no);
$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());

//存在已选课程
if($rel = mysql_fetch_array($result))
{
	do
	{
		echo '<tr>';
		echo '<td>', $rel ["cor_no"], '</td>';
		echo '<td>', $rel ["cor_name"], '</td>';
		echo '<td>', $rel ["groups"], '</td>';
		echo '<td>', $rel ["name"], '</td>';
		echo '<td>', $rel ["report_mark"], '</td>';
		echo '<td>', $rel ["usual_mark"], '</td>';
		echo '<td>', $rel ["exam_mark"], '</td>';
		//判断成绩的优良
		if ($rel ['mark'] == NULL)
		{
			$str = NULL;
		}
		else 
		{
			switch(floor($rel ['mark'] / 10))
			{
				case '10':$str = '优';break;
				case '9':$str = '优';break;
				case '8':$str = '良';break;
				case '7':$str = '中';break;
				case '6':$str = '合格';break;
				case '5':$str = '不合格';break;
				case '4':$str = '不合格';break;
				case '2':$str = '不合格';break;
				case '1':$str = '不合格';break;
				case '0':$str = '不合格';break;
		//		default:$str = '未知';break;
			}	
		}
		echo "<td>{$str}</td>";
		echo '</tr>';
	}
	while ($rel = mysql_fetch_array($result));		
}
else
{
	echo '还没有已经被审核通过的课程';
}
?>