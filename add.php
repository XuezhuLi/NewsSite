<!DOCTYPE html>
<html>
<head>
    <title>Add a story</title>
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
	
    if(isset($_SESSION["user"])){//Judge if the user has logged in.
		//Judge if there is request forgery detected
		if($_SESSION['token'] !== $_POST['token']){
			die("Request forgery detected");
		}
	
        //Get the username
        $user = $_SESSION["user"];
        //Get the contents
        $conts = $_POST["contents"];
        //Get the title
        $title = $_POST["title"];
        //Set the current time
        date_default_timezone_set('America/Chicago');
        $date = date("Y-m-d H:i:s");
        
        //Prepare to insert the data
        $stmt = $mysqli->prepare("insert into story (username, date, content, title) values (?, ?, ?, ?)");
        if(!$stmt){
            $err = "Query Prep Failed: %s\n".$mysqli->error.".";
        }
        else{
            //Insert the story
            $stmt->bind_param('ssss', $user, $date, $conts, $title);
            $stmt->execute();
            $stmt->close();
            header("Location: index2.php");
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