	<?php
			session_start();
			ini_set('session.gc_maxlifetime',300);
			//注销登录
			if($_GET['action'] == "logout"){
			    unset($_SESSION['uid']);
			    unset($_SESSION['uname']);
			    unset($_SESSION['state']);
			    unset($_SESSION['curtime']);
			    unset($_SESSION['bid']);
			    echo 'logout sucess <a href="login.html">login</a>';
			    exit;
			}

			
		 include('conn.php');
		$uid=$_SESSION['uid'];
		if($_GET['id']){
		$_SESSION['bid']=$_GET['id'];
		$bid=$_SESSION['bid'];}
		else{$bid=$_SESSION['bid'];}


        date_default_timezone_set('America/New_York');
        $time = date('Y-m-d H:i:s');

    if($_GET['action'] == "comment"){
	$c=$_POST['comtext'];
	$pid=$_POST['pid'];
	$bid=$_SESSION['bid'];
	$add_com=mysql_query("INSERT INTO comment (uid,pid,bid,ctext,ctime) VALUES ('$uid','$pid','$bid','$c','$time')");

}


if($_GET['action'] == "likenum"){
	$pid=$_POST['pid'];
	

	$uid=$_SESSION['uid'];

	$add_like= mysql_query("INSERT INTO likenum (uid,pid,ltime) VALUES ('$uid','$pid','$time')");
	}
	
	if($_GET['action'] == "createrepin"){
	$pid=$_POST['pid'];
	$bid=$_SESSION['bid'];
	$abid=$_POST['pinboard'];
	$addrepin= mysql_query("INSERT INTO pin (pid,bid,ptime,prebid) VALUES ('$pid','$abid','$time','$bid')");
	//mysql_query($addrepin);
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
		<title>Picture</title>
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
	<input type="text" name="keyword" placeholder="Search..." class="keyword" /> 
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
			
             <h2><a href="#">Picture Showing:
              <?php
				$uid=$_SESSION['uid'];
			    if($_GET['id']){
				$_SESSION['bid']=$_GET['id'];
				$bid=$_SESSION['bid'];}
				else{$bid=$_SESSION['bid'];}
			   echo "<form action='followstream.php?action=follow' method='post'>
					  <input type='hidden' name='bid'  value= ".$bid. "> ";
			    $follow_add = mysql_query("select fid, fname from followstream where uid = '$uid'");
				echo" <p><select name='followboard'>";
				while($f_add=mysql_fetch_array($follow_add)){
				echo"<option value=".$f_add['fid'].">".$f_add['fname']."</option>";
					}
					echo"</select>
				<input type ='submit' name= 'follow' value ='  follow  '/></td></p>"; 
				echo"</form>";
				?></a>			
				</h2>
				
			
			
			<?php

				$all_picture="(SELECT pid, pname, descript,local, URL, tag, prebid FROM board NATURAL JOIN pin natural join picture WHERE bid= '$bid')";
				
				$allpic = mysql_query($all_picture);
	            while($showpic= mysql_fetch_array($allpic)){ 
		        echo "<div class='ftcontent'>".$showpic['pname']."</b></strong></font></br><div>";	           echo "<div class='ftcontent'><img src = ".$showpic['local']." width='550' height='350' alt='image' class='hboxthumb'></div>";
				echo "<br><strong>Description:</strong>".$showpic['descript']."</br>";	            echo "<p><strong>Tag: </strong>&nbsp".$showpic['tag']."</p>";
				
		        
		
//like

echo "<form action='followboard.php?action=likenum' method='post'>
                      <p><input type = 'hidden', name = 'pid', value = ".$showpic['pid'].">
              
		              <input type='submit' name='likenum' value=' like '/></p>
		               </form>";
//repin
echo "<form action='followboard.php?action=createrepin' method='post'>
      <p><input type = 'hidden', name = 'pid', value = ".$showpic['pid']."/>";
$repin_add = mysql_query("select bid, bname from board where uid = '$uid'");
echo" <p><select name='pinboard'>";
while($r_add=mysql_fetch_array($repin_add)){
     echo"<option value=".$r_add['bid'].">".$r_add['bname']."</option>";
}
echo"</select>
     <input type ='submit' name= 'createrepin' value ='  repin  '/></p>"; 
     echo"</form>";    
   //comment           
  echo "<form action='followboard.php?action=comment' method='post'>
                      <p><input type = 'hidden', name = 'pid', placeholder='Add a comment...' value = ".$showpic['pid']."/>
		              <input type = 'text', name = 'comtext'/>  
		              <input type='submit' name='comment' value=' comment'/></p>"; 
				 echo "<br><table border='1' style='margin-left:8px'></br>";
		
		$pid= $showpic['pid'];
		$bid=$_SESSION['bid'];
		$comment=mysql_query("select uname,ctext,ctime from comment natural join user where pid= '$pid' and bid ='$bid'");
		while($res=mysql_fetch_array($comment)){
			echo "<tr>
				  <td align='center'>".$res['uname']."</td>
				  <td align='center'>".$res['ctext']."</td>
				  <td align='center'>".$res['ctime']."</td>";
		}
				echo "</form>";
	            echo "</table>";
	

		}
		
		
			    mysql_free_result($allpic);
				
				?>
				
				
													
								</div>                   
							
				</p>
				</div>                   
				</div>
				
			    </div>
                </div>
				<div id="footer">
					<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
				</div>
				</div>
			</body>
		</html>
