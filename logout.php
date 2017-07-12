<?php
session_start();

//清空session文件中的数据，但文件还在
$_SESSION = [];

//方法二：把服务器上的session文件删除
//session_destroy();

//删除cookie自动登录的变量
setcookie('autologin', '', 1, '/');
//告诉浏览器转到index.php
header('Location:index.php');
