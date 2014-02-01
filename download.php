<?php
  //get the requested file
  $resource = sanitize_input($_POST['resource']);
  $password = sanitize_input($_POST['passcode']);
  
  $connection = mysqli_connect("localhost", "root", "", "bytelockr");

  if (mysqli_connect_errno()) {
    //die("Error: " . mysqli_connect_error());
    header ("Location: error.html");
    die ();
  }

  if ($resource == "") {
    header ("Location: error.html");
    die();
  }

  $sql = "SELECT filename, password, guid, download_limit FROM files WHERE expiry_date > now() AND guid='" . $resource . "' LIMIT 1";

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_array($result);

  $guid = $row['guid'];
  $file_password = $row['password'];
  $filename = $row['filename'];
  $file = "/var/data/" . $guid . "." . $filename;
  $download_limit = $row['download_limit'];
  
  if ($password == $file_password && ($download_limit > 0 || $download_limit == NULL)) {
    if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$filename);
      header('Expires: 0');
      header('Cache-Control: no-cache');
      header('Pragma: no-cache');
      header('Content-length: ' . filesize($file));
      ob_clean();
      flush();
      $results = readfile($file);
      
      if ($results && $download_limit != NULL) {
        $sql = "UPDATE files SET download_limit = download_limit - 1 WHERE guid='" . $guid . "'";
        mysqli_query($connection, $sql);
      }
      exit;
    } else {
      header ("Location: error.html");
      die ();
    }
  } else {
    header ("Location: error.html");
    die ();
  }

  function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
     
    return mysql_real_escape_string($input);
   }
?>
