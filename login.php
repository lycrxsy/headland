<?php

include_once './lib/fun.php';
//check if already login or not.
if(checkLogin())
{
    msg(1,'you have already login','index.php');
}
//handle the form commit
if(!empty($_POST['username']))
{
    $username = trim($_POST['username']);//mysql_real_escape_string()进行过滤
    $password = trim($_POST['password']);
    //check if the user is empty
    if(!$username)
    {
        msg(2, 'username can not be empty');
    }
    if(!$password)
    {
        msg(2, 'password can not be empty');
    }
    //database connection
    $con = mysqlInit('localhost', 'root', 'LYC2bll?', 'headline');
    if(!$con)
    {
        echo mysqli_errno();
        exit;
    }
    //search for the user
    $sql = "SELECT * FROM `im_user` WHERE `username` = '{$username}' LIMIT 1";
    $obj = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($obj);
    if(is_array($result) && !empty($result))
    {
        if(createPassword($password) === $result['password'])
        {
            $_SESSION['user'] = $result;
            header('Location:index.php');
            exit;
        }
        else
        {
            msg(2, 'password is incorrect');
        }
    }
    else
    {
        msg(2, 'username do not find');

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEADLAND|User log in</title>
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
                        <p>log in please</p>
                    </div>
                    <form class="login-table" name="login" id="login-form" action="login.php" method="post">
                        <div class="login-left">
                            <label class="username">username</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">password</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-btn">
                            <button type="submit">log in</button>
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
        $('#login-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val();
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
            return true;
        })
    })
</script>
</html>