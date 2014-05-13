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
if($_GET['action'] == "createboard"){
	$t=$_POST['boardname'];
	echo"$t";
	$uid = $_SESSION['uid'];
	$s = $_POST['privacy_select'];
	
	$add_board = mysql_query("INSERT INTO board(uid, bname, authority) VALUES ('$uid', '$t', '$s') ");
}
//delete a board
if($_GET['action'] == "delete"){
	
	//$bid 如何获取
	
	if(isset($_POST['bid'])){

		$bid=$_POST['bid'];
		$board_follow_delete = "delete from followstream where bid = $bid";
		$board_pin_delete = "delete from pin where bid = $bid";
		$board_delet = "delete from board where bid = $bid";//按照fid删除filter
		mysql_query($board_follow_delete);
		mysql_query($board_pin_delete);
		mysql_query($board_delete);
	}  
}	
 date_default_timezone_set('America/New_York');
        $time = date('Y-m-d H:i:s');	

if($_GET['action'] == "follow"){
	$fid=$_POST['followboard'];
	$bid = $_POST['bid'];
	$add_follow = mysql_query("INSERT INTO follow(fid, bid, followtime) VALUES ('$fid', '$bid', '$time') ");
}

if($_GET['action'] == "delete"){
		$bid=$_POST['bid'];
		$select_pic=mysql_query("select pid from picture natural join pin where bid=$bid");
		$pin_delete=mysql_query("delete from pin where bid = '$bid'");
		$repin_delete=mysql_query("delete from pin where prebid='$bid'");
		$like_delete=mysql_query("delete from likenum where bid = '$bid'");
		$comment_delete=mysql_query("delete from comment where bid = '$bid'");
		while($pic=mysql_fetch_array($select_pic)){
		mysql_query("delete from picture where pid = ".$pic['pid']."");
		}
		$board_delete = mysql_query("delete from board where bid = '$bid'");
		echo"<script>alert('delete successfully');</script>" ;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Board</title>
<link href="style.css" rel="stylesheet" type="text/css" />
		<!-datetime picker->
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
		<style type="text/css">
		.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; } 
		.ui-timepicker-div dl { text-align: left; } 
		.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px;} 
		.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; } 
		.ui-timepicker-div td { font-size: 90%; } 
		.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; } 
		.ui_tpicker_hour_label,.ui_tpicker_minute_label,.ui_tpicker_second_label, 
		.ui_tpicker_millisec_label,.ui_tpicker_time_label{padding-left:20px} 
				
		</style>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery-ui-slide.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
				
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
<li ><a href="recommendation.php"><span>Recommendation</span></a></li>
<li class="active"><a href="boards.php"><span>Boards</span></a></li>
<li ><a href="profile.php"><span>My Account</span></a></li>
<li ><a href="followstream.php"><span>Followstream</span></a></li>
<li ><a href="friend.php"><span>Friends</span></a></li>

</ul>
</div>
<div id="contentwrap">

<div id="header">


</div>
<div id="mainpage" class="normalpage">
<div id="right" class="widepage">		
		<div class="post">
		<h2><a href="#">Create Boards:</a></h2>
		
			<p>
			<form action='boards.php?action=createboard' method='post'>
			<!--下拉式菜单显示state-->
			<strong>Privacy:</strong>
			<!--<input type = "select" name = "sch_date"> -->
			<select name='privacy_select' method='post'>
				<option value = "NULL">Public</option>
				<option value = "friends">Only friends</option>
			
			</select>
			<strong>boardname:</strong> 
		      <p><textarea cols='40' rows='1' name='boardname'></textarea>
		      <input type="submit" name="createboard" value="create"></p>
		</form>
		
		
		
		</div>
		<div class="post">
		<h2><a href="#">My Boards:</a></h2>
		<p>
		<?php
		$all_board="(SELECT bid,bname FROM board WHERE uid=$uid)";
		$all = mysql_query($all_board);
		echo "<table border='1' style='margin-left:8px'>";
		while($bll= mysql_fetch_array($all)){ 
			echo "<td>".'<a href="picture.php?id='.$bll['bid'].'".>'.$bll['bname']."</td></a>";
			echo "<td><form action='boards.php?action=delete' method='post'>
								     <input type = 'hidden' name='bid' value = ".$bll['bid'].">
									  <input type='submit' name='delete' value='delete'></form></td>";
		
		
		}
	
		echo "</table>";
	
		mysql_close($conn);
		?>
		</p>
		
		<h2><a href="#">Visit Boards:</a></h2>
		<p>
		<?php
		 include('conn.php');
		 $uid=$_SESSION['uid'];
		 $sql =" SELECT distinct bname, bid, uname FROM board natural join user";
		 $add_follow = mysql_query($sql);
			echo "<table border='1' style='margin-left:8px'>
				  <tr>
				  <th>boardname</th>
				  <th>owner</th>
				  <th>add to followstream</th>
				  </tr>";
		 
		 while($result = mysql_fetch_array($add_follow)){
			echo"<tr>";
			    echo "<td>"."<a href='followboard.php?"."id=".$result['bid']."'>".$result['bname']."</td>";
			    echo "<td align='center'>".$result['uname']."</td>";

			    echo "<td><form action='boards.php?action=follow' method='post'>
			           <p><input type='hidden' name='bid' size='3' value= ".$result['bid']. " readonly='readonly'> ";
  $follow_add = mysql_query("select fid, fname from followstream where uid = '$uid'");
echo" <p><select name='followboard'>";
while($f_add=mysql_fetch_array($follow_add)){
     echo"<option value=".$f_add['fid'].">".$f_add['fname']."</option>";
}
echo"</select>
     <input type ='submit' name= 'follow' value ='  follow  '/></td></p>"; 
     echo"</tr>
          </form>";    
				     	
		}
		echo "</table>";
		echo "</div>";

			mysql_close();
		?>
		</p>
		</div>
		<script type="text/javascript">
			
			function show(){
				var leng=document.form1.privacy_select.length;
				leng=leng-1;
				var x=document.form1.privacy_select.options[leng].selected;
			
			
			
			function openMyWindow() { 
	            ret = window.showModalDialog("googlemaps.html",window, 'dialogWidth=1000px;dialogHeight=600px'); 
	            if (ret != null) 
	            { 
	              window.document.getElementById("loc_select").value = ret; 
	               
	            } 
	        }   
				

		</script>
		<div id="footer">
			<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
		</div>
	</body>
</html>