<?php
session_start();
ini_set('session.gc_maxlifetime',300);

if($_GET['action'] == "logout"){
    unset($_SESSION['uid']);
    unset($_SESSION['uname']);
    unset($_SESSION['state']);
    unset($_SESSION['curtime']);
    echo 'logout sucess <a href="login.html">login</a>';
    exit;
}

include('conn.php');
$uid=$_SESSION['uid'];

if($_GET['action'] == "updatefile"){
$birthday=$_POST['birthday'];
echo($birthday);
$email=$_POST['email'];
$interest=$_POST['interest'];
$facebook=$_POST['facebook'];
if(isset($_POST['birthday'])||isset($_POST['email'])||isset($_POST['interest'])||isset($_POST['facebook'])){
	$info_update = "UPDATE user SET bday='$birthday', email='$email',facebook='$facebook',interest='$interest' where uid='$uid'";
	mysql_query($info_update);

}
}
if($_GET['action'] == "change"){
$pw=$_POST['pw'];
$rpw=$_POST['repass'];
if(strlen($pw) < 3){
    exit('password length should be no less than 3 <a href="javascript:history.back(-1);">back</a>');
}
if($pw!=$rpw){
	exit('comfirmed password should be the same as the password you entered <a href="javascript:history.back(-1);">back</a>');
}
$password_update = "UPDATE user SET pwd='$pw' where uid='$uid'";
mysql_query($password_update);
echo "password has been changed successfully!";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>friend request</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrap">
<div id="top">
			<?php
		if($_SESSION['uid']!=null){
			echo "<a><p id='name'>".$_SESSION['uname']."</p></a>";
			echo "<a href='login.php?action=logout'><p id='logout'>logout</p></a>";
		} else {
			echo "<a href='login.html'><p id='name'>login</p></a>";
		}
		?>
<h1 id="sitename">Welcome <em>to</em> Pinterest</h1>

<div id="searchbar">
<form action="#">
<div id="searchfield">
<input type="text" name="keyword" class="keyword" /> <input class="searchbutton" type="image" src="images/searchgo.gif"  alt="search" /></div>

</form>

</div>

</div>
<div id="menu">
<ul>
<li><a href="index.html"><span>Recommendation</span></a></li>
<li><a href="boards.php"><span>Boards</span></a></li>
<li class="active"><a href="profile.php"><span>MyAccount</span></a></li>
<li><a href="followstream.php"><span>Followstream</span></a></li>
<li><a href="friend.php"><span>Friends</span></a></li>

</ul>
</div>
<div id="contentwrap">

<div id="header">


</div>
<div id="mainpage" class="normalpage">
<div id="right" class="widepage">
<div class="post">
<h2><a href="#">Registration Information</a></h2>
		<?php
		$user_info = mysql_query("select * from user where uid=$uid");
		if($result=mysql_fetch_array($user_info)){
			echo "<p> <strong> userName: </strong> ".$result['uname']."</p>";
			echo "<p> <strong> email: </strong> ".$result['email']."</p>";
			echo "<p> <strong> birthday: </strong> ".$result['bday']."</p>";
			echo "<p> <strong> gender: </strong> ".$result['gender']."</p>";
			echo "<p> <strong> interest: </strong> ".$result['interest']."</p>";
			echo "<p> <strong> facebook URL: </strong> ".$result['facebook']."</p>";	
		}
		echo"<br></br>";
		mysql_close($conn);
		?>
		<h2><a href="#">Update Profile</a></h2>
		
	    <form action='profile.php?action=updatefile' method='post'>
		<p> <strong>birthday:</strong> <input type="date" name="birthday" value="<?php echo $result['birthday'];?>"/></p>
		<p> <strong>interest:</strong> <input type="text" name='interest' value="<?php echo $result['interest'];?>"/></p>
		<p> <strong>facebook URL:</strong> <input type="text" name="facebook" value="<?php echo $result['facebook'];?>"/></p>
		<input type='submit' name='updatefile' value='update' style='margin-left:8px;font-size:20px' />
		</form>
		<br></br>
		<h2><a href="#">Change Password</a></h2>
		<form action='profile.php?action=change' method='post'>
		<p>
<label for="pw" class="label">password:</label>
<input id="pw" name="pw" type="password" class="input" />


<label for="repass" class="label">confirm password:</label>
<input id="repass" name="repass" type="password" class="input" />
<input type="submit" name="change" value="change" style='margin-left:8px;font-size:20px'/>
</p>
</form>
		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
		</div>
	</body>
</html>