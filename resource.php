<html>
  <head>
		<link rel="stylesheet" type="text/css" href="css/styles.css" />
    <title>ByteLockr</title>
  </head>

  <body>
		<div class="top">
			<p class="name" > <a href="index.html">ByteLockr</a>  <span><a href="faq.html">FAQ</a></span></p>
		</div>
		<div class="content">
      <p style="font-size:45pt; font: bold;padding: 0; margin: 0;">Download.</p>
      <form action="download.php" method="post">
        Password: <input type="password" name="passcode"></br>
        <input type="hidden" name=resource value="<?php echo $_GET['resource']; ?>" /><br>
        <input style="float: right;" type="submit" value="Download" />
      </form>
  	</div>
	</body>
</html>

