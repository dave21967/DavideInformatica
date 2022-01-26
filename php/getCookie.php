<?php
  $cookie = $_GET["cookie"];
  if(isset($_COOKIE[$cookie])) {
    echo $_COOKIE[$cookie];
  }
  else {
    echo NULL;
  }
?>