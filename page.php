<!DOCTYPE html>
<html>
<head>
<title>页码分页</title>
<meta charset="utf-8">
<style type="text/css">
body{
	font-family: '微软雅黑';
	color: #222;
}
table{
	color: #222;
	text-align: center;
	margin: 40px auto;
}
.page{
	color: #222;
	text-align: center;
	margin-top: 20px;
	margin-bottom: 20px;
}
.page a{
	display: inline-block;
	min-width: 20px;
	min-height: 20px;
	text-align: center;
	border:1px solid #bbb;
	text-decoration: none;
	padding: 2px 5px 2px 5px;
	margin: 5px;
	color: #666;
	border-radius: 3px;
}
.page a:hover{
	border: 1px solid deepskyblue;
	background-color: deepskyblue;
	color: #fff;
}
.current{
	display: inline-block;
	min-width: 20px;
	min-height: 20px;
	text-align: center;
	border: 1px solid deepskyblue;
	background-color: deepskyblue;
	padding: 2px 5px 2px 5px;
	margin: 5px;
	color: #fff;
	font-weight: bold;
	border-radius: 3px;
}
.disable{
	border:1px solid #ccc;
	border-radius: 3px;
	padding: 2px 5px 2px 5px;
	margin: 5px;
	color: #ccc;
}
form{
	display: inline-block;
}

</style>
</head>
<body>
<?php

/*1.传入页码*/
	$page=$_GET['p'];
	$pageSize = 8;
	$showPage = 5;
	$offset = ($page-1)*$pageSize;
/*2.根据页码去除数据*/
	$host = 'localhost';
	$username = 'root';
	$password = '125478';
	$db = 'test';
	//连库
	$conn = mysql_connect($host,$username,$password);
	if(!$conn){
		echo '连接数据库失败';
		exit;
	}
	//选库
	mysql_select_db($db);
	//设置编码
	mysql_query("set names utf8");
	//编写sql获取分页数据 SELECT * FROM 表名 LIMIT 起始位置,显示条数
	$sql = "select * from tb01 limit {$offset},{$pageSize}";
	$result = mysql_query($sql);
	echo "<div class='content'><table border='1' cellspacing=0 width=40% >";
	echo "<tr><td>ID</td><td>名字</td></tr>";
	while($row = mysql_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['name']."</td>";
		echo "</tr>";
	}
	echo "</table></div>";
//释放结果，关闭链接
mysql_free_result($result);
//获取数据总数
$total_sql = "select count(*) from tb01";
$total_result = mysql_fetch_array(mysql_query($total_sql));
$total = $total_result[0];
//计算页数
$total_pages = ceil($total/$pageSize);
mysql_close($conn);
/*3.显示数据+和显示分页条*/
$page_banner = "<div class='page'>";
//计算偏移量
$pageOffset = ($showPage-1)/2;
$start = 1;
$end = $total_pages;

if($page>1){
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=1'>首页</a>";
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page-1)."'>< 上一页</a>";
}else{
	$page_banner .= "<span class='disable'>首页</span>";
	$page_banner .= "<span class='disable'>< 上一页</span>";
}
if($total_pages>$showPage){
	//头部省略
	if($page>$pageOffset+1){
		$page_banner .="..."; 
	}
	if($page> $pageOffset){
		$start = $page -$pageOffset;
		$end = $total_pages>$page+$pageOffset?$page+$pageOffset:$total_pages;
	}else{
		$start = 1;
		$end = $total_pages>$showPage?$showPage:$total_pages;
	}
	if($page+$pageOffset>$total_pages){
		$start = $start-($page+$pageOffset-$end);
	}
}
for($i = $start;$i<=$end;$i++){
	if($page ==$i){
		$page_banner .= "<span class='current'>{$i}</span>";
	}else{
		$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".$i."'>{$i}</a>";
	}
}
//尾部省略
if($total_pages>$showPage&&$total_pages>$page+$pageOffset){
	$page_banner .="..."; 
}
if($page<$total_pages){
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page+1)."'>下一页 ></a>";
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($total_pages)."'>尾页</a>";
}else{
	$page_banner .= "<span class='disable'>下一页 ></span>";
	$page_banner .= "<span class='disable'>尾页</span>";
}
	$page_banner .="共{$total_pages}页";
	$page_banner .="<form action='page.php' method='get'>";
	$page_banner .="到第 <input type='text' size=2 name='p'> 页 ";
	$page_banner .="<input type='submit' value='确定'>";
	$page_banner .="</form></div>";
	echo $page_banner;
?>
</body>
</html>