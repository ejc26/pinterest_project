<?php
session_start();
ini_set('session.gc_maxlifetime',300);
if($_GET['action'] == "logout"){
    unset($_SESSION['uid']);
    unset($_SESSION['uname']);
    unset($_SESSION['state']);
    unset($_SESSION['curtime']);
    echo 'logout success <a href="login.html">login</a>';
    exit;
}

include('conn.php');
$uid=$_SESSION['uid'];
 
date_default_timezone_set('America/New_York');
$time = date('Y-m-d H:i:s');

if($_GET['action'] == "accept"){
    $fromid=$_POST['fromid'];
    $response = mysql_query("UPDATE friendship SET status='accept' where fromid=$fromid and toid=$uid and status ='1'");
}
if($_GET['action'] == "refuse"){
    $fromid=$_POST['fromid'];
    $response = mysql_query("UPDATE friendship SET status='refuse' where fromid=$fromid and toid=$uid and status ='1'");
}

if($_GET['action'] == "ignore"){
    $fromid=$_POST['fromid'];
    $response = mysql_query("delete from friendship where fromid=$fromid and toid=$uid");
}
if(isset($_POST['Search'])){
	$un=$_POST['unameS'];
	if($un != null){
		$search= "SELECT uid, uname FROM user WHERE uname like '%$un%' and uid !='$uid' and (uid not in (select u.uid from friendship f, user u where f.fromid=u.uid and f.toid=$uid and status='accept')) and (uid not in (select u.uid from friendship f, user u where f.fromid='$uid' and f.toid = u.uid and status='accept'))";
	}
}

if($_GET['action'] == "friendrequest"){
	$fuid=$_POST['fuid'];
    $requestSent="INSERT INTO friendship (fromid,toid, requesttime,status) VALUES('$uid','$fuid','$time',1)";
	mysql_query($requestSent);
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
<form action="search.php" method="get">
<div id="searchfield">
<input type="text" name="keyword" class="keyword" /> 
<input class="searchbutton" type="image" src="images/searchgo.gif"  alt="search" /></div>

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
		<h4>Friend Request</h4>
		<p>
		<?php
		$friend_request = mysql_query("select u.uname, u.uid ,f.requesttime, f.status from user u, friendship f where f.fromid = u.uid and f.toid=$uid and f.status='1'");
		echo "<table border='1' style='margin-left:8px'>
		<tr>
		<th>requester</th>
		<th>request_time</th>
		<th>response</th>
		</tr>";

        while($row = mysql_fetch_array($friend_request))
        {
        	echo "<tr>";
            echo "<td align='center'>
                  <input type='text' name='requester' value=".$row['uname']." readonly='readonly' /></td>
        		  <td align='center'><input type='text' name='senttime' value=".$row['requesttime']." readonly='readonly' /></td>";
        	echo"<td>" ;
            echo "<form action='friend.php?action=accept' method='POST'>
                  <input type='hidden' name='fromid' value=".$row['uid'].">
        	      <input type='submit' name='accept' value='accept' ></form> ";
        	  echo"<form action='friend.php?action=refuse' method='POST'>
                  <input type='hidden' name='fromid' value=".$row['uid'].">
                  <input type='submit' name='refuse' value='refuse' ></form> ";
              echo"<form action='friend.php?action=ignore' method='POST'>
                  <input type='hidden' name='fromid' value=".$row['uid'].">
                  <input type='submit' name='ignore' value='ignore' ></form> ";
        	  echo " </td>";
              echo" </tr>";
        	     
        	     
        }
       
        echo "</table>"; 
		?>		
		</p>
		</div>
		<div class="post">
		<h4> Search Friend </h4>
		<form action="friend.php" method="post" >
		<p><strong>uname:</strong> <input type="text" name="unameS" />
		<input type="submit" value="Search" name="Search" style="margin-left:30px;font-size:30px"/>
		</p>
		</form>
		<p>
		<?php
		include('conn.php');
		$friend = @mysql_query($search);
		echo "<table border='1' style='margin-left:8px'>";
		while($r= @mysql_fetch_array($friend)){
			echo "<td>".'<a href = "viewprofile.php?id='.$r['uid'].'">'.$r['uname']."</td>";
			echo"<td><form action='friend.php?action=friendrequest' method='post'>
					  <input type = 'hidden' name='fuid' value = ".$r['uid'].">
					 <input type='submit' name='request' value='request'></form></td>";
		}
		echo "</table>";
		?>
		</p>
		</div>
		<div class="post">
		<h4> Friends </h4>
		<p>
		<?php
		$all_friend="(SELECT uid, uname FROM friendship f, user u WHERE f.toid = u.uid AND f.fromid = '$uid' AND STATUS = 'accept') UNION (SELECT uid, uname FROM friendship f, user u WHERE f.toid = '$uid' AND f.fromid = u.uid AND STATUS =  'accept')";
		$a = mysql_query($all_friend);
		echo "<table border='1' style='margin-left:8px'>";
		while($b= mysql_fetch_array($a)){
			echo "<td align='center'><a href = 'viewprofile.php?id=".$b['uid']."'>".$b['uname']."</td>";
		}
	
		echo "</table>";
	
		mysql_close($conn);
		?>
		</p>

		</div>

		<div id="footer">
			<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
		</div>
	</body>
</html>
