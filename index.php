<?php
session_start();
//连接数据库
include('conn.php');
/*** 自动登录 ***/
//如果没有登录，但是有自动登录的密钥
if (!isset($_SESSION['id']) && isset($_COOKIE['autologo'])) {
    //根据密钥，查询相应账号
    $rs = mysqli_query($link, "select id,username from member where auto_login_key='{$_COOKIE['autologin']}'");
    //从资源中取出数据
    $user = mysqli_fetch_assoc($rs);
    if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    }
}

/*** 安全过滤 ***/
//htmlspecialchars();过滤XSS
//addslashes();过滤SQL注入
foreach ($_GET as $k => $v) {
    $_GET[$k] = htmlspecialchars(addslashes($v));
}
foreach ($_POST as $k => $v) {
    $_POST[$k] = htmlspecialchars(addslashes($v));
}
foreach ($_COOKIE as $k => $v) {
    $_POST[$k] = htmlspecialchars(addslashes($v));
}

/*** 排序【拼order by 字符串】 ***/
$orderby = 'addtime'; //默认的排序字段
$orderway = 'desc';   //默认的排序方式
//判断用户传了排序字段并且是四种排序之一
if (isset($_GET['od']) && in_array($_GET['od'], ['title_asc', 'title_desc', 'time_asc', 'time_desc'])) {
    if ($_GET['od'] == 'title_asc') {
        $orderby = 'title';
        $orderway = 'asc';
    } else if ($_GET['od'] == 'title_desc') {
        $orderby = 'title';
    } else if ($_GET['od'] == 'time_asc') {
        $orderby = 'addtime';
        $orderway = 'asc';
    } else {
        $orderby = 'addtime';
    }
}

//设置显示错误的级别
//error_reporting(~E_NOTICE); //不显示提示性错误

/*** 根据用户传的参数来拼搜索的where条件 ***/
$where = [];
//判断有没有传标题
if (isset($_GET['t']) && !empty($_GET['t'])) {
    //在$where数组中添加一个根据标题搜索的条件
    $where[] = "title like '%{$_GET['t']}%'";
}
//判断有没有传开始时间
if (isset($_GET['st']) && !empty($_GET['st'])) {
    $where[] = "addtime >= '{$_GET['st']}'";
}
//判断有没有传开始时间
if (isset($_GET['et']) && !empty($_GET['et'])) {
    $where[] = "addtime <= '{$_GET['et']}'";
}
//把数组变成SQL语句
$whereSQL = ''; //空的where
if ($where) {
    //把数组转化成字符串，用 and 隔开
    $whereSQL = 'where' . implode('AND', $where);
}

//连接数据库
$link = mysqli_connect('127.0.0.1', 'root', 'Xlxygp95', 'notebook');
mysqli_set_charset($link, 'utf8');

/*** 翻页 ***/
//1.取出总得记录数
$rs = mysqli_query($link, "select count(*) from blog $whereSQL");
//从这个资源中取出一条记录,返回的是数组，并取出数组中的第0个元素就是总的记录数
$count = mysqli_fetch_row($rs)[0];
//2.调用函数生成翻页,返回一个数组
$pageRet = makePage($count, 5);

//3.把翻页函数返回的limit放到SQL语句上
$rs = mysqli_query($link, "select a.*,b.username,b.face from blog a left join member b on a.member_id=b.id $whereSQL order by $orderby $orderway limit $pageRet[limit]");
$data = [];
while ($row = mysqli_fetch_assoc($rs)) {
    $data[] = $row;
}
/*** 加载视图 ***/
include('html/index.html');

/*** 制作翻页 ***/
// $count 总的记录数
// $perPage 每页显示数【默认10条】
function makePage($count, $perPage = 10)
{
    //1.计算总的页数
    //计算总得页数 = ceil（总得记录数 / 每页显示的条数）;
    $pageCount = ceil($count / $perPage); //ceil:向上取整

    //2.接受当前是第几条的变量【必须是一个大于1的整数】
    //接受页码，默认是第1页
    //max(1,(int)$_GET['p']:确保是一个大于等于的整数
    $p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;

    //3.限制当前页不能超过总页数
    //当前总页数不等于0时要限制一下当前页不能大于总页数
    //如果当前页大于总页数，就设置当前页等于总页数
    if ($pageCount > 0 && $p > $pageCount)
        $p = $pageCount;

    //4.计算limit的第一个参数【从第几条记录开始取】
    $offset = ($p - 1) * $perPage; //(当前页-1)*第页显示的字数

    //5.拼出翻页的字符串
    //并翻页字符串
    $pageString = '<div class="page">';
    //当有记录才拼出翻页的按钮
    if ($pageCount > 0) {
        /*** 获取当前所有URL后面的参数 ***/
        //把原来的get参数拼成一个URL形式的字符串
        $oldPara = '';
        $oldParaHidden = '';
        //如果不为空说明有参数就拼一个URL字符串
        if ($_GET) {
            //把原来的p删除
            unset($_GET['p']);
            foreach ($_GET as $k => $v) {
                //拼出字符串放到翻页的按钮上
                $oldPara .= "$k=$v&";
                //拼出隐藏域放到跳转的表单中
                $oldParaHidden .= "<input type='hidden' name='$k' value='$v'>";
            }
        }

        //如果大于第一页才有上一页
        if ($p > 1)
            $pageString .= '<a href="index.php?' . $oldPara . 'p=' . ($p - 1) . '">上一页</a>';

        /*** 仿京东，只显示7个页码按钮 ***/
        if ($p <= 5) {
            $start = 1;
            //如果总页数大于7就到7，如果总的页数小于7就到总得页数
            $end = min(7, $pageCount);
        } else if ($p >= 6 && $p <= $pageCount - 3) {
            //加上1，2页和三个点
            $pageString .= '<a href="index.php?' . $oldPara . 'p=1">1</a>';
            $pageString .= '<a href="index.php?' . $oldPara . 'p=2">2</a>';
            $pageString .= ' ... ';

            $start = $p - 2;
            $end = $p + 2;
        } else {
            //加上1，2页和三个点
            $pageString .= '<a href="index.php?' . $oldPara . 'p=1">1</a>';
            $pageString .= '<a href="index.php?' . $oldPara . 'p=2">2</a>';
            //总页数大于7时需要加前三点
            if ($pageCount > 7)
                $pageString .= ' ... ';

            $start = $pageCount - 4;
            $end = $pageCount;
        }
        //从第1页开始循环制作按钮
        for ($i = $start; $i <= $end; $i++) {
            //如果是当前页是价格active样式
            if ($i == $p)
                $pageString .= "<a class='active' href='index.php?{$oldPara}p={$i}'>{$i}</a>";
            else
                $pageString .= "<a href='index.php?{$oldPara}p={$i}'>{$i}</a>";
        }

        //当前最后一个页码如果小于总页数
        if ($end < $pageCount) {
            $pageString .= ' ... ';
        }

        //如果当前页小于最后一页
        if ($p < $pageCount)
            $pageString .= '<a href="index.php?' . $oldPara . 'p=' . ($p + 1) . '">下一页</a>';

        $pageString .= "共{$pageCount}页，跳转<form style='display:inline;' action='index.php'>{$oldParaHidden}<input value='{$p}' type='text' size='3' name='p'>页<input type='submit' value='跳转'></form>";
    } else {
        $pageString .= '当前没有记录!';
    }
    $pageString .= '</div>';

    //7.返回制作好的翻页数据
    return [
        'pageString' => $pageString, //翻页字符串
        'limit' => "$offset,$perPage", //返回LIMIT参数
    ];
}