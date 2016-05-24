<!DOCTYPE html>
<html>
<head>
    <title>Mainpage</title>
    <link rel="stylesheet" type="text/css" href="./css2/index.css">
</head>
<body>
<div class="webname">
    ONE STORY
</div>
<div class="list2">
    <?php
    	require 'database2.php';
        $query1 = "select id from story";
        $stmt1 = $mysqli->prepare($query1);
        if (!$stmt1) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt1->execute();
        $stmt1->bind_result($story_id_result);

        //$comment_id = array();
        $i = 0;
        while($stmt1->fetch()){
            $story_id[$i] = $story_id_result;
            //echo "\nstory_id-".$i."=".$story_id[$i];
            $i = $i + 1;
        }
        $stmt1->close();

        $j = 0;
        while(isset($story_id[$j])){
	        $query2 = "select count(*) from links where links.story_id = \"$story_id[$j]\" ";
	        $stmt2 = $mysqli->prepare($query2);
	        if (!$stmt2) {
	            printf("Query Prep Failed: %s\n", $mysqli->error);
	            exit;
	        }
	        $stmt2->execute();
	        $stmt2->bind_result($Num[$j]);
	        $stmt2->fetch();
	        //echo "\nNum-".$j."=".$Num[$j];
	        $stmt2->close();
        	$j = $j + 1;
        }

        arsort($Num);
        $k = 0;
        foreach ($Num as $key => $value) {
        	$popular[$k] = $key;
        	$k = $k + 1;
        }

    echo "<div class=\"article\">";
    echo "<div class=\"sort\">Most Popular Stories</div>";
    $k = 0;
    while (isset($popular[$k])&&$k<= 4) {
    	$order = $popular[$k];
	    $stmt = $mysqli->prepare("select * from story where id = \"$story_id[$order]\" ");
	    if(!$stmt){
	        echo "<span>Query Prep Failed: $story->error</span>";
	        exit;
	    }
	    $stmt->execute();
	    $stmt->bind_result($id, $author, $date, $content, $title);
	    $stmt->fetch();
	        echo "<div class=\"title2\"><a href=\"story.php?id=$id\">".htmlspecialchars($title)."</a></div>";
	        echo "<div class=\"count\">by ".htmlspecialchars($author)." ".htmlspecialchars($Num[$order])."comments"."</div>";
    	$stmt->close();
    	$k = $k + 1;
    }
    echo "</div>";
    ?>
</div>
    <?php
        session_start();
        if(isset($_SESSION["user"])){//judge if the user has logged in.
            $user = $_SESSION["user"];
            echo "<div class=\"top2\">\n";
            echo "<div class=\"textstyle3\">\n";
            echo "<a href = \"logout2.php\">(logout)</a>";
            echo "</div>\n";
            echo "<div class=\"textstyle2\">\n";
            echo "<a href = \"useraccount.php\">$user</a>";
            echo "</div>\n";
            echo "</div>";
        }
        else{
            echo "<div class=\"top\">\n";
            echo "<form action=\"signin.php\" method=\"post\">";
            echo "<ul class=\"faceul\">";
            echo "<li class=\"buttonstyle\">\n";
            echo "<button type=\"submit\" class=\"button\">login</button>\n";
            echo "<button type=\"submit\" class=\"button\" formaction=\"signup2.php\">signup</button>\n";
            echo "</li>";
            echo "<li class=\"textstyle\">";
            echo "Username: <input type=\"text\" name=\"user\" size=\"20\" class=\"text\">\n";
            echo "Password: <input type=\"password\" name=\"password\" size=\"20\" class=\"text\">\n";
            echo "<input type=\"hidden\" name=\"token\" value=\"<?php echo $_SESSION\['token'\];?>\" />";
            echo "</li>\n";
            echo "</ul>\n";
            echo "</form>\n";
            echo "</div>";
        }
    ?>
<div class="add">
    <form action = "addstory.php" method = "post">
        <button type="submit" class="writebutton">+</button>Write a story
    </form>
</div>
<div class="list">
    <?php
    require 'database2.php';

    $stmt = $mysqli->prepare("select * from story");
    if(!$stmt){
        echo "<span>Query Prep Failed: $story->error</span>";
        exit;
    }
    
    $stmt->execute();
    $stmt->bind_result($id, $author, $date, $content, $title);
    while($stmt->fetch()){
        $subcontent = substr($content,0,600);
        echo "\t<div class=\"article\">";
        echo "\t\t<div class=\"title\"><a href=\"story.php?id=$id\">".htmlspecialchars($title)."</a></div>\n";
        echo "\t\t<div class=\"author\">by <a href=\"viewuser.php?user=$author\">".htmlspecialchars($author)."</a></div><br>\n";
        echo "\t\t<p class=\"content\">".htmlspecialchars($subcontent)."...</p><br>\n";
        echo "\t\t<div class=\"date\">".htmlspecialchars($date)."</div>\n";
        echo "\t</div>";
    }
    
    $stmt->close();
    ?>
</div>
</body>
</html>