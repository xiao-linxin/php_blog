<!DOCTYPE html>
<html>
<head>
    <title>注册 - 博客</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="html/css.css">
    <link rel="stylesheet" type="text/css" href="html/croppic.css">
    <script type="text/javascript" src="html/jquery.js"></script>
    <script type="text/javascript" src="html/croppic.min.js"></script>
    <style type="text/css">
        #face {
            width: 180px;
            height: 180px;
            border: 1px solid #F00;
            position: relative;
        }

        .form .cropControls {
            margin: 0
        }
    </style>
</head>
<body>
<h1 id="web-title" class="container">博客</h1>
<?php include('nav.html'); ?>
<form method="post" action="reg.php" class="container form">
    <div id="face"></div>
    <input type="hidden" name="face" id="face-txt">
    <div>
        <label>账 号：</label>
        <input id="reg" placeholder="请输入6~18位数字、字母和下划线" type="text" class="txt" name="username" size="40">
    </div>
    <div>
        <label>密 码：</label>
        <input placeholder="请输入6~18位数字、字母和下划线" type="password" class="txt" name="password" size="40">
    </div>
    <div>
        <label>确认密码：</label>
        <input placeholder="请再次输入密码" type="password" class="txt" name="cpassword" size="40">
    </div>
    <div>
        <label>手 机：</label>
        <input placeholder="请输入常用的手机号码" type="text" class="txt" name="tel" size="40">
    </div>
    <input class="form-btn" type="submit" value="注册">
</form>
</body>
</html>
<script type="text/javascript">
    //配置插件
    var cropperOptions = {
        //图片上传的地址 -> 选择图片之后插件会把图片提交到这个文件
        uploadUrl: 'croppic.php',
        //裁切图片的PHP文件，当点击裁切时拆件会把图片上传到这个文件去
        cropUrl: 'croppic_crop.php',
        //上传图片过程中的动画
        loaderHtml: '<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubbingG_2"></span><span id="bubbingG_3"></span></div>',
        //处理之后的图片路劲保存的元素ID
        outputUrlId: 'face-txt',
        //打开新窗口
        modal: true
    };
    var cropperHeader = new Croppic('face', cropperOptions);

    /*** js原生写法 ***/
    //    var xhr;//ajax对象
    //    //失去焦点时
    //    reg.onblur = function () {
    //        /*** AJAX把账号提交到服务器判断是否已经存在 ***/
    //        //1.创建XMLHttpRequest对象
    //        try {
    //            xhr = new ActiveXObject('Msxml2.XMLHTTP');
    //        } catch (E) {
    //            try {
    //                xhr = new ActiveXObject('Microsoft.XMLHTTP');
    //            } catch (E) {
    //                xhr = new XMLHttpRequest();
    //            }
    //        }
    //        //2.创建一个回调函数，这个函数在请求发送之后会被调用
    //        //这个函数中应该写处理服务器返回的数据代码
    //        function success() {
    //            //xhr.readyState = 4 -> ajax已经执行结束
    //            //xhr.status == 200 -> 并且这次请求成功了
    //            if (xhr.readyState == 4 && xhr.status == 200) {
    //                //xhr.responseText -> 服务器返回的数据
    //                if (xhr.responseText == 1) {
    //                    reg.style.backgroundColor = '#F00';
    //                    alert('账号已经存在！');
    //                } else {
    //                    reg.style.backgroundColor = '#FFF';
    //                }
    //            }
    //        }
    //        //3.把这个函数绑定到这个对象上
    //        xhr.onreadystatechange = success;
    //        //4.配置请求地址
    //        xhr.open('GET', 'chkuser.php?u=' + reg.value);
    //        //5.发送请求，参数是当POST提交表单时，表单中的数据放在这
    //        //因为现在是GET请求所以设置NULL
    //        xhr.send(null);
    //    };
    /*** jq写法 ***/
    $("#reg").blur(function () {
        //发送AJAX请求
        $.ajax({
            type: "GET",
            url: "chkuser.php?u=" + $("#reg").val(),
            //AJAX执行完之后调用函数
            //参数就是服务器返回的函数
            success: function (ret) {
                if (ret == 1) {
                    $('#reg').css('backgroundColor', '#F00');
                    alert('账号已经存在！');
                } else {
                    $('#reg').css('backgroundColor', '#FFF');
                }
            }
        })
    });
</script>