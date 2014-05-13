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

		 
		date_default_timezone_set('America/New_York');
		        $time = date('Y-m-d H:i:s');


               if($_GET['action'] == "comment"){
				$c=$_POST['comtext'];
				$pid=$_POST['pid'];
				echo($pid);
				$bid=$_POST['bid'];
				echo($bid);
				$add_com=mysql_query("INSERT INTO comment (uid,pid,bid,ctext,ctime) VALUES ('$uid','$pid','$bid','$c','$time')");

			}


			if($_GET['action'] == "likenum"){
				$pid=$_POST['pid'];
				echo($pid);
				$add_like= mysql_query("INSERT INTO likenum (uid,pid,ltime) VALUES ('$uid','$pid','$time')");
				}
				
		   if($_GET['action'] == "createrepin"){
				$pid=$_POST['pid'];
				$bid=$_SESSION['bid'];
				$abid=$_POST['pinboard'];
				$addrepin= mysql_query("INSERT INTO pin (pid,bid,ptime,prebid) VALUES ('$pid','$abid','$time','$bid')");
				//mysql_query($addrepin);
			 }


		?>


		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>MyPinboard</title>
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
		<li class="active"><a href="recommendation.php"><span>Recommendation</span></a></li>
		<li><a href="boards.php"><span>Boards</span></a></li>
		<li><a href="profile.php"><span>MyAccount</span></a></li>
		<li><a href="followstream.php"><span>Followstream</span></a></li>
		<li><a href="friend.php"><span>Friends</span></a></li>

		</ul>
		</div>
		<div id="contentwrap">

		<div id="header">


		</div>
		<div id="mainpage">
		<div id="right" class="widepage">
		<div class="post">

		<?php
                       if(isset($_GET['keyword'])){
	
	                   $keyword=$_GET['keyword'];

	     $search= "(SELECT bid, pid, pname, descript, URL, tag FROM picture natural join pin WHERE prebid = 0 and pname like '%$keyword%') UNION (SELECT bid, pid, pname, descript, URL, tag FROM picture natural join pin WHERE prebid = 0 and tag like '%$keyword%') ";

						$keyword_picture = mysql_query($search);
			            while($showkeypic= mysql_fetch_array($keyword_picture)){ 
				     
			           //title
			      echo "<div class='ftcontent'>".'<a href="followboard.php?id='.$showkeypic['bid'].'"./>'.$showkeypic['pname']."</a></div>";
					  //picture
					   echo "<div class='ftcontent'><img src = ".$showkeypic['URL']." width='550' height='350' alt='image' class='hboxthumb'></div>";
						//description
						echo "<br><strong>Description:</strong>".$showkeypic['descript']."</br>";	            //tag
						echo "<p><strong>Tag: </strong>&nbsp".$showkeypic['tag']."</p>";
						//like
						echo "<form action='recommendation.php?action=likenum' method='post'>
						      <p><input type = 'hidden', name = 'pid', value = ".$showkeypic['pid'].">
							  <input type='submit' name='likenum' value=' like '></p>";
							   echo "</form>";
						
						//repin	
							echo "<form action='recommendation.php?action=createrepin' method='post'>
							      <p><input type = 'hidden', name = 'pid', value = ".$showkeypic['pid'].">";
							$repin_add = mysql_query("select bid, bname from board where uid = '$uid'");
							echo" <p><select name='pinboard'>";
							
							while($r_add=mysql_fetch_array($repin_add)){
							     echo"<option value=".$r_add['bid'].">".$r_add['bname']."</option>";
							}
							echo"</select>
							     <input type ='submit' name= 'createrepin' value ='  repin  '/></p>"; 
							     echo"</form>";    
							//comment		
							     echo "<form action='recommendation.php?action=comment' method='post'>
														<p><input type = 'hidden', name = 'pid', value = ".$showkeypic['pid'].">
														<input type = 'hidden', name = 'bid', value = ".$showkeypic['bid'].">
														<input type = 'text', placeholder='Add a comment...', name = 'comtext'/>  
														 <input type='submit' name='comment' value='comment'></p>"; 
																			  echo "<br><table border='0.1' style='margin-left:8px'></br>";
						$comment=mysql_query("select uname,ctext,ctime from comment natural join user where pid= ".$$showkeypic['pid']." and bid =".$showkeypic['bid']."");
							while($res=mysql_fetch_array($comment)){
							echo "<tr>
							<td align='center'>".$res['uname']."</td>
							<td align='center'>".$res['ctext']."</td>
							<td align='center'>".$res['ctime']."</td>";
							}echo "</table>";
														echo "</form>";
														
														}
}
														echo "</div>";				 ?>

		  </div>
		</div>
		</div>

				</div>
		</div>



		</p>
						</div>                   
						</div>
					

						
						
					</body>
				</html>



