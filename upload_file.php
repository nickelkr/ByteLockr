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
      } 

      $guid = "";
      do {
        // create our guid
        $guid = random_string(4) . "-" . random_string(4) . "-" . random_string(4);
      } while (mysqli_fetch_array(mysqli_query($connection, "SELECT guid FROM files WHERE guid={$guid} LIMIT 1")));

      // generate the file's password
      $password = random_string(15);

      $expiry = mysql_real_escape_string(sanitize_input($_POST["time_limit"]));
      $download_limit = mysql_real_escape_string(sanitize_input($_POST["download_limit"]));
      $filename = sanitize_input($_FILES["file"]["name"]);
      $filename_sql = mysql_real_escape_string($filename);

      // check to make sure our values are numbers
      check_num($expiry);
      check_num($download_limit);

      // 0 represents unlimited downloads
      if ($download_limit  == 0) {
        $download_limit = "NULL";
      }

      $sql = "INSERT INTO files (filename, guid, expiry_date, download_limit, password) VALUES ('{$filename_sql}', '{$guid}', DATE_ADD(NOW(), INTERVAL {$expiry} HOUR), {$download_limit}, '{$password}')"; 
      if (!mysqli_query($connection, $sql)) {
        die ('Error: ' . mysqli_error($connection));
      }
      
      $result = move_uploaded_file($_FILES["file"]["tmp_name"], "/var/data/" . $guid . "." . $_FILES["file"]["name"]);
      if ($result)
      {
        echo "SUCCESS: <br/> Link: 192.168.1.9/resource.php?resource=" . $guid . "<br/> Password: " . $password;
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
  
  function check_num($num) {
    if (!is_numeric($num)) {
      header ("Location: error.html");
      die();
    }
  }

  function random_string($length) {
    $string = "abcdefghijklmnopqrstuvwxyz" .
              "ABCDEFGHIJKLMNOPQRSTUVWXYZ" .
              "1234567890";

    for ($x = 0; $x < $length; $x++) {
      $output .= $string[rand(0, strlen($string) - 1)];
    }

    return $output;
  }
?>

