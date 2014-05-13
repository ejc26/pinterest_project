<?php
session_start();
ini_set('session.gc_maxlifetime',300);
//注销登录
if(@$_GET['action'] == "logout"){
    unset($_SESSION['uid']);
    unset($_SESSION['uname']);
    unset($_SESSION['state']);
    unset($_SESSION['curtime']);
    echo 'logout success <a href="login.html">login</a>';
    exit;
}

//登录
if(!isset($_POST['login'])){
    exit('invalid visit!');
}

$uname = $_POST['uname'];
$pw = $_POST['pw'];

//包含数据库连接文件
include('conn.php');
//检测用户名及密码是否正确
$check_query = mysql_query("select uid from user where uname='$uname' and pwd='$pw' 
limit 1");

if($result = mysql_fetch_array($check_query)){
    //登录成功
    $_SESSION['uname'] = $uname;
    $_SESSION['uid'] = $result['uid'];
echo"<script>alert('login success');location.href= 'recommendation.php';</script>";
 
} else {
    exit('login fail! Click here <a href="javascript:history.back(-1);">back</a> try again');
}

mysql_close($conn)
?>


		