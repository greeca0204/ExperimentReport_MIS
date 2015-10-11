<?php
/*名称：添加课程前台 
 * 功能：提示输入课程信息
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
include '../config.php';
include '../is_login_admin.php';

$queryStr = "select tea_no,name  from tea";
$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
mysql_close ( $conn );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>添加课程</title>
<link rel="stylesheet" type="text/css" href="../static/css/global.css" />
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<body>

	<div class="center" style="margin-left: auto; margin-right: auto;">
		添加课程

		<form method="post" action="./course_add_deal.php">
			<table>

				<tr>
					<td>课号</td>
					<td><input name="cor_no" type="text"></input></td>
				</tr>

				<tr>
					<td>课程名称</td>
					<td><input name="cor_name" type="text"></input></td>
				</tr>

				<tr>
					<td>学年</td>
					<td><input width="10px" name="term" type="text">(如2013-2)</input></td>
				</tr>

				<tr>
					<td>面向年级</td>
					<td><input width="10px" name="grade" type="text">(如2009)</input></td>
				</tr>

				<tr>
					<td>教师</td>
					<td><select name="tea_no">
					<?php
					while ( $rel = mysql_fetch_array ( $result ) ) {
						$tea_no = $rel ['tea_no'];
						$tea_name = $rel ['name'];
						if ($tea_name == NULL) {
							$option = sprintf ( "<option value=%s>教师编号:%s</option>", $tea_no, $tea_no );
						} else {
							$option = sprintf ( "<option value=%s>%s</option>", $tea_no, $tea_name );
						}
						
						echo $option;
					}
					?>
					</select></td>
				</tr>

				<tr>
					<td>选课最后期限</td>
					<td><input name="select_time" type="text">(如:2013-02-02)</input></td>
				</tr>
				<tr>
					<td>关闭时间</td>
					<td><input name="close_time" type="text">(如:2013-02-02)</input></td>
				</tr>

				<tr>
					<td>平时所长百分比</td>
					<td><input name="usual_rate" type="text">%(如:10)</input></td>
				</tr>

				<tr>
					<td>实验报告所长百分比</td>
					<td><input name="report_rate" type="text">%(如:20)</input></td>
				</tr>

				<tr>
					<td>考试成绩所长百分比</td>
					<td><input name="exam_rate" type="text">%(如:70)</input></td>
				</tr>

				<tr>
					<td><input class=button type="submit" value="添加"></input></td>
					<td><input class=button type="reset" value="重填"></input></td>
				</tr>
			</table>
		</form>

	</div>
</body>
</html>