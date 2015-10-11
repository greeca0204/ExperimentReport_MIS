<?php session_start ();?>
<?php
/*
 * 名称：安装系统 功能：初始化数据库
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="./static/css/global.css" />
<script type="text/javascript" src="./static/jquery/jquery-1.8.3.js"></script>
</head>
<div
	style="background-image: url(./static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
	<div
		style="clear: left; width: 1024px; margin-left: auto; margin-right: auto;">
<?php
// require './config.php';
$step = isset ( $_REQUEST ['step'] ) ? $_REQUEST ['step'] : 1;

switch ($step) {
	// 第1步,数据库信息输入界面
	case '1' :
		{
			?>
<form method="post" action="./install.php?step=2">
	<table>
		<tr>
			<th scope="row"><label>数据库名</label></th>
			<td><input type="text" name="db_name"></input></td>
			<td>将系统安装到那个数据库?</td>
		</tr>

		<tr>
			<th scope="row"><label>用户名</label></th>
			<td><input type="text" name="db_user"></input></td>
			<td>您的数据库用户名</td>
		</tr>

		<tr>
			<th scope="row"><label>密码</label></th>
			<td><input type="password" name="db_psw"></input></td>
			<td>……及其密码</td>
		</tr>

		<tr>
			<th scope="row"><label>数据库主机</label></th>
			<td><input type="text" name="db_host"></input></td>
			<td>请填写数据库地址</td>
		</tr>

		<tr>
			<td><input type="submit" class="button" value="下一步"></input></td>
		</tr>
	</table>
</form>
<?php
		}
		;
		break;
	
	// 第2步，创建数据库表，添加管理员，把数据库信息写到配置文件
	case '2' :
		{
			$db_name = isset ( $_REQUEST ['db_name'] ) ? trim ( $_REQUEST ['db_name'] ) : NULL;
			$db_user = isset ( $_REQUEST ['db_user'] ) ? trim ( $_REQUEST ['db_user'] ) : NULL;
			$db_psw = isset ( $_REQUEST ['db_psw'] ) ? trim ( $_REQUEST ['db_psw'] ) : NULL;
			$db_host = isset ( $_REQUEST ['db_host'] ) ? trim ( $_REQUEST ['db_host'] ) : NULL;
			if (empty ( $db_name ) || empty ( $db_user ) ||  empty ( $db_host )) {
				die ( '请填写所有的数据库信息！' );
			}
			$conn = mysql_connect ( $db_host, $db_user, $db_psw ) or die ( "数据库账号和密码不对" );
			mysql_query ( "create database if not exists`$db_name`  charset utf8", $conn ) or die ( "数据库创建失败" );
			mysql_select_db ( $db_name, $conn );
			// echo mysql_error();
			$sql = array ();
			
			// 管理员表
			$sql ['admin'] = "CREATE TABLE IF NOT EXISTS `admin` (
			`user` varchar(12) NOT NULL,
			`psw` varchar(33) NOT NULL,
			`name` varchar(10) DEFAULT NULL,
			`mail` varchar(50) DEFAULT NULL,
			`mobile` varchar(11) DEFAULT NULL,
			PRIMARY KEY (`user`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
			// 学生表
			$sql ['stu'] = "CREATE TABLE IF NOT EXISTS `stu` (
			`stu_no` varchar(12) NOT NULL,
			`psw` varchar(33) NOT NULL,
			`mail` varchar(50) DEFAULT NULL,
			`name` varchar(10) DEFAULT NULL,
			`statue` tinyint(4) DEFAULT NULL,
			`mobile` varchar(11) DEFAULT NULL,
			`class_no` varchar(12) DEFAULT NULL,
			`grade` varchar(5) DEFAULT NULL,
			PRIMARY KEY (`stu_no`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
			// 教师表
			$sql ['tea'] = "CREATE TABLE IF NOT EXISTS `tea` (
			`tea_no` varchar(12) NOT NULL,
			`psw` varchar(33) NOT NULL,
			`name` varchar(10) DEFAULT NULL,
			`mail` varchar(50) DEFAULT NULL,
			`department` tinyint(4) DEFAULT NULL,
			`skill` varchar(60) DEFAULT NULL,
			`mobile` varchar(11) DEFAULT NULL,
			PRIMARY KEY (`tea_no`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
			// 课程表
			$sql ['course'] = "CREATE TABLE IF NOT EXISTS `course` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`cor_no` varchar(12) NOT NULL,
			`term` varchar(11) NOT NULL DEFAULT '0',
			`tea_no` varchar(12) NOT NULL,
			`cor_name` varchar(50) NOT NULL,
			`usual_rate` tinyint(4) DEFAULT NULL,
			`report_rate` tinyint(4) DEFAULT NULL,
			`exam_rate` tinyint(4) DEFAULT NULL,
			`select_time` date DEFAULT NULL,
			`report_time` date DEFAULT NULL,
			`close_time` date DEFAULT NULL,
			`grade` varchar(5) DEFAULT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `cor_no` (`cor_no`)
			) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;";
			
			// 批次表
			$sql ['groups'] = "CREATE TABLE IF NOT EXISTS `groups` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`groups` varchar(2) DEFAULT NULL,
			`cor_no` varchar(12) DEFAULT NULL,
			`week_start` varchar(2) DEFAULT NULL,
			`week_end` varchar(2) DEFAULT NULL,
			`week_nums` varchar(7) DEFAULT NULL,
			`lesson_seq` char(1) DEFAULT NULL,
			`num` varchar(3) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;";
			
			// 实验项目表
			$sql ['item'] = "CREATE TABLE IF NOT EXISTS `item` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`item_no` varchar(12) NOT NULL,
			`item_name` varchar(80) NOT NULL,
			`body` varchar(1000) NOT NULL,
			`report_format` varchar(1000) NOT NULL,
			`cor_no` varchar(12) NOT NULL,
			`exam_rate` tinyint(4) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;";
			
			// 评语表
			$sql ['remark'] = "CREATE TABLE IF NOT EXISTS `remark` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`tea_no` varchar(12) NOT NULL DEFAULT '',
			`no` varchar(25) DEFAULT NULL,
			`body` varchar(60) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;";
			
			// 实验表格表
			$sql ['report'] = "CREATE TABLE IF NOT EXISTS `report` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`cor_no` varchar(12) DEFAULT NULL,
			`stu_no` varchar(12) NOT NULL,
			`item_no` varchar(12) NOT NULL,
			`date` date DEFAULT NULL,
			`body` varchar(2000) NOT NULL,
			`item_mark` tinyint(4) DEFAULT NULL,
			`remark` varchar(60) DEFAULT NULL,
			`status` tinyint(4) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;";
			
			// 选课表
			$sql ['sel_cor'] = "CREATE TABLE IF NOT EXISTS `sel_cor` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`stu_no` varchar(12) NOT NULL,
			`cor_no` varchar(12) NOT NULL,
			`groups` varchar(2) DEFAULT NULL,
			`status` tinyint(3) DEFAULT NULL,
			`mark` tinyint(4) DEFAULT NULL,
			`usual_mark` tinyint(4) DEFAULT NULL,
			`report_mark` tinyint(4) DEFAULT NULL,
			`exam_mark` tinyint(4) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;";
			
			// 主题表
			$sql ['topic'] = "CREATE TABLE IF NOT EXISTS `topic` (
			`post_id` int(11) NOT NULL AUTO_INCREMENT,
			`sort` varchar(4) DEFAULT NULL,
			`title` varchar(80) NOT NULL,
			`author` varchar(12) DEFAULT NULL,
			`body` varchar(1000) NOT NULL,
			`post_time` datetime DEFAULT NULL,
			`last_floor` int(11) DEFAULT NULL,
			`last_reply_time` datetime DEFAULT NULL,
			`visible` tinyint(4) DEFAULT NULL,
			`enable_post` tinyint(4) DEFAULT NULL,
			`top` tinyint(4) DEFAULT NULL,
			PRIMARY KEY (`post_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;";
			
			// 回复表
			$sql ['reply'] = "CREATE TABLE IF NOT EXISTS `reply` (
			`reply_id` int(11) NOT NULL AUTO_INCREMENT,
			`author` varchar(12) DEFAULT NULL,
			`body` varchar(600) NOT NULL,
			`reply_time` datetime DEFAULT NULL,
			`floor` int(11) DEFAULT NULL,
			`post_id` int(11) DEFAULT NULL,
			PRIMARY KEY (`reply_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;";
			
			// 主题分类表
			$sql ['sort'] = "CREATE TABLE IF NOT EXISTS `sort` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(10) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;";
			
			// $sqlStr = implode("", $sql);
			$count_fail = 0;
			foreach ( $sql as $sqlStr ) {
				$result = mysql_query ( $sqlStr, $conn );
				if (!$result)
				 {
					echo mysql_error ();
					$count_fail ++ ;
					echo '<br />';
				}
			}
			if ($count_fail != 0) die("创建数据库表不成功");
			
			//添加一个管理员
			$psw = md5(base64_encode('admin'));
			$sqlStr = "insert into `admin`(`user`,`psw`,`name`,`mail`,`mobile`) 
			values('admin','$psw','admin','admin@admin.com','admin')";
			mysql_query ( $sqlStr, $conn );	
			if (mysql_affected_rows () != 1) die('创建管理员失败');
		
			//把数据库信息写到配置文件
			$file_name = "./config.php";
			$line = array();
			if (!file_exists($file_name) || !is_writable($file_name))  die("文件不存在或无读写权限");
		/*	$file_handle = fopen($file_name, 'r+');
			while (!feof($file_handle))
			{
				$line[] = fgets($file_handle);
			}
			fclose($file_handle);
			*/
			$line = file($file_name);
			$line = preg_replace("/(?<=define\('DB_NAME', ')(.*)(?='\))/i", $db_name, $line);
			$line = preg_replace("/(?<=define\('DB_USER', ')(.*)(?='\))/i", $db_user, $line);
			$line = preg_replace("/(?<=define\('DB_PASSWORD', ')(.*)(?='\))/i", $db_psw, $line);
			$line = preg_replace("/(?<=define\('DB_HOST', ')(.*)(?='\))/i", $db_host, $line);
			//var_dump($line) ;			
		//	$file_handle = fopen("./config1.php", 'w');
			file_put_contents($file_name, $line);	
			echo '<br /><br />';
			echo '数据库初始化成功';	
			echo '<br />';
			echo '管理员账号是:admin,密码是:admin';
			echo '<br /><br />';
			echo '请删除网站目录中的install.php文件';
			echo '<br />';
			echo '建议首次进入系统后修改密码';
			echo '<br /><br />';
			echo "<a href='./index.php'>转到首页</a>";
		}
		;
		break;
	
	// 默认
	default :
		break;
}
?>
</div>