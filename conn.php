<?php
/*****************************
*db connection
*****************************/
$conn = @mysql_connect('localhost',"root","1234","Pinterest1");
if (!$conn){
    die("fail to connect database：" . mysql_error());
}
mysql_select_db("Pinterest1", $conn);
