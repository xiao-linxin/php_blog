<?php
//连接数据库
include('conn.php');
$rs = mysqli_query($link, "select count(*) from member where username='{$_GET['u']}'");
$count = mysqli_fetch_row($rs);
if ($count[0] > 0)
    echo 1;
else
    echo 0;