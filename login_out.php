<?php
include_once './lib/fun.php';
session_start();
//unset user
unset($_SESSION['user']);
msg(1, 'log out successful!', 'index.php');