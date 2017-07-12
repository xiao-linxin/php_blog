<?php
session_start();
/*** 表单验证 ***/
$title = trim($_POST['title']);
$content = trim($_POST['content']);
if($title==''){
    $info = '标题不能为空！';
    include('html/error.html');
    exit;
}
if($content==''){
    $info = '内容不能为空！';
    include('html/error.html');
    exit;
}

//过滤XSS
$title = htmlspecialchars($title);
$content = htmlspecialchars($content);

/*** 把数据插入到数据库中 ***/
include('conn.php');
$ret = mysqli_query($link,"insert into blog(title,content,member_id) values('$title','$content','$_SESSION[id]')");
if($ret === false){
    echo mysqli_error($link);
    exit;
}
$info = '发表成功!';
include('html/success.html');