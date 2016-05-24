<!DOCTYPE html>
<html>
<head>
    <title>ViewStory</title>
    <link rel="stylesheet" type="text/css" href="./css2/story.css">
</head>
<body>
<div class="webname">
    ONE STORY
</div>
<div class="oper">
    <?php
    session_start();
    
    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
        echo "<a href=\"useraccount.php\">".$user."</a>&nbsp;&nbsp;";
    }
    ?>
    <a href="index2.php">Return</a>
</div>
    <?php
    require 'database2.php';
    
    //Get the story's id
    $story_id = $_GET["id"];
    //find all fields for the story
    $stmt = $mysqli->prepare("select title,content,date,username from story where id=$story_id");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    //$stmt->bind_param('i', $story_id);
    $stmt->bind_result($title, $content, $date, $username);
    $stmt->fetch();
    echo "<div class=\"article\">";
    echo "<div class=\"title\">".htmlspecialchars($title)."</div>";
    echo "<div class=\"sub\"><a href=\"viewuser.php?user=$username\">".htmlspecialchars($username)."</a>&nbsp;&nbsp;".htmlspecialchars($date)."</div><br/>";
    echo "<div class=\"content\">".htmlspecialchars($content)."</div>";
    echo "</div>";
    
    $stmt->close();    
    ?>
<div class="inputComms">
    <form action="comment.php" method="post">
        <textarea rows="10" cols="100" name="contents" class="writecommts"></textarea><br/>
        <input type="hidden" name="id" value="<?php echo htmlentities($_GET["id"]); ?>"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <div class="buttonstyle">
        <button type="submit" class="button">Comment</button>
        <button type="submit" class="button" formaction="index2.php">Return</button>
        </div>
    </form>
</div>
<div class="list">
    <?php
    require 'database2.php';
    
    //Get the story's id
    $story_id=$_GET["id"];
    //find all fields for the comment
    $stmt = $mysqli->prepare("select comment,date,username from comment join links on (id=comment_id) where story_id=$story_id");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    //$stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->bind_result($comment, $date, $username);
    while($stmt->fetch()){
        echo "<div class=\"comment\">";
        echo "<div class=\"comment_conts\">".htmlspecialchars($comment)."</div>";
        echo "<div class=\"sub2\">".htmlspecialchars($date)."&nbsp;&nbsp;&nbsp;&nbsp;by&nbsp;<a href=\"viewuser.php?user=$username\">".htmlspecialchars($username)."</a></div>";
        echo "</div>";
    }
    $stmt->close();
    ?>
</div>
</body>
</html>