<!DOCTYPE html>
<html>
<head>
    <title>Alter Comment</title>
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
    
    $conts = $_POST["contents"];
    $comment_id=$_POST["id"];
	
	session_start();
    
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
		//Update the comment according to the comment_id
        $query = "update comment set username = \"".htmlspecialchars($user)."\", date = \"".htmlspecialchars($date)."\", comment = \"".htmlspecialchars($conts)."\" where id = \"".htmlspecialchars($comment_id)."\" ";
        $stmt1 = $mysqli->prepare($query);

        if(!$stmt1){
            $err = "Query Prep Failed: %s\n".$mysqli->error.".";
            exit;
        }
        else{
            //Alter the comments
            $stmt1->execute();
            $stmt1->close();

            header("Location: useraccount.php");
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