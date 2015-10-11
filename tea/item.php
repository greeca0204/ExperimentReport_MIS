<?php 
/*名称：实验项目管理前台
 * 功能：查看实验项目列表，修改实验项目，新添实验项目，修改实验报告提交截止时间
 */
?>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>
<?php
include '../is_login_tea.php';
include '../config.php';
//$tea_no = "tea";
$today = date("Y-m-d");

$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
/** 根据action参数的值，进行不同处理：select选择课号查看实验内容，add添加实验内容，update更新实验内容
 *    edit_time编辑实验报告提交时间
 * 
 */

switch($action)
{
	//根据课号查看实验项目
	case 'select':
		{
			if (!isset($_REQUEST ['cor_no']))
			{
				die('请先选择一个实验项目');
			}
			$cor_no = $_POST ['cor_no'];
			
		//	echo '课程名称：',$_POST['cor_name'];
			echo "<form method='post' action='./item.php'>";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
					
			echo '<td>实验编号</td>';
			echo '<td>实验名称</td>';
			echo '<td>成绩百分比</td>';
			echo '</tr>';

			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ( $rel = mysql_fetch_array ( $result ) ) {
				do{
				echo '<tr>';
				echo '<td>', "<input type=radio name = id value={$rel['id']}>",'</td>';
				echo '<td>', $rel ["item_no"], '</td>';
				echo '<td>', $rel ["item_name"], '</td>';
				echo '<td>', $rel ["exam_rate"], '</td>';
				echo '</tr>';
				}while($rel = mysql_fetch_array ( $result ));
			}
			else 
			{
				echo '<tr><td>';
				echo "该课程暂时还没有添加实验内容";
				echo '</tr></td>';
			}
			echo '<br />';
			echo '<table><tr>';
			echo '<td>',"<input type=hidden name=cor_no value={$cor_no}>",'<td>';
			echo '<td>',"<input name=action id=action value='' type=hidden></input>",'</td>';
			echo '<td>','<input type=button class=btn  value=增加>','</input>','</td>';
			echo '<td>','<input type=button class=btn  value=更新>','</input>','</td>';
			echo '<td>','<input type=button class=btn  value=删除>','</input>','</td>';
			echo '</tr></table>';
			echo '</table>';	
			echo '</form>';
			
		};break;
		
	//修改实验项目，前台
	case 'update':
		{
			if (!isset($_REQUEST['id']))
			{
				die('请先选择实验项目');
			}
			$id = $_REQUEST['id'];
			$cor_no = $_REQUEST['cor_no'];
			
			//判断课程是否已经关闭
			$queryStr = sprintf ( "select close_time  from course where  cor_no='%s' ", $cor_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel=mysql_fetch_array($result);
			if( $today > $rel['close_time'] )
			{
					die('该课程关闭时间已到，不能再修改');
			}
			
			//$item_no=$_GET ['item_no'];
			$queryStr = sprintf ( "select  *  from item where  id='%s' ", $id);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();

			if($rel=mysql_fetch_array($result))
			{
				echo "<form method='post' action='./item_deal.php?action=update&id={$rel['id']}'>";
				echo '实验编号:';
				echo "<input name='item_no_new' value={$rel['item_no']}></input>";
				echo '<br />';
				echo '实验名称:';
				echo "<input name='item_name' value={$rel['item_name']}></input>";
				echo '<br />';
				echo '所占成绩百分比:';
				echo "<input name='exam_rate' value={$rel['exam_rate']}>%</input>";
				echo '<br />';
				echo '实验内容：:';
				echo '<br />';
				echo '<pre>';
				echo "<textarea name='body' style='width:700;height:400;'>";

				echo htmlspecialchars(stripslashes ($rel['body']));
				
				echo "</textarea>";
				echo '</pre>';
				echo '<br /><br />';
				echo '实验报告模板:';
				echo '<br />';
				echo '<pre>';
				echo "<textarea name='report_format' style='width:700;height:500;'>";
				echo htmlspecialchars(stripslashes ($rel['report_format']));
				echo "</textarea>";
				echo '</pre>';
				echo '<br /><br />';
			}
			echo "<td><input type='hidden' name=cor_no value={$cor_no}></input></td>";
			echo "<td><input type='hidden' name=item_no_old value={$rel['item_no']}></input></td>";
			echo "<td><input class=button type='submit' value='提交'></input></td>";
			echo '</form>';
			
		};break;
		
	//新添实验项目
	case 'add':
		{
			//判断课程是否已经关闭
			$cor_no = $_REQUEST['cor_no'];
			$queryStr = sprintf ( "select close_time  from course where  cor_no='%s' and tea_no='%s'", $cor_no,$tea_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			$rel=mysql_fetch_array($result);
			if( $today > $rel['close_time'] )
			{
				die('该课程关闭时间已到，不能更新');
			}
			
			echo "<form method='post' action='./item_deal.php?action=add'>";
			echo '实验编号:';
			echo "<input name='item_no' ></input>";
			echo '<br />';
			echo '实验名称:';
			echo "<input name='item_name' ></input>";
			echo '<br />';
			echo '本次实验报告成绩所占百分比:';
			echo "<input name='exam_rate' >%</input>";
			echo '<br />';
			echo '实验内容：';
			echo '<br />';
			echo "<textarea name='body' style='width:700;height:400;margin-left:auto;margin-right:auto;'></textarea>";
			echo '<br /><br />';
			
			echo '实验报告提交模板：';
			echo '<br />';				
			echo "<textarea name='report_format' style='width:700;height:500;margin-left:auto;margin-right:auto;'></textarea>";
			echo '<br /><br />';
			
			echo "<input type='hidden' name='cor_no' value={$_POST['cor_no']}> </input>";
			
			echo "<td><input class=button  type='submit' value='提交'></input></td>";
		};break;
		
		//删除实验项目
/**		case 'delete':
			{
				echo "<form  method='post' action='./item_deal.php?action=delete'>";
				echo "";
				
			};break;
*/			
			//修改实验报告提交截止时间
		case 'edit_time':
			{
				if (! isset($_REQUEST['cor_no']))
				{
					die('请先选择一项');
				}
				$cor_no = $_REQUEST['cor_no'];
				//判断课程是否已经关闭
				$queryStr = sprintf ( "select close_time  from course where  cor_no='%s' ", $cor_no);
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				$rel=mysql_fetch_array($result);
				if( $today > $rel['close_time'] )
				{
					echo '选课截止时间是:';
					echo $rel['close_time'];
					die('<br />该课程关闭时间已到，不能再修改');
				}
				
				$queryStr = sprintf ( "select report_time from course where  cor_no='%s'",$cor_no);
				$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				mysql_close ();	
                
				echo "<form method='post' action='./item_deal.php?action=edit_time'>";
				echo '报告提交截止时间：';
				
				if($rel=mysql_fetch_array($result))
				{
					
					echo "<input name=report_time value={$rel['report_time']}>(格式:2013-05-05)</input>";	
				}
				else
				{
					echo "<input name=report_time value=>(格式:2013-05-05)</input>";
				}
				echo '<br /><br />';
				echo "<input type=hidden name=cor_no value={$_REQUEST['cor_no']}></input>";
				echo "<input class=button type=submit value='提交'></input>";
				echo '</form>';		
			};break;
		
		//显示所有课程
	default:
		{
			echo "<form  method='post' action='./item.php'>";
		//	echo "正常";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>平时成绩比重</td>';
			echo '<td>考试成绩比重</td>';
			echo '<td>实验报告成绩比重</td>';
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
				echo '<td>', $rel ["usual_rate"], '%</td>';
				echo '<td>', $rel ["exam_rate"], '%</td>';
				echo '<td>', $rel ["report_rate"], '%</td>';
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
			echo '<td>','<input type=button class=btn value=查看>','</input>','</td>';
			echo '<td>','<input type=button class=btn value=报告提交时间>','</input>','</td>';
				
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
          case '查看':
              $('#action').attr('value',"select");break;
          case '增加':
              $('#action').attr('value',"add");break;
          case '更新':
              $('#action').attr('value',"update");break;
          case '删除':
          {
              var tips = window.confirm("提交之后无法更改，你确定要提交?");
              if(tips == false)
              {
                  return;
              }   
              $('form').attr('action',"./item_deal.php?action=delete");
              $('#action').attr('value',"delete");
          };break;
          case '报告提交时间':
        	  $('#action').attr('value',"edit_time");break;
          default:
        	  break;
          }
    //    alert($('#action').attr('value'));
         frm1.submit();     	
       }
      var start=function() { $(".btn").click( submit_action );   }
      $(start);
</script>