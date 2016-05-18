<!DOCTYPE html>
<html>
    <head>
        <title>Logout</title>
    </head>
    <body>       
        <?php
            session_start();
            session_destroy();
            header("Location: index2.php");
        ?>
    </body>
</html>