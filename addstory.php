<!DOCTYPE html>
<html>
<head>
    <title>AddStory</title>
    <link rel="stylesheet" type="text/css" href="./css2/addstory.css">
</head>
<body>
    <?php session_start(); ?>
    <div class="webname">
        ONE STORY
    </div>
    <div class="write">
    <form action="add.php" method="post">
        <div class="buttonstyle">
        <button type="submit" class="button">Add</button>
        <button type="submit" class="button" formaction="index2.php">Return</button>
        </div>
        <input type="text" name="title" class="title" size="66" value="TITLE..."/><br><br>
        <textarea rows="30" cols="90" name="contents" class="contents">CONTENTS...</textarea><br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    </div>
</body>
</html>