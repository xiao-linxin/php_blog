<?php
/*** 表单验证 ***/
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$cpassword = trim($_POST['cpassword']);
$tel = trim($_POST['tel']);
//用户名必须是6~18位的数字、字母、下划线
$re = '/^\w{6,18}$/';
if (!preg_match($re, $username)) {
    $info = '用户名必须是6~18位的数字、字母、下划线！';
    include('html/error.html');
    exit;
}
//密码必须是6~18位的数字、字母、下划线
if (!preg_match($re, $password)) {
    $info = '密码必须是6~18位的数字、字母、下划线！';
    include('html/error.html');
    exit;
}
//两次密码必须一致
if ($password != $cpassword) {
    $info = '两次密码必须一致！';
    include('html/error.html');
    exit;
}
//手机验证
$re = '/^1[34578][0-9]{9}$/';
if (!preg_match($re, $tel)) {
    $info = '请输入正确的手机号码！';
    include('html/error.html');
    exit;
}

/*** 把数据插入到数据库中 ***/
include('conn.php');

/*** 再判断这个账号是否已经存在 ***/
$rs = mysqli_query($link, "select count(*) from member where username = '$username'");
$row = mysqli_fetch_row($rs);
if ($row[0] >= 1) {
    $info = '账号已经存在！';
    include('html/error.html');
    exit;
}

/*** 再判断手机是否存在 ***/
$rs = mysqli_query($link, "select count(*) from member where tel = '$tel'");
$row = mysqli_fetch_row($rs);
if ($row[0] >= 1) {
    $info = '手机已经存在！';
    include('html/error.html');
    exit;
}

//定义一个加密盐，一旦确定不能再修改
$salt = '32!@#jieiJFI()3.FKDW32';
//把密码加密
$password = md5($password . $salt);
/*** 把头像从临时 目录 移动到正确的目录 ***/
if ($_POST['face'] != '') {
    $today = date('Ymd');
    if (!is_dir('face/' . $today))
        mkdir('face/' . $today, 0777);
    //取出这个图片的名字
    $face = 'face/' . $today . strrchr($_POST['face'], '/'); //新的文件名
    //把图片从临时目录复制到保存头像的目录
    copy($_POST['face'], $face);
} else
    $face = '';

$ret = mysqli_query($link, "insert into member(username,password,tel,face) values('$username','$password','$tel','$face')");
if ($ret === false) {
    echo mysqli_error($link);
    exit;
}
$info = '注册成功!';
include('html/success.html');