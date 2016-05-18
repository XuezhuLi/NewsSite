<!DOCTYPE html>
<html>
<head>
    <title>sign in</title>
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

	//Get the login information
	$user = $_POST["user"];
    $pwd_guess = $_POST["password"];
	
	//Check if this user is valid.
	//If username is empty, that is, user submits without inputting anything, record error.
	if($user == ""){
		$err = "*Username cannot be empty.";
	}
    else if($pwd_guess == ""){
        $err = "*Password cannot be empty.";
    }
	else{
		//Connect the database
        $stmt = $mysqli->prepare("select password from UserAccount where username=?");
        if(!$stmt){
            $err = "Query Prep Failed: %s\n".$mysqli->error.".";
        }
        else{
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $stmt->bind_result($pwd_hash);
            $stmt->fetch();
            if($pwd_hash == ""){
                $err = "*No such user.";
            }
            else{
                if(crypt($pwd_guess, $pwd_hash)==$pwd_hash){
                    session_start();
                    $_SESSION["user"] = $user;
					$_SESSION['token'] = substr(md5(rand()), 0, 10);
					header("Location: index2.php");
					exit;
                }
                else{
                    $err = "*Illegal user.";
                }
            }
            $stmt->close();
        }
	}
	
	echo "<span class=\"err\">$err..</span>";
	exit;
?>
</body>
</html>