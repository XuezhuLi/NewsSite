<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo "*You need to sign in first";
 //   echo '<br><br><br><a href = "index2.php">' . 'Back to index page' . '</a>';
    exit;
} else {
    $user = $_SESSION['user'];
}
$action = $_GET['action'];
$id = $_GET['id'];

require "database2.php";

    if(strcmp($action, "edit_story")==0){
        //Go to edit story
        header("Location: editstory.php?id=$id");
        exit;
    }elseif(strcmp($action, "delete_story")==0){
        //Delete Story
        $query1 = "select comment_id from links where links.story_id = $id ";
        $stmt1 = $mysqli->prepare($query1);
        if (!$stmt1) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt1->execute();
        $stmt1->bind_result($comment_id_result);

        //$comment_id = array();
        $i = 0;
        while($stmt1->fetch()){
            $comment_id[$i] = $comment_id_result;
            $i = $i + 1;
        }
        $stmt1->close();

        $query2 = "delete from links where links.story_id = $id";
         $stmt2 = $mysqli->prepare($query2);
        if (!$stmt2) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt2->execute();
        $stmt2->close();

        $j = 0;
        while(isset($comment_id[$j])){
            $query3 = "delete from comment where comment.id = $comment_id[$j]";   
            $stmt3 = $mysqli->prepare($query3);
            if (!$stmt3) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt3->execute();
            $stmt3->close(); 
            $j = $j + 1;
        }

        $query4 = "delete from story where story.id = $id";
        $stmt4 = $mysqli->prepare($query4);
        if (!$stmt4) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt4->execute();
        $stmt4->close();
        header('Location: useraccount.php');
        exit;
    }elseif(strcmp($action, "edit_comment")==0){
        //Go to edit comment
        header("Location: editcomment.php?id=$id");
        exit;
    }elseif(strcmp($action, "delete_comment")==0){
        //delete comment
        $query1 = "delete from links where links.comment_id = $id";
        $stmt1 = $mysqli->prepare($query1);
        if (!$stmt1) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt1->execute();
        $stmt1->close();

        $query2 = "delete from comment where comment.id = $id";
        $stmt2 = $mysqli->prepare($query2);
        if (!$stmt2) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt2->execute();
        $stmt2->close();
        header('Location: useraccount.php');
        exit;
    }
?>