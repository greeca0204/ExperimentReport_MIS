<?php 
/*名称：评语管理前台
 * 功能：修改评语，新添评语，删除评语，显示所有评语
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
//$tea_no = "tea";
?>

<?php
$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;
/** 根据action参数的值，进行不同处理：select选择课号查看实验内容，add添加实验内容，update更新实验内容
 *    edit_time编辑实验报告提交时间
 * 
 */

switch($action)
{	
	//修改评语，前台
	case 'update':
		{
			if (!isset($_REQUEST['id']))
			{
				die('请先选择实验项目');
			}
			$ids = $_REQUEST ['id'];
			if (count ( $ids ) > 1) {
				die ( "请只选择一条评语" );
			}
			$id =  $ids[0];
			
			$queryStr = sprintf ( "select  *  from remark where id='%s' and tea_no='%s'", $id,$tea_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if($rel=mysql_fetch_array($result))
			{
				echo "<form method='post' action='./remark_deal.php?action=update&id={$id}'>";
				echo '评语编号:';
				echo "<input name='no' value={$rel['no']}></input>";
				echo '<br />';
				echo '评语内容:';
				echo '<br />';
				echo "<textarea name='body' style='width:300;height:200;'>{$rel['body']}</textarea>";
				echo '<br />';
				echo "<input class=button type='submit' value='提交'></input>";
				echo '</form>';
			}		
		};break;
		
	//新添评语，前台
	case 'add':
		{
			echo "<form method='post' action='./remark_deal.php?action=add'>";
			echo '编号:';
			echo "<input name='no' ></input>";
			echo '<br />';
			
			echo '评语内容:';
			echo '<br />';
			echo "<textarea name='body' style='width:300;height:200;margin-left:auto;margin-right:auto;'></textarea>";
			echo '<br /><br />';
	
			echo "<input type='hidden' name='cor_no' value={$tea_no}> </input>";
			
			echo "<td><input class=button type='submit' value='提交'></input></td>";
		};break;
		
		//删除评语，伪前台
		case 'delete':
			{
				if (!isset($_REQUEST['id'] ))
				{
					die("请先选择评语");
				}
				$ids =$_REQUEST['id'];
				$url = "./remark_deal.php?action=delete";
				for($i = 0 ; $i < count($ids) ; $i++)
				{
				$url =$url .  "&id[{$i}]=" . $ids[$i];
				}
				echo $url;
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL={$url}\">";			
			};break;	
	
		//默认显示所有评语
	default:
		{
			echo '我的评语<br /><br />';
			echo "<form  method='post' action='./remark.php'>";
			//	echo "正常";
			echo '<table class=table_border>';
			echo '<tr class="first">';
			echo '<td />';
			echo '<td>编号</td>';
			echo '<td>内容</td>';
			//			echo '<td>学期</td>';
			echo '</tr>';
				
			$queryStr = sprintf ( "select  *  from remark where tea_no='%s'", $tea_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			mysql_close ();
			if ( $rel = mysql_fetch_array ( $result ) ) {
				do{
					echo '<tr>';
					echo '<td>', "<input type=checkbox name=id[] value={$rel['id']}>", '</td>';
			
					echo '<td>', $rel ["no"], '</td>';
					echo '<td>', $rel ["body"], '</td>';
					//	echo '<td>', $rel ["term"], '</td>';
					//	echo "<td><a href='./update_item.php?action=select&cor_no={$rel['cor_no']}'>进入修改</a></td>";
					echo '</tr>';
				}while($rel = mysql_fetch_array ( $result ) );
			}
			else
			{
				echo '<tr><td>';
				echo '暂时没有评语';
				echo '</tr></td>';
			}
			
			echo '</table>';
			echo '<br />';	
			echo "<table style='align:center;'>";
			echo '<tr>';
			echo '<td>','<input type=button class=btn value=增加>','</input>','</td>';
			echo '<td>','<input type=button class=btn value=编辑>','</input>','</td>';
			echo '<td>','<input type=button class=btn value=删除>','</input>','</td>';
			
			echo '</tr>';
			echo '</table>';
			echo '<input type=hidden name=action id=action value="" />';
			echo '</form>';
			};break;
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
          case '增加':
              $('#action').attr('value',"add");break;
          case '编辑':
              $('#action').attr('value',"update");break;
          case '删除':
          {
              var tips = window.confirm("确定要删除?");
              if(tips == false)
              {
                  return;
              }   
              $('form').attr('action',"./remark_deal.php?action=delete");
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