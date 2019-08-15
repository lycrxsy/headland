<?php
include_once './lib/fun.php';
if(!checkLogin())
{
    msg(2, 'login please', 'login.php');
}
$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';
//if the id dose not exist, then return the list
if(!$goodsId)
{
    msg(2, 'failed', 'index.php');
}
//based on the ID to check the date detail
$con = mysqlInit('127.0.0.1', 'root', 'LYC2bll?', 'headline');
$sql = "SELECT `id` FROM `im_goods` WHERE `id` = {$goodsId}";
$obj = mysqli_query($con,$sql);
//if the id dose not exist, then return the list
if(!$goods = mysqli_fetch_assoc($obj))
{
    msg(2, 'picture does not exist', 'index.php');
}
//delete date
$sql = "DELETE FROM `im_goods` where `id` = {$goodsId} LIMIT 1";

if($result = mysqli_query($con,$sql))
{
    //mysql_affected_rows()
    msg(1, 'successful', 'index.php');
}
else
{
    msg(2, 'failed', 'index.php');
}


