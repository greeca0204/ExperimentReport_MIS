<?php
/* 名称：学生管理前台 
 * 功能：显示班级中的学生列表，添加学生界面，删除班级，删除学生，换班，修改学生信息
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
// echo $action;

/**
 * 根据action参数的值显示前台页面:
 * select_class 显示班级的学生列表
 * add_class 添加新班级
 * delete_class 删除班级
 * select 显示班级
 * add_stu 添加学生
 * update_stu 更新学生信息
 * delete_stu 删除学生
 */

switch ($action) {
	case 'add_class' :
		{
			echo "<form method='post' action='./class_deal.php?action=add_class'>";
			echo '<table>';
			echo '<tr>';
			echo "<td>班级号</td>";
			echo "<td><input name=class_no></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo "<td>入学年份</td>";
			echo "<td><input name=grade></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo "<td>开始学号(最多10位)</td>";
			echo "<td><input name=start_no></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo "<td>结束学号(最多10位)</td>";
			echo "<td><input name=end_no></input></td>";
			echo '</tr>';
			echo '<tr>';
			
			echo "<td><input class=button type=submit value=新添></input></td>";
			echo "<td><input class=button type=reset value=重置></input></td>";
			echo '</tr>';
			
			echo '</table>';
			echo '</form>';
			echo '学生初始密码为学号后6位';
		}
		;
		break;
	
	// 添加学生到班级，由 'select_class' 发来的请求
	case 'add_stu' :
		{
			if (! isset ( $_REQUEST ['class_no'] )) {
				die ( "参数缺少班级号" );
			}
			$class_no = $_REQUEST ['class_no'];
			
			echo "<form method='post' action='./class_deal.php?action=add_stu&class_no={$class_no}'>";
			echo '<table>';
			echo '<tr>';
			echo '<td>开始学号</td>';
			echo '<td><input name=start_no ></input></td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>结束学号</td>';
			echo '<td><input name=end_no >若只添加一个学生，则此处留空</input></td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>入学年份</td>';
			echo '<td><input name=grade ></input></td>';
			echo '</tr>';
			
			echo '<tr>';
			echo "<td><input class=button type='submit' value='提交'></input></td>";
			echo "<td><input class=button type='reset' value='重置'></input></td>";
			echo '</tr>';
		}
		;
		break;
	
	// 删除班级，来自 class_select.php 的请求
	case 'delete_class' :
		{
			if (! isset ( $_REQUEST ['class_no'] )) {
				die ( "请先选择班级" );
			}
			$class_nums = $_REQUEST ['class_no'];
			$url = "./class_deal.php?action=delete_class";
			for($i = 0; $i < count ( $class_nums ); $i ++) {
				$url = $url . "&class_no[{$i}]=" . $class_nums [$i];
			}
			echo $url;
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";
		}
		;
		break;
	
	// 删除学生，来自 select_class 的请求
	case 'delete_stu' :
		{
			if (! isset ( $_REQUEST ['stu_no'] )) {
				die ( "请先选择学生" );
			}
			
			$stu_nums = $_REQUEST ['stu_no'];
			$url = "./class_deal.php?action=delete_stu";
			for($i = 0; $i < count ( $stu_nums ); $i ++) {
				$url = $url . "&stu_no[{$i}]=" . $stu_nums [$i];
			}
			echo $url;
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";
		}
		;
		break;
	
	// 改变学生班级，来自 select_class 的请求
	case 'change_class' :
		{
			if (! isset ( $_REQUEST ['stu_no'] )) {
				die ( "请先选择学生" );
			}
			$old_class = $_REQUEST ['class_no'];
			$stu_nums = $_REQUEST ['stu_no'];
			
			// 得到学生学号
			$url = "./class_deal.php?action=change_class";
			for($i = 0; $i < count ( $stu_nums ); $i ++) {
				$url = $url . "&stu_no[{$i}]=" . $stu_nums [$i];
			}
			// echo '这是用来测试的:';
			// echo $url;
			
			// 访问数据库，选出班级号
			$queryStr = sprintf ( "select  distinct class_no from stu where class_no <>'%s' order by class_no desc", $old_class );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			echo "<form method='post' action={$url}>";
			echo '选择新班级:';
			echo '<select name="new_class">';
			
			while ( $rel = mysql_fetch_array ( $result ) ) {
				echo "<option value='{$rel['class_no']}'>{$rel['class_no']}</option>";
			}
			echo '</select>';
			echo "<input type='hidden' name=old_class value={$old_class}></input>";
			echo "<input class=button type='submit' value='改变班级'></input>";
			echo '</form>';
		}
		;
		break;
	
	// 修改学生信息，前台
	case 'update_stu' :
		{
			if (! isset ( $_REQUEST ['stu_no'] )) {
				die ( "请先选择学生" );
			}
			$stu_nums = $_REQUEST ['stu_no'];
			if (count ( $stu_nums ) > 1) {
				die ( "请只选择一个学生" );
			}
			$stu_no = $stu_nums [0];
			$queryStr = sprintf ( "select  *  from stu where  stu_no='%s' ", $stu_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			
			if ($rel = mysql_fetch_array ( $result )) {
				echo '修改学生信息<br />';
				echo "<form method='post' action='./class_deal.php?action=update_stu'>";
				echo '<table>';
				
				echo '<tr>';
				echo '<td>姓名</td>';
				echo '<td>', "<input name = name value={$rel['name']}></input>", '</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>邮件</td>';
				echo '<td>', "<input type=email name = mail value={$rel['mail']}></input>", '</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>手机</td>';
				echo '<td>', "<input  name = mobile value={$rel['mobile']}></input>", '</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>新密码(留空则不修改)</td>';
				echo '<td>', "<input  name = psw></input>", '</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>', "<input type=hidden name = stu_no value={$stu_no}></input>", '</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo "<td><input class=button type='submit' value='提交'></input></td>";
				echo "<td><input class=button type='reset' value='重置'></input></td>";
				echo '</tr>';
				echo '</table>';
				echo '</form>';
			} else {
				echo '无此学生';
			}
		}
		;
		break;
	
	// 显示某一个班级的学生,有导航:增加，查看，删除，改变班级
	case 'select_class' :
		{
			// 分页中每一页的条目数量
			$page_size = 10;
			
			// 获取页码
			$page = isset ( $_REQUEST ['page'] ) ? intval ( $_REQUEST ['page'] ) : 1;
			
			// 获取班级号
			if (! isset ( $_REQUEST ['class_no'] )) {
				die ( "请先选择一个班级" );
			}
			$class_nums = $_REQUEST ['class_no'];
			if (count ( $class_nums ) > 1) {
				die ( "请只选择一个班级" );
			}
			$class_no = $class_nums [0];
			
			// 获取班级人数
			$queryStr = sprintf ( "select  count(stu_no)  from stu where class_no='%s'", $class_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			// echo $rel[0];
			$numrows = $rel [0];
			
			// 总页数
			$pages = intval ( $numrows / $page_size );
			if ($numrows % $page_size) {
				$pages ++;
			}
			
			// 前一页和后一页
			$prev = $page - 1;
			$next = $page + 1;
			
			// 计算记录偏移量
			$offset = $page_size * ($page - 1);
			$queryStr = sprintf ( "select  *  from stu where class_no='%s' order by stu_no asc limit %s,%s", $class_no, $offset, $page_size );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			
			// 显示班级学生
			echo '班级:', $class_no;
			echo '<br />';
			echo "<form method='post' action='./class.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>学号</td>';
			echo '<td>姓名</td>';
			echo '<td>电子邮件</td>';
			echo '<td>手机号码</td>';
			echo '</tr>';
			
			if ($rel = mysql_fetch_array ( $result )) {
				do {
					echo '<tr>';
					echo '<td>', "<input type=checkbox name = stu_no[] value={$rel['stu_no']}>", '</td>';
					echo '<td>', $rel ["stu_no"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '<td>', $rel ["mail"], '</td>';
					echo '<td>', $rel ["mobile"], '</td>';
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} 			

			// 班级无学生
			else {
				echo '<tr><td>';
				echo "班级还没有学生，请先添加学生";
				echo '</td></tr>';
			}
			echo '</table>';
			
			// 分页导航
			if ($page > 1) {
				echo "<a href=./class.php?action=select_class&class_no[0]={$class_no}&page=1>首页</a>";
				echo "<a href=./class.php?action=select_class&class_no[0]={$class_no}&page={$prev}>上一页</a>";
			}
			if ($page < $pages) {
				echo "<a href=./class.php?action=select_class&class_no[0]={$class_no}&page={$next}>下一页</a>";
				echo "<a href=./class.php?action=select_class&class_no[0]={$class_no}&page={$pages}>尾页</a>";
			}
			echo "共有{$pages}页 ({$page}/{$pages})";
			
			// 功能导航
			echo '<table>';
			echo '<br />';
			echo '<tr><td>';
			
			echo '<td>', "<input name=class_no value={$class_no} type=hidden></input>", '</td>';
			echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
			echo '<td>', '<input type=button class=btn  value=增加>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=修改>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=删除>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=换班>', '</input>', '</td>';
			echo '</tr></table>';
			echo '</form>';
			?>

<!-- 绑定导航条点击事件 -->
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
          var frm1 = $('form');  
        switch(e.target.value)
          {
          case '查看修改':
              $('#action').attr('value',"select_stu");break;
          case '增加':
              $('#action').attr('value',"add_stu");break;
          case '修改':
              $('#action').attr('value',"update_stu");break;
          case '删除':
          {
              var tips = window.confirm("确定要删除?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"delete_stu");
          };break;
          case '换班':
              $('#action').attr('value',"change_class");break;
          default:
        	  break;
          }
         frm1.submit();     	
       }
      var start = function() { $(".btn").click( submit_action );   }
      $(start);	
</script>

<?php
		}
		;
		break;
	default :
		break;
}
?>