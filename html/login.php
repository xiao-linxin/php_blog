<!DOCTYPE html>
<html>
<head>
	<title>登录 - 微博</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="html/css.css">
</head>
<body>
	<h1 id="web-title" class="container">登录 - 微博</h1>
	<?php include('nav.html'); ?>
	<form method="post" action="login.php" class="container form">
		<div>
			<label>账 号：</label>
			<input placeholder="请输入6~18位数字、字母和下划线" type="text" class="txt" name="username" size="40">
		</div>
		<div>
			<label>密 码：</label>
			<input placeholder="请输入6~18位数字、字母和下划线" type="password" class="txt" name="password" size="40">
		</div>
		<div>
			<label>自动登录：</label>
			<input type="checkbox" value="1" name="autologin">
		</div>
		<input class="form-btn" type="submit" value="登录">
	</form>
</body>
</html>