<?php
/*名称：备份还原模块 
 * 功能：根据action参数，进行备份或者是还原操作
 */
?>

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css">
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_admin.php';

$action = isset ( $_REQUEST ['action'] ) ? $_REQUEST ['action'] : NULL;
switch ($action) {
	case 'backup' :
		{
			// 数据库备份
			$mysql = "set charset utf8;\r\n";
			$q1 = mysql_query ( "show tables" );
			while ( $t = mysql_fetch_array ( $q1 ) ) {
				$table = $t [0];
				$q2 = mysql_query ( "show create table `$table`" );
				$sql = mysql_fetch_array ( $q2 );
				$mysql .= $sql ['Create Table'] . ";\r\n";
				$q3 = mysql_query ( "select * from `$table`" );
				while ( $data = mysql_fetch_assoc ( $q3 ) ) {
					$keys = array_keys ( $data );
					$keys = array_map ( 'addslashes', $keys );
					$keys = join ( '`,`', $keys );
					$keys = "`" . $keys . "`";
					$vals = array_values ( $data );
					$vals = array_map ( 'addslashes', $vals );
					$vals = join ( "','", $vals );
					$vals = "'" . $vals . "'";
					$mysql .= "insert into `$table`($keys) values($vals);\r\n";
				}
			}
			// $filename="../backup/" . date('Ymjgi') . ".sql";
			// //存放路径，默认存放到backup
			$filename = "../backup/" . "sql.sql"; // 存放路径，默认存放到backup
			$fp = fopen ( $filename, 'w' );
			if (FALSE === $fp) {
				die ( '服务器不可写' );
			}
			fputs ( $fp, $mysql );
			fclose ( $fp );
			echo "数据备份成功";
		}
		;
		break;
	case 'restore' :
		{
			/*
			 * $filename = "test20101216923.sql"; $host = "localhost"; // 主机名
			 * $user = "root"; // MYSQL用户名 $password = "123456"; // 密码 $dbname =
			 * "test"; // 在此指定您要恢复的数据库名，不存在则必须先创建,请自已修改数据库名 mysql_connect (
			 * $host, $user, $password ); mysql_select_db ( $dbname );
			 */
			function restore($fname) {
				if (file_exists ( $fname )) {
					$sql_value = "";
					$cg = 0;
					$sb = 0;
					$sqls = file ( $fname );
					foreach ( $sqls as $sql ) {
						$sql_value .= $sql;
					}
					$a = explode ( ";\r\n", $sql_value ); // 根据";\r\n"条件对数据库中分条执行
					$total = count ( $a ) - 1;
					mysql_query ( "set names 'utf8'" );
					for($i = 0; $i < $total; $i ++) {
						mysql_query ( "set names 'utf8'" );
						// 执行命令
						if (mysql_query ( $a [$i] )) {
							$cg += 1;
						} else {
							$sb += 1;
							$sb_command [$sb] = $a [$i];
						}
					}
					echo "操作完毕，共处理 $total 条命令，成功 $cg 条，失败 $sb 条";
					// 显示错误信息
					if ($sb > 0) {
						echo "<hr><br><br>失败命令如下：<br>";
						for($ii = 1; $ii <= $sb; $ii ++) {
							echo "<p><b>第 " . $ii . " 条命令（内容如下）：</b><br>" . $sb_command [$ii] . "</p><br>";
						}
					} // -----------------------------------------------------------
				} else {
					echo "MySQL备份文件不存在，请检查文件路径是否正确！";
				}
			}
			$filename = "sql.sql";
			$mysql_file = "../backup/" . $filename; // 指定要恢复的MySQL备份文件路径,请自已修改此路径
			restore ( $mysql_file ); // 执行MySQL恢复命令
		}
		;
		break;
	
	default :
		break;
}
?>