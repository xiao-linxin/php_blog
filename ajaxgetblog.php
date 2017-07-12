<?php
/*** 根据p变量取某一页的数据 ***/
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
//每页显示5条
$perpage = 5;
$offset = ($p - 1) * $perpage;
$sql = "select a.id,a.title,a.content,a.addtime,b.face,b.username from blog a left join member b on a.member_id=b.id limit $offset,$perpage";
include('conn.php');
$rs = mysqli_query($link, $sql);
$data = [];
while($row = mysqli_fetch_assoc($rs)){
    $data[] = $row;
}
echo json_encode($data);