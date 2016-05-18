<!DOCTYPE html>
<html>
<head>
    <title>Alter Story</title>
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
    
    $story_id = $_POST["id"];
    $title = $_POST["title"];
    $conts = $_POST["contents"];
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
		//Update the story according to the story_id
        $query = "update story set username = \"".htmlspecialchars($user)."\", date = \"".htmlspecialchars($date)."\", title = \"".htmlspecialchars($title)."\", content = \"".htmlspecialchars($conts)."\" where id = \"$story_id\" ";
        $stmt1 = $mysqli->prepare($query);

        if(!$stmt1){
            $err = "Query Prep Failed: %s\n".$mysqli->error.".";
			echo $query;
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