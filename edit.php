<?php
session_start();
if(!isset($_SESSION['id'])){
    $info = '没有登录，不能删除！';
    include('html/error.html');
    exit;
}
//接受要删除记录的id
$id = (int)$_GET['id']; //转成整形，避免用户输入SQL语句进行注入
//根据ID取出要修改的记录信息
include('conn.php');
$rs = mysqli_query($link,"select title,content from blog where id = $id and member_id={$_SESSION['id']}");
//从资源中取出要修改的数据
$data = mysqli_fetch_assoc($rs);
//如果没有取出
if(!$data){
    $info = '无法修改这个日志！';
    include('html/error.html');
    exit;
}
//显示修改的表单 -> 直接复制添加的表单
include('html/edit.html');