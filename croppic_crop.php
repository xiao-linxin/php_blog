<?php
/*** 缩放图片 ***/
//获取图片信息
//imgUrl: uploads/.../xxx.jpg
$size = getimagesize($_POST['imgUrl']);
if ($size['mime'] == 'image/jpeg' || $size['mime'] == 'image/jpg' || $size['mime'] == 'image/pjpeg') {
    $img = imagecreatefromjpeg($_POST['imgUrl']);
} elseif ($size['mime'] == 'image/png') {
    $img = imagecreatefrompng($_POST['imgUrl']);
} elseif ($size['mime'] == 'image/gif') {
    $img = imagecreatefromgif($_POST['imgUrl']);
} else {
    $arr = [
        'status' => 'error',
        'message' => '图片类型不正确！'
    ];
    echo json_encode($arr);
    exit;
}
/*** 图片缩放 ***/
//创建画布
$newImage = imagecreatetruecolor($_POST['imgW'], $_POST['imgH']);
//把原图放到这个画布中实现缩放
imagecopyresampled($newImage, $img, 0, 0, 0, 0, $_POST['imgW'], $_POST['imgH'], $_POST['imgInitW'], $_POST['imgInitH']);
/*** 在缩放之后再从中截切一个区域 ***/
//创建裁切尺寸的画布
$newImage1 = imagecreatetruecolor($_POST['cropW'], $_POST['cropH']);
imagecopyresampled($newImage1, $newImage, 0, 0, $_POST['imgX1'], $_POST['imgY1'], $_POST['cropW'], $_POST['cropH'], $_POST['cropW'], $_POST['cropH']);
/*** 把裁切的图片保存起来 ***/
//imgUrl: uploads/.../xxx.jpg
if ($size['mime'] == 'image/jpeg' || $size['mime'] == 'image/jpg' || $size['mime'] == 'image/pjpeg') {
    $newname = $_POST['imgUrl'] . '.jpg';
    imagejpeg($newImage1, $newname);
} elseif ($size['mime'] == 'image/png') {
    $newname = $_POST['imgUrl'] . '.png';
    imagepng($newImage1, $newname);
} elseif ($size['mime'] == 'image/gif') {
    $newname = $_POST['imgUrl'] . '.gif';
    imagegif($newImage1, $newname);
}

//删除原图
unlink($_POST['imgUrl']);
//返回数据
$arr = [
    'status' => 'success',
    'url' => $newname
];
echo json_encode($arr);