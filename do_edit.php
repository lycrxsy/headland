<?php
//edit production
include_once './lib/fun.php';
if(!checkLogin())
{
    msg(2, 'log in please', 'login.php');
}
//handle form submit
if(!empty($_POST['name']))
{
    $con = mysqlInit('127.0.0.1', 'root', 'LYC2bll?', 'headline');
    if(!$goodsId = intval($_POST['id']))
    {
        msg(2, 'wrong url');
    }
    $sql = "SELECT * FROM `im_goods` WHERE `id` = {$goodsId}";
    $obj = mysqli_query($con,$sql);
    //if no id found,return list
    if(!$goods = mysqli_fetch_assoc($obj))
    {
        msg(2, 'production does not exist', 'index.php');
    }
    $name = mysqli_real_escape_string($con,trim($_POST['name']));
    //price
    $price = intval($_POST['price']);
    //description
    $des = mysqli_real_escape_string($con,trim($_POST['des']));
    //detail
    $content = mysqli_real_escape_string($con,trim($_POST['content']));
    $nameLength = mb_strlen($name, 'utf-8');
    if($nameLength <= 0 || $nameLength > 30)
    {
        msg(2, '画品名应在1-30字符之内');
    }
    if($price <= 0 || $price > 999999999)
    {
        msg(2, '画品名称应小于999999999');
    }
    $desLength = mb_strlen($des, 'utf-8');
    if($desLength <= 0 || $desLength > 100)
    {
        msg(2, '画品简介应在1-100字符之内');
    }
    if(empty($content))
    {
        msg(2, '画品详情不能为空');
    }
    $update = array(
        'name'    => $name,
        'price'   => $price,
        'des'     => $des,
        'content' => $content
    );
    //only change only when the data changed
    if($_FILES['file']['size'] > 0)
    {
        $pic = imgUpload($_FILES['file']);
        $update['pic'] = $pic;
    }
    foreach($update as $k => $v)
    {
        if($goods[$k] == $v)//对应key相等 删除要更新的字段
        {
            unset($update[$k]);
        }
    }
    if(empty($update))
    {
        msg(1, 'successful', 'edit.php?id=' . $goodsId);
    }
    $updateSql = '';
    foreach($update as $k => $v)
    {
        $updateSql .= "`{$k}` = '{$v}' ,";
    }
    $updateSql = rtrim($updateSql, ',');
    unset($sql, $obj, $result);
    $sql = "UPDATE `im_goods` SET {$updateSql} WHERE `id` = {$goodsId}";
    //update successful
    if($result = mysqli_query($con,$sql))
    {
        //mysql_affected_rows();//
        msg(1, 'successful', 'edit.php?id=' . $goodsId);
    }
    else
    {
        msg(2, 'successful', 'edit.php?id=' . $goodsId);
    }
}
else
{
    msg(2, 'url wrong', 'index.php');
}