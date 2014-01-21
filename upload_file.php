<?php
  if ($_FILES["file"]["size"] < 10000000000)
  {
    if ($_FILES["file"]["error"] > 0)
    {
      echo "ERROR: " . $_FILES["file"]["error"];
    }

    if (file_exists("/var/data/" . $_FILES["file"]["name"]))
    {
      echo "File exists!";
    } else {
      // create our database connection
      $connection = mysqli_connect("localhost", "root", "", "bytelockr");

      // check for a connection error
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL database: " . mysqli_connect_error();
      } else {
        echo "Connected";
      }

      $guid = "";
      do {
        // create our guid
        for ($x = 0; $x < 14; $x++) {
          if ($x == 4 || $x == 9) {
            $guid = $guid . "-";
          } else {
            $guid = $guid . rand(0, 9);
          }
        }
      } while (mysqli_fetch_array(mysqli_query($connection, "SELECT guid FROM files WHERE guid={$guid} LIMIT 1")));

      // generate the file's password
      $password = "";
      for ($x = 0; $x < 15; $x++) {
        $password = $password . rand(0, 9);
      }

      $expiry = mysql_real_escape_string(sanitize_input($_POST["time_limit"]));
      $download_limit = mysql_real_escape_string(sanitize_input($_POST["download_limit"]));
      $filename = sanitize_input($_FILES["file"]["name"]);
      $filename_sql = mysql_real_escape_string($filename);

      $sql = "INSERT INTO files (filename, guid, expiry_date, download_limit, password) VALUES ('{$filename_sql}', '{$guid}', DATE_ADD(NOW(), INTERVAL {$expiry} HOUR), {$download_limit}, '{$password}')"; 
      echo $sql;
      if (!mysqli_query($connection, $sql)) {
        echo "Error: query error @ 47 of upload";
        die ('Error: ' . mysqli_error($connection));
      }
      
      $result = move_uploaded_file($_FILES["file"]["tmp_name"], "/var/data/" . $_FILES["file"]["name"]);
      if ($result)
      //if (true)
      {
        echo "SUCCESS:--GUID: {$guid}\--PASSWORD: {$password}--DL LIMIT: {$download_limit}-- Expiry: {$expiry}";
      } else {
        echo "ERROR: " . $result;
      }

    }
  } else {
    echo "ERROR: File too large.";
  }

  function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
  }
?>
