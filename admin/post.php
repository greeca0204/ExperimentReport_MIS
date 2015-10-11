<?php 
/*名称：帖子管理前台
 * 功能：帖子列表，回复列表，修改帖子，分类列表，修改分类
 */
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../config.php';
include '../is_login_admin.php';

$now = date ( "Y-m-d G:i:s" );
//$user = '123';

/**
 * 根据action参数的值，进行不同的前台处理：post_manage管理帖子，post_delete删除帖子
 * reply_manage管理回复，post_update更新帖子，post_sort分类管理，sort_add添加分类，sort_update分类更新
 */

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
switch ($action)
{
	//帖子管理,显示帖子列表
	case 'post_manage':
		{
			echo '帖子列表:<br />';
			// 分页中每一页的条目数量
			$page_size = 10;
			
			// 获取页码
			$page = isset ( $_REQUEST ['page'] )?intval($_REQUEST ['page']):1;
			
			// 获取主题总数
			$queryStr = sprintf ( "select  count(post_id)  from topic" );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel = mysql_fetch_array ( $result );
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
			
			//显示班级学生
			echo "<form method='post' action='./post.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>分类</td>';
			echo '<td>标题</td>';
			echo '<td>作者</td>';
			echo '<td>发表时间</td>';
		//	echo '<td>内容摘要</td>';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  post_id,sort,title,author,body,post_time,last_reply_time,last_floor,name  from topic,sort where visible='0' and topic.sort=sort.id  order by post_time desc limit %s,%s", $offset, $page_size );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			
			if ($rel = mysql_fetch_array ( $result )) 
			{
				do
				{
					echo '<tr>';
					echo '<td>', "<input type=checkbox name = post_id[] value={$rel['post_id']}>", '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo "<td><a href=../bbs.php?topic={$rel['post_id']}  target='_blank'>";
					echo mb_substr($rel ['title'],0,20,'utf-8');
					echo "</a></td>";
					echo '<td>', $rel ["author"], '</td>';
					echo '<td>', $rel ["post_time"], '</td>';
				}while ( $rel = mysql_fetch_array ( $result ) );
			}else 
		   {
			 	echo '暂时还没有帖子';
	        }
	        echo '</table>';
	        // 分页导航
	        echo '<br />';
	        if ($page > 1) {
	        	echo "<a href=./post.php?action=post_manage&page=1>首页</a>";
	        	echo "<a href=./post.php?action=post_manage&page={$prev}>上一页</a>";
	        }
	        if ($page < $pages) {
	        echo "<a href=./post.php?action=post_manage&page={$next}>下一页</a>";
	        echo "<a href=./post.php?action=post_manage&page={$pages}>尾页</a>";
	        }
	        echo "共有{$pages}页 ({$page}/{$pages})";

	        //功能导航
	        echo '<table>';
	        echo '<br />';
	        echo '<tr><td>';
	        echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
	        echo '<td>', '<input type=button class=btn  value=管理回帖>', '</input>', '</td>';
	        echo '<td>', '<input type=button class=btn  value=修改>', '</input>', '</td>';
	        echo '<td>', '<input type=button class=btn  value=删除>', '</input>', '</td>';
	        echo '<td>', '<input type=button class=btn  value=隐藏>', '</input>', '</td>';
	        echo '</tr></table>';
	        echo '</form>';        
		};break;
		
		//删除帖子，伪前台
	case 'post_delete':
		{
			if (!isset($_REQUEST['post_id'] ))
			{
				die("请先选择帖子");
			}
			$ids =$_REQUEST['post_id'];
			$url = "./post_deal.php?action=post_delete";
			for($i = 0 ; $i < count($ids) ; $i++)
			{
				$url =$url .  "&post_id[{$i}]=" . $ids[$i];		
			}
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";	
		};break;
		
		//修改帖子，前台
	case 'post_update':
		{
			if (!isset($_REQUEST['post_id'] ))
			{
				die("请先选择帖子");
			}
			$ids = $_REQUEST['post_id'];
			if (count ( $ids ) > 1) {
				die ( "请只选择一个帖子" );
			}
			$post_id = $ids[0];
			
			$queryStr = sprintf ( "select  *  from topic where  post_id='%s' ", $post_id );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
		//	mysql_close ();
			
			if ($rel = mysql_fetch_array ( $result ))
			{
				echo "<form method='post' action='./post_deal.php?action=post_update'>";
				echo "标题";
				echo "<input name=title style='width:400px;' value={$rel['title']}></input>";
				echo "楼主:{$rel['author']}";
				echo '<br />';
				echo '帖子内容:';
				echo '<br />';
				echo "<textarea name=body style='width:700;height:500;'>{$rel['body']}</textarea>";
				echo '<br /><br />';
				
				echo "<input  type=hidden name=post_id value={$post_id}></input>";
				echo "<input  type=hidden name=action value=post_update></input>";
				echo "<input class=button type=submit value=更新该贴></input>";
				echo '</form>';
			}
			else 
			{
				echo '找不到该贴！';
			}
		};break;
		
		//管理回复
	case 'reply_manage':
		{
			if (!isset($_REQUEST['post_id'] ))
			{
				die("请先选择帖子");
			}
			$ids = $_REQUEST['post_id'];
			if (count ( $ids ) > 1) {
				die ( "请只选择一个帖子" );
			}
			$post_id = $ids[0];
			
			// 分页中每一页的条目数量
			$page_size = 10;
			
			// 获取页码
			$reply_page = isset ( $_REQUEST ['reply_page'] )?intval($_REQUEST ['reply_page']):1;
			
			// 获取回帖总数
			$queryStr = sprintf ( "select  count(reply_id)  from reply where post_id='%s'",$post_id );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel = mysql_fetch_array ( $result );
			$numrows = $rel [0];
			
			if ( 0 == $numrows )
			{
				mysql_close();
				die('该贴还没回复帖');
			}
			
			// 总页数
			$reply_pages = intval ( $numrows / $page_size );
			if ($numrows % $page_size) {
				$reply_pages ++;
			}
			// 前一页和后一页
			$prev = $reply_page - 1;
			$next = $reply_page + 1;
			// 计算记录偏移量
			$offset = $page_size * ($reply_page - 1);
			
			//显示回复贴
			echo '主题帖id:',$post_id;
			echo '<br />';
			echo "<form method='post' action='./post.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>楼层</td>';
			echo '<td>发表人</td>';
			echo '<td>发表时间</td>';
			echo '<td>内容摘要</td>';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from reply where post_id='%s'  order by floor asc limit %s,%s",$post_id,$offset, $page_size);
			$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			while($rel2 = mysql_fetch_array ( $result2 ))
			{
				echo '<tr>';
				echo '<td>', "<input type=checkbox name = reply_id[] value={$rel2['reply_id']}>", '</td>';
				echo '<td>', $rel2 ["floor"], '</td>';
				echo '<td>', $rel2 ["author"], '</td>';
				echo '<td>', $rel2 ["reply_time"], '</td>';
				$str_body=$rel2 ["body"];
				if (strlen($str_body) > 50)
				{
					$str_body = mb_substr($str_body, 0,20,'utf-8') . "……";
				}
				echo '<td>', $str_body, '</td>';
				echo '</tr>';
			}
			echo '</table>';
			// 分页导航
			echo '<br />';
			if ($reply_page > 1) {
				echo "<a href=./post.php?action=reply_manage&post_id={$post_id}&reply_page=1>首页</a>";
				echo "<a href=./post.php?action=reply_manage&post_id={$post_id}&reply_page={$prev}>上一页</a>";
			}
			if ($reply_page < $reply_pages) {
			echo "<a href=./post.php?action=reply_manage&post_id={$post_id}&reply_page={$next}>下一页</a>";
			echo "<a href=./post.php?action=reply_manage&post_id={$post_id}&reply_page={$reply_pages}>尾页</a>";
			}
			echo "共有{$reply_pages}页 ({$reply_page}/{$reply_pages})";
			
			//功能导航
			echo '<table>';
			echo '<br />';
			echo '<tr><td>';
			echo '<td>', "<input name=post_id value={$post_id} type=hidden></input>", '</td>';
			echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
	//		echo '<td>', '<input type=button class=btn  value=修改>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=删除回帖>', '</input>', '</td>';
			echo '</tr></table>';
			echo '</form>';
			
		};break;
		
		//删除回复贴，伪前台
		case 'reply_delete':
			{
				if (!isset($_REQUEST['reply_id'] ))
				{
					die("请先选择回复贴");
				}
				$ids =$_REQUEST['reply_id'];
				$url = "./post_deal.php?action=reply_delete&post_id={$_REQUEST['post_id']}";
				for($i = 0 ; $i < count($ids) ; $i++)
				{
				$url =$url .  "&reply_id[{$i}]=" . $ids[$i];
				}
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";
			};break;
		
		
		//帖子分类管理
	case 'post_sort':
		{
		//	echo 'sort';
			//显示分类列表
			echo "<form method='post' action='./post.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>编号</td>';
			echo '<td>分类名</td>';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from sort");
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			while ( $rel = mysql_fetch_array ( $result ) )
			{
				echo '<tr>';
				echo '<td>', "<input type=radio name = id value={$rel['id']}>", '</td>';
				echo '<td>', $rel ["id"], '</td>';
				echo '<td>', $rel ["name"], '</td>';
				echo '</tr>';
			}
			echo '</table>';
			
			//功能导航
			echo '<table>';
			echo '<br />';
			echo '<tr><td>';
			echo '<td>', "<input name=action id=action value='' type=hidden></input>", '</td>';
			echo '<td>', '<input type=button class=btn  value=增加分类>', '</input>', '</td>';
			echo '<td>', '<input type=button class=btn  value=修改分类>', '</input>', '</td>';
			echo '</tr></table>';
			echo '</form>';			
		};break;
		
	//增加分类
	case 'sort_add':
		{
			echo '添加新分类:<br /><br />';
			echo "<form method='post' action='./post_deal.php?'>";
			echo "分类名:";
			echo "<input name = name></input>";

			echo "<input name = action value=sort_add type=hidden></input>";
			echo "<input class=button type='submit' value='添加'></input>";

			
		};break;
		
		//修改分类，前台
	case 'sort_update':
		{
	//		echo 'b';
			if (!isset($_REQUEST['id'] ))
			{
				die("请先选择分类编号");
			}
			$id = $_REQUEST['id'] ;
		//	echo $id;
		
			$queryStr = sprintf ( "select  *  from sort where  id='%s'", $id );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			//修改分类名
			if ($rel = mysql_fetch_array ( $result ))
			{
				echo "<form method='post' action='./post_deal.php?'>";
				echo "新分类名:";
				echo "<input name = name value={$rel['name']}></input>";
				echo "<input name = id value={$id} type=hidden></input>";
				echo "<input name = action value=sort_update type=hidden></input>";
				echo "<input class=button type='submit' value='提交'></input>";
			}
			else
			{
				echo '找不到该分类编号';
			}	
		};break;	
	default:break;
}
?>

<!-- 绑定导航条点击事件 -->
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
          var frm1 = $('form');  
        switch(e.target.value)
          {
          case '管理回帖':
              $('#action').attr('value',"reply_manage");break;
          case '修改':
              $('#action').attr('value',"post_update");break;
          case '隐藏':
              $('#action').attr('value',"post_hide");break;
          case '增加分类':
              $('#action').attr('value',"sort_add");break;
          case '修改分类':
              $('#action').attr('value',"sort_update");break;
          case '删除':
          {
              var tips = window.confirm("确定要删除?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"post_delete");
          };break;
          case '删除回帖':
          {
              var tips = window.confirm("确定要删除?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"reply_delete");
          };break;
          default:
        	  break;
          }
         frm1.submit();     	
       }
      var start = function() { $(".btn").click( submit_action );   }
      $(start);
</script>