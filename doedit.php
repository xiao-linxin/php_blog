<?php
session_start();
if(!isset($_SESSION['id'])){
    $info = '没有登录，不能删除！';
    include('html/error.html');
    exit;
}
/*** 表单验证 ***/
$title = trim($_POST['title']);
$content = trim($_POST['content']);
if ($title == '') {
    include('html/error.html');
    exit;
}
if ($content == '') {
    include('html/error.html');
    exit;
}

//过滤XSS
$title = addslashes(htmlspecialchars($title));
$content = addslashes(htmlspecialchars($content));
$id = (int)$_POST['id']; //接受表单中的要修改记录的id

/*** 把数据插入到数据库中 ***/
include('conn.php');
$ret = mysqli_query($link, "update blog set title='$title',content='$content' where id=$id and member_id={$_SESSION['id']}");
if ($ret === false) {
    echo mysqli_error($link);
    exit;
}
$info = '修改成功!';
include('html/success.html');