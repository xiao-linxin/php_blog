<?php
/*** 表单验证 ***/
$username = trim($_POST['username']);
$password = trim($_POST['password']);
//用户名必须是6~18位的数字、字母、下划线
$re = '/^\w{6,18}$/';
if (!preg_match($re, $username)) {
    include('html/error.html');
    exit;
}
//密码必须是6~18位的数字、字母、下划线
if (!preg_match($re, $password)) {
    include('html/error.html');
    exit;
}

/*** 把数据插入到数据库中 ***/
include('conn.php');

//是否存在账号
$rs = mysqli_query($link, "select * from member where username = '$username'");
//从资源里取出记录
$user = mysqli_fetch_assoc($rs);
if ($user) {
    //再判断密码是否正确
    $salt = '32!@#jieiJFI()3.FKDW32';
    if ($user['password'] == md5($_POST['password'] . $salt)) {
        //判断有没有点击自动登录
        if (isset($_POST['autologin'])) {
            //生成自动登录的密钥 -> uniqid:生成一个唯一的随机字符串
            $key = md5(uniqid());
            $time = time();
            //把这个密钥更新到数据库中
            mysqli_query($link, "update member set auto_login_key='$key',auto_login_key_time=$time where id={$user['id']}");
            //把这个密钥存到用户的浏览器中,86400=24小时
            setcookie('autoload', $key, time() + 86400 * 30, '/');
        }

        //如果登录成功->把常用的用户信息保存到session中
        session_start();
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $info = '登录成功！';
        include('html/success.html');
    } else {
        $info = '密码不正确！';
        include('html/error.html');
    }
} else {
    $info = '账号不存在！';
    include('html/error.html');
}