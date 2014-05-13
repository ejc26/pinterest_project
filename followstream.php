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

// Logout;

	//creat a board
if($_GET['action'] == "stream"){
	$t=$_POST['streamname'];
	echo"$t";
	$uid = $_SESSION['uid'];
	$s = $_POST['privacy_select'];
	echo"$s";
	$add_stream = mysql_query("INSERT INTO followstream(uid, fname, fprivacy) VALUES ('$uid', '$t', '$s') ");
}
//delete stream
if($_GET['action'] == "delete"){
		$fid=$_POST['dfid'];
		$follow_delete = "delete from follow where fid = '$fid'";
		$followstream_delete = "delete from followstream where fid = '$fid'";
		mysql_query($follow_delete);
		mysql_query($followstream_delete);
		echo "delete is succsseful!";
	}  

if($_GET['name'] == "deleteboard")
   { $bid=$_POST['bid'];
	 $fid=$_POST['fid'];
	 $follow_board_delete = "delete from follow where bid = $bid and fid = $fid";
	 mysql_query($follow_board_delete);
 }

if($_GET['action'] == "follow"){
	$fid=$_POST['followboard'];

	$bid = $_POST['bid'];

	$add_follow = mysql_query("INSERT INTO follow(fid, bid, followtime) VALUES ('$fid', '$bid', '$time') ");
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Followstream</title>
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
		<li class="active"><a href="followstream.php"><span>Followstream</span></a></li>
		<li><a href="friend.php"><span>Friends</span></a></li>

		</ul>
		</div>
		<div id="contentwrap">

		<div id="header">


		</div>
		<div id="mainpage" class="normalpage">
		<div id="right" class="widepage">
		<div class="post">
		<h2><a href="#">Create Followstream:</a></h2>
		
			<p>
			<form action='followstream.php?action=stream' method='post'>
			<!--下拉式菜单显示state-->
			<strong>Privacy:</strong>
			<!--<input type = "select" name = "sch_date"> -->
			<select name='privacy_select' method='post'>
				<option value = 0 >Public</option>
				<option value = "friends">Only friends</option>
			
			</select>
			<br><strong>Followstream Name:</strong> 
		      <p><textarea cols='40' rows='1' name='streamname'></textarea>
		      <input type="submit" name="stream" value="create"></br></p>
		</form>
		    <form action='followstream.php?action=boardsearch' method='get'>
		    <div id="searchfield">
		    <input type="text" name="keyword" class="keyword" />
		    <input class="searchbutton" type="image" src="images/searchgo.gif" alt="search" /></div>
		  </form>

		<?php
		if(isset($_GET['keyword'])){$keyword=$_GET['keyword'];
		$search= "(SELECT bid, bname FROM board WHERE bname like '%$keyword%' and uid<>'$uid') UNION (SELECT bid, bname FROM board natural join follow natural join followstream WHERE fname like '%$keyword%' and uid<>'$uid') ";

		while($showboard=mysql_fetch_array($keyword_board)){echo"<div class='ftcontent'>".'<a href="followboard.php?id='.$showboard['bid'].'"./>'.$showboard['bname']."</a></div>";
		}

		}
		?>

	    <h2><a href="#">My Followstream:</a></h2>
		<p>
		<?php
		 include('conn.php');
		 $uid=$_SESSION['uid'];
		$followstream=mysql_query("select distinct fname, fid from followstream where uid='$uid'");
		
			echo "<table border='1' style='margin-left:8px'>
				  <tr>
				  <th>streamname</th>
				  <th>boards</th>
				  <th>operation</th>
				  </tr>";
		 
		
		
		
		 //while($result = mysql_fetch_array($user_follow)){
			while($result=mysql_fetch_array($followstream)){
			echo"<tr>
				 <td><input type='hidden' name='fid' size='3' value= ".$result['fid']. " readonly='readonly' ";
			     echo "<td align='center'>".$result['fname']."</td>
				 <td align='center'>";
				$fid_tag = $result['fid'];
                $follow_tag = mysql_query("select bid, bname from follow natural join board where fid = '$fid_tag'");
				 while($f_tag = mysql_fetch_array($follow_tag)){
					echo "".'<a href="followboard.php?id='.$f_tag['bid'].'".>'.$f_tag['bname']."";
					echo"<form action='followstream.php?name=deleteboard' method='post'>
					     <input type = 'hidden' name='bid' value = ".$f_tag['bid'].">
		                 <input type = 'hidden' name='fid' value = ".$fid_tag.">
						  <input type='submit' name='delete' value='unfollow'></form>";
					echo "&nbsp &nbsp";
				 }
				 echo "</td>";
				echo"<td align='center'>
				     <form action='followstream.php?action=delete' method='post'>
				     <input type = 'hidden', name = 'dfid' value =".$result['fid']."/>
				     <input type='submit' name='delete' value='delete'/> </form></td>";
				echo "</tr>";
		}
		echo "</table>";
		echo "</div>";

			mysql_close();
		?>
		</p>
		</div>
		<div id="footer">
			<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
		</div>
	</body>
</html>
