<?php
include_once './lib/fun.php';
$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

//if no date found,return list
if(!$goodsId)
{
    msg(2,'wrong url','index.php');
}
$con = mysqlInit('127.0.0.1', 'root', 'LYC2bll?', 'headline');

$sql = "SELECT * FROM `im_goods` WHERE `id` = {$goodsId}";
$obj = mysqli_query($con,$sql);

if(!$goods = mysqli_fetch_assoc($obj))
{
    msg(2,'production does not exist','index.php');
}
//publisher
unset($sql,$obj);
$sql = "select * from `im_user` where `id`='{$goods['user_id']}'";
$obj = mysqli_query($con,$sql);
$user= mysqli_fetch_assoc($obj);
//view number
unset($sql,$obj);
$sql = "update `im_goods` set `view`=`view`+1 where `id`={$goods['id']}";
mysqli_query($con,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEADLAND|<?php echo $goods['name']?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css" />
    <link rel="stylesheet" type="text/css" href="./static/css/detail.css" />
</head>
<body class="bgf8">
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">log in</a></li>
            <li><a href="register.php">register</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="section" style="margin-top:20px;">
        <div class="width1200">
            <div class="fl"><img src="<?php echo $goods['pic']?>" width="720px" height="432px"/></div>
            <div class="fl sec_intru_bg">
                <dl>
                    <dt><?php echo $goods['name']?></dt>
                    <dd>
                        <p>publisher：<span><?php echo $user['username']?></span></p>
                        <p>publish time：<span><?php echo date('Y-m-d',$goods['create_time'])?></span></p>
                        <p>edit time：<span><?php echo date('Y-m-d',$goods['update_time'])?></span></p>
                        <p>viewer：<span><?php echo $goods['view']?></span></p>
                    </dd>
                </dl>
                <ul>
                    <li>Price：<br/><span class="price"><?php echo $goods['price'] ?></span>EUR</li>
                    <li class="btn"><a href="javascript:;" class="btn btn-bg-red" style="margin-left:38px;">Buy now</a></li>
                    <li class="btn"><a href="javascript:;" class="btn btn-sm-white" style="margin-left:8px;">My list</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="secion_words">
        <div class="width1200">
            <div class="secion_wordsCon">
                <?php echo $goods['content']?>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2019 POWERED BY HEADLAND</p>
</div>
</div>
</body>
</html>

