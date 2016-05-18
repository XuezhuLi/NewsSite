<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
		<link rel="stylesheet" type="text/css" href="./css2/signup.css">
    </head>
    <body>
<?php
    require 'database2.php';
    
    $err = "";
    //Check if the page has been submitted. If so, go on processing.
    if(isset($_POST["sign"])){
        $user = $_POST["newuser"];
		$email = $_POST["mail"];
        $pwd = $_POST["pwd"];
        
        //Do the sign up part.
		//If username is empty, that is, user submits without inputting anything, record error.
        if($user == ""){
            $err = "*Username cannot be empty.";
        }
        else if($pwd == ""){
            $err = "*Password cannot be empty.";
        }
		//If username is invalid, record error.
		else if(!preg_match('/^[\w_\-]+$/', $user)){
            $err = "*Invalid username";
        }
        else{
            //Check if this username exists. If so, record error.
            //Connect the database
            $stmt = $mysqli->prepare("select username from UserAccount where username=?");
            if(!$stmt){
                $err="Query Prep Failed: %s\n".$mysqli->error.".";
            }
            else{
                $stmt->bind_param('s', $user);
                $stmt->execute();
                $stmt->bind_result($user_hash);
                $stmt->fetch();
                $stmt->close();
                if($user_hash!=""){
                    $err = "*The username has already exists.";
                }
                else{
                    $stmt = $mysqli->prepare("insert into UserAccount (username, email, password) values (?, ?, ?)");
                    if(!$stmt){
                        $err="Query Prep Failed: %s\n".$mysqli->error.".";
                    }
                    else{
                        $pwd_hash = crypt($pwd);
                        $stmt->bind_param('sss', $user, $email, $pwd_hash);
                        $stmt->execute();
                        $stmt->close();
                        header("Location: index2.php");
                        exit;
                    }
                }
            }
        }
    }
?>
    <div class="sign">
	<div class="formstyle">
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
		<div class="textstyle">
			Username:<input type="text" name="newuser" size="20" class="text"><br><br>
			email:<input type="text" name="mail" size="20" class="text"><br><br>
            Password:<input type="password" name="pwd" size="20" class="text">
			<input type="hidden" name="sign" value="ok"><br>
			<span class="error"><?php echo htmlentities($err)?></span>
		</div>
		<div class="buttonstyle">
			<button type="submit" class="button">OK</button>
			<button type="submit" formaction="index2.php" class="button">Return</button>
		</div>
	</form>
	</div>
	</div>
    </body>
</html>