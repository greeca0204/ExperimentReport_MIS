<?php
/*名称：学生管理后台 
 * 功能：处理添加班级请求，删除班级，添加学生，修改学生信息，删除学生，换班
 */
?>

<?php
include '../config.php';
include '../is_login_admin.php';

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;

/**
 * 根据action参数的值显示后台页面:add 为添加班级
 * delete_class 删除班级
 * add_class 添加班级
 * add_stu 添加学生
 * update_stu 修改学生信息和密码
 * delete_stu 删除学生
 * change_class 换班级
 */

switch ($action) {
	// 添加班级，插入数据库
	case 'add_class' :
		{
			$class_no = trim ( $_REQUEST ['class_no'] );
			$grade = trim ( $_REQUEST ['grade'] );
			$start_no = trim ( $_REQUEST ['start_no'] );
			$end_no = trim ( $_REQUEST ['end_no'] );
			
			// 记录操作数据库次数
			$count_sucess = 0;
			$count_fail = 0;
			
			// 检测学号位数
			$len_sno = strlen ( $start_no );
			if ($len_sno != strlen ( $end_no )) {
				die ( "开始学号与结束学号位数不一致" );
			}
			if ($len_sno > 10) {
				die ( "学号大于10位" );
			}
			// 检测班级号
			if ($class_no == NULL) {
				die ( "班级号为空" );
			}
			$queryStr = sprintf ( "select count(distinct class_no) from stu where class_no='%s'", $class_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if ($rel = mysql_fetch_array ( $result )) {
				if ($rel [0] != 0) {
					mysql_close ();
					die ( "班级号已存在" );
				}
			}
			// 给班级批量添加学生
			
			echo '<table><tr>';
			echo '<td>学生学号</td>';
			echo '<td>添加状态(成功/失败)</td>';
			echo '</tr>';
			
			for($i = $start_no; $i <= $end_no; $i ++) {
				$temp_sno = 100000000000 + $i;
				$temp_sno = substr ( $temp_sno, - 1 * $len_sno );
				$psw = substr ( $temp_sno, - 6 );
				$psw = md5 ( base64_encode ( $psw ) );
				
				$queryStr = sprintf ( "insert into stu values('%s','%s',NULL,NULL,'1',NULL,'%s','%s')", $temp_sno, $psw, $class_no, $grade );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				
				// 添加学生成功
				if ($result == TRUE && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $temp_sno;
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				// 添加学生失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $temp_sno;
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个学生添加成功<br />";
			echo "{$count_fail }个学生添加失败<br />";
			mysql_close ();
		}
		;
		break;
	
	// 添加学生，对数据库的操作
	case 'add_stu' :
		{
			$class_no = $_REQUEST ['class_no'];
			$start_no = $_REQUEST ['start_no'];
			$grade = $_REQUEST ['grade'];
			
			// 统计相应结果次数
			$count_sucess = 0;
			$count_fail = 0;
			
			// 若设置了结束学号
			if (isset ( $_REQUEST ['end_no'] ) && $_REQUEST ['end_no'] != NULL) {
				$end_no = $_REQUEST ['end_no'];
			} else {
				$end_no = $start_no;
			}
			
			// 检测学号位数
			$len_sno = strlen ( $start_no );
			if ($len_sno != strlen ( $end_no )) {
				die ( "开始学号与结束学号位数不一致" );
			}
			if ($len_sno > 10) {
				die ( "学号大于10位" );
			}
			
			// 给班级批量添加学生
			echo "向班级 : {$class_no} 添加学生";
			echo '<table><tr>';
			echo '<td>学生学号</td>';
			echo '<td>添加状态(成功/失败)</td>';
			echo '</tr>';
			
			for($i = $start_no; $i <= $end_no; $i ++) {
				$temp_sno = 10000000000 + $i;
				$temp_sno = substr ( $temp_sno, - 1 * $len_sno );
				$psw = substr ( $temp_sno, - 6 );
				$psw = md5 ( base64_encode ( $psw ) );
				
				// 向数据库添加学生
				$queryStr = sprintf ( "insert into stu values('%s','%s',NULL,NULL,'1',NULL,'%s','%s')", $temp_sno, $psw, $class_no, $grade );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				
				// 添加学生成功
				if ($result == TRUE && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $temp_sno;
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				// 添加学生失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $temp_sno;
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个学生添加成功<br />";
			echo "{$count_fail }个学生添加失败<br />";
			mysql_close ();
		}
		;
		break;
	
	// 修改学生信息，后台
	case 'update_stu' :
		{
			$stu_no = trim ( $_REQUEST ['stu_no'] );
			$name = trim ( $_REQUEST ['name'] );
			$mail = trim ( $_REQUEST ['mail'] );
			$mobile = trim ( $_REQUEST ['mobile'] );
			$psw = trim ( $_REQUEST ['psw'] );
			
			if (NULL != $psw) {
				$psw = md5 ( base64_encode ( $psw ) );
				$queryStr = sprintf ( "update  stu set name='%s',mail='%s',mobile='%s',psw='%s' where stu_no='%s'", $name, $mail, $mobile, $psw, $stu_no );
			} else {
				$queryStr = sprintf ( "update  stu set name='%s',mail='%s',mobile='%s' where stu_no='%s'", $name, $mail, $mobile, $stu_no );
			}
			
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			if ($result == TRUE && 1 == mysql_affected_rows ()) {
				echo "修改成功";
			} else {
				echo '修改失败';
			}
			mysql_close ();
		}
		;
		break;
	// 删除班级，对数据库的操作
	case 'delete_class' :
		{
			$class_nums = $_REQUEST ['class_no'];
			$count_sucess = 0;
			$count_fail = 0;
			
			echo '<table><tr>';
			echo '<td>删除班级</td>';
			echo '<td>状态(成功/失败)</td>';
			echo '</tr>';
			for($i = 0; $i < count ( $class_nums ); $i ++) {
				$queryStr = sprintf ( "select  count(stu_no)  from stu where class_no='%s'", $class_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				$rel = mysql_fetch_array ( $result );
				$count_stu = $rel [0];
				
				$queryStr = sprintf ( "delete from stu where class_no='%s'", $class_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				
				// 删除成功
				if ($result != NULL && $count_stu == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $class_nums [$i];
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				

				// 删除失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $class_nums [$i];
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个班级成功删除<br />";
			echo "{$count_fail }个班级删除失败<br />";
			
			mysql_close ();
		}
		;
		break;
	
	// 删除学生，对数据的后天操作
	case 'delete_stu' :
		{
			$stu_nums = $_REQUEST ['stu_no'];
			$count_sucess = 0;
			$count_fail = 0;
			
			echo '<table><tr>';
			echo '<td>学生号</td>';
			echo '<td>状态(成功/失败)</td>';
			echo '</tr>';
			
			for($i = 0; $i < count ( $stu_nums ); $i ++) {
				$queryStr = sprintf ( "delete from stu where stu_no='%s'", $stu_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				
				// 删除成功
				if ($result != NULL && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $stu_nums [$i];
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				

				// 删除失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $stu_nums [$i];
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个学生成功删除<br />";
			echo "{$count_fail }个学生删除失败<br />";
			
			mysql_close ();
		}
		;
		break;
	
	// 改变学生班级，对数据库的操作
	case 'change_class' :
		{
			$stu_nums = $_REQUEST ['stu_no'];
			$old_class = $_REQUEST ['old_class'];
			$new_class = $_REQUEST ['new_class'];
			$count_sucess = 0;
			$count_fail = 0;
			
			// die($old_class);
			
			echo '把原班级为: ', $old_class, ' 的以下学生移动到新班级: ', $new_class;
			echo '<br />';
			
			echo '<table><tr>';
			echo '<td>学生号</td>';
			echo '<td>状态(成功/失败)</td>';
			echo '</tr>';
			
			for($i = 0; $i < count ( $stu_nums ); $i ++) {
				$queryStr = sprintf ( "update stu set class_no='%s' where stu_no='%s'", $new_class, $stu_nums [$i] );
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败: " . mysql_error () );
				
				// 改变班级成功
				if ($result != FALSE && 1 == mysql_affected_rows ()) {
					$count_sucess ++;
					echo "<tr><td>";
					echo $stu_nums [$i];
					echo "</td><td>";
					echo '成功';
					echo "</td></tr>";
				} 				

				// 改变失败
				else {
					$count_fail ++;
					echo "<tr><td>";
					echo $stu_nums [$i];
					echo "</td><td>";
					echo '失败';
					echo "</td></tr>";
				}
			}
			
			echo '</table>';
			echo '<br />';
			echo "{$count_sucess }个学生成功改变班级<br />";
			echo "{$count_fail }个学生改变班级失败<br />";
			
			mysql_close ();
		}
		;
		break;
	default :
		break;
}
?>