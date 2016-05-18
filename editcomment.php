<!DOCTYPE html>
<html>
    <head>
        <title>Edit Comment</title>
        <link rel="stylesheet" type="text/css" href="./css2/story.css">
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
    $comment_id=$_GET["id"];

    //this is for comments activities
    $query = "select comment from comment where comment.id = $comment_id";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    //$stmt->bind_param('s', $comment_id);
    $stmt->execute();
    $stmt->bind_result($comment);
    $stmt->fetch();
    $token = $_SESSION['token'];
    echo '<div class="inputComms"><form action="altercomment.php" method= "POST">';
    echo '<textarea rows="10" cols="100" name="contents" class="writecommts">' . htmlentities($comment) . '</textarea><br>';
    echo '<input type="hidden" name="id" value="'.$comment_id.'"/>';
    echo "<input type=\"hidden\" name=\"token\" value=\"$token\" />";
    echo '<div class="buttonstyle">';
    echo '<button type="submit" class="button">Comment</button>';
    echo '<button type="submit" class="button" formaction="useraccount.php">Cancel</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    $stmt->close();
    ?>
    </body>
</html>