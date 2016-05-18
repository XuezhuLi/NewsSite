<!DOCTYPE html>
<html>
    <head>
        <title>Edit Story</title>
        <link rel="stylesheet" type="text/css" href="./css2/addstory.css">
    </head>
    <body>

    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        echo "You need to log in first.";
        echo '<br><br><br><a href = "index2.php">' . 'Back to the index page' . '</a>';
        exit;
    } else {
        $user = $_SESSION['user'];
    }
    require 'database2.php';
    $story_id=$_GET["id"];

    //this is for comments activities
    $query = "select title,content from story where story.id = $story_id";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    //$stmt->bind_param('s', $comment_id);
    $stmt->execute();
    $stmt->bind_result($title,$content);
    $stmt->fetch();
    $token = $_SESSION['token'];
    echo '<div class="write"><form action="alterstory.php" method= "POST">';
    echo '<div class="buttonstyle">';
    echo '<button type="submit" class="button">Submit</button>';
    echo '<button type="submit" class="button" formaction="useraccount.php">Cancel</button>';
    echo '</div>';
    echo '<input type="text" name="title" class="title" size="66" value="'.htmlentities($title).'"/><br><br>';
    echo '<textarea rows="30" cols="90" name="contents" class="contents">'. htmlentities($content) . '</textarea><br>';
    echo '<input type="hidden" name="id" value="'.$story_id.'"/>';
    echo "<input type=\"hidden\" name=\"token\" value=\"$token\" />";
    echo '</form>';
    echo '</div>';
    $stmt->close();
    ?>
    </body>
</html>