<?php 
/*名称：批次管理前台
 * 功能：显示实验批次，修改批次，添加批次，删除批次，显示所有课程
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
//$tea_no = $_REQUEST['tea_no'];

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
/** 根据action参数的值，进行不同处理：select选择课号查看批次，add添加批次，update更新批次
 * 
 */

switch($action)
{
	//根据课号查看实验批次
	case 'select':
		{
			if (!isset($_REQUEST ['cor_no']))
			{
				die('请先选择一门课程');
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			echo '课程编号：',$cor_no;
			echo "<form method='post' action='./group.php?tea_no={$tea_no}'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
					
			echo '<td>实验批次</td>';
			echo '<td>开始时间(周)</td>';
			echo '<td>结束时间(周)</td>';
			echo '<td>上课时间(星期)</td>';
			echo '<td>上课时间(第几大节)</td>';
			echo '<td>容量</td>';
			echo '</tr>';

			$queryStr = sprintf ( "select  *  from groups where  groups.cor_no='%s'", $cor_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ( $rel = mysql_fetch_array ( $result ) ) {
				do{
				echo '<tr>';
				echo '<td>', "<input type=radio name = id value={$rel['id']}>",'</td>';
				echo '<td>', $rel ["groups"], '</td>';
				echo '<td>第', $rel ["week_start"], '周</td>';
				echo '<td>第', $rel ["week_end"], '周</td>';
				
				//判断星期几上课
				$count_str = strlen($rel ["week_nums"]);
				$str = NULL;
				for ($i = 0;$i < $count_str; $i++)
				{
					$str = $str . '星期' . substr($rel['week_nums'], $i,1) . ';';
				}
				
				echo '<td>', $str, '</td>';
				echo '<td>', $rel ["lesson_seq"], '</td>';
				echo '<td>', $rel ["num"], '</td>';
				echo '</tr>';
				}while($rel = mysql_fetch_array ( $result ));
			}
			else 
			{
				echo '<tr><td>';
				echo "该课程暂时还没有添加批次";
				echo '</tr></td>';
			}
			echo '<br />';
			echo '<table><tr>';
			echo '<td>',"<input type=hidden name=cor_no value={$tea_no}>",'<td>';
			echo '<td>',"<input type=hidden name=cor_no value={$cor_no}>",'<td>';
			echo '<td>',"<input name=action id=action value='' type=hidden></input>",'</td>';
			echo '<td>','<input type=button class=btn  value=增加>','</input>','</td>';
			echo '<td>','<input type=button class=btn  value=更新>','</input>','</td>';
			echo '<td>','<input type=button class=btn  value=删除>','</input>','</td>';
			echo '</tr></table>';
			echo '</table>';	
			echo '</form>';
			
		};break;
		
	//修改批次
	case 'update':
		{
			if (!isset($_REQUEST['id']))
			{
				die('请先选择批次');
			}
			$id = $_REQUEST['id'];
			//$item_no=$_GET ['item_no'];
			$queryStr = sprintf ( "select  *  from groups where  id='%s' ", $id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();

			if($rel=mysql_fetch_array($result))
			{
				echo "<form method='post' action='./group_deal.php?action=update&id={$rel['id']}'>";	
				echo '<table>';
				echo '<tr>';
				echo '<td>实验批次:</td>';
				echo "<td><input name='groups' value={$rel['groups']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>开始周:</td>';
				echo "<td><input name='week_start' value={$rel['week_start']}></input></td>";
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>结束周:</td>';
				echo "<td><input name='week_end' value={$rel['week_end']}></input></td>";
				echo '</tr>';
					
				echo '<tr>';
				echo '<td>星期几上课(如周4，周5则填45):</td>';
				echo "<td><input name='week_nums' value={$rel['week_nums']}></input></td>";
				echo '</tr>';
					
				echo '<tr>';
				echo '<td>第几大节:</td>';
				echo "<td><input name='lesson_seq' value={$rel['lesson_seq']}></input></td>";
				echo '</tr>';
					
				echo '<tr>';
				echo '<td>容量:</td>';
				echo "<td><input name='num' value={$rel['num']}></input></td>";
				echo '</tr>';
				echo '</table>';
				
			//	echo '<tr>';
				echo "<td><input type='hidden' name='cor_no' value={$_POST['cor_no']}> </input></td>";
				echo "<input class=button type='submit' value='提交'></input>";
			//	echo '</tr>';
			    
			    echo '</form>';
			}	
		};break;
		
	//新添实验批次
	case 'add':
		{
			echo "<form method='post' action='./group_deal.php?action=add'>";
			echo '<table>';
			echo '<tr>';		
			echo '<td>实验批次:</td>';
			echo "<td><input name='groups' ></input></td>";
			echo '</tr>';
		
			echo '<tr>';
			echo '<td>开始周:</td>';
			echo "<td><input name='week_start' ></input></td>";
			echo '</tr>';

			echo '<tr>';
			echo '<td>结束周:</td>';
			echo "<td><input name='week_end' ></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>星期几上课(如周4，周5则填45):</td>';
			echo "<td><input name='week_nums' ></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>第几大节:</td>';
			echo "<td><input name='lesson_seq' ></input></td>";
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>容量:</td>';
			echo "<td><input name='num' ></input></td>";
			echo '</tr>';
			echo '</table>';
			
			echo "<input type='hidden' name='cor_no' value={$_POST['cor_no']}> </input>";
			echo "<input class=button type='submit' value='提交'></input>";
			
			echo '</form>';
		};break;
		
		//删除批次
		case 'delete':
			{
				if (!isset($_REQUEST['id'] ))
				{
					die("请先选择批次");
				}
				$id =$_REQUEST['id'];
				$url = "./group_deal.php?action=delete&id={$id}";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";	
			};break;
	
		//显示所有课程
	default:
		{
			echo "<form  method='post' action='./group.php?tea_no={$tea_no}'>";
		//	echo "正常";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>学期</td>';
			echo '<td />';
			echo '</tr>';
			
			$queryStr = sprintf ( "select  *  from course where tea_no='%s'", $tea_no );
			if (isset($_POST['term']) &&  'all'!=$_POST['term'])
			{
				$queryStr =$queryStr . sprintf(" and term='%s'",$_POST['term']);
			}
			
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ( $rel = mysql_fetch_array ( $result ) ) {
				do{
				echo '<tr>';
				echo '<td>', "<input type=radio name=cor_no value={$rel['cor_no']}>", '</td>';
				
				echo '<td>', $rel ["cor_no"], '</td>';
				echo '<td>', $rel ["cor_name"], '</td>';
				echo '<td>', $rel ["term"], '</td>';
			//	echo "<td><a href='./update_item.php?action=select&cor_no={$rel['cor_no']}'>进入修改</a></td>";
				echo '</tr>';
				}while($rel = mysql_fetch_array ( $result ) );
			}
			else
			{
				echo '<tr><td>';
				echo '暂时没有您的课程';
				echo '</tr></td>';
			}

			echo '</table>';
            echo '<br />';

			echo "<table style='align:center;'>";
			echo '<tr>';
			echo '<td>','<input type=button class=btn value=查看批次>','</input>','</td>';
	//		echo '<td>','<input type=button class=btn value=编辑时间 style="width:65px;background-image:url(../static/image/but_1.png)">','</input>','</td>';
				
			echo '</tr>';			
			echo '</table>';
			echo '<input type=hidden name=action id=action value="" />';
			echo '</form>';
		}
}
?>

<!-- 绑定导航条点击事件 -->

<div id='table'></div>
<script type="text/javascript">
      var submit_action = function submit_action(e)
      {
          var frm1=$('form');
          
        switch(e.target.value)
          {
          case '查看批次':
              $('#action').attr('value',"select");break;
          case '增加':
              $('#action').attr('value',"add");break;
          case '更新':
              $('#action').attr('value',"update");break;
          case '删除':
          {
              var tips = window.confirm("确定要删除?");
              if(tips == false)
              {
                  return;
              }   
              $('#action').attr('value',"delete");
          };break;
          default:
        	  break;
          }
    //    alert($('#action').attr('value'));
       
         frm1.submit();     	
       }
      var start=function() { $(".btn").click( submit_action );   }
      $(start);
</script>