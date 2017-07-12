<?
/*** 检查图片 ***/
if ($_FILES['img']['error'] != 0) {
    $arr = [
        'status' => 'error',
        'message' => '图片上传失败'
    ];
    //把数组转化成json格式并返回
    echo json_encode($arr);
    exit;
}
//图片类型
$arr = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['img']['type'], $arr)) {
    $arr = [
        'status' => 'error',
        'message' => '只允许上传gif,png,jpg类型的图片'
    ];
    echo json_encode($arr);
    exit;
}
//尺寸
if ($_FILES['img']['size'] > 1024 * 1024){
    $arr = [
        'status' => 'error',
        'message' => '图片不能超过1M'
    ];
    echo json_encode($arr);
    exit;
}
//创建日期的目录
$today = date('Ymd');
if (!is_dir('./uploads/' . $today)) {
    mkdir('./uploads/' . $today, 0777);
}
/*** 为上传的图片生成文件名 ***/
//获取原图片的扩展名
$ext = strrchr($_FILES['img']['name'], '.'); //.jpg
//生成随机名
$name = uniqid() . $ext;
//上传
move_uploaded_file($_FILES['img']['tmp_name'], './uploads/' . $today . '/' . $name);
//返回数据
//获取图片宽、高
$size = getimagesize('./uploads/' . $today . '/' . $name);
$arr = [
    'status' => 'success',
    'url' => 'uploads/' . $today . '/' . $name,
    'width' => $size[0],
    'height' => $size[1]
];
echo json_encode($arr);