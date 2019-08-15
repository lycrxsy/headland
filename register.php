<?php
//handle the form
if(!empty($_POST['username']))
{
    include_once './lib/fun.php';
    $username = trim($_POST['username']);//mysql_real_escape_string() filter
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);
    //username not empty
    if(!$username)
    {
        msg(2, 'input username');
    }
    if(!$password)
    {
        msg(2, 'password can not be empty');

    }
    if(!$repassword)
    {
        msg(2,'repassword can not be empty');
    }
    if($password !== $repassword)
    {
        msg('confirmed password and new password do not match,input again');
    }
    //database connection
    $con = mysqlInit('127.0.0.1', 'root', 'LYC2bll?', 'headline');
    if(!$con)
    {
        echo mysqli_errno($con);
        exit;
    }
    //check if the username exist
    $sql = "SELECT COUNT(  `id` ) as total FROM  `im_user` WHERE  `username` =  '{$username}'";
    $obj = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($obj);
    if(isset($result['total']) && $result['total'] > 0)
    {
        msg(2,'User name already exits, choose another user name');
    }
    //encode password
    $password = createPassword($password);
    unset($obj, $result, $sql);
    //insert data in database
    $sql = "INSERT `im_user`(`username`,`password`,`create_time`) values('{$username}','{$password}','{$_SERVER['REQUEST_TIME']}')";
    $obj = mysqli_query($con,$sql);
    if($obj)
    {
        msg(1,'register successful','login.php');
//        $userId = mysql_insert_id();//
//
//        echo sprintf('successful:%s,userid:%s', $username, $userId);
//        exit;
    }
    else
    {
        msg(2,mysqli_errno($con));
//        echo mysqli_error();
//        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEADLAND|user register</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <link rel="stylesheet" type="text/css" href="./static/css/login.css">
</head>
<body>
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
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>user log in</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="register.php" method="post">
                        <div class="login-left">
                            <label class="username">username</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">password</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-right">
                            <label class="passwd">re-password</label>
                            <input type="password" class="yhmiput" name="repassword" placeholder="Repassword"
                                   id="repassword">
                        </div>
                        <div class="login-btn">
                            <button type="submit">register</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span> ©2019 POWERED BY HEADLAND</p>
</div>

</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val();
            if (username == '' || username.length <= 0) {
                layer.tips('username can not be empty', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }
            if (password == '' || password.length <= 0) {
                layer.tips('password can not be empty', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }
            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('confirmed password and new password do not match,input again', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }
            return true;
        })

    })
</script>
</html>


