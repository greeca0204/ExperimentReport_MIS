<?php
/*名称：教师管理后台 
 * 功能：添加教师，删除教师，改密码
 */
?>
<?php

include '../config.php';
include '../is_login_admin.php';

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;

/**
 * 根据action参数的值显示后台页面:add 为添加教师
 * add 添加教师
 * delete 删除教师
 * change_psw 更改教师密码
 */

switch ($action) {
	// 添加教师，插入数据库
	case 'add' :
		{
			if (! isset ( $_REQUEST ['tea_no'] ) || $_REQUEST ['tea_no'] == NULL || ! isset ( $_REQUEST ['department'] ) || $_REQUEST ['department'] == NULL || ! isset ( $_REQUEST ['name'] ) || $_REQUEST ['name'] == NULL) {
				die ( '输入不完整，请重新输入' );
			}
			
			$tea_no = trim ( $_REQUEST ['tea_no'] );
			$department = trim ( $_REQUEST ['department'] );
			$name = trim ( $_REQUEST ['name'] );
			
			// 检测学号位数
			$len_sno = strlen ( $tea_no );
			if ($len_sno > 10) {
				die ( "教师编号大于10位" );
			}
			
			// 检测教师编号是否存在
			$queryStr = sprintf ( "select count(distinct tea_no) from tea where tea_no='%s'", $tea_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				if ($rel [0] != 0) {
					mysql_close ();
					die ( "教师编号号已存在" );
				}
			}
			// 给数据库添加教师
			
			$psw = substr ( $tea_no, - 6 );
			$psw = md5 ( base64_encode ( $psw ) );
			
			$queryStr = sprintf ( "insert into tea values('%s','%s','%s',NULL,'%s',NULL,NULL)", $tea_no, $psw, $name, $department );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			// 添加教师成功
			if ($result == TRUE && 1 == mysql_affected_rows ()) {
				echo '以下教师添加成功<br />';
				echo "教师编号{$tea_no}<br />";
				echo "姓名{$name}<br />";
				echo "院系{$department}<br />";
			} 			// 添加学生失败
			else {
				echo '以下教师添加失败<br />';
				echo "教师编号{$tea_no}<br />";
				echo "姓名{$name}<br />";
				echo "院系{$department}<br />";
			}
			mysql_close ();
		}
		;
		break;
	
	// 删除教师，对数据的后天操作
	case 'delete' :
		{
			$tea_nums = $_REQUEST ['tea_no'];
			$count_sucess = 0;
			$count_fail = 0;
			
			echo '<table><tr>';
			echo '<td>教师编号</td>';
			echo '<td>状态(成功/失败)</td>';
			echo '</tr>';
			
			for($i = 0; $i < count ( $tea_nums ); $i ++) {
				$queryStr = sprintf ( "delete from tea where tea_no='%s'", $tea_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				
				// 删除成功
				if ($result != NULL && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $tea_nums [$i];
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				

				// 删除失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $tea_nums [$i];
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个教师成功删除<br />";
			echo "{$count_fail }个教师删除失败<br />";
			
			mysql_close ();
		}
		;
		break;
	
	// 改变教师密码，对数据库的操作
	case 'change_psw' :
		{
			if (! isset ( $_REQUEST ['psw1'] ) || $_REQUEST ['psw1'] == NULL || ! isset ( $_REQUEST ['psw2'] ) || $_REQUEST ['psw2'] == NULL) {
				die ( '请输入两次密码' );
			}
			$tea_nums = $_REQUEST ['tea_no'];
			$psw1 = trim ( $_REQUEST ['psw1'] );
			$psw2 = trim ( $_REQUEST ['psw2'] );
			
			if ($psw1 != $psw2) {
				die ( '修改失败:两次密码不一致' );
			}
			$psw = md5 ( base64_encode ( $psw1 ) );
			
			// 统计操作相应结果次数
			$count_sucess = 0;
			$count_fail = 0;
			
			echo '更改教师账号密码';
			echo '<br />';
			
			echo '<table><tr>';
			echo '<td>教师编号</td>';
			echo '<td>状态(成功/失败)</td>';
			echo '</tr>';
			for($i = 0; $i < count ( $tea_nums ); $i ++) {
				$queryStr = sprintf ( "update tea set psw='%s' where tea_no='%s'", $psw, $tea_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				
				// 改变密码成功
				if ($result != NULL && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $tea_nums [$i];
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				

				// 改变失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $tea_nums [$i];
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个教师成功过修改密码<br />";
			echo "{$count_fail }个教师修改密码失败<br />";
			
			mysql_close ();
		}
		;
		break;
	
	default :
		break;
}
?>