<?php
include_once './lib/fun.php';
if(!checkLogin())
{
    msg(2, 'login please', 'login.php');
}
$user = $_SESSION['user'];
if(!empty($_POST['name']))
{
    $con = mysqlInit('127.0.0.1', 'root', 'LYC2bll?', 'headline');
    //pic name
    $name = mysqli_real_escape_string($con,trim($_POST['name']));
    //pic price
    $price = intval($_POST['price']);
    //pic description
    $des = mysqli_real_escape_string($con,trim($_POST['des']));
    //pic detail
    $content = mysqli_real_escape_string($con,trim($_POST['content']));
    $nameLength = mb_strlen($name, 'utf-8');
    if($nameLength <= 0 || $nameLength > 30)
    {
        msg(2, 'The name should contain 1-30 character');
    }
    if($price <= 0 || $price > 999999999)
    {
        msg(2, 'price must less than 999999999');
    }
    $desLength = mb_strlen($des, 'utf-8');
    if($desLength <= 0 || $desLength > 100)
    {
        msg(2, 'description should be 1-100 character');
    }
    if(empty($content))
    {
        msg(2, 'description can not be empty');
    }
    $userId = $user['id'];
    $now = $_SERVER['REQUEST_TIME'];
    $pic = imgUpload($_FILES['file']);
    //insert data
    $sql = "INSERT `im_goods`(`name`,`price`,`des`,`content`,`pic`,`user_id`,`create_time`,`update_time`,`view`) values('{$name}','{$price}','{$des}','{$content}','{$pic}','{$userId}','{$now}','{$now}',0)";
    if($obj = mysqli_query($con,$sql))
    {
        msg(1,'successful','index.php');
    }
    else
    {
      echo mysqli_error();exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEADLAND|Painting Publish</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><span>administrator：<?php echo $user['username'] ?></span></li>
            <li><a href="login_out.php">log out</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>Painting Publish</header>
            <form name="publish-form" id="publish-form" action="publish.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">Painting Name</label><input type="text" name="name" id="name" placeholder="input name">
                </div>
                <div class="additem">
                    <label id="for-price">Painting Price</label><input type="text" name="price" id="price" placeholder="input the price">
                </div>
                <div class="additem">
                    <!-- accept html5 png gif jpeg type film -->
                    <label id="for-file">Painting</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file"
                                                          name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">Painting Description</label>
                    <textarea id="des" name="des" placeholder="Write Painting description"></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">Painting Detail</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"></textarea>
                    </div>
                </div>
                <div style="margin-top: 20px">
                    <button type="submit">publish</button>
                </div>
            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2019 POWERED BY HEADLAND</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script src="./static/js/kindeditor/kindeditor-all-min.js"></script>
<script src="./static/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var K = KindEditor;
    K.create('#content', {
        width      : '475px',
        height     : '400px',
        minWidth   : '30px',
        minHeight  : '50px',
        items      : [
            'undo', 'redo', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'clearhtml',
            'fontsize', 'forecolor', 'bold',
            'italic', 'underline', 'link', 'unlink', '|'
            , 'fullscreen'
        ],
        afterCreate: function () {
            this.sync();
        },
        afterChange: function () {
            this.sync();
        }
    });
</script>
<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('The name should contain 1-30 character', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('price should be less than 999999999', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }
            if (file == '' || file.length <= 0) {
                layer.tips('choose pic please', '#file', {time: 2000, tips: 2});
                $('#file').focus();
                return false;
            }
            if (des.length <= 0 || des.length >= 100) {
                layer.tips('description should be 1-100 character', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }
            if (content.length <= 0) {
                layer.tips('input detail please', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;
        })
    })
</script>
</html>
