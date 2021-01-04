<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In</title>
        <!-- we need to include the full path of "views/styles.css" because the path actually starts from the login.php file which is in the includes folder. -->
        <link href="../views/style.css" media="screen" rel="stylesheet" type="text/css">
    </head>

    <body>

        <h1>Log In</h1>
        <div id="content">
            <form action="" method="post">
            <div>
                <?php if ($error['alert'] != '') {
                echo "<div class='alert'>". $error['alert'] ."</div>"; } ?>

                <label for="username">Username: *</label>
                <input type="text" name="username" value="<?php echo $input['user']; ?>"><div class="error"><?php echo $error['user']; ?></div>

                <label for="password">Password: *</label>
                <input type="password" name="password" value="<?php echo $input['pass']; ?>"><div class="error"><?php echo $error['pass']; ?></div>

                <p class="required">* required fields</p>

                <input type="submit" name="submit" class="submit" value="Submit">

            </div>
            </form>

        </div>

    </body>

</html>