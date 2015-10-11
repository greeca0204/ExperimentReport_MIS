<?php
/*
 * 名称：中心概括
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="./static/css/global.css"></link>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert title here</title>
</head>
<body>
	<div
		style="background-image: url(./static/image/topback.jpg); width: 1024px; height: 80px; margin-left: auto; margin-right: auto;"></div>
	<div
		style="margin-left: auto; margin-right: auto; min-width: 960px; text-align: center;">
		
		<!--主导航条-->
		<div class="sep3"></div>
<?php
require_once './nav.php';
?>
	<div>

			<div class="center" style="width: 720px;">
				<p class="white">   
					桂林电子科技大学的计算机学科始建于1980年，是全国高校中较早开办计算机及应用专业的学校之一。2000年学校在体制改革中，为了加强计算机基础实验课程建设，通过对全校各系相关专业的计算机实验室进行重新整合成立了校级计算机基础实验教学中心。中心现有基础课程实验室8个，并设有一个软件设计训练基地、一个大学生创新基地、一个软件开发基地和一个大学生电子设计训练基地，实验室面积2500多平方米。
				</p>

				<p class="white">   
					中心成立以来，在学校的高度重视和支持下，通过资源优化，学校的经费划拨，中央与地方共建项目等多方筹措资金，近５年来共投入教学
					资金2200多万元，用于仪器设备更新，实验室环境建设，网络信息化平台建设，仪器设备维护及教学活动开展，购置设备数达1700多台套。</p>


				<p class="white">   
					计算机基础实验教学中心担负着全校的计算机基础实验教学任务，每年承担计算机与控制学院1400余名本科生、1000余名研究生的计算机
					实验教学，另外还承担全校40个非计算机本科专业15000余名学生的计算机公共基础实验教学。年均本科生实验工作量达40万人时数，计算机基
					础实验教学中心已经成为学校重要的实践教学基地。</p>

				<p class="white">   
					2006年，学校由桂林电子工业学院升格为桂林电子科技大学，计算机基础实验教学中心秉承桂林电子科技大学“勤俭显本色，创新铸灵魂，
					质量如生命，人才立根本”的办学理念，以创新人才培养为目标，以能力培养为主线，在管理体制、实验教学内容与体系、教学手段与方法等方
					面进行较大的改革和创新。重点解决教学与实验内容滞后于计算机技术发展的问题及“学校所学”与“社会所需”之间的差距问题。中
					心提出“任务驱动，探究式学习，精讲多练，实验与实训相结合”的实验教学理念，让学生在做中学，在多次、多种、开放性试验中
					学会学习，学会探索、学会创新。在不断完善８个基础实验室的同时积极开展实践创新先后成立了４个实践基地，并取得了丰硕的成果，在区、
					国家级竞赛中屡创佳绩。</p>

				<p class="white">    中心围绕计算机应用学科和校级实验教学中心的建设，大力开展教学改革和教育科研，获得2
					项国家级教学成果二等奖，10项广西区级教学
					成果奖，12项国家专利，12项省、部级科研成果奖，部分项目成果在全国广泛应用。发表了一批高水平的教学改革和教育研究论文，建设了2门
					自治区级精品课程，一批教材被评为自治区级精品教材、重点教材和广西普通高校本科优秀教材。</p>

				<p class="white">   
					目前，计算机基础实验教学中心拥有一支高素质的实验教学队伍，正以精良的实验设备、先进的实验内容、现代化的实验方法和手段、“课
					内与课外、实验与实训”紧密结合的计算机基础教学实验大环境，努力创建广西区级乃至国家级实验教学中心。</p>


			</div>


		</div>
</div>
		<div style="clear: both; text-align: center;">
			<hr />
		© 2013 By 梁德斌
<?php
if (isset ( $_SESSION ['user'] ) && NULL != $_SESSION ['user']) {
	echo "<div style='float:right;'>";
	echo "你的身份是:{$_SESSION['user']['type']}&nbspID为:{$_SESSION['user']['id']}";
	echo "</div>";
}
?>
	</div>

</body>
</html>