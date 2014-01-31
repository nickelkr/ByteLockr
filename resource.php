<html>
  <head>
    <title>ByteLockr</title>
  </head>

  <body>
    <form action="download.php" method="post">
      Password: <input type="password" name="passcode"></br>
      <input type="hidden" name=resource value="<?php echo $_GET['resource']; ?>" />
      <input type="submit" />
    </form>
  </body>
</body>
