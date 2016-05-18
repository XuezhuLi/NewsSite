<!DOCTYPE HTML>
<?php
session_start();
?>
<html>
    <head>
    	<title>MyAccount</title>
		<link rel="stylesheet" type="text/css" href="./css2/useraccount.css">
    </head>
    <body>
		<div class="webname">ONE STORY</div>
        <?php
        if (!isset($_SESSION['user'])) {
            echo "<span class=\"err\">*You need to sign in first</span>";
            exit;
        } else {
            $user = $_SESSION['user'];
        }
        require 'database2.php';
        echo "<div class=\"subtitle\">" . htmlentities($user) . "'s Account</div>";
		echo "<div class=\"oper\">";
		echo "<ul class=\"faceul\">";
        echo '<li><a href = "logout2.php">' . 'Logout' . '</a></li>';
        echo '<li><a href = "index2.php">' . 'Return' . '</a></li>';
		echo "</ul>";
		echo "</div>";
        //echo '<br><br><a href = "upload_form.php">' . 'Upload a post' . '</a>';

        //this is for story activities.
        $stmt = $mysqli->prepare("select id,title, content, date from story where story.username=? ORDER BY date DESC");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $stmt->bind_result($story_id, $title, $content, $date);

        //echo '<br><form action="option.php" method= "POST">';
		echo "<div class=\"stories\">";
		echo "<div class=\"style\">My Story</div>";
        while ($stmt->fetch()) {
			echo '<div class = "onestory">';
            echo '<div class="title"><a href="story.php?id='.$story_id.'">'.htmlentities($title).'</a></div>';
            echo '<div class="date">' . htmlspecialchars($date) . '</div>';
            echo '<div>' . substr(htmlspecialchars($content),0,600).'...</div>';
			echo '<div class = "del">';
            echo '<a href="option.php?action=edit_story&id='.$story_id.'">Edit</a>&nbsp;&nbsp;';
            echo '<a href="option.php?action=delete_story&id='.$story_id.'">Delete</a>';
			echo '</div>';
			echo '</div>';
        }
        //echo '</form>';
		echo "</div>";
        $stmt->close();


        //this is for comments activities
        $stmt = $mysqli->prepare("select story.id,title, comment.id, comment.username, comment, comment.date from (comment INNER JOIN links ON comment.id=links.comment_id INNER JOIN story ON links.story_id=story.id) where comment.username=? ORDER BY date DESC");
     
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $stmt->bind_result($story_id, $title, $comment_id, $username, $comment, $date);

        //echo '<br><form action="option.php" method= "POST">';
		echo "<div class=\"comments\">";
		echo "<div class=\"style\">My Comments</div>";
        while ($stmt->fetch()) {
			echo '<div class = "onecomment">';
            echo '<div class= "title"><a href="story.php?id='.$story_id.'">'. htmlspecialchars($title).'</a></div>';
            echo '<div class = "date">' . htmlspecialchars($date) . '</div>';
            //echo '<br><textarea rows="1" cols="60">' . htmlentities($comment) . '</textarea>';
            echo '<div>' . htmlspecialchars($comment) . '</div>';
			echo '<div class = "del">';
            echo '<a href="option.php?action=edit_comment&id='.$comment_id.'">Edit</a>&nbsp;&nbsp;';
            echo '<a href="option.php?action=delete_comment&id='.$comment_id.'">Delete</a>';
			echo '</div>';
			echo '</div>';
        }
		echo "</div>";
        //echo '</form>';
        $stmt->close();
        ?>
    </body>
</html>