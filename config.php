<?php
/* 名称：数据库配置信息
 * 
 */
?>

<?php
/** WordPress 数据库的名称 */
define('DB_NAME', 'report');

/** MySQL 数据库用户名 */
define('DB_USER', 'root');

/** MySQL 数据库密码 */
define('DB_PASSWORD', '');

/** MySQL 主机 */
define('DB_HOST', 'localhost');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');


$conn = mysql_connect ( DB_HOST, DB_USER, DB_PASSWORD ) or die ( "连接失败:" . mysql_error () );
mysql_select_db ( DB_NAME, $conn ) or die ( "选择数据库失败" . mysql_error () );
mysql_query ( "SET NAMES 'UTF8'" );
?>