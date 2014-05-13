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
$vuid=$_GET['id'];
$home=mysql_query("select uname from user where uid =$vuid");
$vname=$home['uname'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Homepage</title>
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
<div id="searchbar">
<form action="#">
<div id="searchfield">
<input type="text" name="keyword" class="keyword" /> <input class="searchbutton" type="image" src="images/searchgo.gif"  alt="search" /></div>

</form>

</div>

</div>
<div id="menu">
<ul>
<li><a href="recommendation.php"><span>Recommendation</span></a></li>
<li><a href="boards.php"><span>Boards</span></a></li>
<li><a href="profile.php"><span>MyAccount</span></a></li>
<li><a href="followstream.php"><span>Followstream</span></a></li>
<li class="active"><a href="friend.php"><span>Friends</span></a></li>

</ul>
</div>
<div id="contentwrap">

<div id="header">


</div>
<div id="mainpage" class="normalpage">
<div id="right" class="widepage">
<div class="post">
<h2><a href="#">My Profile</a></h2>
		<?php
		$user_info = mysql_query("select * from user where uid=$vuid");
		if($result=mysql_fetch_array($user_info)){
			echo "<p> <strong> userName: </strong> ".$result['uname']."</p>";
			echo "<p> <strong> email: </strong> ".$result['email']."</p>";
			echo "<p> <strong> birthday: </strong> ".$result['bday']."</p>";
			echo "<p> <strong> gender: </strong> ".$result['gender']."</p>";
			echo "<p> <strong> interest: </strong> ".$result['interest']."</p>";
			echo "<p> <strong> facebook URL: </strong> ".$result['facebook']."</p>";	
		}
		
		?>
		<h2><a href="#">My boards</a></h2>
		  
		<p>
		<?php
		
		$my_board="SELECT bid,bname FROM board WHERE uid='$vuid'";
		$my = mysql_query($my_board);
		echo "<table border='1' style='margin-left:8px'>";
		while($mybll= mysql_fetch_array($my)){ 
			echo "<td>".'<a href="followboard.php?id='.$mybll['bid'].'".>'.$mybll['bname']."</td>";
			echo"</tr>";
		}
	
		echo "</table>";
		

		?>
		</p>
		</div>
		<div class="post">

	    <h2><a href="#">My Followstream:</a></h2>

		<p>
		<?php
		 $u=$_SESSION['uid'];
		$sql ="(select fname, fid from followstream where uid='$vuid'and fprivacy='NULL') union (select fname, fid from followstream, friendship where uid='$vuid'and fprivacy= 'friends' and uid=fromid and toid=$uid and status='accept') union  (select fname, fid from followstream, friendship where uid='$vuid'and fprivacy= 'friends' and uid=toid and fromid=$uid and status='accept') ";
		
		
		 $host_follow = mysql_query($sql);
			echo "<br><table border='1' style='margin-left:8px'></br>
				  <tr>
				  <th>streamname</th>
				  <th>boards</th>
				  </tr>";
		 while($result = mysql_fetch_array($host_follow)){
			echo"<tr>
				 <td><input type='hidden' name='fid' size='3' value= ".$result['fid']. " readonly='readonly' ";
			     echo "<td align='center'>".$result['fname']."</td>
				 <td align='center'>";
				 $host_tag = $result['fid'];
				 $host_follow_tag = mysql_query("select bid, bname from follow natural join board where fid = '$host_tag'");
				 while($h_tag = mysql_fetch_array($host_follow_tag)){
					echo "".'<a href="followboard.php?id='.$h_tag['bid'].'".>'.$h_tag['bname']."";
					}
				echo "</tr>";
			}
		
		echo "</table>";
		echo "</div>";
		?>
		</p>
		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
		</div>
	</body>
</html>