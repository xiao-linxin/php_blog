<?php
session_start();
if(!isset($_SESSION['id'])){
    $info = '没有登录，不能删除！';
    include('html/error.html');
    exit;
}
//接受要删除的记录的id
$id = (int)$_GET['id']; //转成整形，避免用户输入SQL语句进行注入
$sql = "delete from blog where id = $id and member_id={$_SESSION['id']}";
include('conn.php');
mysqli_query($link, $sql);
$info = '删除成功!';
include('html/success.html');