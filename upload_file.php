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
      $result = move_uploaded_file($_FILES["file"]["tmp_name"], "/var/data/" . $_FILES["file"]["name"]);
      if ($result)
      {
        echo "SUCCESS";
      } else {
        echo "ERROR: " . $result;
      }
    }
  } else {
    echo "ERROR: File too large.";
  }
?>
