<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Members Only</title>
        <!-- we need to include the full path of "views/styles.css" because the path actually starts from the login.php file which is in the includes folder. -->
        <link href="../views/style.css" media="screen" rel="stylesheet" type="text/css">
    </head>

    <body>

        <h1>Members Only</h1>
        <div id="content">
            <p>You have successfully logged in to the member's area.</p>
            <p><a href="register.php">Register Member</a>
                <a href="change_password.php">Change Password</a>
                <a href="logout.php">Log Out</a></p>

        </div>

    </body>

</html>
