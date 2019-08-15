<?php
include_once './lib/fun.php';
if(!checkLogin())
{
    msg(2, 'log in please', 'login.php');
}
$user = $_SESSION['user'];
// check if the url exist
$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';
// if no id found,return list
if(!$goodsId)
{
    msg(2,'参数非法','index.php');
}
//search for the production based on id
$con = mysqlInit('127.0.0.1', 'root', 'LYC2bll?', 'headline');
$sql = "SELECT * FROM `im_goods` WHERE `id` = {$goodsId}";
$obj = mysqli_query($con,$sql);
//if the id is empty,return the list
if(!$goods = mysqli_fetch_assoc($obj))
{
    msg(2,'production does not exist','index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEADLAND|edit</title>
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
            <header>edit</header>
            <form name="publish-form" id="publish-form" action="do_edit.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">Name</label><input type="text" name="name" id="name" placeholder="input name" value="<?php echo $goods['name']?>">
                </div>
                <div class="additem">
                    <label id="for-price">Price</label><input type="text" name="price" id="price" placeholder="input price" value="<?php echo $goods['price']?>" >
                </div>
                <div class="additem">
                    <!-- accept html5 accept png gif jpeg type    -->
                    <label id="for-file">Painting</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file" name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">Painting Description</label>
                    <textarea id="des" name="des" placeholder="Write Painting description"><?php echo $goods['des']?></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">Painting Detail</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"><?php echo $goods['content']?></textarea>
                    </div>
                </div>
                <div style="margin-top: 20px">
                    <input type="hidden" name="id" value="<?php echo $goods['id']?>">
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
            //编辑器失去焦点时直接同步，可以取到值
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
                layer.tips('画品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

//            if (file == '' || file.length <= 0) {
//                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
//                $('#file').focus();
//                return false;
//
//            }

            if (des.length <= 0 || des.length >= 100) {
                layer.tips('画品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入画品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;

        })
    })
</script>
</html>
