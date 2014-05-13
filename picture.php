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
		include('function.php');
		$uid=$_SESSION['uid'];
		date_default_timezone_set('America/New_York');
		$UpdateTime=date('Y-m-d H:i:s');
		
		if($_GET['id']){
		$_SESSION['bid']=$_GET['id'];
		$bid=$_SESSION['bid'];}
		else{$bid=$_SESSION['bid'];}


	if($_GET['action'] == "picturesave")
	{
	    $image = new GetImage;
	    $image->source = $_POST["URL"]; // remote file
	    $image->save_to = './pictures/'; // local dir
	    $image->new_filename = $_POST['title']; // if you want rename downloaded images, file name without extension
	    $image->download();

	     $URL = $_POST["URL"];
	    $ext=strrchr($URL,"."); 
	    $ext_arr= array(".gif",".png",".jpg",".bmp"); 
	    if (!in_array($ext, $ext_arr)) return false;
	    $local = 'http://localhost:8888/pinterest/pictures/'.$image->new_filename.$ext;
	   
	    $title=$_POST['title'];
	    $description=$_POST['description'];
	    $tag=$POST['tag'];
	    
	    
	    $StrSql=mysql_query("INSERT INTO picture (pname, URL, local,descript, tag) VALUES ('$title','$URL','$local','$description','$tag')");
	       	     $pid=mysql_insert_id();
		
		$response= mysql_query("INSERT INTO pin(pid, bid, ptime) VALUES ('$pid', '$bid', '$UpdateTime')");	                
	        }



		if($_GET['action'] == "delete"){
			if(isset($_POST['pid'])){
				$pid=$_POST['pid'];
				echo($pid);
				$prebid=$_POST['prebid'];
				echo($pribid);
				if($prebid){
				$picture_repin_delete = "delete from pin where pid = $pid and prebid = $prebid";
		        mysql_query($picture_repin_delete);
		        $picture_comment_delete="delete from comment where pid= $pid and bid=$bid";
						mysql_query($picture_comment_delete);
		       
			    }
			    else{
				$picture_repin_delete1="delete from pin where pid = $pid";
				mysql_query($picture_repin_delete1);
				$picture_comment_delete1="delete from comment where pid= $pid";
				mysql_query($picture_comment_delete1);
				$picture_like_delete="delete from like where pid= $pid";
				mysql_query($picture_comment_delete1);
				$picture_delete = "delete from picture where pid = $pid";
				mysql_query($picture_delete);
				echo "delete is succsseful!";
			    }  
			}
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
		<input type="text" name="keyword" class="keyword" /> 
		<input class="searchbutton" type="image" src="images/searchgo.gif"  alt="search" /></div>

		</form>

		</div>

		</div>
		<div id="menu">
		<ul>
		<li><a href="recommendation.php"><span>Recommendation</span></a></li>
		<li class="active"><a href="boards.php"><span>Boards</span></a></li>
		<li><a href="profile.php"><span>MyAccount</span></a></li>
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
			
	              <h4> Add Picture: </h4>
				<p>
				<table align=center width=40% cellpadding=0 cellspacing=1>
				<form action='picture.php?action=picturesave' method='post'>
				  <td width="10%">Title</td>
				  <td width="35%"><input class="formfield" type="text" name='title' size="30" /></td>
				
			   	<td>URL</td>
				  <td><input class="formfield" type="text" name='URL' size="30" /></td>
				<tr class=tablecell>
						   	<td>Description</td>
							  <td><input class="formfield" type="text" name='description' size="30" /></td>
						   	<td>Tag</td>
							  <td><input class="formfield" type="text" name='tag' size="30" /></td>
				  
				  <?php
				  
				  //  hidden the and post bid value
				  echo '<td><input type="hidden" name="id" value="'.$_GET['id'].'"/></td>';
				  
				  ?>
				  
				</tr>
						<tr class=tablecell><td></td><td><input class="formbutton" type= 'submit' value='submit'<a href="picture.php?pid = $pid" name= 'PhotoSave'>&nbsp;&nbsp; 
				<input class="formbutton" type='reset' value='reset' name= 'reset'></td></tr>
				</form>
				</table>
				</p>
				</div>

	         
				 <div class="ftcontent">
				 <div class="post">				
				 <p>

			<?php

				$all_picture="(SELECT pid, pname, descript, local, tag, prebid FROM board NATURAL JOIN pin natural join picture WHERE bid= '$bid' and uid = '$uid')";
				
				$allpic = mysql_query($all_picture);
	            while($showpic= mysql_fetch_array($allpic)){ 
			    echo "<br><strong><b><font color=\"red\">".$showpic['pname']."</b></strong></font></br>";	           echo "<img src = ".$showpic['local']." width='550' height='350' alt='image' class='hboxthumb'>";
				echo "<br><strong>Description:</strong>".$showpic['descript']."</br>";	            echo "<p><strong>Tag: </strong>&nbsp".$showpic['tag']."</p>";
				echo"<form action='picture.php?action=delete' method='post'>
					            	     <input type = 'hidden' name='pid' value = ".$showpic['pid'].">
					            	     <input type = 'hidden' name='prebid' value = ".$showpic['prebid'].">
					                    <input type='submit' name='delete' value='delete'>";

				echo "<br> </br>";
				echo "</form>";
				 echo "<table border='1' style='margin-left:8px'>";
						
						$pid= $showpic['pid'];
												$bid=$_SESSION['bid'];
						$likenum=mysql_query("SELECT count(*) as likenum FROM likenum where pid='$pid'  GROUP BY pid");
						
						while($lnum=mysql_fetch_array($likenum)){
						echo"<font size='3' face='arial' color='red'>likes:".$lnum['likenum']."</font></p>";				        }
						
						$pinnum=mysql_query("SELECT count(*) as pinnum FROM pin where pid='$pid' and prebid!=0 GROUP BY pid");
						while($pnum=mysql_fetch_array($pinnum)){
						echo"<font size='3' face='arial' color='blue'>repins:".$pnum['pinnum']."</font></p>";						}	
			
						
						
						$comment=mysql_query("select uname,ctext,ctime from comment natural join user where pid= '$pid' and bid ='$bid'");
						while($res=mysql_fetch_array($comment)){
							echo "<tr>
								  <td align='center'>".$res['uname']."</td>
								  <td align='center'>".$res['ctext']."</td>
								  <td align='center'>".$res['ctime']."</td>";
						}
								
					            echo "</table>";

			    }
			    mysql_free_result($allpic);
				mysql_close($conn);
				?>
				</p>
				</div>                   
				</div>
			

				<div id="footer">
					<p>  Copyright &copy 2013 Xinrui and Yunfeng. All rights reserved.</p>
				</div>
				</div>
			</body>
		</html>
