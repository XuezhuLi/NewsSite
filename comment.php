<!DOCTYPE html>
<html>
<head>
    <title>insert Comment</title>
    <style type="text/css">
		span.err{
			font-size: 15px;
			color: red;
			font-family: "Geneva";
		}
	</style>
</head>
<body>
    <?php
    require 'database2.php';
    
	session_start();
	
    $conts = $_POST["contents"];
    $story_id=$_POST["id"];
    
    if(isset($_SESSION["user"])){//Judge if the user has logged in.
		//Judge if there is request forgery detected
		if($_SESSION['token'] !== $_POST['token']){
			die("Request forgery detected");
		}
		
		//Set the timezone and get the current time
		date_default_timezone_set('America/Chicago');
        $date = date("Y-m-d H:i:s");
		//Get the username
        $user = $_SESSION["user"];
        $stmt1 = $mysqli->prepare("insert into comment (username, date, comment) values (?, ?, ?)");
        $stmt2 = $mysqli->prepare("select id from comment where date=? and username=?");
        $stmt3 = $mysqli->prepare("insert into links (story_id,comment_id) values (?, ?)");
		$stmt4 = $mysqli->prepare("select email, title from UserAccount join story on (UserAccount.username=story.username) where id=?");
        if(!$stmt1||!$stmt2||!$stmt3||!$stmt4){
            $err = "Query Prep Failed: %s\n".$mysqli->error.".";
            $stmt1->close();
            $stmt2->close();
            $stmt3->close();
			$stmt4->close();
        }
        else{
            //Insert the comments
            $stmt1->bind_param('sss', $user, $date, $conts);
            $stmt1->execute();
            $stmt1->close();
            //Get the comment_id
            $stmt2->bind_param('ss',$date,$user);
            $stmt2->execute();
            $stmt2->bind_result($comment_id);
            $stmt2->fetch();
            $stmt2->close();
            //Insert into links
            $stmt3->bind_param('ii', $story_id, $comment_id);
            $stmt3->execute();
            $stmt3->close();
			
			//Get the author's email and story_title
			$stmt4->bind_param('i', $story_id);
            $stmt4->execute();
            $stmt4->bind_result($email, $title);
            $stmt4->fetch();
            $stmt4->close();
			//Send the email to the author
			$message="There is a comment for your \"".$conts."\" from ".$user;
			mail($email, $title, $message);
			
            header("Location: story.php?id=$story_id");
            exit;
        }
    }
    else{
        $err = "You should sign in first.";
    }
    
	echo "<span class=\"err\">$err..</span>";
	exit;
    ?>
</body>
</html>